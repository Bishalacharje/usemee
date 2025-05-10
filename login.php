<?php
error_reporting(0);
include("connection.php");
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Login | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- HEADER -->
    <div><?php include("./components/header.php"); ?></div>

    <section class="conSection otherPageSection loginSection">
        <div class="container">
            <div class="loginTitle">
                <h2>Log In</h2>
                <h2>To Your Account</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            </div>

            <div class="loginGrid">
                <div class="loginForm scrollToRevealRight">
                    <form class="form-horizontal mt-3" method="post">
                        <div class="formGrid grid1">
                            <div class="inputCon">
                                <!-- <label>Email</label> -->
                                <input type="text" required="" name="email" placeholder="Email">
                            </div>
                            <div class="inputCon">
                                <!-- <label>Password</label> -->
                                <input type="password" required="" name="password" placeholder="Password">
                            </div>
                        </div>




                        <div class="form-group mb-3 text-center row mt-3 pt-1">
                            <div class="col-12">
                                <button class="btn btn-info w-100 waves-effect waves-light" name="submit"
                                    type="submit">Log
                                    In</button>
                                <p style="text-align: center; margin-top: 10px;">
                                    <a href="forgot_password.php">Forgot Password?</a>
                                </p>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="loginRight scrollToRevealLeft">
                    <h3>DON'T HAVE AN ACCOUNT?</h3>
                    <p>Add items to your wishlistget personalised recommendations
                        check out more quickly track your orders register</p>
                    <a href="signup.php"><button>Sign Up</button></a>
                </div>

            </div>



        </div>
    </section>




    <?php

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch the user's hashed password from the database
        $query = "SELECT * FROM `user` WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            // Fetch the associative array of the user data
            $row = mysqli_fetch_assoc($result);

            // Use password_verify() to check if the entered password matches the hashed password
            if (password_verify($password, $row['password'])) {
                // Password is correct, set the session and redirect
                $_SESSION['email'] = $email;
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
Swal.fire({
icon: 'success',
title: 'Login Successful',
text: 'You will be redirected shortly.',
showConfirmButton: false,
timer: 2000 // Auto close after 2 seconds
}).then(() => {
window.location.href = 'index.php'; // Redirect after the alert
});
</script>";
            } else {
                // Password is incorrect
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
Swal.fire({
icon: 'error',
title: 'Login Failed',
text: 'Invalid email or password.',
confirmButtonText: 'Try Again'
});
</script>";
            }
        } else {
            // No user found with the given email
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
Swal.fire({
icon: 'error',
title: 'Login Failed',
text: 'Invalid email or password.',
confirmButtonText: 'Try Again'
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