<?php
session_start();
include '../config.php';

$userId = $_SESSION['idno'];

$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department, ed.company 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");

if (!$userQuery) {
    die("Error fetching user details: " . mysqli_error($con));
}

$userDetails = mysqli_fetch_assoc($userQuery);

if (!$userDetails) {
    die("Error: No user details found");
}

$designation = $userDetails['designation']; 
$userDept = $userDetails['department'];
$userCompany = $userDetails['company'];

$whereClause = "";

if ($designation == 8 || $designation == 59 || $designation == 65 || $designation == 94) { //Assessor || Team Leader || Team Manager || OIC
    $whereClause = "ed.department = '$userDept'";
} elseif ($designation == 50 || $designation == 89) { //Operations Supervisor || Operations Manager
    $whereClause = "ed.company = '$userCompany'";
} else if ($designation == 102 || $designation == 3 || $designation == 88 || $designation == 114 || $designation == 92) { // Accounting Assistant || Accounting Specialist || Accounting Associate || Admin Executive || Senior Admin Auditor
    $whereClause = "1=1";
}

$infractionNotif = 0;

$infractionQuery = "SELECT COUNT(*) AS total 
    FROM infraction i
    INNER JOIN infraction_view_status ivs ON ivs.infraction_id = i.id
    WHERE $whereClause 
    AND status = 'Served'
    AND user_id != '$userId'";
$stmt = $con->prepare($infractionQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$infractionNotif = $row ? $row['total'] : 0;

header('Content-Type: application/json');
echo json_encode([
    "infractions" => $infractionNotif
]);

?>
