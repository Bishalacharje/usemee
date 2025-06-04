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

    // Get product details from POST
    if (!isset($_POST['product_id']) || !isset($_POST['variant_id']) || !isset($_POST['variant_data'])) {
        echo "Invalid request.";
        exit;
    }

    $product_id = $_POST['product_id'];
    $variant_id = $_POST['variant_id'];
    $variant_data = json_decode($_POST['variant_data'], true);
    $price = $variant_data['sale_price'];
    $variant_name = $variant_data['variant_name'];
    $quantity = 1;

    // Get product name from database
    $product_query = mysqli_query($conn, "SELECT name FROM product WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($product_query);
    $product_name = $product['name'];

    $subtotal = $price * $quantity;
    $delivery_charge = 49;
    $total_price = $subtotal + $delivery_charge;

    // Insert order
    $insert_order = "INSERT INTO orders (user_id, name, phone, address, zone, pin, subtotal, delivery_charge, total_price, payment_mode, status) 
        VALUES ('$user_id', '$name', '$phone', '$address', '$zone', '$pin', '$subtotal', '$delivery_charge', '$total_price', '$payment_mode', 'Pending')";
    mysqli_query($conn, $insert_order);

    $order_id = mysqli_insert_id($conn);

    // Insert order item
    mysqli_query($conn, "INSERT INTO order_details (order_id, product_id, variant_id, product_name, variant_name, price, quantity) 
        VALUES ('$order_id', '$product_id', '$variant_id', '$product_name', '$variant_name', '$price', '$quantity')");

    // Redirect to success page
    header('Location: order-success.php');
    exit;
} else {
    header('Location: checkout_single.php');
    exit;
}
?>