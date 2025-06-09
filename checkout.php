<?php
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");

$user_email = $_SESSION['email'];
$queryadmin = "SELECT * FROM `user` WHERE email ='$user_email'";
$dataadmin = mysqli_query($conn, $queryadmin);
$resultadmin = mysqli_fetch_assoc($dataadmin);
$user_id = $resultadmin['id'];

// Fetch delivery details if available
$name = $resultadmin['name'];
$phone = $resultadmin['phone'];
$zone = $resultadmin['zone'];

if (empty($zone) || empty($address) || empty($pin)) {

} else {
    $queryn = "SELECT * FROM `zone` WHERE `id`='$zone'";
    $datan = mysqli_query($conn, $queryn);
    $resultn = mysqli_fetch_assoc($datan);
    $cityName = $resultn['city'];
    $address = $resultadmin['address'];
    $pin = $resultadmin['pin'];
}



// Fetch cart items
$cart_query = mysqli_query($conn, "SELECT c.*, p.name, v.variant_name FROM cart c 
JOIN product p ON c.product_id = p.id 
JOIN product_variant v ON c.variant_id = v.id
WHERE c.user_id = '$user_id'");

$cart_items = [];
$sub_total = 0;
while ($row = mysqli_fetch_assoc($cart_query)) {
    $cart_items[] = $row;
    $sub_total += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}
$queryShipping = "SELECT * FROM `delivery_charge` LIMIT 1";
$dataShipping = mysqli_query($conn, $queryShipping);
$resultShipping = mysqli_fetch_assoc($dataShipping);


$delivery_cost = $resultShipping['shipping_charge'];
$total_price = $sub_total + $delivery_cost;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Checkout | Usemee - Your one-stop online store for all your shopping needs!</title>
    <style>
        .error-message {
            color: #d9534f;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>

<body>
    <div><?php include("./components/header.php"); ?></div>


    <section class="conSection otherPageSection">
        <div class="container">

            <div class="title">
                <div>
                    <h2>Checkout</h2>
                    <p><a href="index.php">Home</a> - <a href="cart.php">Cart</a> - <span>Checkout</span> </p>
                </div>
            </div>
            <div class="checkoutGrid">
                <div>

                    <form action="place_order.php" method="POST" id="checkoutForm">
                        <h3>Shipping Address</h3>

                        <div class="chekoutBox">
                            <div class="formGrid">
                                <div class="inputCon">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo $name; ?>" required>
                                </div>

                                <div class="inputCon">
                                    <label>Phone No</label>
                                    <input type="text" name="phone_no" id="phone_no" value="<?php echo $phone; ?>"
                                        pattern="[0-9]{10}" maxlength="10" required>
                                    <span class="error-message" id="phone-error">Phone number must be exactly 10
                                        digits</span>
                                </div>

                            </div>
                            <div class="formGrid grid3">

                                <div class="inputCon">
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
                                <div class="inputCon">
                                    <label>Pin</label>
                                    <input type="text" name="pin" id="pin" value="<?php echo $pin; ?>"
                                        pattern="[0-9]{6}" maxlength="6" required>
                                    <span class="error-message" id="pin-error">PIN must be exactly 6 digits</span>
                                </div>
                            </div>

                            <div>
                                <label>Street Address / Apartment / Suite / Unit</label>
                                <textarea name="address" required><?php echo $address; ?></textarea>
                            </div>

                        </div>
                        <h3>Payment Method</h3>

                        <div class="chekoutBox">
                            <div class="radioInput">
                                <input type="radio" name="payment_mode" value="COD" id="cod" required>
                                <label for="cod">Cash on Delivery</label>
                            </div>

                        </div>
                        <p>Your personal data will be used to process your order, support your experience throughout
                            this website, and for other purposes described in our privacy policy.</p>
                        <br>
                        <button type="submit">Place Order</button>
                    </form>
                </div>
                <div class="chekoutOrders">
                    <h3>Order Items</h3>
                    <div class="checkoutOrderBox">
                        <ul>
                            <?php foreach ($cart_items as $item) { ?>
                                <li>
                                    <div>
                                        <?php echo $item['name']; ?> (<?php echo $item['variant_name']; ?>) x
                                        <?php echo $item['quantity']; ?>
                                    </div>
                                    ₹<?php echo $item['price'] * $item['quantity']; ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <h3>Sub Total: <span>₹<?php echo $sub_total; ?></span></h3>
                        <h6>Delivery Cost: <span>₹<?php echo $delivery_cost; ?></span></h6>
                        <h2>Order Total: <span>₹<?php echo $total_price; ?></span></h2>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkoutForm');
            const phoneInput = document.getElementById('phone_no');
            const pinInput = document.getElementById('pin');
            const phoneError = document.getElementById('phone-error');
            const pinError = document.getElementById('pin-error');

            // Phone validation
            phoneInput.addEventListener('input', function () {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Show error if not exactly 10 digits
                if (this.value.length > 0 && this.value.length !== 10) {
                    phoneError.style.display = 'block';
                } else {
                    phoneError.style.display = 'none';
                }
            });

            // PIN validation
            pinInput.addEventListener('input', function () {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Show error if not exactly 6 digits
                if (this.value.length > 0 && this.value.length !== 6) {
                    pinError.style.display = 'block';
                } else {
                    pinError.style.display = 'none';
                }
            });

            // Form submission validation
            form.addEventListener('submit', function (e) {
                // Check phone validation
                if (phoneInput.value.length !== 10) {
                    e.preventDefault();
                    phoneError.style.display = 'block';
                    phoneInput.focus();
                    return false;
                }

                // Check PIN validation
                if (pinInput.value.length !== 6) {
                    e.preventDefault();
                    pinError.style.display = 'block';
                    pinInput.focus();
                    return false;
                }

                return true;
            });
        });
    </script>
</body>