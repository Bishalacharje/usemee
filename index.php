<?php
error_reporting(0);
include("connection.php");
session_start();
// include("checked-login.php");


include("enc_dec.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("./components/headlink.php"); ?>
  <title>Usemee - Your one-stop online store for all your shopping needs!</title>


</head>

<body>

  <!-- ---------------------------------------------------- Loading  section ------------------------- ------------ -->

  <section class="loadingSec">
    <div class="loadingCon">
      <div class="logo">
        <img src="assets/images/logo/logo.png" alt="" />
      </div>
      <!-- <div class="loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div> -->
    </div>
  </section>
  <script>
    window.addEventListener("load", () => {
      // Ensure at least 2 seconds before hiding loader
      setTimeout(() => {
        document.querySelector(".loadingSec").classList.add("hide");
      }, 300);
    });

  </script>


  <div>

    <?php include("./components/topHeader.php"); ?>
    <?php include("./components/header.php"); ?>

  </div>
  <!-- ---------------------------------  Banner Section  ------------------------------------ -->
  <section class="conSection bannerSec">
    <div class="container">
      <div class="bannerSlider">
        <div class="slider-wrapper">
          <?php
          // Fetch header banners where visibility is set to visible
          $headerBannerQuery = "SELECT * FROM `header_banners` WHERE `visibility` = 1 ORDER BY id DESC";
          $headerBannerData = mysqli_query($conn, $headerBannerQuery);

          if (mysqli_num_rows($headerBannerData) > 0) {
            while ($banner = mysqli_fetch_assoc($headerBannerData)) {
              ?>
              <a href="<?php echo htmlspecialchars($banner['link']); ?>" class="banner">
                <div class="bannerImage">
                  <img src="superadmin/<?php echo $banner['image_path']; ?>"
                    alt="<?php echo htmlspecialchars($banner['title']); ?>" />
                </div>
              </a>
              <?php
            }
          } else {
            // Fallback if no banners are available
            echo '<div class="no-banners">No header banners available</div>';
          }
          ?>
        </div>

        <button class="prev" onclick="prevSlide()">&#8592;</button>
        <button class="next" onclick="nextSlide()">&#8594;</button>
      </div>
    </div>
  </section>


  <!-- ---------------------------------  Subcategory Cards  ------------------------------------ -->

  <section class="conSection SubcategoryCardSec homeSubcategoryCardSec scrollToReveal">
    <div class="container">
      <div class="subCategoryCardGrid">
        <?php
        $queryns = "SELECT * FROM `subcategory` LIMIT 16";
        $datans = mysqli_query($conn, $queryns);

        while ($resultns = mysqli_fetch_assoc($datans)) {
          $encryptedSubCatId = encryptId($resultns['id']);
          $subCategoryName = $resultns['name'];
          $subCategoryImg = $resultns['image'];
          ?>
          <a href="shop.php?subcategory=<?php echo $encryptedSubCatId; ?>" class="subCategoryCard">
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

  <section class="conSection sellingSec scrollToReveal">
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
              // Encrypt the ID
              $encryptedPId = encryptId($pid);

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
                <a href="product.php?id=<?php echo $encryptedPId; ?>"
                  class="productBox  <?php echo $resultSelling['status'] == 0 ? 'out-of-stock' : ''; ?>">
                  <div class="productImg">
                    <img src="superadmin/<?php echo $resultSelling['image']; ?>" alt="">
                    <?php if ($resultSelling['status'] == 0) { ?>
                      <div class="outOfStockBand">
                        <p>Out of Stock</p>
                      </div>
                    <?php } else {
                      ?>
                      <span class="subCategory"><?php echo $subCategoryName; ?></span>
                      <?php
                    }
                    ?>
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
        <?php
        // Fetch center cards that are visible, ordered by position
        $centerCardsQuery = "SELECT * FROM `center_cards` WHERE `visibility` = 1 ORDER BY position ASC";
        $centerCardsResult = mysqli_query($conn, $centerCardsQuery);

        $centerCards = [];
        while ($card = mysqli_fetch_assoc($centerCardsResult)) {
          $centerCards[] = $card;
        }

        // Large banner (position 1)
        if (!empty($centerCards[0])) {
          $large = $centerCards[0];
          ?>
          <div class="centerBannerLg scrollToRevealRight">
            <a href="<?php echo !empty($large['link']) ? htmlspecialchars($large['link']) : '#'; ?>">
              <img src="superadmin/<?php echo $large['image_path']; ?>"
                alt="<?php echo htmlspecialchars($large['title']); ?>">
            </a>
          </div>
        <?php } ?>

        <div class="centerBannerSmall">
          <?php
          // Two small banners
          for ($i = 1; $i <= 2; $i++) {
            if (!empty($centerCards[$i])) {
              $small = $centerCards[$i];
              ?>
              <div class="centerBanner scrollToRevealLeft">
                <a href="<?php echo !empty($small['link']) ? htmlspecialchars($small['link']) : '#'; ?>">
                  <img src="superadmin/<?php echo $small['image_path']; ?>"
                    alt="<?php echo htmlspecialchars($small['title']); ?>">
                </a>
              </div>
              <?php
            }
          }
          ?>
        </div>
      </div>
    </div>
  </section>



  <!-- ---------------------------------  Category Cards  ------------------------------------ -->

  <section class="conSection SubcategoryCardSec scrollToReveal">
    <div class="containerSlider">

      <div class="slide-container swiper">
        <div class="slide-content-category">
          <div class="card-wrapper swiper-wrapper">
            <?php
            $queryc = "SELECT * FROM `category`";
            $datac = mysqli_query($conn, $queryc);

            while ($resultc = mysqli_fetch_assoc($datac)) {
              $encryptedCatId = encryptId($resultc['id']);
              $categoryName = $resultc['name'];
              $categoryImg = $resultc['image'];
              ?>
              <div class="card swiper-slide categoryCard">
                <a href="shop.php?category=<?php echo $encryptedCatId; ?>" class="subCategoryCard">
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

  <section class="conSection sellingSec scrollToReveal">
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
          // Encrypt the ID
          $encryptedPId = encryptId($id);
          $queryp = "SELECT * FROM `product_variant` WHERE `product_id` = '$id' ORDER BY `sale_price` ASC LIMIT 1";
          $datap = mysqli_query($conn, $queryp);
          $resultp = mysqli_fetch_assoc($datap);
          $mrp = $resultp['mrp'];
          $sale_price = $resultp['sale_price'];
          $discount = round((($mrp - $sale_price) / $mrp) * 100);

          ?>
          <a href="product.php?id=<?php echo $encryptedPId; ?>"
            class="productBox <?php echo $result['status'] == 0 ? 'out-of-stock' : ''; ?>">

            <div class="productImg">
              <img src="superadmin/<?php echo $result['image']; ?>" alt="">
              <?php if ($result['status'] == 0) { ?>
                <div class="outOfStockBand">
                  <p>Out of Stock</p>
                </div>
              <?php } else {
                ?>
                <span class="subCategory"><?php echo $subCategoryName; ?></span>
                <?php
              }
              ?>


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
        <a href="shop.php" class="scrollToRevealTop"><button>View all</button></a>
      </center>

    </div>
  </section>


  <!-- ---------------------------------  Footer card Section  ------------------------------------ -->

  <section class="conSection scrollToReveal">
    <div class="container">
      <div class="footerCardGrid">
        <?php
        // Fetch all footer cards
        $footerCardQuery = "SELECT * FROM `footer_cards` ORDER BY id ASC";
        $footerCardResult = mysqli_query($conn, $footerCardQuery);

        // Check if we have any cards
        if (mysqli_num_rows($footerCardResult) > 0) {
          while ($card = mysqli_fetch_assoc($footerCardResult)) {
            ?>
            <a href="<?php echo !empty($card['link']) ? htmlspecialchars($card['link']) : '#'; ?>" class="footerCard">
              <img src="superadmin/<?php echo $card['image_path']; ?>"
                alt="<?php echo htmlspecialchars($card['title']); ?>">
            </a>
            <?php
          }
        } else {
          // Fallback if no footer cards are found
          echo '<div class="no-cards">No footer cards available</div>';
        }
        ?>
      </div>
    </div>
  </section>


  <?php include("./components/footer.php"); ?>

  <?php include("./components/footscript.php"); ?>
</body>

</html>