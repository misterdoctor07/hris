<?php
include('../config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    die(json_encode([]));
}

$idno = $_SESSION['idno'];
$start = $_GET['start'];
$end = $_GET['end'];

$query = "SELECT logindate, remarks, loginam, logoutam, loginpm, logoutpm 
          FROM attendance 
          WHERE idno = '$idno' 
          AND logindate BETWEEN '$start' AND '$end'";
          

$result = mysqli_query($con, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>