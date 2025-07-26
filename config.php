<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost"; // Always 'localhost' on GoDaddy
$user = "root"; // Replace with your actual database username
$pass = ""; // Replace with the correct database password
$dbase = "hris"; // Replace with your full database name (sometimes prefixed)

$con = mysqli_connect($host, $user, $pass, $dbase);

mysqli_set_charset($con, "utf8mb4");

// Check connection
// if (!$con) {
//     die("Connection failed: " . mysqli_connect_error());
// } else {
//     echo "Database connected successfully!";
// }

?>
