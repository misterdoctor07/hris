<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['idno'];
    $infractionIds = isset($_POST['ids']) ? explode(',', $_POST['ids']) : [];

    // Debugging output
    error_log("Received IDs: " . json_encode($infractionIds)); // Log to Apache/PHP error log

    if (empty($infractionIds)) {
        echo json_encode(["success" => false, "message" => "No infraction IDs received"]);
        exit;
    }

    $values = [];
    foreach ($infractionIds as $id) {
        $id = intval($id);
        if ($id > 0) { // Ensure valid ID
            $values[] = "($id, $userId, 'viewed')";
        }
    }

    if (!empty($values)) {
        $query = "INSERT INTO infraction_view_status (infraction_id, user_id, view_status) 
                  VALUES " . implode(',', $values) . " 
                  ON DUPLICATE KEY UPDATE view_status = 'viewed'";

        error_log("SQL Query: " . $query); // Log the query

        if (mysqli_query($con, $query)) {
            echo json_encode(["success" => true]);
        } else {
            error_log("Database error: " . mysqli_error($con)); // Log DB error
            echo json_encode(["success" => false, "message" => "Database update failed"]);
        }
    }
}
?>
