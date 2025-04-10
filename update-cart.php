<?php
include("connection.php");
session_start();

header('Content-Type: application/json');

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    $update = mysqli_query($conn, "UPDATE cart SET quantity = '$quantity' WHERE id = '$cart_id'");
    if ($update) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
} else {
    echo json_encode(["status" => "invalid"]);
}
?>