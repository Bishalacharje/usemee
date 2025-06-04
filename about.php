<?php
error_reporting(0);
include("connection.php");
include("enc_dec.php");
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>About | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- HEADER -->
    <div><?php include("./components/header.php"); ?></div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="aboutGrid">
                <img src="https://broccoli-omega.vercel.app/_next/image?url=%2Fimg%2Fothers%2F6.png&w=1200&q=75" alt="">
                <div>
                    <h6>Know More About Usemee</h6>
                    <h1>Your one-stop online store</h1>
                    <p>Welcome to UseMee, your one-stop online store for all your shopping needs! At
                        UseMee, we aim to provide a seamless and convenient shopping experience, offering a wide range
                        of products, including groceries, fresh fish & meat, clothing, electronics, and more. Our
                        mission is to bring quality products to your doorstep with just a few clicks. We are committed
                        to customer satisfaction, affordability, and innovation.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>



</body>

</html>