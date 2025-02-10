<?php
session_start();
include '../config.php';

// Get the logged-in user ID
$userId = $_SESSION['idno'];


// Count pending missed log applications
$pendingEEOCount = 0;
$mlQuery = "SELECT COUNT(*) AS total
            FROM missed_log_application ml 
            INNER JOIN employee_profile ep ON ep.idno = ml.idno 
            INNER JOIN employee_details ed ON ed.idno = ep.idno 
            WHERE ml.idno != '$userId' 
            AND ml.applic_status = 'Pending'
            AND ($whereClause)";

    $sqlEEO = mysqli_query($con, $mlQuery);

    if (!$sqlEEO) {
        die("Error: " . mysqli_error($con));
    }

    $mlRow = mysqli_fetch_assoc($sqlEEO);

    if (!$mlRow) {
        die("Error: No EEO data found");
    }

    $pendingEEOCount = $mlRow['total'];

$totalCount = $pendingEEOCount;

// Output the pending counts as JSON
header('Content-Type: application/json');
echo json_encode(array(
    'eeo_count' => $pendingEEOCount,
    'total_count' => $totalCount
));
?>