<?php
include('../config.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $announcement_ids = json_decode($_POST['announcement_ids']);
    $today = date('Y-m-d H:i:s');
    
    // Mark each announcement as read
    foreach ($announcement_ids as $announcement_id) {
        mysqli_query($con, "INSERT INTO announcement_reads (user_id, announcement_id, read_date) 
                           VALUES ('$user_id', '$announcement_id', '$today')");
    }
    
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false]);
?>
