<?php
session_start();
include('../config.php');

// Set timezone
date_default_timezone_set('Asia/Manila');

if(isset($_GET['token']) || isset($_POST['token'])) {
    // Get token from either GET (initial load) or POST (form submission)
    $token = isset($_GET['token']) ? $_GET['token'] : $_POST['token'];
    
    // Verify token exists and isn't expired
    $query = "SELECT pt.*, pd.idno 
              FROM payslip_tokens pt
              JOIN payroll_details pd ON pt.payslip_id = pd.id
              WHERE pt.token = ? AND pt.expiry > NOW() AND pt.is_used = FALSE
              LIMIT 1";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        // Store verification data in session
        $_SESSION['token_verification'] = [
            'token' => $token,
            'idno' => $data['idno'],
            'salary_type' => $data['salary_type']
        ];
        
        // Show PIN entry form
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Verify Payslip Access</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
                .pin-form { margin-top: 30px; }
                input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; }
                button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
            </style>
        </head>
        <body>
            <h2>Payslip Access Verification</h2>
            <p>Please enter your PIN to view this payslip:</p>
            
            <form class="pin-form" method="POST">
                <!-- Hidden token field to maintain it through form submission -->
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <input type="password" name="pin" placeholder="Enter your 6-digit PIN" required>
                <button type="submit">Verify</button>
            </form>
            
            <?php 
            if(isset($_POST['pin'])) {
                // PIN verification logic
                $pin = $_POST['pin'];
                $token = $_POST['token'];
                $idno = $_SESSION['token_verification']['idno'];
                
                $sql = "SELECT pin FROM users WHERE idno = ? LIMIT 1";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "s", $idno);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) > 0) {
                    $user = mysqli_fetch_assoc($result);
                    
                    if($user['pin'] === $pin) {
                        // PIN correct - mark token as used
                        mysqli_query($con, "UPDATE payslip_tokens SET is_used = TRUE WHERE token = ?", [$token]);
                        
                        // Restore full session
                        $_SESSION['idno'] = $idno;
                        
                        $redirectPage = ($data['salary_type'] === 'Rated') ? "payslipRated_qr.php" : "payslip_qr.php";
                        // Redirect to actual payslip with token
                        header("Location: " . $redirectPage . "?token=" . urlencode($token));
                        exit();
                    } else {
                        echo "<p style='color:red'>Incorrect PIN. Please try again.</p>";
                    }
                } else {
                    echo "<p style='color:red'>User not found.</p>";
                }
            }
            ?>
        </body>
        </html>
        <?php
    } else {
        die("Invalid or expired token. Please generate a new QR code from your payroll view.");
    }
} else {
    die("Token required to access payslip.");
}