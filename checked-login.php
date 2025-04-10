<?php

$_SESSION['email'];
$sprofile = $_SESSION['email'];

if ($sprofile == true) {
    $queryuser = "SELECT * FROM `user` WHERE email ='$sprofile'";
    $datauser = mysqli_query($conn, $queryuser);
    $totaluser = mysqli_num_rows($datauser);
    $resultuser = mysqli_fetch_assoc($datauser);
    $userid = $resultuser['id'];
    $user_name = $resultuser['name'];
    $user_phone = $resultuser['phone'];
    $user_email = $resultuser['email'];
    $zone = $resultuser['zone'];
    $address = $resultuser['address'];
    $pin = $resultuser['pin'];

    $queryn = "SELECT * FROM `zone` WHERE `id`='$zone'";
    $datan = mysqli_query($conn, $queryn);
    $resultn = mysqli_fetch_assoc($datan);
    $cityName = $resultn['city'];

} else {
    header('location:login.php');
}




?>