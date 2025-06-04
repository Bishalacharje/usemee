<?php
include("connection.php");
include("enc_dec.php");
session_start();

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['email'])) {
    echo json_encode([
        'status' => 'not_logged_in',
        'message' => 'Please log in to add items to cart.'
    ]);
    exit();
}

// Get logged-in user
$sprofile = $_SESSION['email'];
$queryadmin = "SELECT * FROM `user` WHERE email ='$sprofile'";
$dataadmin = mysqli_query($conn, $queryadmin);
$resultadmin = mysqli_fetch_assoc($dataadmin);
$user_id = $resultadmin['id'];

// Add to cart logic
if (isset($_POST['product_id']) && isset($_POST['variant_data'])) {
    $product_id = $_POST['product_id'];
    $variant_data = json_decode($_POST['variant_data'], true);
    $variant_id = $variant_data['id'];
    $variant_name = $variant_data['variant_name'];
    $sale_price = $variant_data['sale_price'];

    // Check if already in cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND variant_id = '$variant_id'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode([
            'status' => 'exists',
            'message' => 'Product is already in your cart.'
        ]);
    } else {
        $insert = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, variant_id, variant_name, price, quantity) VALUES ('$user_id', '$product_id', '$variant_id', '$variant_name', '$sale_price', 1)");
        if ($insert) {
            echo json_encode([
                'status' => 'added',
                'message' => 'Product added to your cart.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error adding product to cart. Please try again.'
            ]);
        }
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Missing product data.'
    ]);
}
?>