<?php
session_start();
include('config.php');

// Get input & sanitize
$username = trim(mysqli_real_escape_string($con, $_POST['username']));
$password = trim($_POST['password']); // Don't escape to avoid hashing issues

// Query user credentials
$sql = "SELECT ep.*, ed.status, u.username, u.password, u.access, u.idno, u.password_status, u.login_attempts, u.account_locked
        FROM employee_profile ep
        INNER JOIN employee_details ed ON ep.idno = ed.idno
        INNER JOIN users u ON ep.idno = u.idno
        WHERE u.username = '$username'";

$UserLogin = mysqli_query($con, $sql);

if (mysqli_num_rows($UserLogin) > 0) {
    $user = mysqli_fetch_array($UserLogin);
    $idno = $user['idno'];

    // Check if the account is locked
    if ($user['account_locked'] == 1) {
        echo "<script>alert('Your account is locked. Please contact admin support or the webmaster.'); window.location='index.php';</script>";
        exit();
    }

    // Compare passwords
    if ($password === $user['password']) {
        // Reset login attempts on successful login
        mysqli_query($con, "UPDATE users SET login_attempts = 0 WHERE idno = '$idno'");

        if (strtolower($user['status']) === 'resigned') {
            echo "<script>alert('Unauthorized Access! Resigned Employee.'); window.location='index.php';</script>";
            exit();
        }

        //Store session data
        $_SESSION['idno'] = $idno;
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $user['firstname'] . " " . $user['lastname'];
        $_SESSION['access'] = $user['access'];
        $_SESSION['password_status'] = $user['password_status']; // Store password status

        // Check if the password is default
        if ($user['password_status'] === 'default') {
            // Redirect to the password change page
            echo "<script>alert('Please change your password to continue, {$user['firstname']}!'); window.location='/hris/employeeportal/emp_dev.php';</script>";
            exit();
        } else {
            // Redirect to the main dashboard
           echo "<script>alert('Hello, {$user['firstname']}!'); window.location='/hris/attendance/{$username}';</script>";
        }
    } else {
        // Increment login attempts
        $loginAttempts = $user['login_attempts'] + 1;
        mysqli_query($con, "UPDATE users SET login_attempts = $loginAttempts WHERE idno = '$idno'");

        // Lock the account after 3 failed attempts
        if ($loginAttempts >= 5) {
            mysqli_query($con, "UPDATE users SET account_locked = 1 WHERE idno = '$idno'");
            echo "<script>alert('Your account has been locked due to too many failed login attempts. Please contact admin support or the webmaster.'); window.location='index.php';</script>";
        } else {
            // Check if the user is still using the default password
            if ($user['password_status'] === 'default') {
                echo "<script>alert('Incorrect password! Please use the default password (12345) to login.'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Incorrect password! You have " . (5 - $loginAttempts) . " attempts remaining.'); window.location='index.php';</script>";
            }
        }
    }
} else {
    echo "<script>alert('User not found!'); window.location='index.php';</script>";
}
?>