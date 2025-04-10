<?php
include("../connection.php");

if (isset($_POST['categoryId'])) {
    $categoryId = $_POST['categoryId'];

    $query = "SELECT * FROM `subcategory` WHERE `categoryId` = '$categoryId'";
    $result = mysqli_query($conn, $query);

    echo '<option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}
?>