<?php
include("../connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);

    // Delete query
    $query = "DELETE FROM product_variant WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    $response = [];
    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["success"] = false;
    }

    echo json_encode($response);
}
?>