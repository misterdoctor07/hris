<?php
session_start();
include '../config.php';

// Get the logged-in user ID
$userId = $_SESSION['idno'];

//Approver name
$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userQuery);
$approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";

//Identifying jobtitle
$sqlDetails = "SELECT ed.designation, ed.* 
               FROM employee_details ed 
               WHERE ed.idno = '$userId'";

$result = mysqli_query($con, $sqlDetails);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $jobtitle = !empty($row['designation']) ? $row['designation'] : '';
} else {
    $jobtitle = ''; // Fallback if no record is found
}

// Initialize an empty array for conditions
$conditions = [];

// Query to fetch the approver's combinations
$approverQuery = "SELECT company, department, requestingofficer, shift 
                FROM leave_protocols 
                WHERE approvingofficer = '$userId'";
$result = mysqli_query($con, $approverQuery);

if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Loop through each row and build a condition
while ($row = mysqli_fetch_assoc($result)) {
    $clauseParts = [];

    // Build conditions based on non-null values
    if (!empty($row['shift'])) {
        $clauseParts[] = "ed.startshift = '{$row['shift']}'";
    }
    if (!empty($row['company'])) {
        $clauseParts[] = "ed.company = '{$row['company']}'";
    }
    if (!empty($row['department'])) {
        $clauseParts[] = "ed.department = '{$row['department']}'";
    }
    if (!empty($row['requestingofficer'])) {
        $clauseParts[] = "ed.designation = '{$row['requestingofficer']}'";
    }

    // Combine the conditions for this specific row
    if (!empty($clauseParts)) {
        $conditions[] = '(' . implode(' AND ', $clauseParts) . ')';
    }
}
// Join all conditions with OR to match any valid combination
$whereClause = !empty($conditions) ? implode(' OR ', $conditions) : '1=1';

//Id in where clause
$idno = ($jobtitle == '78' || $jobtitle == '116') 
    ? "eeo.idno != '$userId'" 
    : '1=1';
//Type in where clause
$type = ($jobtitle == '78' || $jobtitle == '116') 
    ? "eeo.type_EEO = 'Medical'" 
    : '1=1';
//Status in where clause
$status = ($jobtitle == '78' || $jobtitle == '116') 
    ? "eeo.eeo_status = 'Pending'" 
    : "eeo.eeo_status LIKE '%Approved%'";
//acknowledge in where clause
$acknowledge = ($jobtitle == '78' || $jobtitle == '116') 
    ? "1=1" 
    : "COALESCE(eeo.acknowledged, '') NOT LIKE '%$approval%'";

// Count pending leave applications for the same company
$pendingLeaveCount = 0;
$leaveQuery = "SELECT COUNT(*) AS total 
                    FROM leave_application la 
                    INNER JOIN employee_profile ep ON ep.idno = la.idno 
                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                    WHERE la.idno != '$userId' 
                    AND la.appstatus = 'Pending'
                    AND ($whereClause)";

    $sqlLeave = mysqli_query($con, $leaveQuery);

    if (!$sqlLeave) {
        die("Error: " . mysqli_error($con));
    }

    $leaveRow = mysqli_fetch_assoc($sqlLeave);

    if (!$leaveRow) {
    die("Error: No leave data found");
}

$pendingLeaveCount = $leaveRow['total'];

// Count pending OT applications
$pendingOTCount = 0;
if (!empty($requestingOfficers)) {
    $otQuery = "SELECT COUNT(*) AS total FROM overtime_application ot
                INNER JOIN employee_details ed ON ot.idno = ed.idno
                WHERE ot.app_status = 'Pending' AND ed.designation IN ('$requestingOfficersStr')";

    if ($designation == '17' || $designation == '114' || $designation == '105' || $designation == '93')  {
        $sqlOT = mysqli_query($con, $otQuery);
    } elseif ($designation == '50' || $designation == '89') {
        $sqlOT = mysqli_query($con, $otQuery . " AND ed.company='$companyFilter'");
    } else {
        $sqlOT = mysqli_query($con, $otQuery . " AND ed.company='$companyFilter' AND ed.department='$department'");
    }

    if (!$sqlOT) {
        die("Error: " . mysqli_error($con));
    }

    $otRow = mysqli_fetch_assoc($sqlOT);

    if (!$otRow) {
        die("Error: No OT data found");
    }

    $pendingOTCount = $otRow['total'];
}

// Count pending missed log applications
$pendingMLCount = 0;
$mlQuery = "SELECT COUNT(*) AS total
            FROM missed_log_application ml 
            INNER JOIN employee_profile ep ON ep.idno = ml.idno 
            INNER JOIN employee_details ed ON ed.idno = ep.idno 
            WHERE ml.idno != '$userId' 
            AND ml.applic_status = 'Pending'
            AND ($whereClause)";

    $sqlML = mysqli_query($con, $mlQuery);

    if (!$sqlML) {
        die("Error: " . mysqli_error($con));
    }

    $mlRow = mysqli_fetch_assoc($sqlML);

    if (!$mlRow) {
        die("Error: No ML data found");
    }

    $pendingMLCount = $mlRow['total'];

// Count pending EEO applications
$pendingEEOCount = 0;
$eeoQuery = "SELECT COUNT(*) AS total
             FROM emergencyearlyout eeo
             INNER JOIN employee_profile ep ON ep.idno = eeo.idno
             INNER JOIN employee_details ed ON ed.idno = ep.idno
             WHERE ($status)
             AND ($idno)
             AND ($type)
             AND ($acknowledge)
             AND ($whereClause)";

$sqlEEO = mysqli_query($con, $eeoQuery);
if (!$sqlEEO) {
    die("Error: " . mysqli_error($con));
}

$eeoRow = mysqli_fetch_assoc($sqlEEO);
$pendingEEOCount = $eeoRow['total'] ?? 0;

$totalCount = ($jobtitle === '116' || $jobtitle === '78') 
              ? (int)$pendingEEOCount 
              : (int)$pendingLeaveCount + (int)$pendingOTCount + (int)$pendingMLCount + (int)$pendingEEOCount;


// Output the pending counts as JSON
header('Content-Type: application/json');
echo json_encode(array(
    'leave_count' => $pendingLeaveCount,
    'ot_count' => $pendingOTCount,
    'ml_count' => $pendingMLCount,
    'eeo_count' => $pendingEEOCount,
    'total_count' => $totalCount
));
?>
