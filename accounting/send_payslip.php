<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_GET['id']) && isset($_GET['email'])) {
    // Sanitize inputs
    $payslip_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $employee_email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($employee_email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Path to the payslip file (PDF or HTML)
    $payslip_file = __DIR__ . "/payslip_html/payslip_$payslip_id.html"; // Change to .pdf if needed

    // Check if the file exists
    if (!file_exists($payslip_file)) {
        die("Payslip file not found.");
    }

    // PHPMailer setup
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtpout.secureserver.net'; // GoDaddy SMTP Server
        $mail->SMTPAuth = true;
        $mail->Username = 'northeastsolinc@nesistaff.com'; // Your GoDaddy Email
        $mail->Password = 'nesistaff25/!'; // Your Email Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email Settings
        $mail->setFrom('northeastsolinc@nesistaff.com', 'NorthEast Solution Inc.');
        $mail->addAddress($employee_email);
        $mail->Subject = 'Your Payslip for ' . date('F Y'); // Dynamic subject
        $mail->Body = "Dear Employee,<br><br>Please find your attached payslip for " . date('F Y') . ".<br><br>Regards,<br>NorthEast Solution Inc.";

        // Attach payslip
        $mail->addAttachment($payslip_file, 'Payslip.html'); // Change to .pdf if needed

        // Send Email
        if ($mail->send()) {
            echo "<script>alert('Payslip sent successfully!'); window.location='/accounting/success.php';</script>";
        } else {
            echo "<script>alert('Failed to send payslip.'); window.location='paysliperror.php';</script>";
        }
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}"); // Log the error
        echo "<script>alert('Failed to send payslip. Please try again later.'); window.location='paysliperror.php';</script>";
    }
} else {
    die("Invalid request.");
}
?>