<?php
 include('../config.php');// Include your DB connection file

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mlid = $_POST['mlid'];
    $remarks = mysqli_real_escape_string($con, $_POST['monitoring_remarks']);

    $query = "UPDATE missed_log_application SET monitoring_remarks = '$remarks' WHERE id = '$mlid'";
    if (mysqli_query($con, $query)) {
        echo json_encode(['success' => true, 'message' => 'Remarks saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save remarks: ' . mysqli_error($con)]);
    }
    exit;
}
?>
