<?php
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_email = $_SESSION['email'];
    $user_query = mysqli_query($conn, "SELECT * FROM user WHERE email='$user_email'");
    $user = mysqli_fetch_assoc($user_query);
    $user_id = $user['id'];

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $zone = mysqli_real_escape_string($conn, $_POST['zone']);
    $pin = mysqli_real_escape_string($conn, $_POST['pin']);
    $payment_mode = 'COD';

    // Calculate subtotal and total
    $cart_query = mysqli_query($conn, "SELECT c.*, p.name AS product_name, v.variant_name, c.price FROM cart c 
        JOIN product p ON c.product_id = p.id 
        JOIN product_variant v ON c.variant_id = v.id 
        WHERE c.user_id = '$user_id'");

    $subtotal = 0;
    $cart_items = [];
    while ($item = mysqli_fetch_assoc($cart_query)) {
        $subtotal += $item['price'] * $item['quantity'];
        $cart_items[] = $item;
    }
    if (empty($cart_items)) {
        header('Location: cart.php');
        exit;
    }

    $queryShipping = "SELECT * FROM `delivery_charge` LIMIT 1";
    $dataShipping = mysqli_query($conn, $queryShipping);
    $resultShipping = mysqli_fetch_assoc($dataShipping);


    $delivery_charge = $resultShipping['shipping_charge'];
    $total_price = $subtotal + $delivery_charge;

    // Insert order
    $insert_order = "INSERT INTO orders (user_id, name, phone, address, zone, pin, subtotal, delivery_charge, total_price, payment_mode, status) 
        VALUES ('$user_id', '$name', '$phone', '$address', '$zone', '$pin', '$subtotal', '$delivery_charge', '$total_price', '$payment_mode', 'Pending')";
    mysqli_query($conn, $insert_order);

    $order_id = mysqli_insert_id($conn);

    // Insert order details
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $variant_id = $item['variant_id'];
        $product_name = $item['product_name'];
        $variant_name = $item['variant_name'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        mysqli_query($conn, "INSERT INTO order_details (order_id, product_id, variant_id, product_name, variant_name, price, quantity) 
            VALUES ('$order_id', '$product_id', '$variant_id', '$product_name', '$variant_name', '$price', '$quantity')");
    }

    // Clear cart after order placed
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

    // Optional: Update user delivery details
    // mysqli_query($conn, "UPDATE user SET name='$name', phone='$phone', zone='$zone', address='$address', pin='$pin' WHERE id='$user_id'");

    // Redirect to order success page
    header('Location: order-success.php');
    exit;
} else {
    header('Location: checkout.php');
    exit;
}
?>