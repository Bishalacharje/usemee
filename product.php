<?php
error_reporting(0);
include("connection.php");
session_start();

include("enc_dec.php");

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Decrypt the ID
    $decryptId = decryptId($product_id);

    // Fetch product
    $query = "SELECT * FROM product WHERE id = '$decryptId'";
    $data = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($data);

    // Fetch category
    $cid = $result['categoryId'];
    $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
    $datan = mysqli_query($conn, $queryn);
    $resultn = mysqli_fetch_assoc($datan);
    $categoryName = $resultn['name'];

    // Fetch subcategory
    $scid = $result['subCategoryId'];
    $queryns = "SELECT * FROM `subcategory` WHERE `id`='$scid'";
    $datans = mysqli_query($conn, $queryns);
    $resultns = mysqli_fetch_assoc($datans);
    $subCategoryName = $resultns['name'];

    // Fetch all variants
    $queryp = "SELECT * FROM product_variant WHERE product_id = '$decryptId' ORDER BY sale_price ASC";
    $datap = mysqli_query($conn, $queryp);
    $variants = [];
    while ($v = mysqli_fetch_assoc($datap)) {
        $variants[] = $v;
    }

    // Default variant
    $default_variant = $variants[0];
    $mrp = $default_variant['mrp'];
    $sale_price = $default_variant['sale_price'];
    $discount = round((($mrp - $sale_price) / $mrp) * 100);
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title><?php echo $result['name']; ?> | Usemee - Your one-stop online store for all your shopping needs!</title>
    
</head>

<body>
    <div>
        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="title">
                <div>
                    <h2><?php echo $result['name']; ?></h2>
                    <p><a href="index.php">Home</a> - <a href="shop.php">Shop</a> - <span><?php echo $result['name']; ?></span></p>
                </div>
            </div>
            <div class="singleProduct">
                <div class="showcase-banner scrollToRevealRight">
                    <img src="superadmin/<?php echo $result['image']; ?>" alt="<?php echo $result['name']; ?>"
                        class="showcase-img" />
                </div>

                <div class="showcase-content scrollToRevealLeft">
                    <div class="categorySubcategory">
                        <span class="category"><?php echo $categoryName; ?></span>
                        <span class="subCategory"><?php echo $subCategoryName; ?></span>
                    </div>

                    <h3 class="showcase-title"><?php echo $result['name']; ?></h3>

                    <!-- Stock Status Display -->
                    <div class="stock-status">
                        <p><strong>Availability : </strong>
                            <span id="stock-status" class="<?php echo $result['status'] == 1 ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php echo $result['status'] == 1 ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                        </p>
                    </div>

                    <div class="price-box">
                        <div class="flex">
                            <del>₹ <span id="variant-mrp"><?php echo $mrp; ?></span></del>
                            <h5><span id="variant-discount"><?php echo $discount; ?></span> % off</h5>
                        </div>
                        <p class="price">₹ <span id="variant-sale-price"><?php echo $sale_price; ?></span></p>
                    </div>

                    <div class="variantSection">
                        <?php foreach ($variants as $index => $v) { ?>
                            <div class="variant-wrapper">
                                <input type="radio" name="variant" class="variant-radio"
                                    id="variant_<?php echo $v['id']; ?>"
                                    value='<?php echo json_encode($v); ?>' 
                                    <?php echo $index == 0 ? 'checked' : ''; ?>>
                                <label for="variant_<?php echo $v['id']; ?>" class="variant-label"><?php echo $v['variant_name']; ?></label>
                            </div>
                        <?php } ?>
                    </div>

                    <script>
                        document.querySelectorAll(".variant-label").forEach(label => {
                            label.addEventListener("click", function () {
                                document.querySelectorAll(".variant-label").forEach(l => l.classList.remove("selected"));
                                this.classList.add("selected");
                            });
                        });
                    </script>

                    <div class="buyProductButtonCon">
                        <form action="checkout_single.php" method="POST" id="buyNowForm">
                            <input type="hidden" name="product_id" id="buyNowProductId" value="<?php echo $decryptId; ?>">
                            <input type="hidden" name="variant_id" id="buyNowVariantId" value="<?php echo $default_variant['id']; ?>">
                            <input type="hidden" name="variant_data" id="buyNowVariantData" value='<?php echo json_encode($default_variant); ?>'>
                            <button type="submit" class="buyButton" <?php echo $result['status'] == 0 ? 'disabled' : ''; ?>>
                                Buy now
                            </button>
                        </form>

                        <script>
                            // Update hidden fields on variant change
                            document.querySelectorAll('.variant-radio').forEach(radio => {
                                radio.addEventListener('change', function () {
                                    const variantData = JSON.parse(this.value);
                                    document.getElementById('buyNowVariantId').value = variantData.id;
                                    document.getElementById('buyNowVariantData').value = JSON.stringify(variantData);

                                    // Update price box
                                    let mrp = variantData.mrp;
                                    let salePrice = variantData.sale_price;
                                    let discount = Math.round(((mrp - salePrice) / mrp) * 100);
                                    document.getElementById('variant-sale-price').innerText = salePrice;
                                    document.getElementById('variant-mrp').innerText = mrp;
                                    document.getElementById('variant-discount').innerText = discount;

                                    // Ensure buttons remain disabled if out of stock
                                    let stockStatus = <?php echo $result['status']; ?>;
                                    if (stockStatus == 0) {
                                        document.querySelector('.buyButton').disabled = true;
                                        document.querySelector('#addToCartBtn').disabled = true;
                                    }
                                });
                            });
                        </script>

                        <button class="add-cart-btn" id="addToCartBtn" <?php echo $result['status'] == 0 ? 'disabled' : ''; ?>>
                            <img src="./assets/images/imgicon/cart-white.png" alt=""> Add to cart
                        </button>
                    </div>

                    <div class="productFooter">
                        <p><img src="assets/images/imgicon/original.png" alt="">100% Original Products</p>
                        <p><img src="assets/images/imgicon/cod.png" alt="">Cash on Delivery Available</p>
                        <p><img src="assets/images/imgicon/exchange.png" alt="">Easy Exchange Policy</p>
                    </div>
                </div>
            </div>
            <div class="productDescription">
                <h3>Description</h3>
                <p class="showcase-desc"><?php echo $result['description']; ?></p>
            </div>

            <div class="relatedProductSection scrollToReveal">
                <h4>// <?php echo $categoryName; ?></h4>
                <h2 class="relatedProductTitle">Related Products<span>.</span></h2>

                <div class="productGrid grid5">
                    <?php
                    $query = "SELECT * FROM `product` WHERE `categoryId`='$cid'";
                    $data = mysqli_query($conn, $query);
                    $total = mysqli_num_rows($data);

                    while ($result = mysqli_fetch_assoc($data)) {
                        $cid = $result['categoryId'];
                        $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
                        $datan = mysqli_query($conn, $queryn);
                        $resultn = mysqli_fetch_assoc($datan);
                        $categoryName = $resultn['name'];
                        $categoryId = $resultn['id'];

                        $scid = $result['subCategoryId'];
                        $queryns = "SELECT * FROM `subcategory` WHERE `id`='$scid'";
                        $datans = mysqli_query($conn, $queryns);
                        $resultns = mysqli_fetch_assoc($datans);
                        $subCategoryName = $resultns['name'];
                        $subCategoryId = $resultns['id'];

                        $id = $result['id'];
                        // Encrypt the ID
                        $encryptedId = encryptId($id);

                        $queryp = "SELECT * FROM `product_variant` WHERE `product_id` = '$id' ORDER BY `sale_price` ASC LIMIT 1";
                        $datap = mysqli_query($conn, $queryp);
                        $resultp = mysqli_fetch_assoc($datap);
                        $mrp = $resultp['mrp'];
                        $sale_price = $resultp['sale_price'];
                        $discount = round((($mrp - $sale_price) / $mrp) * 100);
                    ?>
                        <a href="product.php?id=<?php echo $encryptedId; ?>" class="productBox <?php echo $result['status'] == 0 ? 'out-of-stock' : ''; ?>">
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
                                <h4><?php echo $result['name']; ?></h4>
                                <div class="productFlex">
                                    <del>₹ <?php echo $mrp; ?></del>
                                    <h3><?php echo $discount; ?>%</h3>
                                </div>
                                <h2>₹ <?php echo $sale_price; ?></h2>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Add to cart via AJAX
        $('#addToCartBtn').click(function () {
            let variantData = JSON.parse($('input[name="variant"]:checked').val());
            $.ajax({
                url: 'add-to-cart.php',
                type: 'POST',
                data: {
                    product_id: <?php echo $decryptId; ?>,
                    variant_data: JSON.stringify(variantData)
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'added') {
                        toastr.success(response.message);
                        setTimeout(function () {
                            location.reload(); // Refresh the page after adding to cart
                        }, 1000); // Delay to show the success message
                    } else if (response.status === 'exists') {
                        toastr.info(response.message);
                    } else if (response.status === 'not_logged_in') {
                        toastr.warning(response.message);
                        setTimeout(function () {
                            window.location.href = 'login.php?redirect=product.php?id=<?php echo $decryptId; ?>';
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
    </script>
</body>
</html>