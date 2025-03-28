<?php
session_start();
include '../config.php';

$idno = $_SESSION['idno'];
$pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';

if (empty($pin)) {
    echo "PIN cannot be empty.";
    exit;
}

// Hash the PIN for security
$hashedPin = password_hash($pin, PASSWORD_DEFAULT);

// Check if user already has a PIN
$stmtCheck = $con->prepare("SELECT pin FROM users WHERE idno = ?");
$stmtCheck->bind_param("s", $idno);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$row = $resultCheck->fetch_assoc();
$stmtCheck->close();

if ($row && !is_null($row['pin'])) {
    echo "PIN already set. You cannot reset it here.";
    exit;
}

// Save the PIN
$stmtSave = $con->prepare("UPDATE users SET pin = ? WHERE idno = ?");
$stmtSave->bind_param("ss", $hashedPin, $idno);

if ($stmtSave->execute()) {
    echo "PIN registered successfully!";
} else {
    echo "Failed to register PIN.";
}

$stmtSave->close();
$con->close();
?>
