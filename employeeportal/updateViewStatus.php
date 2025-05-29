<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['idno'];
    $type = $_POST['type']; // Get the type of application (leave, missedlog, overtime, eeo)

    $tableMapping = [
        'leave' => 'leave_application',
        'missedlog' => 'missed_log_application',
        'overtime' => 'overtime_application',
        'eeo' => 'emergencyearlyout'
    ];

    if (isset($tableMapping[$type])) {
        $tableName = $tableMapping[$type];

        $updateQuery = "UPDATE $tableName SET view_status = 'Seen', remarks_view_status = 'Seen' WHERE idno = ? AND (view_status = 'Unseen' OR remarks_view_status = 'Unseen')";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Database update failed"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid application type"]);
    }
}
?>