session_start();
include '../config.php';

$idno = $_SESSION['idno'];
$pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';

if (empty($pin)) {
    echo "<script>showNotification('Error', 'PIN cannot be empty.');</script>";
    exit;
}

// Hash the PIN for security
$hashedPin = password_hash($pin, PASSWORD_DEFAULT);

// Check if user already has a PIN
$sqlCheck = mysqli_query($con, "SELECT pin FROM users WHERE idno = '$idno'");
$row = mysqli_fetch_array($sqlCheck);

if ($row && !empty($row['pin'])) {
    echo "<script>showNotification('Error', 'PIN already set. You cannot reset it here.');</script>";
    exit;
}

// Save the PIN
$sqlSave = mysqli_query($con, "UPDATE users SET pin = '$hashedPin' WHERE idno = '$idno'");

if ($sqlSave) {
    echo "<script>showNotification('Success', 'PIN registered successfully!');</script>";
} else {
    echo "<script>showNotification('Error', 'Failed to register PIN.');</script>";
}
?>