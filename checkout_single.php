<?php
session_start();
include("connection.php");
include("enc_dec.php");
include("checked-login.php");

// Validate required POST data
if (!isset($_POST['product_id']) || !isset($_POST['variant_id']) || !isset($_POST['variant_data'])) {
    echo "Invalid request.";
    exit;
}

$product_id = $_POST['product_id'];
$variant_id = $_POST['variant_id'];
$variant_data = json_decode($_POST['variant_data'], true);
$price = $variant_data['sale_price'];
$quantity = 1;

$user_email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$user_email'");
$user = mysqli_fetch_assoc($user_query);
$user_id = $user['id'];

// Load only the selected product for order summary
$product_query = mysqli_query($conn, "SELECT p.name AS product_name, v.variant_name 
    FROM product p 
    JOIN product_variant v ON v.id = '$variant_id' AND v.product_id = p.id 
    WHERE p.id = '$product_id'");

if (!$product_query || mysqli_num_rows($product_query) === 0) {
    echo "Product not found.";
    exit;
}

$product = mysqli_fetch_assoc($product_query);
$cart_items = [
    [
        'product_name' => $product['product_name'],
        'variant_name' => $product['variant_name'],
        'price' => $price,
        'quantity' => $quantity
    ]
];
$subtotal = $price * $quantity;
$delivery_charge = 49;
$total_price = $subtotal + $delivery_charge;

// Prefill address
$name = $user['name'];
$phone = $user['phone'];
$zone = $user['zone'];
$address = $user['address'];
$pin = $user['pin'];
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
    <?php include("./components/header.php"); ?>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="title">
                <div>
                    <h2>Checkout</h2>
                    <p><a href="index.php">Home</a> - <span>Checkout</span></p>
                </div>
            </div>

            <div class="checkoutGrid">
                <div>
                    <form action="place_order_single.php" method="POST" id="checkoutForm">
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
                                    <label>City / Zone</label>
                                    <select name="zone" class="form-select" required>
                                        <?php
                                        $zone_query = mysqli_query($conn, "SELECT * FROM `zone`");
                                        while ($z = mysqli_fetch_assoc($zone_query)) {
                                            $selected = ($z['id'] == $zone) ? 'selected' : '';
                                            echo "<option value='{$z['id']}' $selected>{$z['city']}</option>";
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

                        <br>
                        <button type="submit">Place Order</button>

                        <!-- Send the selected product/variant info with the order -->
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="variant_id" value="<?php echo $variant_id; ?>">
                        <input type="hidden" name="variant_data" value='<?php echo json_encode($variant_data); ?>'>
                        <input type="hidden" name="quantity" value="1">
                    </form>
                </div>

                <div class="chekoutOrders">
                    <h3>Order Summary</h3>
                    <div class="checkoutOrderBox">
                        <ul>
                            <?php foreach ($cart_items as $item): ?>
                                <li>
                                    <div>
                                        <?php echo $item['product_name']; ?> (<?php echo $item['variant_name']; ?>) x
                                        <?php echo $item['quantity']; ?>
                                    </div>
                                    ₹<?php echo $item['price'] * $item['quantity']; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <h3>Sub Total: <span>₹<?php echo $subtotal; ?></span></h3>
                        <h6>Delivery Cost: <span>₹<?php echo $delivery_charge; ?></span></h6>
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

</html>