<?php
session_start();
include('../config.phpconfig.php');
$idno = $_SESSION['idno'];

// Get input & sanitize
$pin = trim($_POST['pin']);

// Query user credentials
$sql = "SELECT ep.*, ed.status, u.pin, u.idno, u.pin_status, u.pin_login_attempts
        FROM employee_profile ep
        INNER JOIN employee_details ed ON ep.idno = ed.idno
        INNER JOIN users u ON ep.idno = u.idno
        WHERE u.idno = '$idno'";

$PinLogin = mysqli_query($con, $sql);

if (mysqli_num_rows($UserLogin) > 0) {
    $user = mysqli_fetch_array($PinLogin);
    $idno = $user['idno'];

    // Check if the account is locked
    if ($user['pin_status'] == 'Locked') {
        echo "<script>alert('Your PIN is locked. Please contact admin support or the webmaster.'); window.location='viewpayroll.php';</script>";
        exit();
    }

    // Compare PIN
    if ($pin === $user['pin']) {
        // Reset login attempts on successful login
        mysqli_query($con, "UPDATE users SET pin_login_attempts = 0 WHERE idno = '$idno'");

    } else {
        // Increment login attempts
        $loginAttempts = $user['pin_login_attempts'] + 1;
        mysqli_query($con, "UPDATE users SET pin_login_attempts = $loginAttempts WHERE idno = '$idno'");

        // Lock the account after 3 failed attempts
        if ($loginAttempts >= 3) {
            mysqli_query($con, "UPDATE users SET pin_status = 'Locked' WHERE idno = '$idno'");
            echo "<script>alert('Your PIN has been locked due to too many failed login attempts. Please contact admin support or the webmaster.'); window.location='viewpayroll.php';</script>";
        } else {
                echo "<script>alert('Incorrect PIN! You have " . (3 - $loginAttempts) . " attempts remaining.'); window.location='viewpayroll.php';</script>";
        }
    }
} else {
    echo "<script>alert('User not found!'); window.location='viewpayroll.php';</script>";
}
?>