<footer>
    <div class="footerMain">

        <div class="footerFeatureGrid scrollToReveal">
            <div class="footerFeature">
                <img src="assets/images/imgicon/original.png" alt="">
                <div>
                    <h3>100% Original Products</h3>
                    <p>Guaranteed authentic, high-quality, and brand-new products.</p>
                </div>

            </div>
            <div class="footerFeature">
                <img src="assets/images/imgicon/cod.png" alt="">
                <div>
                    <h3>Cash on Delivery Available</h3>
                    <p>Enjoy the convenience of paying with cash when your order is delivered to your doorstep.</p>
                </div>

            </div>
            <div class="footerFeature last">
                <img src="assets/images/imgicon/exchange.png" alt="">
                <div>
                    <h3>Easy Exchange Policy</h3>
                    <p>Hassle-free exchanges with a simple and quick process for your convenience.</p>
                </div>

            </div>
        </div>

        <div class="footerGrid">
            <div class="footerAbout">
                <a href="index.php" class="logo">
                    <img src="./assets/images/logo/logo.png" alt="">
                </a>
                <p>Lorem Ipsum is simply dummy text of the and typesetting industry. Lorem Ipsum is dummy text of the
                    printing.</p>
                <div>
                    <img src="./assets/images/imgicon/locationdark.png" alt="">
                    <p>Dhaleswar, Agartala, Tripura 799007</p>
                </div>
                <div>
                    <img src="./assets/images/imgicon/call.png" alt="">
                    <p>+91-88373-10967</p>
                </div>
                <div>
                    <img src="./assets/images/imgicon/maildark.png" alt="">
                    <p>contact@usemee.in</p>
                </div>
                <div class="socialLogo">
                    <a href="#"><img src="./assets/images/imgicon/fb.png" alt=""></a>
                    <a href="#"><img src="./assets/images/imgicon/twitter.png" alt=""></a>
                    <a href="#"><img src="./assets/images/imgicon/instagram.png" alt=""></a>
                </div>
            </div>
            <div class="footerMenu">
                <h3>Company</h3>
                <ul>
                    <a href="about.php">
                        <li>About</li>
                    </a>
                    <a href="#">
                        <li>Blog</li>
                    </a>
                    <a href="shop.php">
                        <li>All Products</li>
                    </a>
                    <a href="#">
                        <li>FAQ</li>
                    </a>
                    <a href="contact.php">
                        <li>Contact us</li>
                    </a>
                </ul>
            </div>
            <div class="footerMenu">
                <h3>Services</h3>
                <ul>
                    <a href="#">
                        <li>Orders</li>
                    </a>
                    <a href="#">
                        <li>Login</li>
                    </a>
                    <a href="#">
                        <li>My account</li>
                    </a>
                    <a href="#">
                        <li>Terms & Conditions</li>
                    </a>
                    <a href="#">
                        <li>Customer Policy</li>
                    </a>
                </ul>
            </div>
            <div class="footerMenu">
                <h3>Category</h3>
                <ul>
                    <?php
                    $queryc = "SELECT * FROM `category` LIMIT 4";
                    $datac = mysqli_query($conn, $queryc);

                    while ($resultc = mysqli_fetch_assoc($datac)) {
                        $encryptedCatId = encryptId($resultc['id']);
                        $categoryName = $resultc['name'];
                        ?>
                        <a href="shop.php?category=<?php echo $encryptedCatId; ?>">
                            <li><?php echo $categoryName; ?></li>
                        </a>
                        <?php
                    }
                    ?>

                    <a href="sub-category.php#category">
                        <li>All Categories</li>
                    </a>

                </ul>
            </div>
            <div class="footerMenu">
                <h3>Newsletter</h3>
                <p>Subscribe to our weekly Newsletter and receive updates via email.</p>
                <form action="">
                    <input type="email" placeholder="Email *" required>
                    <button type="submit">
                        <img src="./assets/images/imgicon/send.png" alt="">
                    </button>
                </form>
            </div>
        </div>

    </div>
    <div class="footerBottom">
        <div>
            <p>All Rights Reserved @ Usemee 2025</p>
        </div>
        <div>
            <p>Design & Developed by Usemee</p>

        </div>
    </div>
</footer>