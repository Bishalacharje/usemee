<?php

$_SESSION['super_email'];
$sprofile = $_SESSION['super_email'];

if ($sprofile == true) {
    $queryadmin = "SELECT * FROM `superadmin` WHERE email ='$sprofile'";
    $dataadmin = mysqli_query($conn, $queryadmin);
    $resultadmin = mysqli_fetch_assoc($dataadmin);
    $admin_name = $resultadmin['name'];
    $admin_email = $resultadmin['email'];
} else {
    header('location:login.php');
}

?>