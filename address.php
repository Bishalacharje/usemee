<?php
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Address | Usemee - Your one-stop online store for all your shopping needs!</title>

</head>

<body>
    <div>

        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="myAccountGrid">
                <div class="myAccountSidebar">
                    <a href="my_account.php" class="myAccountSidebarMenu">
                        My Account
                        <img src="./assets/images/imgicon/user-dark.png" alt="">
                    </a>
                    <a href="order.php" class="myAccountSidebarMenu">
                        Orders
                        <img src="./assets/images/imgicon/order.png" alt="">
                    </a>
                    <a href="address.php" class="myAccountSidebarMenu active">
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
                            <h2>Shipping Address</h2>
                            <p><a href="index.php">Home</a> - <a href="my_account.php">My Account</a> - <span>Shipping
                                    Address</span> </p>


                        </div>

                    </div>
                    <div class="note">
                        <p>The following addresses will be used on the checkout page by default.</p>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" value="<?php echo $userid ?>" name="uid">
                        <div class="formGrid grid3">
                            <div>
                                <label for="">City / Zone</label>
                                <select name="zone" class="form-select" required>

                                    <?php
                                    $queryc = "SELECT * FROM `zone`";
                                    $datac = mysqli_query($conn, $queryc);
                                    if (empty($zone)) {

                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                            echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
                                        }
                                    } else {
                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                            echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
                                        }
                                        ?>
                                        <option value="<?php echo $zone; ?>" selected><?php echo $cityName; ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <div>
                                <label for="">Pin <span id="pin-guidelines"
                                        style="font-size: 12px; color: green; margin-left: 10px;">[ Must be exactly 6
                                        digits ]</span></label>
                                <input type="text" name="pin" id="pin-input" value="<?php echo $pin ?>" maxlength="6"
                                    pattern="[0-9]{6}" required>
                            </div>
                        </div>
                        <div class="formGrid grid1">

                            <div>
                                <label for="">Street Address / Apartment / Suite / Unit</label>
                                <textarea name="address" id=""><?php echo $address ?></textarea>
                            </div>


                        </div>
                        <button type="submit" name="updateAccount">Update Address</button>
                    </form>


                    <?php
                    if (isset($_POST['updateAccount'])) {
                        // Sanitize input
                        $id = $_POST['uid'];
                        $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                        $pin = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pin']));
                        $address = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['address']));

                        // PIN validation
                        if (!preg_match('/^\d{6}$/', $pin)) {
                            // Invalid PIN format
                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                            echo "<script>
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'PIN must be exactly 6 digits.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        } else {
                            // Proceed with update
                            $query2 = "UPDATE `user` SET `zone`='$zone',`pin`='$pin',`address`='$address' WHERE `id`='$id'";
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
                                        text: 'Address Updated.',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000 
                                    }).then(() => {
                                        window.location.href = 'address.php'; 
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
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>


    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

    <script>
        const pinInput = document.getElementById("pin-input");
        const pinGuideSpan = document.getElementById("pin-guidelines");

        pinInput?.addEventListener("input", () => {
            // Allow only digits and limit to 6 characters
            pinInput.value = pinInput.value.replace(/\D/g, '').slice(0, 6);

            if (/^\d{6}$/.test(pinInput.value)) {
                pinGuideSpan.innerText = "[ ✅ Valid ]";
                pinGuideSpan.style.color = "green";
            } else {
                pinGuideSpan.innerText = "[ Must be exactly 6 digits ]";
                pinGuideSpan.style.color = "red";
            }
        });
    </script>

</body>

</html>