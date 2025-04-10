<?php

$_SESSION['email'];
$sprofile = $_SESSION['email'];

if ($sprofile == true) {
    $queryadmin = "SELECT * FROM `user` WHERE email ='$sprofile'";
    $dataadmin = mysqli_query($conn, $queryadmin);
    $totaluser = mysqli_num_rows($dataadmin);
    $resultadmin = mysqli_fetch_assoc($dataadmin);
    $userid = $resultadmin['id'];
    $admin_name = $resultadmin['name'];
    $admin_email = $resultadmin['email'];
}



$querycart = "SELECT * FROM `cart` WHERE user_id ='$userid'";
$datacart = mysqli_query($conn, $querycart);
$totalcart = mysqli_num_rows($datacart);

?>

<header class="conSection">
    <div class="container">
        <div class="header">
            <div class="headerLeft">
                <a href="index.php" class="logo">
                    <img src="./assets/images/logo/logo.png" alt="">
                </a>
                <a href="index.php" class="mobileLogo">
                    <img src="./assets/images/logo/mobileLogo.png" alt="">
                </a>
            </div>
            <div class="headerCenter">
                <div class="phone">
                    <img src="./assets/images/imgicon/call.png" alt="">
                    <!-- 434343 -->
                    <div>
                        <p>Phone</p>
                        <h6>+91-88373-10967</h6>
                    </div>
                </div>

                <form action="" class="searchCon">
                    <img src="./assets/images/imgicon/search.png" alt="">
                    <input type="search" placeholder="Search here...">
                </form>

            </div>
            <div class="headerRight">
                <div class="userDropdown">
                    <img src="./assets/images/imgicon/user.png" alt="">
                    <ul class="dropdownMenu">

                        <?php
                        if ($sprofile == true) {
                            ?>
                            <li><a href="my_account.php"><img src="./assets/images/imgicon/user.png" alt="">My Account</a>
                            </li>
                            <li><a href="order.php"><img src="./assets/images/imgicon/box.png" alt=""> Orders</a></li>
                            <li><a href="logout.php"><img src="./assets/images/imgicon/power-off.png" alt="">Logout</a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="login.php"><img src="./assets/images/imgicon/login.png" alt="">Login</a></li>
                            <?php
                        }
                        ?>

                    </ul>
                </div>
                <?php
                if ($totalcart != 0) {
                    ?>
                    <a href="cart.php">
                        <div class="cartIcon">
                            <img src="./assets/images/imgicon/cart.png" alt="">
                            <span><?php echo $totalcart ?></span>
                        </div>
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="cart.php">
                        <div class="cartIcon">
                            <img src="./assets/images/imgicon/cart.png" alt="">
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</header>






<script>
    window.addEventListener("scroll", function () {
        const header = document.querySelector("header"); // or use '.conSection' if you want that specifically
        const scrollPosition = window.scrollY;
        if (scrollPosition > window.innerHeight * 0.1) {
            header.classList.add("sticky-header", "show");
        } else {
            header.classList.remove("sticky-header", "show");
        }
    });
</script>