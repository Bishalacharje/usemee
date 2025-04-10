<?php
include("../connection.php");

if (isset($_GET['product_id']) && isset($_GET['district'])) {
    $product_id = $_GET['product_id'];
    $district = $_GET['district'];

    // Get product category
    $productQuery = mysqli_query($conn, "SELECT category FROM product WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($productQuery);
    $category = $product['category'];

    // Fetch sellers matching category & district
    $sellerQuery = mysqli_query($conn, "SELECT id, name FROM seller WHERE category = '$category' AND district = '$district'");
    $sellers = [];
    while ($row = mysqli_fetch_assoc($sellerQuery)) {
        $sellers[] = $row;
    }

    echo json_encode($sellers);
}
?>