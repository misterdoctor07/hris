<?php
session_start();
include '../config.php';

$userId = $_SESSION['idno'];

// Fetch user details
$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department, ed.company 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");

if (!$userQuery) {
    die(json_encode(["error" => "Error fetching user details: " . mysqli_error($con)]));
}

$userDetails = mysqli_fetch_assoc($userQuery);
if (!$userDetails) {
    die(json_encode(["error" => "No user details found"]));
}

$designation = $userDetails['designation']; 
$userDept = $userDetails['department'];
$userCompany = $userDetails['company'];

// Default condition (fetch all)
$whereClause = "1=1";

if ($designation == 8 || $designation == 59 || $designation == 65 || $designation == 94) { // Assessor, Team Leader, etc.
    $whereClause = "ed.department = '$userDept'";
} elseif ($designation == 50 || $designation == 89) { // Operations Supervisor, Operations Manager
    $whereClause = "ed.company = '$userCompany'";
}

// Fetch unseen infractions
$infractionQuery = "SELECT i.id 
    FROM infraction i
    LEFT JOIN employee_details ed ON i.idno = ed.idno
    LEFT JOIN infraction_view_status ivs 
        ON ivs.infraction_id = i.id AND ivs.user_id = '$userId' 
    WHERE $whereClause 
    AND i.status = 'Served'
    AND ivs.infraction_id IS NULL"; // Ensures only unseen infractions

$result = mysqli_query($con, $infractionQuery);

if (!$result) {
    die(json_encode(["error" => "Error fetching infractions: " . mysqli_error($con)]));
}

$infractions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $infractions[] = $row['id']; // Correctly fetch from `infraction` table
}

$infractionNotif = count($infractions);

// Send JSON response
header('Content-Type: application/json');
echo json_encode([
    'infractionNotif' => $infractionNotif,
    'infractionIds' => $infractions
]);

?>
