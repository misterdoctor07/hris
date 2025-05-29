<?php
session_start();
include('../config.php');

if (isset($_POST['password'])) {
  $enteredPassword = $_POST['password'];
  $userId = $_SESSION['idno'];

  // Fetch the user's hashed password from the database
  $sql = "SELECT password FROM employee_profile WHERE idno = '$userId'";
  $result = mysqli_query($con, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hashedPassword = $row['password'];

    // Verify the entered password against the hashed password
    if (password_verify($enteredPassword, $hashedPassword)) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false]);
    }
  } else {
    echo json_encode(['success' => false]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'No password provided.']);
}
?>