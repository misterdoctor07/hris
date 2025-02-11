<?php
include('../config.php');

// Get and sanitize input data
$idno = mysqli_real_escape_string($con, $_POST['empid']);
$birthdate = date('Y-m-d', strtotime($_POST['birthdate']));

// Query to validate user and fetch their status using INNER JOIN
$sql = "  SELECT 
        ep.*, ed.status 
    FROM 
        employee_profile ep
    INNER JOIN 
        employee_details ed 
    ON 
        ep.idno = ed.idno
    WHERE 
        ep.idno = '$idno' AND ep.birthdate = '$birthdate'";

$UserLogin = mysqli_query($con, $sql);

if (mysqli_num_rows($UserLogin) > 0) {
    $user = mysqli_fetch_array($UserLogin);

    // Check if the employee is resigned or active
    if (strtolower($user['status']) === 'RESIGNED') {
        echo "<script>alert('Unauthorize Access! Resigned ID.'); window.location='../employeeportal/';</script>";
        exit; // Stop further execution
    }

    // If employee is active, start a session and redirect to the dashboard
    session_start();
    $_SESSION['idno'] = $idno;
    echo "<script>alert('Hello, {$user['firstname']}!'); window.location='dashboard.php?main';</script>";
} else {
    // Invalid login attempt
    echo "<script>alert('You are not authorized to access this portal!'); window.location='../employeeportal/';</script>";
}
?>
