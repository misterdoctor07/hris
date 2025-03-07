<?php
session_start();
include '../config.php';

// Get logged-in user ID
$userId = $_SESSION['idno'];

$leaveNotif = 0;
$missedLogNotif = 0;
$overtimeNotif = 0;
$eeoNotif = 0;

// Count new approved leave applications
$leaveQuery = "SELECT COUNT(*) AS total 
    FROM leave_application 
    WHERE idno = ? 
    AND appstatus != 'Pending'
    AND appstatus != 'Cancelled'
    AND (appstatus LIKE '%Approved%' OR appstatus LIKE '%Disapproved%')
    AND view_status = 'Unseen'";
$stmt = $con->prepare($leaveQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$leaveNotif = $row ? $row['total'] : 0;

// Count new missed log notifications
$missedLogQuery = "SELECT COUNT(*) AS total 
    FROM missed_log_application 
    WHERE idno = ? 
    AND applic_status != 'Pending'
    AND applic_status != 'Cancelled' 
    AND (applic_status LIKE '%Approved%' OR applic_status LIKE '%Disapproved%')
    AND view_status = 'Unseen'";
$stmt = $con->prepare($missedLogQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$missedLogNotif = $row ? $row['total'] : 0;

// Count new overtime applications
$overtimeQuery = "SELECT COUNT(*) AS total 
    FROM overtime_application 
    WHERE idno = ? 
    AND app_status != 'Pending'
    AND app_status != 'Cancelled' 
    AND (app_status LIKE '%Approved%' OR app_status LIKE '%Disapproved%')
    AND view_status = 'Unseen'";
$stmt = $con->prepare($overtimeQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$overtimeNotif = $row ? $row['total'] : 0;

// Count new Emergency Early Out applications
$eeoQuery = "SELECT COUNT(*) AS total 
    FROM emergencyearlyout 
    WHERE idno = ? 
    AND eeo_status != 'Pending'
    AND eeo_status != 'Cancelled' 
    AND (eeo_status LIKE '%Approved%' OR eeo_status LIKE '%Disapproved%')
    AND view_status = 'Unseen'";
$stmt = $con->prepare($eeoQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$eeoNotif = $row ? $row['total'] : 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
  'leave_notif' => $leaveNotif,
  'missedlog_notif' => $missedLogNotif,
  'overtime_notif' => $overtimeNotif,
  'eeo_notif' => $eeoNotif
]);
?>
