<?php
error_reporting(0);
include("connection.php");
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Usemee - Signup</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- HEADER -->
    <div><?php include("./components/header.php"); ?></div>

    <section class="conSection otherPageSection loginSection">
        <div class="container">
            <div class="loginTitle">
                <h2>Sign Up</h2>
                <h2>To Your Account</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="signupCon">
                <form class="form-horizontal mt-3" method="post">
                    <div class="formGrid grid1">
                        <div> <input type="text" required="" name="name" placeholder="Full name*"></div>
                        <div>
                            <input type="number" required="" name="phone" placeholder="Phone Number*">
                        </div>
                        <div>
                            <input type="email" required="" name="email" placeholder="Email*">
                        </div>
                        <div>
                            <input type="password" required="" name="password" placeholder="Password">
                        </div>
                        <div class="chekboxCon">


                            <input type="checkbox" id="c1" required>
                            <label for="c1"> I consent to Usemee processing my personal data to send me personalized marketing content, 
                                in accordance with the consent form and privacy policy.</label>
                        </div>
                        <div class="chekboxCon">
                            <input type="checkbox" id="c2" required>
                            <label for="c2"> By clicking "create account", I consent to the privacy
                                policy.</label>
                        </div>

                        <button class="btn btn-info w-100 waves-effect waves-light" name="submit" type="submit">Sign
                            Up</button>
                    </div>

                </form>
                <div class="signUpFooter">
                    <p>By creating an account, you agree to our:</p>
                    <div class="signupFlex">
                        <a href="#">Terms & Condition</a>
                        |
                        <a href="#">Privacy Policy</a>
                    </div>
                    <a href="login.php">ALREADY HAVE AN ACCOUNT ?</a>
                </div>

            </div>

        </div>
    </section>

    <?php
    if (isset($_POST['submit'])) {
        // Sanitize input data
        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
        $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
        // Password_validation
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        $minLength = strlen($password) >= 8;

        $errors = [];

        if (!$uppercase) {
            $errors[] = "• Password must include at least one uppercase letter.";
        }
        if (!$lowercase) {
            $errors[] = "• Password must include at least one lowercase letter.";
        }
        if (!$number) {
            $errors[] = "• Password must include at least one number.";
        }
        if (!$specialChars) {
            $errors[] = "• Password must include at least one special character.";
        }
        if (!$minLength) {
            $errors[] = "• Password must be at least 8 characters long.";
        }

        if (!empty($errors)) {
            $errorMessage = implode("<br>", $errors);
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Weak Password',
                    html: '$errorMessage'
                });
            </script>";
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into the database
        $query2 = "INSERT INTO `user`(`name`, `phone`, `email`, `password`) VALUES ('$name','$phone','$email','$hashedPassword')";

        // Execute the query
        $data2 = mysqli_query($conn, $query2);

        // Check if data was inserted
        if ($data2) {
            $_SESSION['email'] = $email;
            // SweetAlert for successful creation
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Account Created',
                    showConfirmButton: false,
                    timer: 2000 // Auto close after 2 seconds
                }).then(() => {
                    window.location.href = 'index.php'; // Redirect after the alert
                });
              </script>";
        } else {
            // Output error with SweetAlert
            $errorMessage = mysqli_error($conn); // Capture the MySQL error
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create user. Error: $errorMessage',
                    confirmButtonText: 'OK'
                });
              </script>";
        }

        // Close the database connection
        mysqli_close($conn);
    }
    ?>

    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

</body>

</html>