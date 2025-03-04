<?php
session_start();
include '../config.php';

$userId = $_SESSION['idno'];

$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department, ed.company 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");

if (!$userQuery) {
    die("Error fetching user details: " . mysqli_error($con));
}

$userDetails = mysqli_fetch_assoc($userQuery);

if (!$userDetails) {
    die("Error: No user details found");
}

$designation = $userDetails['designation']; 
$userDept = $userDetails['department'];
$userCompany = $userDetails['company'];

$whereClause = "";

if ($designation == 8 || $designation == 59 || $designation == 65 || $designation == 94) {
    $whereClause = "ed.department = '$userDept'";
} elseif ($designation == 50 || $designation == 89) {
    $whereClause = "ed.company = '$userCompany'";
} else if ($designation == 102 || $designation == 3 || $designation == 88 || $designation == 114 || $designation == 92) {
    $whereClause = "1=1";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateQuery = "UPDATE infraction i
                    INNER JOIN employee_details ed ON ed.idno = i.idno
                    SET i.viewstatus = 'viewed'
                    WHERE $whereClause";

    mysqli_query($con, $updateQuery);

    $selectQuery = "SELECT i.id 
                    FROM infraction i
                    INNER JOIN employee_details ed ON ed.idno = i.idno
                    WHERE $whereClause";

    $result = mysqli_query($con, $selectQuery);

    $infractionIds = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $infractionIds[] = $row['id'];
    }

    if (!empty($infractionIds)) {
        foreach ($infractionIds as $infractionId) {
            $insertQuery = "INSERT INTO infraction_view_status (infraction_id, user_id, view_status) 
                            VALUES ('$infractionId', '$userId', 'viewed') 
                            ON DUPLICATE KEY UPDATE view_status = 'viewed'";
            mysqli_query($con, $insertQuery);
        }
    }

    echo json_encode(["message" => "Infractions marked as viewed"]);
    exit;
}

$queryRemaining = "SELECT i.id  
    FROM infraction i
    INNER JOIN employee_details ed ON ed.idno = i.idno
    LEFT JOIN infraction_view_status ivs 
        ON ivs.infraction_id = i.id AND ivs.user_id = '$userId'
    WHERE $whereClause
    AND (
        (i.viewstatus = 'new' OR i.viewstatus = 'updated')
        OR (ivs.view_status IS NULL OR ivs.view_status != 'viewed')
    )";

$result = mysqli_query($con, $queryRemaining);

$remainingInfractions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $remainingInfractions[] = $row['id'];
}

header('Content-Type: application/json');
echo json_encode([
    "infractions" => $remainingInfractions
]);

?>
