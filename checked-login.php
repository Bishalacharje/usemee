<?php

$_SESSION['email'];
$sprofile = $_SESSION['email'];

if ($sprofile == true) {
    $queryuser = "SELECT * FROM `user` WHERE email ='$sprofile'";
    $datauser = mysqli_query($conn, $queryuser);
    $totaluser = mysqli_num_rows($datauser);
    $resultuser = mysqli_fetch_assoc($datauser);
    $userid = $resultuser['id'];
    $user_name = $resultuser['name'];
    $user_phone = $resultuser['phone'];
    $user_email = $resultuser['email'];
    $zone = $resultuser['zone'];
    $address = $resultuser['address'];
    $pin = $resultuser['pin'];
    $showModal = false;

    if (empty($zone) || empty($address) || empty($pin)) {
        $showModal = true;

    } else {
        $queryn = "SELECT * FROM `zone` WHERE `id`='$zone'";
        $datan = mysqli_query($conn, $queryn);
        $resultn = mysqli_fetch_assoc($datan);
        $cityName = $resultn['city'];
        $showModal = false;
    }



} else {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <div id="customModal" class="address-modal-overlay">
        <div class="address-modal-content">
            <span class="close-button" onclick="closeModal()">×</span>
            <div class="myAccountCon">
                <div class="title">
                    <div>
                        <h2>Add Shipping Address</h2>

                    </div>

                </div>
                <form action="" method="post">
                    <input type="hidden" value="<?php echo $userid ?>" name="uid">
                    <div class="formGrid grid3">
                        <div>
                            <label for="">City / Zone</label>
                            <select name="zone" class="form-select" required>

                                <?php

                                if (empty($zone)) {
                                    $queryc = "SELECT * FROM `zone`";
                                    $datac = mysqli_query($conn, $queryc);
                                    while ($resultc = mysqli_fetch_assoc($datac)) {
                                        echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
                                    }
                                } else {
                                    ?>
                                    <option value="<?php echo $zone; ?>" selected><?php echo $cityName; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="">Pin</label>
                            <input type="text" name="pin" value="<?php echo $pin ?>" required>
                        </div>
                    </div>
                    <div class="formGrid grid1">

                        <div>
                            <label for="">Street Address / Apartment / Suite / Unit</label>
                            <textarea name="address" id=""><?php echo $address ?></textarea>
                        </div>


                    </div>
                    <button type="submit" name="updateAddress">Add Address</button>
                </form>

                <?php
                if (isset($_POST['updateAddress'])) {



                    // Sanitize input
                    $nid = $_POST['uid'];
                    $nzone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                    $npin = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pin']));
                    $naddress = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['address']));

                    $nquery2 = "UPDATE `user` SET `zone`='$nzone',`pin`='$npin',`address`='$naddress' WHERE `id`='$nid'";
                    // Execute the query
                    $ndata2 = mysqli_query($conn, $nquery2);

                    // Start output buffering
                    ob_start();

                    // Success alert
                    if ($ndata2) {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Address Added.',
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
                ?>






            </div>
        </div>
    </div>


    <script>
        function closeModal() {
            document.getElementById('customModal').style.display = 'none';
        }

        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($showModal): ?>
                document.getElementById('customModal').style.display = 'flex';
            <?php endif; ?>
        });
    </script>

</body>

</html>