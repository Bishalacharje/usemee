<?php
include("../connection.php");
session_start();

if (isset($_POST['seller_id'])) {
    $seller_id = intval($_POST['seller_id']);
    $now = date('Y-m-d H:i:s');

    $query = "INSERT INTO seller_billing_status (seller_id, last_billed_at)
              VALUES ($seller_id, '$now')
              ON DUPLICATE KEY UPDATE last_billed_at = '$now'";

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>