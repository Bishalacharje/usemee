<?php

include("../connection.php");
session_start();
include("checked-login.php");






?>




<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Seller | Usemee</title>
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
                                <h4 class="mb-sm-0">Seller</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active">Seller</li>
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
                                    <h5 class="mb-0 text-dark">Seller</h5>
                                    <button type="button" class="btn btn-success waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Add new
                                        Seller</button>
                                </div>
                                <div class="card-body">

                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `seller` WHERE `zone` = '$admin_zone'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Category</th>
                                                        <th>Address</th>
                                                        <!-- <th>Category Description</th> -->
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>


                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) { ?>

                                                        <?php
                                                        $cid = $result['category'];
                                                        $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
                                                        $datan = mysqli_query($conn, $queryn);
                                                        $resultn = mysqli_fetch_assoc($datan);
                                                        $categoryName = $resultn['name'];

                                                        ?>


                                                        <tr>
                                                            <td><?php echo $result['name']; ?></td>

                                                            <td><?php echo $result['phone']; ?></td>
                                                            <td><?php echo $categoryName; ?></td>

                                                            <td>
                                                                <p style="width: 500px;overflow: auto; margin-bottom: 0;">
                                                                    <?php echo $result['address']; ?>
                                                                </p>
                                                                <h6><?php echo $result['pin']; ?></h6>
                                                            </td>

                                                            <td>

                                                            <a href="<?php echo "seller-bill-report.php?id=$result[id]" ?>"
                                                                    class="btn btn-dark btn-sm"> <i
                                                                        class="ri-file-paper-2-fill"></i> </a>
                                                                <button type="button"
                                                                    class="btn btn-warning waves-effect waves-light btn-sm viewCategoryBtn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-view">
                                                                    <i class="ri-eye-fill"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-info waves-effect waves-light btn-sm editCategoryBtn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-edit">
                                                                    <i class="ri-pencil-fill"></i>
                                                                </button>


                                                                <a href="<?php echo "delete-seller.php?id=$result[id]" ?>"
                                                                    class="btn btn-danger btn-sm"> <i
                                                                        class="ri-delete-bin-fill"></i> </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Seller found";
                                            }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>








                    <!--  Modal content for add Seller -->
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Add Seller</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-6">
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


                                            <div class="col-md-9">
                                                <div class="mb-3">
                                                    <label class="form-label">Seller Name</label>
                                                    <input type="text" class="form-control" name="name" required>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control" name="phone" required>
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control"
                                                value="<?php echo $admin_zone; ?>" name="zone" readonly>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Pin</label>
                                                    <input type="text" class="form-control variant" name="pin" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="validationCustom03" class="form-label">Address
                                                    </label>
                                                    <textarea id="validationCustom03" name="address"
                                                        class="form-control" required=""></textarea>

                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="submit">Add
                                                Seller</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['submit'])) {



                                        // Sanitize input
                                        $category = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['category']));
                                        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                                        $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                                        $pin = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pin']));
                                        $address = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['address']));


                                        // Insert query
                                        $query2 = "INSERT INTO `seller`(`category`, `name`, `phone`, `zone`, `pin`, `address`) VALUES ('$category', '$name', '$phone', '$zone', '$pin', '$address')";

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
                    text: 'New Seller added.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000 
                }).then(() => {
                    window.location.href = 'seller.php'; 
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


                                    <!-- ---------------------------------- Ajex for load subcategory  ------------------------- -->

                                    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                    </script> -->




                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->




                    <!--  Modal content for edit sub category -->
                    <div class="modal fade bs-example-modal-lg-edit" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Update Sub-Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">


                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">

                                        <input type="hidden" name="uid" id="modalCategoryId">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Select Category</label>
                                                    <select name="category" id="modalCategorySelect" class="form-select"
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
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="modalCategoryNameInput" class="form-label">Sub-Category
                                                        name</label>
                                                    <input type="text" name="name" id="modalCategoryNameInput"
                                                        class="form-control">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row image-wrapper">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Sub-Category
                                                                Image</label>
                                                            <input type="file" name="image"
                                                                class="form-control imageInput" id="">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <img src="" alt="Selected Image" class="showImageInput"
                                                            style="max-width: 100px; display: none;">
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="modalCategoryDescriptionInput"
                                                        class="form-label">Sub-Category
                                                        Description</label>
                                                    <textarea id="modalCategoryDescriptionInput" name="description"
                                                        class="form-control" required=""></textarea>

                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="updatesubcategory">Update
                                                Sub-Category</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['updatesubcategory'])) {

                                        // Handle image upload
                                        $filename = $_FILES["image"]["name"];
                                        $tempname = $_FILES["image"]["tmp_name"];
                                        $folder = "category/" . mysqli_real_escape_string($conn, $filename);

                                        // Sanitize input
                                        $id = $_POST['uid'];
                                        $category = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['category']));
                                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                                        $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));

                                        // Conditional update query
                                        if (!empty($filename)) {
                                            move_uploaded_file($tempname, $folder);
                                            $query2 = "UPDATE `subcategory` SET 
                    `categoryId`='$category',
                    `name`='$name',
                    `image`='$folder',
                    `description`='$description'
                    WHERE `id`='$id'";
                                        } else {
                                            $query2 = "UPDATE `subcategory` SET 
                    `categoryId`='$category',
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
                text: 'Sub Category Updated.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = 'sub-category.php'; 
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
                                    let categoryId = this.getAttribute("data-id");
                                    let categoryName = this.getAttribute("data-name");
                                    let categoryDescription = this.getAttribute("data-description");
                                    let categorySelected = this.getAttribute("data-category"); // Get selected category ID

                                    // Populate input fields
                                    document.getElementById("modalCategoryId").value = categoryId;
                                    document.getElementById("modalCategoryNameInput").value = categoryName;
                                    document.getElementById("modalCategoryDescriptionInput").value = categoryDescription;

                                    // Set the selected category in the dropdown
                                    let categoryDropdown = document.getElementById("modalCategorySelect");
                                    for (let option of categoryDropdown.options) {
                                        if (option.value === categorySelected) {
                                            option.selected = true;
                                            break;
                                        }
                                    }
                                });
                            });
                        });
                    </script>






                    <!--  Modal content for view sub category -->
                    <div class="modal fade bs-example-modal-lg-view" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewCategoryName"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Category Image -->
                                    <img id="viewCategoryImage" src="" alt="Category Image" class="img-fluid rounded"
                                        style="max-width: 200px; border: 1px solid #ddd; margin-bottom: 15px;">

                                    <!-- Category Description -->
                                    <p>Category: <b id="viewCategory"></b></p>
                                    <p id="viewCategoryDescription"></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- /.modal -->





                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewCategoryBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let categoryName = this.getAttribute("data-name");
                                    let categoryDescription = this.getAttribute("data-description");
                                    let categoryImage = this.getAttribute("data-image");
                                    let category = this.getAttribute("data-category");

                                    // Set modal content
                                    document.getElementById("viewCategory").textContent = category;
                                    document.getElementById("viewCategoryName").textContent = categoryName;
                                    document.getElementById("viewCategoryDescription").textContent = categoryDescription;

                                    // Set image source
                                    document.getElementById("viewCategoryImage").src = categoryImage;
                                });
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

</body>

</html>