<?php
include("connection.php");
session_start();
include("checked-login.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>My Account | Usemee - Your one-stop online store for all your shopping needs!</title>

</head>

<body>
    <div>

        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="myAccountGrid">
                <div class="myAccountSidebar">
                    <a href="my_account.php" class="myAccountSidebarMenu active">
                        My Account
                        <img src="./assets/images/imgicon/user-dark.png" alt="">
                    </a>
                    <a href="order.php" class="myAccountSidebarMenu">
                        Orders
                        <img src="./assets/images/imgicon/order.png" alt="">
                    </a>
                    <a href="address.php" class="myAccountSidebarMenu">
                        Address
                        <img src="./assets/images/imgicon/location-dark.png" alt="">
                    </a>
                    <a href="logout.php" class="myAccountSidebarMenu">
                        Logout
                        <img src="./assets/images/imgicon/power-off.png" alt="">
                    </a>
                </div>
                <div class="myAccountCon">
                    <div class="title">
                        <div>
                            <h2>My Account</h2>
                            <p><a href="index.php">Home</a> - <span>My Account</span> </p>


                        </div>

                    </div>
                    <form action="" method="post">
                        <input type="hidden" value="<?php echo $userid ?>" name="uid">
                        <div class="formGrid grid1">
                            <div>
                                <label for="">Full Name</label>
                                <input type="text" name="name" value="<?php echo $user_name ?>" required>
                            </div>
                        </div>
                        <div class="formGrid">

                            <div>
                                <label for="">Phone number</label>
                                <input type="number" name="phone" value="<?php echo $user_phone ?>" required>
                            </div>
                            <div>
                                <label for="">Email</label>
                                <input type="email" name="email" value="<?php echo $user_email ?>" required>
                            </div>

                        </div>
                        <button type="submit" name="updateAccount">Update Profile</button>
                    </form>


                    <?php
                    if (isset($_POST['updateAccount'])) {

                        // Sanitize input
                        $id = $_POST['uid'];
                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));

                        $query2 = "UPDATE `user` SET `name`='$name',`phone`='$phone',`email`='$email' WHERE `id`='$id'";
                        // Execute the query
                        $data2 = mysqli_query($conn, $query2);

                        // Start output buffering
                        ob_start();

                        // Success alert
                        if ($data2) {
                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                            echo "<script>
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Profile Updated.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000 
                                }).then(() => {
                                    window.location.href = 'logout.php'; 
                                });
                            </script>";
                        } else {
                            // Error alert
                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                            echo "<script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        }

                        // Flush output buffer
                        ob_end_flush();
                    }
                    ?>


                    <div class="profileBox">
                        <h3>Change Password</h3>
                        <br>
                        <form action="" method="post">
                            <input type="hidden" value="<?php echo $userid ?>" name="uid">
                            <div class="formGrid" style="align-items: end;">
                                <div>
                                    <label for="">New Password</label>
                                    <input type="password" name="password" required>
                                </div>
                                <button type="submit" name="updatePassword" class="updatePassword">Update
                                    Password</button>
                            </div>

                        </form>
                        <?php
                        if (isset($_POST['updatePassword'])) {



                            // Sanitize input
                            $id = $_POST['uid'];
                            $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
                            // Password_validation
                            $uppercase = preg_match('@[A-Z]@', $password);
                            $lowercase = preg_match('@[a-z]@', $password);
                            $number = preg_match('@[0-9]@', $password);
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

                            $query2 = "UPDATE `user` SET `password`='$hashedPassword' WHERE `id`='$id'";
                            // Execute the query
                            $data2 = mysqli_query($conn, $query2);

                            // Start output buffering
                            ob_start();

                            // Success alert
                            if ($data2) {
                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                echo "<script>
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Password Updated.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000 
                                }).then(() => {
                                    window.location.href = 'logout.php'; 
                                });
                            </script>";
                            } else {
                                // Error alert
                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                echo "<script>
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            }

                            // Flush output buffer
                            ob_end_flush();
                        }
                        ?>
                    </div>


                </div>
            </div>


        </div>
    </section>


    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>











</body>

</html>