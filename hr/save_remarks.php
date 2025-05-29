<?php
ob_start(); // Start output buffering
include('../config.php'); // Include your DB connection file

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Set JSON response header

    // Validate inputs
    if (empty($_POST['remarks'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        ob_end_flush(); // Send the output buffer and turn off output buffering
        exit;
    }

    // Decode the JSON array of remarks
    $remarksData = json_decode($_POST['remarks'], true);

    // Check if the decoded data is valid
    if (!is_array($remarksData)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid remarks data.']);
        ob_end_flush(); // Send the output buffer and turn off output buffering
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("UPDATE missed_log_application SET remarks = ? WHERE id = ?");
    $successCount = 0;

    foreach ($remarksData as $remark) {
        $mlid = $remark['mlid'];
        $remarks = $remark['remarks'];

        // Validate individual remark data
        if (empty($mlid) || empty($remarks)) {
            continue; // Skip invalid entries
        }

        $stmt->bind_param("si", $remarks, $mlid);

        if ($stmt->execute()) {
            $successCount++;
        } else {
            // Log the error or handle it as needed
            error_log("Failed to save remarks for mlid: $mlid - " . $stmt->error);
        }
    }

    $stmt->close();

    // Check if all remarks were saved successfully
    if ($successCount === count($remarksData)) {
        echo json_encode(['success' => true, 'message' => 'Remarks saved successfully.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Some remarks failed to save.']);
    }

    ob_end_flush(); // Send the output buffer and turn off output buffering
    exit;
}
?>
