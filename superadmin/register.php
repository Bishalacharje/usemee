<?php

include("../connection.php");

?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Register | Stock Management</title>
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
                                <img src="assets/images/logo-dark.png" height="30" class="logo-dark mx-auto" alt="">
                                <img src="assets/images/logo-light.png" height="30" class="logo-light mx-auto" alt="">
                            </a>
                        </div>
                    </div>

                    <h4 class="text-muted text-center font-size-18"><b>Register</b></h4>

                    <div class="p-3">
                        <form class="form-horizontal mt-3" action="" method="POST">

                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" name="name" type="text" required=""
                                        placeholder="Full Name">
                                </div>
                            </div>


                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" name="email" type="email" required=""
                                        placeholder="Email">
                                </div>
                            </div>


                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" name="password" type="password" required=""
                                        placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="form-label ms-1 fw-normal" for="customCheck1">I accept <a href="#"
                                                class="text-muted">Terms and Conditions</a></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center row mt-3 pt-1">
                                <div class="col-12">
                                    <button class="btn btn-info w-100 waves-effect waves-light" name="submit"
                                        type="submit">Register</button>
                                </div>
                            </div>

                            <div class="form-group mt-2 mb-0 row">
                                <div class="col-12 mt-3 text-center">
                                    <a href="pages-login.html" class="text-muted">Already have account?</a>
                                </div>
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['submit'])) {
                            // Sanitize input data
                            $adminName = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                            $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                            $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

                            // Hash the password
                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);




                            // Insert data into the database
                            $query2 = "INSERT INTO `superadmin`(`name`, `email`, `password`) VALUES ('$adminName','$email','$hashedPassword')";

                            // Execute the query
                            $data2 = mysqli_query($conn, $query2);

                            // Check if data was inserted
                            if ($data2) {
                                // SweetAlert for successful creation
                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Super Admin Created',
                    showConfirmButton: false,
                    timer: 2000 // Auto close after 2 seconds
                }).then(() => {
                    window.location.href = 'register.php'; // Redirect after the alert
                });
              </script>";
                            } else {
                                // Output error with SweetAlert
                                $errorMessage = mysqli_error($conn); // Capture the MySQL error
                                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create admin. Error: $errorMessage',
                    confirmButtonText: 'OK'
                });
              </script>";
                            }

                            // Close the database connection
                            mysqli_close($conn);
                        }
                        ?>

                        <!-- end form -->
                    </div>
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