<?php

$_SESSION['admin_email'];
$sprofile = $_SESSION['admin_email'];

if ($sprofile == true) {
    $queryadmin = "SELECT * FROM `admin` WHERE email ='$sprofile'";
    $dataadmin = mysqli_query($conn, $queryadmin);
    $resultadmin = mysqli_fetch_assoc($dataadmin);
    $admin_name = $resultadmin['name'];
    $admin_email = $resultadmin['email'];
    $admin_zone = $resultadmin['zone'];
} else {
    header('location:login.php');
}

?>