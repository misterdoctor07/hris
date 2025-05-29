<?php
include('../config.php');

// Validate inputs
if (!isset($_POST['leave_type'], $_POST['employee_id'])) {
    die("Error: Missing parameters.");
}

$leaveType = trim($_POST['leave_type']);
$employeeId = (int)$_POST['employee_id']; // Ensure it's an integer

// Only allow valid leave types
$allowedLeaveTypes = ['VL', 'SL', 'PTO'];
if (!in_array($leaveType, $allowedLeaveTypes)) {
    die("Error: Invalid leave type.");
}

// Check database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Prepare the query for attendance database
$query = "SELECT logindate, remarks FROM attendance 
          WHERE idno = ? 
          AND status = 'leave' 
          AND remarks = ?
          ORDER BY logindate DESC";
$stmt = $con->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($con->error));
}

// Bind parameters & execute
$stmt->bind_param("is", $employeeId, $leaveType);
if (!$stmt->execute()) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();
$dates = [];

while ($row = $result->fetch_assoc()) {
    // Add each leave date to the array
    $dates[] = $row['logindate'];
}

// Output results
if (empty($dates)) {
    echo "<p>No {$leaveType} leave taken.</p>";
} else {
    echo "<ul>";
    foreach ($dates as $date) {
        echo "<li>" . date('M d, Y (l)', strtotime($date)) . "</li>";
    }
    echo "</ul>";
}

$stmt->close();
?>