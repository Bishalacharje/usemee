<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Products | Super Admin | Usemee</title>
</head>

<body data-topbar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <?php include("./components/header.php"); ?>

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("./components/sidebar.php"); ?>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Products</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Products</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Products</h5>
                                    <button type="button" class="btn btn-success waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Add New
                                        Product</button>
                                </div>
                                <div class="card-body">

                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `product`";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th> Photo</th>
                                                        <th>Name</th>
                                                        <th>Category</th>
                                                        <th>Sub-Category</th>
                                                        <!-- <th>Category Description</th> -->
                                                          <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>


                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) { ?>

                                                        <?php
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

                                                        <tr>
                                                            <td><a class="image-popup-no-margins"
                                                                    href="<?php echo $result['image']; ?>">
                                                                    <img class="img-fluid" alt="<?php echo $result['image']; ?>"
                                                                        src="<?php echo $result['image']; ?>"
                                                                        style="height:75px; border: 1px solid #ddd">
                                                                </a></td>
                                                            <td>
                                                                <h6><?php echo $result['name']; ?></h6>
                                                            </td>
                                                            <td><?php echo $categoryName; ?></td>

                                                            <td><?php echo $subCategoryName; ?></td>

                                                            <!-- <td>
                                                                <p style="width: 500px;height: 200px; overflow: auto;">
                                                                    <?php echo $result['description']; ?>
                                                                </p>
                                                            </td> -->

                                                            <td>
                                                                <div class="form-check form-switch form-switch-lg" dir="ltr">
                                                                    <input type="checkbox" class="form-check-input status-toggle" 
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    <?php echo ($result['status'] == 1) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label status-label" for="customSwitchsizelg">
                                                                        <?php echo ($result['status'] == 1) ? 'In Stock' : 'Out of Stock'; ?>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>


                                                                <button type="button"
                                                                    class="btn btn-warning waves-effect waves-light btn-sm viewCategoryBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-name="<?php echo $result['name']; ?>"
                                                                    data-description="<?php echo $result['description']; ?>"
                                                                    data-image="<?php echo $result['image']; ?>"
                                                                    data-category="<?php echo $categoryName; ?>"
                                                                    data-subcategory="<?php echo $subCategoryName; ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-view">
                                                                    <i class="ri-eye-fill"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-info waves-effect waves-light btn-sm editCategoryBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-name="<?php echo $result['name']; ?>"
                                                                    data-description="<?php echo $result['description']; ?>"
                                                                    data-image="<?php echo $result['image']; ?>"
                                                                    data-category="<?php echo $categoryId; ?>"
                                                                    data-subcategory="<?php echo $subCategoryId; ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-edit">
                                                                    <i class="ri-pencil-fill"></i>
                                                                </button>


                                                                <a href="<?php echo "delete-product.php?id=$result[id]" ?>"
                                                                    class="btn btn-danger btn-sm"> <i
                                                                        class="ri-delete-bin-fill"></i> </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Sub Category found";
                                            }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  Modal content for add Product -->
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Add Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Category</label>
                                                    <select id="categorySelect" name="category" class="form-select"
                                                        required>
                                                        <option value="">-- Choose --</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `category`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Sub-Category</label>
                                                    <select id="subCategorySelect" name="subcategory"
                                                        class="form-select" required>
                                                        <option value="">-- Choose --</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="validationCustom01" class="form-label">Product
                                                        name</label>
                                                    <input type="text" class="form-control" id="validationCustom01"
                                                        placeholder="Category name" name="name" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row image-wrapper">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="validationCustom02" class="form-label">Product
                                                                Image</label>
                                                            <input type="file" name="image"
                                                                class="form-control imageInput" id="validationCustom02"
                                                                required="">
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <img src="" alt="Selected Image" class="showImageInput"
                                                            style="max-width: 100px; display: none;">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="validationCustom03" class="form-label">Product
                                                        Description</label>
                                                    <textarea id="validationCustom03" name="description"
                                                        class="form-control" required=""></textarea>

                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div id="productVariants">
                                                <div class="card productVariant">
                                                    <div class="card-body">
                                                        <div class="row d-flex align-items-center">
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Size / Weight /
                                                                        Variant</label>
                                                                    <input type="text" class="form-control variant"
                                                                        name="variant[]" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">MRP</label>
                                                                    <input type="number" class="form-control mrp"
                                                                        name="mrp[]" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Sale Price</label>
                                                                    <input type="number" class="form-control salePrice"
                                                                        name="salePrice[]" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Discount</label>
                                                                    <h6 class="discount">0%</h6>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 text-end">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm removeVariant">x</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <button type="button" id="addMore"
                                                    class="btn btn-warning waves-effect waves-light">
                                                    <i class="ri-error-warning-line align-middle me-2"></i> Add More
                                                </button>
                                            </div>


                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="submit">Add
                                                Product</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['submit'])) {

                                        // Handle image upload
                                        $filename = $_FILES["image"]["name"];
                                        $tempname = $_FILES["image"]["tmp_name"];
                                        $folder = "product/" . mysqli_real_escape_string($conn, $filename);
                                        move_uploaded_file($tempname, $folder);

                                        // Sanitize input
                                        $category = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['category']));
                                        $subcategory = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['subcategory']));
                                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                                        $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));

                                        // Insert product into `product` table
                                        $query2 = "INSERT INTO `product`(`categoryId`, `subCategoryId`, `name`, `image`, `description`, `status`) 
                                                   VALUES ('$category', '$subcategory', '$name', '$folder', '$description', '1')";
                                        $data2 = mysqli_query($conn, $query2);

                                        if ($data2) {
                                            // Get the last inserted product ID
                                            $product_id = mysqli_insert_id($conn);

                                            // Loop through variants and insert them
                                            if (!empty($_POST['variant']) && is_array($_POST['variant'])) {
                                                foreach ($_POST['variant'] as $index => $variant_name) {
                                                    $mrp = floatval($_POST['mrp'][$index]);
                                                    $sale_price = floatval($_POST['salePrice'][$index]);

                                                    // Calculate discount
                                                    $discount = ($mrp > 0) ? (($mrp - $sale_price) / $mrp) * 100 : 0;

                                                    // Insert into `product_variant` table
                                                    $queryVariant = "INSERT INTO `product_variant` (`product_id`, `variant_name`, `mrp`, `sale_price`, `discount`) 
                                                                     VALUES ('$product_id', '$variant_name', '$mrp', '$sale_price', '$discount')";
                                                    mysqli_query($conn, $queryVariant);
                                                }
                                            }

                                            // Success Alert
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'New Product added successfully.',
                                                    icon: 'success',
                                                    showConfirmButton: false,
                                                    timer: 2000 
                                                }).then(() => {
                                                    window.location.href = 'product.php'; 
                                                });
                                            </script>";
                                        } else {
                                            // Error Alert
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Failed to add product. Please try again.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            </script>";
                                        }
                                    }

                                    ?>


                                    <!-- ---------------------------------- Ajex for load subcategory  ------------------------- -->

                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script>
                                        $(document).ready(function () {
                                            $("#categorySelect").change(function () {
                                                var categoryId = $(this).val();
                                                $("#subCategorySelect").html('<option value="">-- Loading... --</option>');

                                                if (categoryId != "") {
                                                    $.ajax({
                                                        url: "fetch-subcategory.php",
                                                        type: "POST",
                                                        data: { categoryId: categoryId },
                                                        success: function (data) {
                                                            $("#subCategorySelect").html(data);
                                                        }
                                                    });
                                                } else {
                                                    $("#subCategorySelect").html('<option value="">-- Choose --</option>');
                                                }
                                            });
                                        });
                                    </script>


                                    <!-------------- Calculate discount percentage dynamically when MRP & Sale Price change. ------------------------ -->

                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script>
                                        $(document).ready(function () {
                                            // Function to calculate discount
                                            function calculateDiscount(element) {
                                                let mrp = parseFloat($(element).closest('.productVariant').find('.mrp').val()) || 0;
                                                let salePrice = parseFloat($(element).closest('.productVariant').find('.salePrice').val()) || 0;

                                                if (mrp > 0 && salePrice > 0 && salePrice < mrp) {
                                                    let discount = ((mrp - salePrice) / mrp) * 100;
                                                    $(element).closest('.productVariant').find('.discount').text(discount.toFixed(2) + "%");
                                                } else {
                                                    $(element).closest('.productVariant').find('.discount').text("0%");
                                                }
                                            }

                                            // When MRP or Sale Price changes, recalculate discount
                                            $(document).on("input", ".mrp, .salePrice", function () {
                                                calculateDiscount(this);
                                            });

                                            // Add More Button Click
                                            $("#addMore").click(function () {
                                                let newVariant = $(".productVariant").first().clone();
                                                newVariant.find("input").val(""); // Clear input fields
                                                newVariant.find(".discount").text("0%"); // Reset discount
                                                $("#productVariants").append(newVariant);
                                            });

                                            // Remove Variant Button
                                            $(document).on("click", ".removeVariant", function () {
                                                if ($(".productVariant").length > 1) {
                                                    $(this).closest(".productVariant").remove();
                                                }
                                            });
                                        });
                                    </script>

                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


                    <!--  Modal content for edit product -->
                    <div class="modal fade bs-example-modal-lg-edit" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Update Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">


                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">
                                        <input type="hidden" id="modalId" name="uid">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Category</label>
                                                    <select id="categorySelectU" name="ucategory" class="form-select"
                                                        required>
                                                        <option value="">-- Choose --</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `category`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Sub-Category</label>
                                                    <select id="subCategorySelectU" name="usubcategory"
                                                        class="form-select" required>
                                                        <option value="">-- Choose --</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `subcategory`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="modalName" class="form-label">Product
                                                        name</label>
                                                    <input type="text" class="form-control" id="modalName"
                                                        placeholder="Category name" name="uname" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row image-wrapper">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="validationCustom02" class="form-label">Product
                                                                Image</label>
                                                            <input type="file" name="image"
                                                                class="form-control imageInput" id="validationCustom02">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <img src="" alt="Selected Image" class="showImageInput"
                                                            style="max-width: 100px; display: none;">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="modalDescription" class="form-label">Product
                                                        Description</label>
                                                    <textarea id="modalDescription" name="udescription"
                                                        class="form-control" required=""></textarea>

                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="updateProduct">Update
                                                Product</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['updateProduct'])) {

                                        // Handle image upload
                                        $filename = $_FILES["image"]["name"];
                                        $tempname = $_FILES["image"]["tmp_name"];
                                        $folder = "product/" . mysqli_real_escape_string($conn, $filename);

                                        // Sanitize input
                                        $id = $_POST['uid'];
                                        $category = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ucategory']));
                                        $subcategory = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['usubcategory']));
                                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uname']));
                                        $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['udescription']));

                                        // Conditional update query
                                        if (!empty($filename)) {
                                            move_uploaded_file($tempname, $folder);
                                            $query2 = "UPDATE `product` SET 
                                            `categoryId`='$category',
                                            `subCategoryId`='$subcategory',
                                            `name`='$name',
                                            `image`='$folder',
                                            `description`='$description'
                                            WHERE `id`='$id'";
                                                                } else {
                                                                    $query2 = "UPDATE `product` SET 
                                            `categoryId`='$category',
                                            `subCategoryId`='$subcategory',
                                            `name`='$name',
                                            `description`='$description'
                                            WHERE `id`='$id'";
                                        }

                                        // Execute the query
                                        $data2 = mysqli_query($conn, $query2);

                                        // Start output buffering
                                        ob_start();

                                        // Success alert
                                        if ($data2) {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Product Updated.',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 2000 
                                        }).then(() => {
                                            window.location.href = 'product.php'; 
                                        });
                                    </script>";
                                        } else {
                                            // Error alert
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Failed. Please try again.',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            });
                                        </script>";
                                        }

                                        // Flush output buffer
                                        ob_end_flush();
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.modal -->


                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".editCategoryBtn").forEach(button => {
                                button.addEventListener("click", function () {



                                    let id = this.getAttribute("data-id");
                                    let name = this.getAttribute("data-name");
                                    let description = this.getAttribute("data-description");
                                    let image = this.getAttribute("data-image");
                                    let category = this.getAttribute("data-category");
                                    let subcategory = this.getAttribute("data-subcategory");

                                    // Populate input fields
                                    document.getElementById("modalId").value = id;
                                    document.getElementById("modalName").value = name;
                                    document.getElementById("modalDescription").value = description;

                                    // Set the selected category in the dropdown
                                    let categoryDropdown = document.getElementById("categorySelectU");
                                    for (let option of categoryDropdown.options) {
                                        if (option.value === category) {
                                            option.selected = true;
                                            break;
                                        }
                                    }

                                    // Set the selected sub-category in the dropdown
                                    let subCategoryDropdown = document.getElementById("subCategorySelectU");
                                    for (let option of subCategoryDropdown.options) {
                                        if (option.value === subcategory) {
                                            option.selected = true;
                                            break;
                                        }
                                    }
                                });
                            });
                        });
                    </script>


                    <!--  Modal content for view product -->
                    <div class="modal fade bs-example-modal-lg-view" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewName"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img id="viewImage" src="" alt="Category Image" class="img-fluid rounded"
                                                style="max-width: 200px; border: 1px solid #ddd; margin-bottom: 15px;">
                                        </div>
                                        <div class="col-md-8">
                                            <!-- <p id="viewId" style="display: none;"></p> -->
                                            <!-- Category Description -->
                                            <p>Category: <b id="viewCategory"></b></p>
                                            <p>Sub-Category: <b id="viewSubCategory"></b></p>
                                            <p id="viewDescription"></p>
                                        </div>
                                    </div>
                                    <!-- Category Image -->



                                    <!-- Product Variants Section -->
                                    <p id="viewProductId" style="display: none;"></p>
                                    <h5>Product Variants
                                        <button type="button"
                                            class="btn btn-warning waves-effect waves-light btn-sm variantBtn"
                                            data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg-variant">
                                            Add new Variants
                                        </button>
                                    </h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Variant</th>
                                                <th>MRP</th>
                                                <th>Sale Price</th>
                                                <th>Discount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantTableBody">
                                            <!-- Variants will be inserted here dynamically -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- /.modal -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewCategoryBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let id = this.getAttribute("data-id");
                                    let name = this.getAttribute("data-name");
                                    let description = this.getAttribute("data-description");
                                    let image = this.getAttribute("data-image");
                                    let category = this.getAttribute("data-category");
                                    let subcategory = this.getAttribute("data-subcategory");

                                    // Set modal content
                                    document.getElementById("viewProductId").textContent = id;
                                    document.getElementById("viewCategory").textContent = category;
                                    document.getElementById("viewSubCategory").textContent = subcategory;
                                    document.getElementById("viewName").textContent = name;
                                    document.getElementById("viewDescription").textContent = description;
                                    document.getElementById("viewImage").src = image;

                                    // Fetch product variants using AJAX
                                    fetch("get_variants.php?id=" + id)
                                        .then(response => response.json())
                                        .then(data => {
                                            let variantTableBody = document.getElementById("variantTableBody");
                                            variantTableBody.innerHTML = ""; // Clear existing content

                                            if (data.length > 0) {
                                                data.forEach(variant => {
                                                    let discount = (
                                                        ((variant.mrp - variant.sale_price) / variant.mrp) * 100
                                                    ).toFixed(2);

                                                    variantTableBody.innerHTML += `
                                <tr data-variant-id="${variant.id}">
                                    <td><input type="text" class="form-control variant-name" value="${variant.variant_name}"></td>
                                    <td><input type="number" class="form-control variant-mrp" value="${variant.mrp}"></td>
                                    <td><input type="number" class="form-control variant-sale" value="${variant.sale_price}"></td>
                                    <td class="variant-discount">${discount}%</td>
                                    <td>
                                        <button class="btn btn-success btn-sm save-variant">Save</button>
                                        <button class="btn btn-danger btn-sm delete-variant">Delete</button>
                                    </td>
                                </tr>`;
                                                });
                                            } else {
                                                variantTableBody.innerHTML = `<tr><td colspan="5">No variants available.</td></tr>`;
                                            }
                                        });
                                });
                            });

                            // Event delegation for handling "Save" button clicks inside the dynamically created table rows
                            document.getElementById("variantTableBody").addEventListener("click", function (event) {
                                if (event.target.classList.contains("save-variant")) {
                                    let row = event.target.closest("tr");
                                    let variantId = row.getAttribute("data-variant-id");
                                    let variantName = row.querySelector(".variant-name").value;
                                    let variantMRP = row.querySelector(".variant-mrp").value;
                                    let variantSale = row.querySelector(".variant-sale").value;

                                    // Calculate new discount
                                    let discount = ((variantMRP - variantSale) / variantMRP * 100).toFixed(2);
                                    row.querySelector(".variant-discount").textContent = discount + "%";

                                    // Send data to update_variants.php using Fetch API
                                    fetch("update_variants.php", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/x-www-form-urlencoded",
                                        },
                                        body: `id=${variantId}&variant_name=${variantName}&mrp=${variantMRP}&sale_price=${variantSale}`
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                alert("Variant updated successfully!");
                                            } else {
                                                alert("Failed to update variant.");
                                            }
                                        });
                                }

                                // Event listener for Delete button
                                if (event.target.classList.contains("delete-variant")) {
                                    let row = event.target.closest("tr");
                                    let variantId = row.getAttribute("data-variant-id");

                                    // Confirm deletion
                                    if (confirm("Are you sure you want to delete this variant?")) {
                                        // Send delete request to delete_variant.php using Fetch API
                                        fetch("delete_variant.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/x-www-form-urlencoded",
                                            },
                                            body: `id=${variantId}`
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    row.remove(); // Remove the row from the table
                                                    alert("Variant deleted successfully!");
                                                } else {
                                                    alert("Failed to delete variant.");
                                                }
                                            });
                                    }
                                }
                            });
                        });

                    </script>


                    <!--  Modal content for add Product Variants  -->
                    <div class="modal fade bs-example-modal-lg-variant" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Update Product Variant</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">

                                        <input type="hidden" id="productVariantId" name="product_id">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Size / Weight /
                                                        Variant</label>
                                                    <input type="text" class="form-control variant" name="variant"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">MRP</label>
                                                    <input type="number" class="form-control mrp" name="mrp" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Sale Price</label>
                                                    <input type="number" class="form-control salePrice" name="salePrice"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="mb-3">
                                                    <label class="form-label">Discount</label>
                                                    <input type="hidden" name="discount"
                                                        class="form-control discountInput" readonly>
                                                    <!-- Readonly input -->
                                                    <h6 class="discountText">0%</h6> <!-- Discount percentage -->
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                function calculateDiscount(mrp, salePrice) {
                                                    if (mrp > 0 && salePrice > 0 && salePrice <= mrp) {
                                                        return (((mrp - salePrice) / mrp) * 100).toFixed(2); // Calculate Discount %
                                                    }
                                                    return "0.00";
                                                }

                                                function updateDiscount(inputElement) {
                                                    let row = inputElement.closest(".row"); // Get the current row
                                                    let mrp = parseFloat(row.querySelector(".mrp").value) || 0;
                                                    let salePrice = parseFloat(row.querySelector(".salePrice").value) || 0;
                                                    let discount = calculateDiscount(mrp, salePrice); // Get calculated discount

                                                    // Update the discount field and h6 text
                                                    row.querySelector(".discountInput").value = discount + "%";
                                                    row.querySelector(".discountText").textContent = discount + "%";
                                                }

                                                // Attach event listeners to both MRP and Sale Price fields
                                                document.querySelectorAll(".mrp, .salePrice").forEach(input => {
                                                    input.addEventListener("input", function () {
                                                        updateDiscount(this);
                                                    });
                                                });
                                            });
                                        </script>


                                        <div>
                                            <button class="btn btn-dark" type="submit" name="addProductVariant">Add
                                                Product variant</button>
                                        </div>
                                    </form>

                                    <?php

                                    if (isset($_POST['addProductVariant'])) {

                                        // Sanitize input
                                        $product_id = $_POST['product_id'];
                                        $variant = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['variant']));
                                        $mrp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['mrp']));
                                        $salePrice = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['salePrice']));
                                        $discount = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['discount']));



                                        // Insert query
                                        $query2 = "INSERT INTO `product_variant`(`product_id`, `variant_name`, `mrp`, `sale_price`, `discount`) VALUES ('$product_id', '$variant', '$mrp', '$salePrice', '$discount')";

                                        // Execute the query
                                        $data2 = mysqli_query($conn, $query2);

                                        // Start output buffering
                                        ob_start();

                                        // Success alert
                                        if ($data2) {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                            Swal.fire({
                                            title: 'Success!',
                                            text: 'New Product Variant added.',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 2000 
                                            }).then(() => {
                                            window.location.href = 'product.php'; 
                                            });
                                            </script>";
                                        } else {
                                            // Error alert
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                            Swal.fire({
                                            title: 'Error!',
                                            text: 'Failed. Please try again.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                            });
                                            </script>";
                                        }

                                        // Flush output buffer
                                        ob_end_flush();
                                    }

                                    ?>


                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewCategoryBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let productId = this.getAttribute("data-id"); // Get product ID from button
                                    document.getElementById("viewProductId").textContent = productId; // Set it in modal

                                    // Also update the "Add Variant" button data-id
                                    document.querySelector(".variantBtn").setAttribute("data-id", productId);
                                });
                            });

                            // When "Add Variant" button is clicked, pass the product ID to the hidden input field
                            document.querySelector(".variantBtn").addEventListener("click", function () {
                                let productId = this.getAttribute("data-id");
                                document.getElementById("productVariantId").value = productId;
                            });
                        });


                    </script>


                </div>

            </div>
            <!-- End Page-content -->

            <?php include("./components/footer.php"); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to all status toggle switches
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const productId = this.getAttribute('data-id');
            const isChecked = this.checked ? 1 : 0;
            const statusLabel = this.nextElementSibling;
            
            // Update label text immediately for user feedback
            statusLabel.textContent = isChecked ? 'In Stock' : 'Out of Stock';
            
            // Send AJAX request to update status
            fetch('status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${productId}&status=${isChecked}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success notification
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `Product is now ${isChecked ? 'In Stock' : 'Out of Stock'}`,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Error notification and revert toggle
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update product status'
                    });
                    this.checked = !this.checked;
                    statusLabel.textContent = this.checked ? 'In Stock' : 'Out of Stock';
                }
            });
        });
    });
});
</script>

</body>

</html>