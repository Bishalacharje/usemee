<?php

include("../connection.php");
session_start();
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Login | Stock Management</title>
</head>

<body class="auth-body-bg">
    <div class="bg-overlay"></div>
    <div class="wrapper-page">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">

                    <div class="text-center mt-4">
                        <div class="mb-3">
                            <a href="index.html" class="auth-logo">
                                <img src="assets/images/logo-white.png" height="30" class="logo-dark mx-auto" alt="">
                                <img src="assets/images/logo-white.png" height="30" class="logo-light mx-auto" alt="">
                            </a>
                        </div>
                    </div>

                    <h4 class="text-dark text-center font-size-18"><b>Super Admin Login</b></h4>

                    <div class="p-3">
                        <form class="form-horizontal mt-3" method="post">

                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" type="text" required="" name="email"
                                        placeholder="Email">
                                </div>
                            </div>

                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" type="password" required="" name="password"
                                        placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="form-label ms-1" for="customCheck1">Remember me</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3 text-center row mt-3 pt-1">
                                <div class="col-12">
                                    <button class="btn btn-dark w-100 waves-effect waves-light" name="submit"
                                        type="submit">Log
                                        In</button>
                                </div>
                            </div>

                            <!-- <div class="form-group mb-0 row mt-2">
                                <div class="col-sm-7 mt-3">
                                    <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock"></i> Forgot
                                        your password?</a>
                                </div>
                                
                            </div> -->
                        </form>

                        <?php

                        if (isset($_POST['submit'])) {
                            $email = $_POST['email'];
                            $password = $_POST['password'];

                            // Fetch the user's hashed password from the database
                            $query = "SELECT * FROM `superadmin` WHERE email='$email'";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) === 1) {
                                // Fetch the associative array of the user data
                                $row = mysqli_fetch_assoc($result);

                                // Use password_verify() to check if the entered password matches the hashed password
                                if (password_verify($password, $row['password'])) {
                                    // Password is correct, set the session and redirect
                                    $_SESSION['super_email'] = $email;
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


                    </div>
                    <!-- end -->
                </div>
                <!-- end cardbody -->
            </div>
            <!-- end card -->
        </div>
        <!-- end container -->
    </div>
    <!-- end -->

    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>

</body>

</html>