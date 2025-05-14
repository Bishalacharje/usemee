<?php
error_reporting(0);
session_start();
include("connection.php");
include("enc_dec.php"); // <-- Include your encryption/decryption functions here

// Decrypt category ID
$selectedCategory = "";
if (isset($_GET['category'])) {
    $selectedCategory = decryptId($_GET['category']);
}

// Decrypt subcategory IDs
$selectedSubcategories = [];
if (isset($_GET['subcategory'])) {
    $encryptedSubs = explode(',', $_GET['subcategory']);
    foreach ($encryptedSubs as $encSub) {
        $selectedSubcategories[] = decryptId($encSub);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Shop | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div><?php include("./components/header.php"); ?></div>

    <section class="conSection shopSection otherPageSection">
        <div class="container">
            <div class="shopGrid">

                <!-- Sidebar -->
                <div class="shopSidebar">
                    <div class="shopSidebarBox" id="categoryFilter">
                        <div class="closeCategoryFilter">x</div>
                        <h4>Category</h4>
                        <label>
                            <input type="radio" name="category" value="" class="category_filter"
                                <?= empty($selectedCategory) ? "checked" : "" ?>>
                            <span>All Categories</span>
                        </label>

                        <?php
                        $queryCategory = "SELECT * FROM `category`";
                        $dataCategory = mysqli_query($conn, $queryCategory);
                        while ($resultCategory = mysqli_fetch_assoc($dataCategory)) {
                            $encryptedCatId = encryptId($resultCategory['id']);
                            $checked = ($selectedCategory == $resultCategory['id']) ? "checked" : "";
                            echo '<label>
                                    <input type="radio" name="category" value="' . $resultCategory['id'] . '" class="category_filter" ' . $checked . '>
                                    <span>' . $resultCategory['name'] . '</span>
                                  </label>';
                        }
                        ?>
                    </div>

                    <div class="shopSidebarBox" id="subCategoryFilter">
                        <div class="closeSubCategoryFilter">x</div>
                        <h4>Sub Category</h4>
                        <?php
                        $querySubCategory = "SELECT * FROM `subcategory`";
                        $dataSubCategory = mysqli_query($conn, $querySubCategory);
                        while ($resultSubCategory = mysqli_fetch_assoc($dataSubCategory)) {
                            $isChecked = in_array($resultSubCategory['id'], $selectedSubcategories) ? "checked" : "";
                            echo '<label>
                                    <input type="checkbox" name="subcategory[]" value="' . $resultSubCategory['id'] . '" class="subcategory_filter" ' . $isChecked . '>
                                    <span>' . $resultSubCategory['name'] . '</span> 
                                  </label>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Main Shop Section -->
                <div class="shopMain">
                    <div class="title">
                        <div>
                            <h2>Shop</h2>
                            <p><a href="index.php">Home</a> - <span>Shop</span></p>
                        </div>

                        <div class="filterSort">
                            <div class="filterBtn">
                                <button class="resetButton"><a href="shop.php"><img
                                            src="assets/images/imgicon/rotate.png" alt=""></a></button>
                                <button class="categoryFilterBtn">Category</button>
                                <button class="subCategoryFilterBtn">Sub Category</button>
                            </div>

                            <form id="sortForm">
                                <select name="sort_option" id="sort_option">
                                    <option value="relevance">Sorting By Relevance</option>
                                    <option value="low_to_high">Low to High</option>
                                    <option value="high_to_low">High to Low</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Product Grid (AJAX Loaded) -->
                    <div class="productGrid scrollToReveal" id="productGrid"></div>

                    <div id="noProductMessage" style="display: none; text-align: center; padding: 20px;">
                        <h3>No products found in this category & subcategory.</h3>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

    <!-- AJAX for Filtering & Sorting -->
    <script>
        $(document).ready(function () {
            function loadProducts() {
                let category = $("input[name='category']:checked").val() || "";
                let subcategories = [];
                $("input[name='subcategory[]']:checked").each(function () {
                    subcategories.push($(this).val());
                });
                let sortOption = $("#sort_option").val();

                $.ajax({
                    url: "fetch_products.php",
                    type: "POST",
                    data: {
                        category: category,
                        subcategories: subcategories.join(","),
                        sort_option: sortOption
                    },
                    success: function (response) {
                        if ($.trim(response) === "") {
                            $("#productGrid").hide();
                            $("#noProductMessage").show();
                        } else {
                            $("#productGrid").show().html(response);
                            $("#noProductMessage").hide();
                        }
                    }
                });
            }

            loadProducts();

            $("#sort_option").change(function () {
                loadProducts();
            });

            $(".category_filter, .subcategory_filter").change(function () {
                loadProducts();
            });
        });
    </script>
</body>

</html>