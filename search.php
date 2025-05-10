<?php
error_reporting(0);
include("connection.php");
session_start();

$query = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($query !== '') {
    $likeQuery = '%' . $query . '%';

    // Search products
    $product_sql = "SELECT * FROM product WHERE name LIKE ? OR description LIKE ?";
    $product_stmt = $conn->prepare($product_sql);
    $product_stmt->bind_param("ss", $likeQuery, $likeQuery);
    $product_stmt->execute();
    $products = $product_stmt->get_result();

    // Search subcategories
    $sub_sql = "SELECT * FROM subcategory WHERE name LIKE ? OR description LIKE ?";
    $sub_stmt = $conn->prepare($sub_sql);
    $sub_stmt->bind_param("ss", $likeQuery, $likeQuery);
    $sub_stmt->execute();
    $subcategories = $sub_stmt->get_result();

    // Search categories
    $cat_sql = "SELECT * FROM category WHERE name LIKE ? OR description LIKE ?";
    $cat_stmt = $conn->prepare($cat_sql);
    $cat_stmt->bind_param("ss", $likeQuery, $likeQuery);
    $cat_stmt->execute();
    $categories = $cat_stmt->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Search | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>

        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="title">
                <div>
                    <h2>Search (<?php echo htmlspecialchars($query); ?>)</h2>
                    <p><a href="index.php">Home</a>-
                        <span>Search (<?php echo htmlspecialchars($query); ?>)</span>
                    </p>
                </div>
            </div>

            <?php if ($products && $products->num_rows > 0): ?>
                <div class="productGrid grid5 scrollToReveal">
                    <?php while ($result = $products->fetch_assoc()): ?>
                        <?php
                        // Get subcategory name
                        $scid = $result['subCategoryId'];
                        $sub_query = "SELECT name FROM subcategory WHERE id = '$scid'";
                        $sub_res = mysqli_query($conn, $sub_query);
                        $sub_data = mysqli_fetch_assoc($sub_res);
                        $subCategoryName = $sub_data['name'];

                        // Get lowest sale price variant
                        $pid = $result['id'];
                        $variant_query = "SELECT * FROM product_variant WHERE product_id = '$pid' ORDER BY sale_price ASC LIMIT 1";
                        $variant_res = mysqli_query($conn, $variant_query);
                        $variant = mysqli_fetch_assoc($variant_res);

                        if ($variant) {
                            $mrp = $variant['mrp'];
                            $sale_price = $variant['sale_price'];
                            $discount = round((($mrp - $sale_price) / $mrp) * 100);
                        } else {
                            // If no variant found, set defaults
                            $mrp = $sale_price = $discount = 0;
                        }
                        ?>
                        <a href="product.php?id=<?php echo $result['id']; ?>" class="productBox scrollToReveal">
                            <div class="productImg">
                                <img src="superadmin/<?php echo htmlspecialchars($result['image']); ?>" alt="">
                                <span class="subCategory"><?php echo html_entity_decode($subCategoryName); ?></span>
                            </div>
                            <div class="productDes">
                                <h4><?php echo html_entity_decode($result['name']); ?></h4>
                                <div class="productFlex">
                                    <del>&#8377; <?php echo number_format($mrp); ?></del>
                                    <h3><?php echo $discount; ?>%</h3>
                                </div>
                                <h2>&#8377; <?php echo number_format($sale_price); ?></h2>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="emptyCartCon scrollToReveal">
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
                        type="module"></script>
                    <dotlottie-player src="https://lottie.host/f38bfa84-2aa7-433c-bd28-dc99de95923e/PXb2Pvqhty.lottie"
                        background="transparent" speed="1" style="width: 160px; height: 160px" loop=""
                        autoplay=""></dotlottie-player>
                    <h2>No products found "<?php echo htmlspecialchars($query); ?>"</h2>
                    <p>Shop available products.</p>
                    <a href="shop.php"><button>Shop now</button></a>
                </div>
            <?php endif; ?>






            <br><br>







            <?php if ($subcategories && $subcategories->num_rows > 0): ?>
                <div class="subCategoryCardGrid scrollToReveal">
                    <?php while ($row = $subcategories->fetch_assoc()): ?>
                        <a href="shop.php?subcategory=<?php echo $row['id']; ?>" class="subCategoryCard">
                            <div class="subCategoryCardImg">
                                <img src="superadmin/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                            </div>
                            <h3><?php echo html_entity_decode($row['name']); ?></h3>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>

            <?php endif; ?>


            <br><br>


            <?php if ($categories && $categories->num_rows > 0): ?>
                <div class="subCategoryCardGrid">
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <a href="shop.php?category=<?php echo $row['id']; ?>" class="subCategoryCard">
                            <div class="subCategoryCardImg">
                                <img src="superadmin/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                            </div>
                            <h3><?php echo html_entity_decode($row['name']); ?></h3>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>

            <?php endif; ?>






        </div>
    </section>








    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>
</body>

</html>