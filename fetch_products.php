<?php
include("connection.php");

$category = isset($_POST['category']) ? $_POST['category'] : "";
$subcategories = isset($_POST['subcategories']) ? $_POST['subcategories'] : "";
$sort_option = isset($_POST['sort_option']) ? $_POST['sort_option'] : "relevance";

// Sorting Conditions
$sort_query = "ORDER BY p.id DESC";
if ($sort_option == "low_to_high") {
    $sort_query = "ORDER BY min_variant_price ASC";
} elseif ($sort_option == "high_to_low") {
    $sort_query = "ORDER BY min_variant_price DESC";
}

// Filtering Conditions
$filter_query = "WHERE 1"; // Ensures SQL syntax remains valid
if (!empty($category)) {
    $filter_query .= " AND p.categoryId = '" . mysqli_real_escape_string($conn, $category) . "'";
}

// Handling multiple subcategories safely
if (!empty($subcategories)) {
    $subcategoriesArray = array_filter(array_map('intval', explode(",", $subcategories))); // Sanitize input
    if (!empty($subcategoriesArray)) {
        $subcategoriesList = implode(",", $subcategoriesArray);
        $filter_query .= " AND p.subCategoryId IN ($subcategoriesList)";
    }
}

// Fetch unique products with the lowest price variant
$query = "SELECT p.*, 
                 MIN(v.sale_price) AS min_variant_price, 
                 v.mrp, 
                 v.sale_price 
          FROM product p 
          JOIN product_variant v ON p.id = v.product_id 
          $filter_query 
          GROUP BY p.id 
          $sort_query";

$data = mysqli_query($conn, $query);
$output = "";

// Check if any products are returned
if (mysqli_num_rows($data) > 0) {
    while ($result = mysqli_fetch_assoc($data)) {
        $mrp = $result['mrp'];
        $sale_price = $result['min_variant_price'];
        $discount = ($mrp > 0) ? round((($mrp - $sale_price) / $mrp) * 100) : 0;

        $output .= '
            <a href="product.php?id=' . $result['id'] . '" class="productBox">
                <div class="productImg">
                    <img src="superadmin/' . $result['image'] . '" alt="">
                </div>
                <div class="productDes">
                    <h4>' . $result['name'] . '</h4>
                    <div class="productFlex">
                        <del>₹' . $mrp . '</del>
                        <h3>' . $discount . '%</h3>
                    </div>
                    <h2>₹' . $sale_price . '</h2>
                </div>
            </a>';
    }
} else {
    // If no products found, show message
    $output = '<div class="noProductsMessage" style="text-align: center; padding: 20px;">
                   <h3>No products found in this category or subcategory.</h3>
               </div>';
}

echo $output;
?>