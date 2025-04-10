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
  <title>Usemee -$_FILES eCommerce Website</title>


</head>

<body>


  <div>

    <?php include("./components/topHeader.php"); ?>
    <?php include("./components/header.php"); ?>

  </div>
  <!-- ---------------------------------  Banner Section  ------------------------------------ -->
  <section class="conSection bannerSec">
    <div class="container">
      <div class="bannerSlider">
        <div class="slider-wrapper">
          <a href="#" class="banner">
            <div class="bannerImage">
              <img src="assets/images/banner/banner1.png" alt="" />
            </div>
            <!-- <div class="bannerLink">
              <button class="btn">Shop now</button>
            </div> -->
          </a>
          <a href="#" class="banner">
            <div class="bannerImage">
              <img src="assets/images/banner/banner2.png" alt="" />
            </div>
            <!-- <div class="bannerLink">
              <button class="btn">Shop now</button>
            </div> -->
          </a>

        </div>
        <button class="prev" onclick="prevSlide()">
          &#8592; </button>
        <button class="next" onclick="nextSlide()">
          &#8594;
        </button>
      </div>

    </div>

  </section>


  <!-- ---------------------------------  Subcategory Cards  ------------------------------------ -->

  <section class="conSection SubcategoryCardSec homeSubcategoryCardSec">
    <div class="container">
      <div class="subCategoryCardGrid">
        <?php
        $queryns = "SELECT * FROM `subcategory` LIMIT 16";
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
      <br><br>
      <center>
        <a href="sub-category.php"><button>View all</button></a>
      </center>
    </div>
  </section>


  <!-- ---------------------------------  Top Selling Products  ------------------------------------ -->

  <section class="conSection sellingSec">
    <div class="containerSlider">
      <h2 class="sectionTitle">Top Selling Products</h2>

      <?php
      $querySelling = "SELECT * FROM `product`";
      $dataSelling = mysqli_query($conn, $querySelling);

      ?>

      <div class="slide-container swiper">
        <div class="slide-content-bigCard">
          <div class="card-wrapper swiper-wrapper">
            <?php
            while ($resultSelling = mysqli_fetch_assoc($dataSelling)) {

              $pid = $resultSelling['id'];

              $cid = $resultSelling['categoryId'];
              $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
              $datan = mysqli_query($conn, $queryn);
              $resultn = mysqli_fetch_assoc($datan);
              $categoryName = $resultn['name'];

              $scid = $resultSelling['subCategoryId'];
              $queryns = "SELECT * FROM `subcategory` WHERE `id`='$scid'";
              $datans = mysqli_query($conn, $queryns);
              $resultns = mysqli_fetch_assoc($datans);
              $subCategoryName = $resultns['name'];


              $queryPv = "SELECT * FROM `product_variant` WHERE `product_id`='$pid' ORDER BY `sale_price` ASC LIMIT 1";
              $datanPv = mysqli_query($conn, $queryPv);
              $resultPv = mysqli_fetch_assoc($datanPv);

              if ($resultPv) {
                $selectedVariantId = $resultPv['id']; // Store the selected product_variant ID
                $productMrp = $resultPv['mrp'];
                $productDiscount = floor(floatval($resultPv['discount'])); // Removes decimal
                $productPrice = $resultPv['sale_price'];
              }



              ?>
              <div class="card swiper-slide">
                <a href="product.php?id=<?php echo $pid; ?>" class="productBox">
                  <div class="productImg">
                    <img src="superadmin/<?php echo $resultSelling['image']; ?>" alt="">
                    <span class="subCategory"><?php echo $subCategoryName; ?></span>
                  </div>
                  <div class="productDes">
                    <h4><?php echo $resultSelling['name']; ?> </h4>
                    <div class="productFlex">
                      <del>₹<?php echo $productMrp; ?></del>
                      <h3><?php echo $productDiscount; ?>%</h3>
                    </div>
                    <h2>₹<?php echo $productPrice; ?></h2>
                  </div>
                </a>
              </div>
              <?php
            }
            ?>
            <!-- <div class="card swiper-slide">
              <a href="#" class="card-con">
                <img src="assets/image/bigCard1.png" alt="" />
              </a>
            </div>
            <div class="card swiper-slide">
              <div class="card-con">
                <img src="assets/image/bigCard2.png" alt="" />
              </div>
            </div>
            <div class="card swiper-slide">
              <div class="card-con">
                <img src="assets/image/bigCard3.png" alt="" />
              </div>
            </div>
            <div class="card swiper-slide">
              <div class="card-con">
                <img src="assets/image/bigCard4.png" alt="" />
              </div>
            </div> -->
          </div>
        </div>

        <div class="swiper-pagination swiper-pagination-bigCard"></div>

        <div class="swiper-button-next swiper-navBtn next-bigCard"></div>
        <div class="swiper-button-prev swiper-navBtn prev-bigCard"></div>
      </div>
    </div>
  </section>

  <!-- ---------------------------------  Center Banner Section  ------------------------------------ -->

  <section class="conSection centerBannerSec">
    <div class="container">
      <div class="centerBannerGrid">
        <div class="centerBannerLg">
          <a href="shop.php?subcategory=12">
            <img src="assets/images/centerBannerLg.png" alt="">
          </a>
        </div>
        <div class="centerBannerSmall">
          <div class="centerBanner">
            <a href="#">
              <img src="assets/images/centerBanner1.png" alt="">
            </a>
          </div>
          <div class="centerBanner">
            <a href="#">
              <img src="assets/images/centerBanner2.png" alt="">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ---------------------------------  Category Cards  ------------------------------------ -->

  <section class="conSection SubcategoryCardSec">
    <div class="containerSlider">

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


  <!-- ---------------------------------  Footer card Section  ------------------------------------ -->

  <section class="conSection">
    <div class="container">
      <div class="footerCardGrid">
        <a href="#" class="footerCard">
          <img src="assets/images/footerCard1.png" alt="">
        </a>
        <a href="#" class="footerCard">
          <img src="assets/images/footerCard2.png" alt="">
        </a>
        <a href="#" class="footerCard">
          <img src="assets/images/footerCard3.png" alt="">
        </a>
      </div>
    </div>
  </section>







  <?php include("./components/footer.php"); ?>

  <?php include("./components/footscript.php"); ?>
</body>

</html>