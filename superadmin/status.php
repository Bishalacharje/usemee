<?php
include("../connection.php");
session_start();
include("checked-login.php");

// Check if request is POST and has required parameters
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    // Sanitize input
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = (int)$_POST['status']; // Convert to integer (0 or 1)
    
    // Update the product status
    $query = "UPDATE `product` SET `status`='$status' WHERE `id`='$id'";
    $result = mysqli_query($conn, $query);
    
    // Prepare response
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    // Invalid request
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>