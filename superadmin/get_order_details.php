<?php
include("../connection.php");

header("Content-Type: application/json");

if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

    // Fetch order details
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'");

    if (!$order_query || mysqli_num_rows($order_query) == 0) {
        echo json_encode(["error" => "Order not found"]);
        exit;
    }

    $order_data = mysqli_fetch_assoc($order_query);

    // Fetch order product details with product name, variant, and seller
    $products_query = mysqli_query($conn, "
        SELECT od.*, 
               p.name AS product_name, 
               v.variant_name,
               s.name AS seller_name
        FROM order_details od
        JOIN product p ON od.product_id = p.id
        JOIN product_variant v ON od.variant_id = v.id
        LEFT JOIN seller s ON od.seller = s.id
        WHERE od.order_id = '$order_id'
    ");

    $products = [];
    while ($row = mysqli_fetch_assoc($products_query)) {
        $products[] = $row;
    }

    echo json_encode([
        "order" => $order_data,
        "products" => $products
    ]);
} else {
    echo json_encode(["error" => "Order ID not provided"]);
}
?>