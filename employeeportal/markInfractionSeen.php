<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['idno'];

        $updateQuery = "UPDATE infraction SET viewstatus = 'viewed' WHERE idno = ? AND viewstatus = 'New'";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Database update failed"]);
        }
}
?>
