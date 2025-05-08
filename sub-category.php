<?php
error_reporting(0);
include("connection.php");
session_start();
// include("checked-login.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Usemee - Sub-Category</title>


</head>

<body>
    <div>

        <?php include("./components/topHeader.php"); ?>
        <?php include("./components/header.php"); ?>

    </div>

    <!-- ---------------------------------  Subcategory Cards  ------------------------------------ -->

    <section class="conSection SubcategoryCardSec">
        <div class="container">
            <div class="title">
                <div>
                    <h2>Sub-Category & Category</h2>
                    <p><a href="index.php">Home</a> - <a href="sub-category.php">Sub-Category</a></p>
                </div>
            </div>
            <div class="subCategoryCardGrid">
                <?php
                $queryns = "SELECT * FROM `subcategory`";
                $datans = mysqli_query($conn, $queryns);

                while ($resultns = mysqli_fetch_assoc($datans)) {
                    $subCategoryName = $resultns['name'];
                    $subCategoryImg = $resultns['image'];
                    ?>
                    <a href="shop.php?subcategory=<?php echo $resultns['id']; ?>" class="subCategoryCard">
                        <div class="subCategoryCardImg">
                            <img src="superadmin/<?php echo $subCategoryImg; ?>" alt="">
                        </div>
                        <h3><?php echo $subCategoryName; ?></h3>
                    </a>
                    <?php


                }

                ?>
            </div>
        </div>
    </section>


    <!-- ---------------------------------  Category Cards  ------------------------------------ -->

    <section class="conSection SubcategoryCardSec" id="category">
        <div class="containerSlider">
            <center>
                <h2 class="sectionTitle">All Category</h2>
            </center>
            <br>
            <div class="slide-container swiper">
                <div class="slide-content-category">
                    <div class="card-wrapper swiper-wrapper">
                        <?php
                        $queryc = "SELECT * FROM `category`";
                        $datac = mysqli_query($conn, $queryc);

                        while ($resultc = mysqli_fetch_assoc($datac)) {
                            $categoryName = $resultc['name'];
                            $categoryImg = $resultc['image'];
                            ?>
                            <div class="card swiper-slide categoryCard">
                                <a href="shop.php?category=<?php echo $resultc['id']; ?>" class="subCategoryCard">
                                    <div class="subCategoryCardImg">
                                        <img src="superadmin/<?php echo $categoryImg; ?>" alt="">
                                    </div>
                                    <h3><?php echo $categoryName; ?></h3>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="swiper-pagination swiper-pagination-category"></div>

                <div class="swiper-button-next swiper-navBtn next-category"></div>
                <div class="swiper-button-prev swiper-navBtn prev-category"></div>
            </div>

            <!-- <div class="subCategoryCardGrid">
        <?php
        $queryc = "SELECT * FROM `category`";
        $datac = mysqli_query($conn, $queryc);

        while ($resultc = mysqli_fetch_assoc($datac)) {
            $categoryName = $resultc['name'];
            $categoryImg = $resultc['image'];
            ?>
          <a href="shop.php?category=<?php echo $resultc['id']; ?>" class="subCategoryCard">
            <div class="subCategoryCardImg">
              <img src="superadmin/<?php echo $categoryImg; ?>" alt="">
            </div>
            <h3><?php echo $categoryName; ?></h3>
          </a>
          <?php


        }

        ?>
      </div> -->
        </div>
    </section>


    <!-- ---------------------------------  Featured Product Section  ------------------------------------ -->

    <section class="conSection sellingSec">
        <div class="container">
            <h2 class="sectionTitle">Featured Products</h2>

            <div class="productGrid grid5">
                <?php
                $query = "SELECT * FROM `product` ORDER BY RAND() LIMIT 10";
                $data = mysqli_query($conn, $query);
                $total = mysqli_num_rows($data);


                while ($result = mysqli_fetch_assoc($data)) {
                    $cid = $result['categoryId'];
                    $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
                    $datan = mysqli_query($conn, $queryn);
                    $resultn = mysqli_fetch_assoc($datan);
                    $categoryName = $resultn['name'];
                    $categoryId = $resultn['id'];
                    ?>
                    <?php
                    $scid = $result['subCategoryId'];
                    $queryns = "SELECT * FROM `subcategory` WHERE `id`='$scid'";
                    $datans = mysqli_query($conn, $queryns);
                    $resultns = mysqli_fetch_assoc($datans);
                    $subCategoryName = $resultns['name'];
                    $subCategoryId = $resultns['id'];

                    ?>
                    <?php
                    $id = $result['id'];
                    $queryp = "SELECT * FROM `product_variant` WHERE `product_id` = '$id' ORDER BY `sale_price` ASC LIMIT 1";
                    $datap = mysqli_query($conn, $queryp);
                    $resultp = mysqli_fetch_assoc($datap);
                    $mrp = $resultp['mrp'];
                    $sale_price = $resultp['sale_price'];
                    $discount = round((($mrp - $sale_price) / $mrp) * 100);

                    ?>
                    <a href="product.php?id=<?php echo $result['id']; ?>" class="productBox">
                        <div class="productImg">
                            <img src="superadmin/<?php echo $result['image']; ?>" alt="">
                            <span class="subCategory"><?php echo $subCategoryName; ?></span>
                        </div>
                        <div class="productDes">
                            <h4> <?php echo $result['name']; ?></h4>
                            <div class="productFlex">
                                <del>&#8377; <?php echo $mrp; ?></del>
                                <h3><?php echo $discount; ?>%</h3>
                            </div>
                            <h2>&#8377; <?php echo $sale_price; ?></h2>

                        </div>

                    </a>

                    <?php
                }


                ?>
            </div>
            <br>
            <center>
                <a href="shop.php"><button>View all</button></a>
            </center>

        </div>
    </section>


    <?php include("./components/footer.php"); ?>

    <?php include("./components/footscript.php"); ?>
</body>

</html>