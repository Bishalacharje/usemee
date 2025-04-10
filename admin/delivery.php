<?php include("../connection.php"); session_start(); include("checked-login.php"); ?>




    <!doctype html>
    <html lang="en">

    <head>
        <?php include("./components/headlink.php"); ?>
        <title>Delivery Boy | Usemee</title>
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
                                    <h4 class="mb-sm-0">Delivery Boy</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                            <li class="breadcrumb-item active">Delivery Boy</li>
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
                                        <h5 class="mb-0 text-dark">Delivery Boy</h5>
                                        <button type="button" class="btn btn-success waves-effect waves-light"
                                            data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Add new
                                            Delivery Boy</button>
                                    </div>
                                    <div class="card-body">

                                        <div class="table-con">
                                            <table id="datatable-buttons"
                                                class="table table-striped table-bordered dt-responsive nowrap"
                                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <?php
                                                $query = "SELECT * FROM `delivery` WHERE `zone` = '$admin_zone'";
                                                $data = mysqli_query($conn, $query);
                                                $total = mysqli_num_rows($data);


                                                if ($total != 0) {
                                                    ?>
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Email</th>
                                                            <!-- <th>Category Description</th> -->
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>


                                                    <tbody>
                                                        <?php

                                                        while ($result = mysqli_fetch_assoc($data)) { ?>




                                                            <tr>
                                                                <td><?php echo $result['name']; ?></td>

                                                                <td><?php echo $result['phone']; ?></td>

                                                                <td><?php echo $result['email']; ?></td>

                                                                <td>

                                                                    <button type="button"
                                                                        class="btn btn-warning waves-effect waves-light btn-sm viewCategoryBtn"
                                                                        data-id="<?php echo $result['id']; ?>"
                                                                        data-name="<?php echo $result['name']; ?>"
                                                                        data-phone="<?php echo $result['phone']; ?>"
                                                                        data-email="<?php echo $result['email']; ?>"
                                                                        data-aadhaarno="<?php echo $result['aadhaar_no']; ?>"
                                                                        data-photo="<?php echo $result['photo']; ?>"
                                                                        data-aadhaarphoto="<?php echo $result['aadhaar_photo']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target=".bs-example-modal-lg-view">
                                                                        <i class="ri-eye-fill"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-info waves-effect waves-light btn-sm editCategoryBtn"
                                                                        data-id="<?php echo $result['id']; ?>"
                                                                        data-name="<?php echo $result['name']; ?>"
                                                                        data-phone="<?php echo $result['phone']; ?>"
                                                                        data-email="<?php echo $result['email']; ?>"
                                                                        data-aadhaarno="<?php echo $result['aadhaar_no']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target=".bs-example-modal-lg-edit">
                                                                        <i class="ri-pencil-fill"></i>
                                                                    </button>

                                                                    <button type="button"
                                                                        class="btn btn-dark waves-effect waves-light btn-sm resetPasswordBtn"
                                                                        data-id="<?php echo $result['id']; ?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target=".bs-example-modal-lg-reset">
                                                                        Reset Password
                                                                    </button>

                                                                    <a href="<?php echo "delete-deleviry.php?id=$result[id]" ?>"
                                                                        class="btn btn-danger btn-sm"> <i
                                                                            class="ri-delete-bin-fill"></i> </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                } else {
                                                    echo "No Delivery Boy found";
                                                }


                                                ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>








                        <!--  Modal content for add delivery boy -->
                        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myLargeModalLabel">Add Delivery Boy</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" enctype="multipart/form-data"
                                            class="needs-validation was-validated" novalidate="">
                                            <div class="row">



                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Delivery Boy Name</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter full name" name="name" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="number" class="form-control"
                                                            placeholder="Enter phone number" name="phone" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="validationCustom04" class="form-label">
                                                            Email</label>
                                                        <input type="email" class="form-control" id="validationCustom04"
                                                            placeholder="Enter email" name="email" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="validationCustom05" class="form-label">
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            id="validationCustom05" placeholder="Enter password"
                                                            name="password" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="form-control"
                                                    value="<?php echo $admin_zone; ?>" name="zone" readonly>



                                            </div>

                                            <div>
                                                <button class="btn btn-dark" type="submit" name="submit">Add
                                                    Delivery Boy</button>
                                            </div>
                                        </form>

                                        <?php
                                        if (isset($_POST['submit'])) {



                                            // Sanitize input
                                        
                                            $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                                            $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                            $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));

                                            $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                                            $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

                                            // Hash the password
                                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


                                            // Insert query
                                            $query2 = "INSERT INTO `delivery`(`name`, `phone`, `zone`, `email`, `password`) VALUES ('$name', '$phone', '$zone', '$email', '$hashedPassword')";

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
                    text: 'New Delivery boy added.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000 
                }).then(() => {
                    window.location.href = 'delivery.php'; 
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


                        <!--  Modal content for view category -->
                        <div class="modal fade bs-example-modal-lg-view" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewDeliveryName"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img id="viewDeliveryPhoto" src="" alt="" class="img-fluid rounded"
                                                                                            style="max-width: 150px; border: 1px solid #ddd; margin-bottom: 15px;">
                                            </div>
                                            <div class="col-md-8">
                                            <h6>Phone: <span id="viewPhone"></span></h6>
                                            <h6>Email: <span id="viewEmail"></span></h6>
                                            <hr>
                                        
                                            <h6>Aadhaar No: <span id="viewAadhaarNo"></span></h6>
                                            <img id="viewAadhaarPhoto" src="" alt="" class="img-fluid rounded"
                                            style="max-width: 300px; border: 1px solid #ddd; margin-bottom: 15px;">
                                            
                                            </div>
                                        </div>
                                        

                                        <!-- Category Description -->
                                     
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- /.modal -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                document.querySelectorAll(".viewCategoryBtn").forEach(button => {
                                    button.addEventListener("click", function () {
                                        let deliveryName = this.getAttribute("data-name");
                                        let phone = this.getAttribute("data-phone");
                                        let email = this.getAttribute("data-email");
                                        let aadhaarNo = this.getAttribute("data-aadhaarno");


                                        let deliveryPhoto = this.getAttribute("data-photo");
                                        let aadhaarPhoto = this.getAttribute("data-aadhaarphoto");

                                        // Set modal content
                                        document.getElementById("viewDeliveryName").textContent = deliveryName;
                                        document.getElementById("viewPhone").textContent = phone;
                                        document.getElementById("viewEmail").textContent = email;
                                        document.getElementById("viewAadhaarNo").textContent = aadhaarNo;

                                        // Set image source
                                        document.getElementById("viewDeliveryPhoto").src = deliveryPhoto;
                                        document.getElementById("viewAadhaarPhoto").src = aadhaarPhoto;
                                    });
                                });
                            });
                        </script>



                        <!--  Modal content for edit delivery boy -->
                        <div class="modal fade bs-example-modal-lg-edit" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myLargeModalLabel">Update Delivery Boy Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">


                                        <form method="POST" enctype="multipart/form-data"
                                            class="needs-validation was-validated" novalidate="">

                                            <input type="hidden" name="uid" id="modaladminId">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="modaladminName" class="form-label">Delivery boy
                                                            name</label>
                                                        <input type="text" class="form-control" placeholder="Full name"
                                                            name="aname" id="modaladminName" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="modaladminPhone" class="form-label">Phone
                                                            Number</label>
                                                        <input type="number" class="form-control" id="modaladminPhone"
                                                            placeholder="admin number" name="phone" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="modaladminEmail" class="form-label">
                                                            Email</label>
                                                        <input type="email" class="form-control" id="modaladminEmail"
                                                            placeholder="admin email" name="email" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">
                                                            Photo</label>
                                                        <input type="file" class="form-control" id="" name="photo">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="modalaadhaarNo" class="form-label">
                                                            Aadhaar No</label>
                                                        <input type="number" class="form-control" id="modalaadhaarNo"
                                                            placeholder="xxxx xxxx xxxx" name="aadhaar_no" required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">
                                                            Aadhaar Photo</label>
                                                        <input type="file" class="form-control" id=""
                                                            name="aadhaar_img">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>





                                            </div>

                                            <div>
                                                <button class="btn btn-dark" type="submit" name="updatedelivery">Update
                                                    Delivery Boy</button>
                                            </div>
                                        </form>

                                        <?php
                                        if (isset($_POST['updatedelivery'])) {
                                            $id = $_POST['uid'];

                                            // Sanitize input
                                            $aname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aname']));
                                            $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                            $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                                            $aadhaar_no = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aadhaar_no']));

                                            // File paths
                                            $photoPath = "";
                                            $aadhaarPath = "";

                                            // Photo upload
                                            if (!empty($_FILES["photo"]["name"])) {
                                                $filename = mysqli_real_escape_string($conn, $_FILES["photo"]["name"]);
                                                $tempname = $_FILES["photo"]["tmp_name"];
                                                $photoPath = "delivery/" . $filename;
                                                move_uploaded_file($tempname, $photoPath);
                                            }

                                            // Aadhaar upload
                                            if (!empty($_FILES["aadhaar_img"]["name"])) {
                                                $filename2 = mysqli_real_escape_string($conn, $_FILES["aadhaar_img"]["name"]);
                                                $tempname2 = $_FILES["aadhaar_img"]["tmp_name"];
                                                $aadhaarPath = "delivery/" . $filename2;
                                                move_uploaded_file($tempname2, $aadhaarPath);
                                            }

                                            // Build the update query
                                            $updateFields = "`name`='$aname', `phone`='$phone', `email`='$email', `aadhaar_no`='$aadhaar_no'";
                                            if (!empty($photoPath)) {
                                                $updateFields .= ", `photo`='$photoPath'";
                                            }
                                            if (!empty($aadhaarPath)) {
                                                $updateFields .= ", `aadhaar_photo`='$aadhaarPath'";
                                            }

                                            $query2 = "UPDATE `delivery` SET $updateFields WHERE `id`='$id'";
                                            $data2 = mysqli_query($conn, $query2);

                                            // Start output buffering
                                            ob_start();

                                            if ($data2) {
                                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                                echo "<script>
                                                    Swal.fire({
                                                        title: 'Success!',
                                                        text: 'Delivery boy Profile Updated.',
                                                        icon: 'success',
                                                        showConfirmButton: false,
                                                        timer: 2000 
                                                    }).then(() => {
                                                        window.location.href = 'delivery.php'; 
                                                    });
                                                </script>";
                                            } else {
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
                                        let adminId = this.getAttribute("data-id");
                                        let adminName = this.getAttribute("data-name");
                                        let adminPhone = this.getAttribute("data-phone");
                                        let adminEmail = this.getAttribute("data-email");
                                        let aadhaarNo = this.getAttribute("data-aadhaarno");






                                        // Populate input fields
                                        document.getElementById("modaladminId").value = adminId;
                                        document.getElementById("modaladminName").value = adminName;
                                        document.getElementById("modaladminPhone").value = adminPhone;
                                        document.getElementById("modaladminEmail").value = adminEmail;
                                        document.getElementById("modalaadhaarNo").value = aadhaarNo;
                                    });
                                });
                            });
                        </script>





                        <!--  Modal content for reset password -->
                        <div class="modal fade bs-example-modal-lg-reset" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reset Password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <form method="POST" enctype="multipart/form-data"
                                            class="needs-validation was-validated" novalidate="">

                                            <input type="hidden" name="nid" id="modaladminId2">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">New
                                                            Password</label>
                                                        <input type="text" class="form-control" name="npassword"
                                                            required="">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>




                                            </div>

                                            <div>
                                                <button class="btn btn-dark" type="submit" name="resetPassword">Update
                                                    Delivery Boy Password</button>
                                            </div>
                                        </form>

                                        <?php
                                        if (isset($_POST['resetPassword'])) {



                                            // Sanitize input
                                            $id = $_POST['nid'];
                                            $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['npassword']));

                                            // Hash the password
                                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


                                            $query2 = "UPDATE `delivery` SET 
                                        `password`='$hashedPassword'
                                        WHERE `id`='$id'";

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
                text: 'Password Updated',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = 'delivery.php'; 
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
                                document.querySelectorAll(".resetPasswordBtn").forEach(button => {
                                    button.addEventListener("click", function () {
                                        let adminId2 = this.getAttribute("data-id");




                                        // Set modal content
                                        document.getElementById("modaladminId2").value = adminId2;
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