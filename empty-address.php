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
                        <label for="">Pin <span id="pin-guidelines"
                                style="font-size: 12px; color: green; margin-left: 10px;">[ Must be exactly 6 digits
                                ]</span></label>
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
                <button type="submit" name="updateAddress">Add Address</button>
            </form>

            <?php
            if (isset($_POST['updateAddress'])) {
                // Sanitize input
                $nid = $_POST['uid'];
                $nzone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                $npin = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pin']));
                $naddress = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['address']));

                // PIN validation
                if (!preg_match('/^\d{6}$/', $npin)) {
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