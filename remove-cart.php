<?php
include("connection.php");
include("enc_dec.php");
session_start();

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    mysqli_query($conn, "DELETE FROM cart WHERE id = '$cart_id'");
    header("Location: cart.php?removed=1");
} else {
    echo "Invalid request.";
}
?>