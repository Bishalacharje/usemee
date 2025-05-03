<?php
session_start();
include("connection.php");
include("checked-login.php");

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

// Insert product into cart only if not already there
$check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND variant_id = '$variant_id'");
if (mysqli_num_rows($check_cart) == 0) {
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, variant_id, price, quantity) VALUES ('$user_id', '$product_id', '$variant_id', '$price', '$quantity')");
}

// Load cart items
$cart_query = mysqli_query($conn, "SELECT c.*, p.name AS product_name, v.variant_name FROM cart c
    JOIN product p ON c.product_id = p.id
    JOIN product_variant v ON c.variant_id = v.id
    WHERE c.user_id = '$user_id'");
$cart_items = [];
$subtotal = 0;
while ($item = mysqli_fetch_assoc($cart_query)) {
    $subtotal += $item['price'] * $item['quantity'];
    $cart_items[] = $item;
}
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
    <title>Checkout</title>
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
                    <form action="place_order.php" method="POST">
                        <h3>Shipping Address</h3>
                        <div class="chekoutBox">
                            <div class="formGrid">
                                <div class="inputCon">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo $name; ?>" required>
                                </div>
                                <div class="inputCon">
                                    <label>Phone No</label>
                                    <input type="text" name="phone_no" value="<?php echo $phone; ?>" required>
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
                                    <input type="text" name="pin" value="<?php echo $pin; ?>" required>
                                </div>
                            </div>

                            <div>
                                <label>Address</label>
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
</body>

</html>