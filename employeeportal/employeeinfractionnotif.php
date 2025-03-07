<?php
session_start();
include '../config.php';

// Get logged-in user ID
$userId = $_SESSION['idno'];

$empinfractionNotif = 0;

$empinfractionQuery = "SELECT COUNT(*) AS total 
    FROM infraction 
    WHERE idno = ? 
    AND (status = 'pending' OR status = 'Served')
    AND viewstatus = 'new'";
$stmt = $con->prepare($empinfractionQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$empinfractionNotif = $row ? $row['total'] : 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
  'emp_infraction_notif' => $empinfractionNotif
]);
?>
