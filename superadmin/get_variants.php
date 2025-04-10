<?php
include("../connection.php"); // Include your database connection file

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    $query = "SELECT * FROM product_variant WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $variants = [];
    while ($row = $result->fetch_assoc()) {
        $variants[] = $row;
    }

    echo json_encode($variants);
} else {
    echo json_encode([]);
}

?>