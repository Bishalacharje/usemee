<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Admin | Usemee</title>
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
                                <h4 class="mb-sm-0">Admin</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active">Admin</li>
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
                                    <h5 class="mb-0 text-dark">Admin</h5>
                                    <button type="button" class="btn btn-success waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Add
                                        Admin</button>
                                </div>
                                <div class="card-body">

                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `admin`";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Zone</th>
                                                        <th>Email</th>
                                                        <!-- <th>Category Description</th> -->
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>


                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) {
                                                        $zoneId = $result['zone'];
                                                        $queryn = "SELECT * FROM `zone` WHERE `id`='$zoneId'";
                                                        $datan = mysqli_query($conn, $queryn);
                                                        $resultn = mysqli_fetch_assoc($datan);
                                                        $cityName = $resultn['city'];

                                                        ?>

                                                        <tr>
                                                            <td><?php echo $result['name']; ?></td>
                                                            <td><?php echo $result['phone']; ?></td>
                                                            <td><?php echo $cityName; ?> </td>
                                                            <td> <?php echo $result['email']; ?></td>

                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-info waves-effect waves-light btn-sm editCategoryBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-name="<?php echo $result['name']; ?>"
                                                                    data-phone="<?php echo $result['phone']; ?>"
                                                                    data-email="<?php echo $result['email']; ?>"
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

                                                                <a href="<?php echo "delete-admin.php?id=$result[id]" ?>"
                                                                    class="btn btn-danger btn-sm"> <i
                                                                        class="ri-delete-bin-fill"></i> </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Admin found";
                                            }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>








                    <!--  Modal content for add admin -->
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Add Admin</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="validationCustom01" class="form-label">Admin
                                                        name</label>
                                                    <input type="text" class="form-control" id="validationCustom01"
                                                        placeholder="admin name" name="aname" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="validationCustom02" class="form-label">Admin
                                                        Phone Number</label>
                                                    <input type="number" class="form-control" id="validationCustom02"
                                                        placeholder="admin number" name="phone" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="validationCustom03" class="form-label">Select
                                                        Zone</label>
                                                    <select name="zone" id="validationCustom03" class="form-select"
                                                        required>
                                                        <option value="">-- Choose --</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `zone`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
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
                                                    <label for="validationCustom04" class="form-label">Admin
                                                        Email</label>
                                                    <input type="email" class="form-control" id="validationCustom04"
                                                        placeholder="admin email" name="email" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="validationCustom05" class="form-label">Admin
                                                        Password</label>
                                                    <input type="text" class="form-control" id="validationCustom05"
                                                        placeholder="admin password" name="password" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="submit">Add
                                                Admin</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['submit'])) {
                                        // Sanitize input data
                                        $aname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aname']));
                                        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                        $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                                        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                                        $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));

                                        // Hash the password
                                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);




                                        // Insert data into the database
                                        $query2 = "INSERT INTO `admin`(`name`, `phone`, `zone`, `email`, `password`) VALUES ('$aname','$phone','$zone','$email','$hashedPassword')";

                                        // Execute the query
                                        $data2 = mysqli_query($conn, $query2);

                                        // Check if data was inserted
                                        if ($data2) {
                                            // SweetAlert for successful creation
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Admin Created',
                    showConfirmButton: false,
                    timer: 2000 // Auto close after 2 seconds
                }).then(() => {
                    window.location.href = 'admin.php'; // Redirect after the alert
                });
              </script>";
                                        } else {
                                            // Output error with SweetAlert
                                            $errorMessage = mysqli_error($conn); // Capture the MySQL error
                                            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create admin. Error: $errorMessage',
                    confirmButtonText: 'OK'
                });
              </script>";
                                        }

                                        // Close the database connection
                                        mysqli_close($conn);
                                    }
                                    ?>



                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->




                    <!--  Modal content for edit admin -->
                    <div class="modal fade bs-example-modal-lg-edit" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Update Admin Profile</h5>
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
                                                    <label for="modaladminName" class="form-label">Admin
                                                        name</label>
                                                    <input type="text" class="form-control" placeholder="admin name"
                                                        name="aname" id="modaladminName" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="modaladminPhone" class="form-label">Admin
                                                        Phone Number</label>
                                                    <input type="number" class="form-control" id="modaladminPhone"
                                                        placeholder="admin number" name="phone" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="modaladminZoneName" class="form-label">Select
                                                        Zone</label>
                                                    <select name="zone" id="modaladminZoneName" class="form-select"
                                                        required>
                                                        <option value="">-- Choose --</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `zone`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
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
                                                    <label for="modaladminEmail" class="form-label">Admin
                                                        Email</label>
                                                    <input type="email" class="form-control" id="modaladminEmail"
                                                        placeholder="admin email" name="email" required="">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="updateadmin">Update
                                                Admin</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['updateadmin'])) {

                                        // Handle image upload
                                        $filename = $_FILES["image"]["name"];
                                        $tempname = $_FILES["image"]["tmp_name"];
                                        $folder = "category/" . mysqli_real_escape_string($conn, $filename);

                                        // Sanitize input
                                        $id = $_POST['uid'];
                                        $aname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aname']));
                                        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                        $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                                        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));

                                        $query2 = "UPDATE `admin` SET 
                                        `name`='$aname',
                                        `phone`='$phone',
                                        `zone`='$zone',
                                        `email`='$email'
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
                text: 'Admin Profile Updated.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = 'admin.php'; 
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
                                    let adminId = this.getAttribute("data-id");
                                    let adminName = this.getAttribute("data-name");
                                    let adminPhone = this.getAttribute("data-phone");
                                    let adminEmail = this.getAttribute("data-email");





                                    // Populate input fields
                                    document.getElementById("modaladminId").value = adminId;
                                    document.getElementById("modaladminName").value = adminName;
                                    document.getElementById("modaladminPhone").value = adminPhone;
                                    document.getElementById("modaladminEmail").value = adminEmail;
                                });
                            });
                        });
                    </script>








                    <!--  Modal content for reset admin password -->
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
                                                Admin</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['resetPassword'])) {



                                        // Sanitize input
                                        $id = $_POST['nid'];
                                        $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['npassword']));

                                        // Hash the password
                                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


                                        $query2 = "UPDATE `admin` SET 
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
                window.location.href = 'admin.php'; 
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