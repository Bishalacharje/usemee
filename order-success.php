<?php
include("connection.php");
session_start();
include("checked-login.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>My Cart - eCommerce Website</title>

</head>

<body>
    <div>

        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="emptyCartCon">
                <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
                    type="module"></script>
                <dotlottie-player src="https://lottie.host/037a8e60-9d0a-4005-abb6-3082fea8b726/jOdzqc2z0l.lottie"
                    background="transparent" speed="0.5" style="width: 160px; height: 160px"
                    autoplay></dotlottie-player>
                <h2>Order Placed</h2>
                <p>Our team will call you to confirm the products from your order.</p>
                <div class="centerBtnContainer">
                    <a href="order.php"><button>View Orders</button></a>
                    <a href="shop.php"><button class="continueBtn">Continue Shopping</button></a>
                </div>

            </div>
        </div>
    </section>



    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>











</body>