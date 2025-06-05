<?php
error_reporting(0);
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Contact | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include("empty-address.php"); ?>
    <!-- HEADER -->
    <div><?php include("./components/header.php"); ?></div>


    <section class="conSection otherPageSection contactPage">
        <div class="container">
            <p class="bradCrumb"><a href="index.php">Home</a> - <span>Contact Us</span>
            <h1>Contact Us <span>.</span></h1>
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

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="contactFormBox scrollToReveal">
                <h3>Get a Quote</h3>
                <form action="" method="post">
                    <input type="hidden" value="6" name="uid">
                    <div class="formGrid grid1">
                        <div>
                            <label for="">Full Name</label>
                            <input type="text" name="name" value="<?php echo $user_name ?>" required="">
                        </div>
                    </div>
                    <div class="formGrid">

                        <div class="inputCon">
                            <label>Phone No <span id="pin-guidelines"
                                    style="font-size: 12px; color: green; margin-left: 10px;">[ Must be exactly 10
                                    digits ]</span></label>
                            <input type="number" name="phone_no" id="phone_no" value="<?php echo $user_phone ?>"
                                pattern="[0-9]{10}" maxlength="10" required="">

                        </div>
                        <div>
                            <label for="">Email</label>
                            <input type="email" name="email" value="<?php echo $user_email ?>" required="">
                        </div>

                    </div>
                    <div class="formGrid grid1">
                        <div>
                            <label for="">Message</label>
                            <textarea name="" id=""></textarea>
                        </div>
                    </div>
                    <button type="submit" name="">Send</button>
                </form>
            </div>
        </div>
    </section>
    <br><br>


    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>



</body>

</html>