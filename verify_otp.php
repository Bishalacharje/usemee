<?php
include("connection.php");
session_start();
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and OTP from the session and form input
    $email = $_SESSION['reset_email'];
    $otp_input = $_POST['otp'];

    // Fetch the stored OTP and expiry time from the database
    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_otp = $user['otp'];
        $expiry_time = $user['otp_expiry'];

        // Check if the OTP matches and if it has not expired
        if ($otp_input == $stored_otp) {
            $current_time = date("Y-m-d H:i:s");

            // If OTP is valid and not expired
            if ($current_time < $expiry_time) {
                $_SESSION['email'] = $email;
                $clearOtp = $conn->prepare("UPDATE `user` SET otp = NULL, otp_expiry = NULL WHERE email = ?");
                $clearOtp->bind_param("s", $email);
                $clearOtp->execute();
                // Redirect to the home screen after successful OTP verification
                header("Location: my_account.php"); // Adjust the home page URL accordingly
                exit();
            } else {
                $error = "OTP has expired. Please request a new one.";
            }
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>

<head>
    <title>Verify OTP</title>
</head>

<body>
    <h2>Verify OTP</h2>
    <form method="post">
        <input type="text" name="otp" placeholder="Enter the OTP" required>
        <button type="submit">Verify OTP</button>
    </form>
    <?php if (isset($error))
        echo "<p style='color:red;'>$error</p>"; ?>
</body>

</html>