<?php
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <?php include("./components/headlink.php"); ?>
    <title>My Cart - eCommerce Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>

<body>
    <header>
        <?php include("./components/topHeader.php"); ?>
        <?php include("./components/header.php"); ?>
        <?php include("./components/navbar.php"); ?>
    </header>

    <main>
        <div class="container">
            <h1>Profile</h1>
            <br>
            <h2><?php echo $user_name; ?></h2>
            <h5><?php echo $user_email; ?></h5>
        </div>
    </main>



    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>






</body>

</html>