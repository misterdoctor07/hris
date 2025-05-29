<?php
include('../config.php'); // Your database connection file

// Get start and end dates from request
$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-t');

// Fetch holidays from database
$query = "SELECT * FROM holidays WHERE date BETWEEN ? AND ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$holidays = [];
while ($row = $result->fetch_assoc()) {
    $holidays[] = [
        'date' => $row['date'],
        'type' => $row['type'],
        'location' => $row['location'],
        'description' => $row['description']
    ];
}

header('Content-Type: application/json');
echo json_encode($holidays);
?>