<?php
include("../connection.php");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'");
    $order_data = mysqli_fetch_assoc($order_query);

    // Fetch order product details with seller name & address
    $products_query = mysqli_query($conn, "
    SELECT od.*, 
           p.name AS product_name, 
           v.variant_name,
           s.name AS seller_name,
           s.address AS seller_address,
           s.pin AS seller_pin
    FROM order_details od
    JOIN product p ON od.product_id = p.id
    JOIN product_variant v ON od.variant_id = v.id
    LEFT JOIN seller s ON od.seller = s.id
    WHERE od.order_id = '$order_id'
");

    $products = [];
    $has_unassigned_seller = false;

    while ($row = mysqli_fetch_assoc($products_query)) {
        if ($row['seller'] == 0) {
            $has_unassigned_seller = true;
        }
        $products[] = $row;
    }

    // Conditionally prepare the confirm form HTML
    $confirm_form_html = '';
    if ($order_data['status'] == 'pending' && !$has_unassigned_seller) {
        $confirm_form_html = '
            <form id="confirmForm" action="confirm_order.php" method="post">
                <input type="hidden" name="order_id" value="' . $order_data['id'] . '">
                <button type="submit" class="btn btn-warning btn-lg">Confirm</button>
            </form>
        ';
    }

    echo json_encode([
        "order" => $order_data,
        "products" => $products,
        "confirm_form" => $confirm_form_html
    ]);
}
?>