<?php
include('../config.php');

// Validate inputs
if (!isset($_POST['leave_type'], $_POST['employee_id'], $_POST['dates'])) {
    die(json_encode(['success' => false, 'error' => 'Missing parameters']));
}

$leaveType = trim($_POST['leave_type']);
$employeeId = (int)$_POST['employee_id'];
$dates = json_decode($_POST['dates']);

// Begin transaction
$con->begin_transaction();

try {
    // First, delete all existing overrides for this employee/leave type
    $query = "DELETE FROM leave_overrides 
              WHERE employee_id = ? AND leave_type = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $employeeId, $leaveType);
    $stmt->execute();
    
    // Then insert the new dates
    $query = "INSERT INTO leave_overrides 
              (employee_id, leave_type, override_date, added_by) 
              VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    
    $currentUserId = $_SESSION['user_id'] ?? 0; // Adjust based on your auth system
    
    foreach ($dates as $date) {
        // Skip dates that exist in the attendance system
        $checkQuery = "SELECT idno FROM attendance 
                      WHERE idno = ? 
                      AND logindate = ? 
                      AND status = 'leave' 
                      AND remarks = ?";
        $checkStmt = $con->prepare($checkQuery);
        $checkStmt->bind_param("iss", $employeeId, $date, $leaveType);
        $checkStmt->execute();
        
        if ($checkStmt->get_result()->num_rows === 0) {
            // Only insert if not in attendance system
            $stmt->bind_param("issi", $employeeId, $leaveType, $date, $currentUserId);
            $stmt->execute();
        }
    }
    
    $con->commit();
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>