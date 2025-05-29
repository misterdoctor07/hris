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
$approveleaveQuery = "SELECT COUNT(*) AS total 
    FROM leave_application 
    WHERE idno = ? 
    AND appstatus != 'Pending'
    AND appstatus != 'Cancelled'
    AND appstatus LIKE '%Approved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($approveleaveQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approveleaveNotif = $row ? $row['total'] : 0;

// Count new disapproved leave applications
$disapproveleaveQuery = "SELECT COUNT(*) AS total 
    FROM leave_application 
    WHERE idno = ? 
    AND appstatus != 'Pending'
    AND appstatus != 'Cancelled'
    AND appstatus LIKE '%Disapproved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($disapproveleaveQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$disapproveleaveNotif = $row ? $row['total'] : 0;

// Count new approved missed log notifications
$approvemissedLogQuery = "SELECT COUNT(*) AS total 
    FROM missed_log_application 
    WHERE idno = ? 
    AND applic_status != 'Pending'
    AND applic_status != 'Cancelled' 
    AND applic_status LIKE '%Approved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($approvemissedLogQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approvemissedLogNotif = $row ? $row['total'] : 0;

// Count new disapproved missed log notifications
$disapprovemissedLogQuery = "SELECT COUNT(*) AS total 
    FROM missed_log_application 
    WHERE idno = ? 
    AND applic_status != 'Pending'
    AND applic_status != 'Cancelled' 
    AND applic_status LIKE '%Disapproved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($disapprovemissedLogQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$disapprovemissedLogNotif = $row ? $row['total'] : 0;

// Count new approved overtime applications
$approveovertimeQuery = "SELECT COUNT(*) AS total 
    FROM overtime_application 
    WHERE idno = ? 
    AND app_status != 'Pending'
    AND app_status != 'Cancelled' 
    AND app_status LIKE '%Approved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($approveovertimeQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approveovertimeNotif = $row ? $row['total'] : 0;

// Count new disapproved overtime applications
$disapproveovertimeQuery = "SELECT COUNT(*) AS total 
    FROM overtime_application 
    WHERE idno = ? 
    AND app_status != 'Pending'
    AND app_status != 'Cancelled' 
    AND app_status LIKE '%Disapproved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($disapproveovertimeQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$disapproveovertimeNotif = $row ? $row['total'] : 0;

// Count new approved Emergency Early Out applications
$approveeeoQuery = "SELECT COUNT(*) AS total 
    FROM emergencyearlyout 
    WHERE idno = ? 
    AND eeo_status != 'Pending'
    AND eeo_status != 'Cancelled' 
    AND eeo_status LIKE '%Approved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($approveeeoQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approveeeoNotif = $row ? $row['total'] : 0;

// Count new disapproved Emergency Early Out applications
$disapproveeeoQuery = "SELECT COUNT(*) AS total 
    FROM emergencyearlyout 
    WHERE idno = ? 
    AND eeo_status != 'Pending'
    AND eeo_status != 'Cancelled' 
    AND eeo_status LIKE '%Disapproved%'
    AND view_status = 'Unseen'";
$stmt = $con->prepare($disapproveeeoQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$disapproveeeoNotif = $row ? $row['total'] : 0;

//*****Remarks*****
// Count unseen leave remarks
$leaveremarkQuery = "SELECT COUNT(*) AS total 
    FROM leave_application 
    WHERE idno = ? 
    AND (
        (remarks IS NOT NULL AND TRIM(remarks) != '') 
        OR 
        (approver_remarks IS NOT NULL AND TRIM(approver_remarks) != '')
    )
    AND remarks_view_status = 'Unseen'";
$stmt = $con->prepare($leaveremarkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$leaveRemarkNotif = $row ? $row['total'] : 0;

// Count unseen missed log remarks
$logremarkQuery = "SELECT COUNT(*) AS total 
    FROM missed_log_application 
    WHERE idno = ? 
    AND (
        (remarks IS NOT NULL AND TRIM(remarks) != '') 
        OR 
        (approver_remarks IS NOT NULL AND TRIM(approver_remarks) != '')
        OR 
        (monitoring_remarks IS NOT NULL AND TRIM(monitoring_remarks) != '')
    )
    AND remarks_view_status = 'Unseen'";
$stmt = $con->prepare($logremarkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$logRemarkNotif = $row ? $row['total'] : 0;

// Count unseen overtime remarks
$overtimeremarkQuery = "SELECT COUNT(*) AS total 
    FROM overtime_application 
    WHERE idno = ? 
    AND (
        (hr_remarks IS NOT NULL AND TRIM(hr_remarks) != '') 
        OR 
        (approver_remarks IS NOT NULL AND TRIM(approver_remarks) != '')
    )
    AND remarks_view_status = 'Unseen'";
$stmt = $con->prepare($overtimeremarkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$overtimeRemarkNotif = $row ? $row['total'] : 0;

// Count unseen eeo remarks
$eeoremarkQuery = "SELECT COUNT(*) AS total 
    FROM emergencyearlyout 
    WHERE idno = ?
    AND approvers_remarks IS NOT NULL AND TRIM(approvers_remarks) != ''
    AND remarks_view_status = 'Unseen'";
$stmt = $con->prepare($eeoremarkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$eeoRemarkNotif = $row ? $row['total'] : 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
  'approve_leave_notif' => $approveleaveNotif,
  'disapprove_leave_notif' => $disapproveleaveNotif,
  'approve_missedlog_notif' => $approvemissedLogNotif,
  'disapprove_missedlog_notif' => $disapprovemissedLogNotif,
  'approve_overtime_notif' => $approveovertimeNotif,
  'disapprove_overtime_notif' => $disapproveovertimeNotif,
  'approve_eeo_notif' => $approveeeoNotif,
  'disapprove_eeo_notif' => $disapproveeeoNotif,
  'leave_remark_notif' => $leaveRemarkNotif,
  'log_remark_notif' => $logRemarkNotif,
  'overtime_remark_notif' => $overtimeRemarkNotif,
  'eeo_remark_notif' => $eeoRemarkNotif
]);
?>