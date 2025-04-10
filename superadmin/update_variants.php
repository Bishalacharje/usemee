<?php
include("../connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $variant_name = mysqli_real_escape_string($conn, $_POST["variant_name"]);
    $mrp = floatval($_POST["mrp"]);
    $sale_price = floatval($_POST["sale_price"]);

    $query = "UPDATE product_variant SET variant_name = ?, mrp = ?, sale_price = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdii", $variant_name, $mrp, $sale_price, $id);

    $response = [];
    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["success"] = false;
    }

    echo json_encode($response);
}
?>