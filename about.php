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
                <img class="scrollToRevealRight"
                    src="https://broccoli-omega.vercel.app/_next/image?url=%2Fimg%2Fothers%2F6.png&w=1200&q=75" alt="">
                <div class="scrollToRevealLeft aboutText">
                    <p class="bradCrumb"><a href="index.php">Home</a> - <span>About Us</span>
                    </p>
                    <!-- <h6>Know More About Usemee</h6> -->
                    <h1>Trusted Organic <br>
                        Online Store</h1>
                    <p class="borderText">Welcome to UseMee, your one-stop online store for all your shopping needs!</p>
                    <p>Welcome to UseMee, your one-stop online store for all your shopping needs! At
                        UseMee, we aim to provide a seamless and convenient shopping experience, offering a wide range
                        of products, including groceries, fresh fish & meat, clothing, electronics, and more. Our
                        mission is to bring quality products to your doorstep with just a few clicks. We are committed
                        to customer satisfaction, affordability, and innovation.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="conSection otherPageSection featuresSec">
        <div class="container">
            <center class="scrollToReveal">
                <h6>// features //</h6>
                <h1>Why Choose Us<span>.</span></h1>
            </center>
            <div class="featureGrid scrollToReveal">
                <div class="featureBox">
                    <img src="assets/images/imgicon/customer-service.png" alt="">
                    <h3>Customer First</h3>
                    <p>We prioritize our customers' needs and work towards exceeding their expectations.</p>
                </div>
                <div class="featureBox">
                    <img src="assets/images/imgicon/reliability.png" alt="">
                    <h3>Quality Assurance</h3>
                    <p>We ensure that all our products meet high-quality standards.</p>
                </div>
                <div class="featureBox">
                    <img src="assets/images/imgicon/innovation.png" alt="">
                    <h3>Innovation</h3>
                    <p>We constantly evolve and adapt to the latest trends to improve the shopping experience.</p>
                </div>
                <div class="featureBox">
                    <img src="assets/images/imgicon/dependable.png" alt="">
                    <h3>Integrity & Trust</h3>
                    <p>We build lasting relationships with customers through transparency and reliability.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="contactGrid scrollToReveal">
                <div class="contactBox">
                    <img src="assets/images/imgicon/letter.png" alt="">
                    <h3>Email Address</h3>
                    <p>info@webmail.com</p>
                    <p>jobs@webexample.com</p>
                </div>
                <div class="contactBox">
                    <img src="assets/images/imgicon/support.png" alt="">
                    <h3>Phone Number</h3>
                    <p>+0123-456789</p>
                    <p>+987-6543210</p>
                </div>
                <div class="contactBox">
                    <img src="assets/images/imgicon/address.png" alt="">
                    <h3>Office Address</h3>
                    <p>18/A, New Born Town Hall</p>
                    <p>New York, US</p>
                </div>
            </div>
        </div>

    </section>
    <br><br>


    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>



</body>

</html>