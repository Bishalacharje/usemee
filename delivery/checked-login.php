<?php

$_SESSION['email'];
$sprofile = $_SESSION['email'];

if ($sprofile == true) {
    $queryadmin = "SELECT * FROM `delivery` WHERE email ='$sprofile'";
    $dataadmin = mysqli_query($conn, $queryadmin);
    $resultadmin = mysqli_fetch_assoc($dataadmin);
    $delivery_id = $resultadmin['id'];
    $delivery_name = $resultadmin['name'];
    $delivery_email = $resultadmin['email'];
    $delivery_zone = $resultadmin['zone'];
    $delivery_photo = $resultadmin['photo'];
} else {
    header('location:login.php');
}

?>