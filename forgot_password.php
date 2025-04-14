<?php
include("connection.php");
session_start();
date_default_timezone_set('Asia/Kolkata');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store OTP and expiry in the database
        $update_stmt = $conn->prepare("UPDATE `user` SET otp = ?, otp_expiry = ? WHERE email = ?");
        $update_stmt->bind_param("sss", $otp, $expiry, $email);
        $update_stmt->execute();

        // PHPMailer setup
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'usemee.com@gmail.com';  // Your Gmail address
            $mail->Password = 'ynqd abnf tpmj uhsk';  // Use your app password here
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('usemee.com@gmail.com', 'Password Reset');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body    = "Your OTP is: <b>$otp</b>. It will expire in 10 minutes.";

            // Send email
            if ($mail->send()) {
                $_SESSION['reset_email'] = $email;
                header("Location: verify_otp.php");
                exit();
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $error = "No account associated with this email.";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
