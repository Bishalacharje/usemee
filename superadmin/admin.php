<?php

include("../connection.php");
session_start();

$old_aname = '';
$old_phone = '';
$old_zone = '';
$old_email = '';
$errors = [];

include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Admin | Super Admin | Usemee</title>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
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
                                                                    class="btn btn-warning waves-effect waves-light btn-sm viewCategoryBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-name="<?php echo $result['name']; ?>"
                                                                    data-phone="<?php echo $result['phone']; ?>"
                                                                    data-zone="<?php echo $result['zone']; ?>"
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
                   <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myLargeModalLabel">Add Admin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" enctype="multipart/form-data" class="needs-validation" id="addAdminForm" novalidate>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="validationCustom01" class="form-label">Admin name</label>
                                                <input type="text" class="form-control" id="validationCustom01" placeholder="admin name" name="aname" value="<?= $old_aname ?>" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please enter admin name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="validationCustom02" class="form-label">Admin Phone Number</label>
                                                <input type="tel" class="form-control" id="validationCustom02" placeholder="admin number" name="phone" value="<?= $old_phone ?>" pattern="\d{10}" title="Enter exactly 10 digits" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Select Zone</label>
                                                <select name="zone" id="validationCustom03" class="form-select" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php
                                                    $queryc = "SELECT * FROM `zone`";
                                                    $datac = mysqli_query($conn, $queryc);
                                                    while ($resultc = mysqli_fetch_assoc($datac)) {
                                                        $selected = ($old_zone == $resultc['id']) ? 'selected' : '';
                                                        echo "<option value='{$resultc['id']}' $selected>{$resultc['city']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please select a zone.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="validationCustom04" class="form-label">Admin Email</label>
                                                <input type="email" class="form-control" id="validationCustom04" placeholder="admin email" name="email" value="<?= $old_email ?>" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please enter a valid email.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="validationCustom05" class="form-label">Strong Password
                                                    <span id="inline-rules" style="font-size:12px;color:green;margin-left:10px;">
                                                        [ Min 8 chars, A-Z, a-z, 0-9, @#$% ]
                                                    </span>
                                                </label>
                                                <input type="password" class="form-control" id="validationCustom05" placeholder="admin password" name="password" required>
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please enter a strong password.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-dark" type="submit" name="submit">Add Admin</button>
                                    </div>
                                </form>

                                <!-- Show server-side validation errors if any -->
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            <?php foreach ($errors as $e): ?>
                                                <li><?= $e ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php
                                if (isset($_POST['submit'])) {
                                    // Store form inputs for redisplay
                                    $old_aname = htmlspecialchars($_POST['aname']);
                                    $old_phone = htmlspecialchars($_POST['phone']);
                                    $old_zone = htmlspecialchars($_POST['zone']);
                                    $old_email = htmlspecialchars($_POST['email']);
                                    $password = $_POST['password'];

                                    // Sanitize input data
                                    $aname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aname']));
                                    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                    $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                                    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                                    $password = mysqli_real_escape_string($conn, $_POST['password']);

                                    // Password validation (server-side fallback)
                                    if (!preg_match('@[A-Z]@', $password)) {
                                        $errors[] = "Password must include at least one uppercase letter.";
                                    }
                                    if (!preg_match('@[a-z]@', $password)) {
                                        $errors[] = "Password must include at least one lowercase letter.";
                                    }
                                    if (!preg_match('@[0-9]@', $password)) {
                                        $errors[] = "Password must include at least one number.";
                                    }
                                    if (!preg_match('@[^\w]@', $password)) {
                                        $errors[] = "Password must include at least one special character.";
                                    }
                                    if (strlen($password) < 8) {
                                        $errors[] = "Password must be at least 8 characters long.";
                                    }

                                    // If no errors, proceed with insertion
                                    if (empty($errors)) {
                                        // Hash the password
                                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                                        // Insert data into the database
                                        $query2 = "INSERT INTO `admin`(`name`, `phone`, `zone`, `email`, `password`) VALUES ('$aname','$phone','$zone','$email','$hashedPassword')";
                                        $data2 = mysqli_query($conn, $query2);

                                        if ($data2) {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Success',
                                                    text: 'Admin Created',
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                }).then(() => {
                                                    window.location.href = 'admin.php';
                                                });
                                            </script>";
                                        } else {
                                            $errorMessage = mysqli_error($conn);
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'Failed to create admin. Error: $errorMessage',
                                                    confirmButtonText: 'OK'
                                                });
                                            </script>";
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal -->


                    <!--  Modal content for view category -->
                    <div class="modal fade bs-example-modal-lg-view" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewAdminName"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img id="viewAdminPhoto" src="" alt="" class="img-fluid rounded"
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
                                    let adminName = this.getAttribute("data-name");
                                    let phone = this.getAttribute("data-phone");
                                    let email = this.getAttribute("data-email");
                                    let aadhaarNo = this.getAttribute("data-aadhaarno");


                                    let deliveryPhoto = this.getAttribute("data-photo");
                                    let aadhaarPhoto = this.getAttribute("data-aadhaarphoto");

                                    // Set modal content
                                    document.getElementById("viewAdminName").textContent = adminName;
                                    document.getElementById("viewPhone").textContent = phone;
                                    document.getElementById("viewEmail").textContent = email;
                                    document.getElementById("viewAadhaarNo").textContent = aadhaarNo;

                                    // Set image source
                                    document.getElementById("viewAdminPhoto").src = deliveryPhoto;
                                    document.getElementById("viewAadhaarPhoto").src = aadhaarPhoto;
                                });
                            });
                        });
                    </script>

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
                                                        <option value="">---</option>
                                                        <?php
                                                        $queryc = "SELECT * FROM `zone`";
                                                        $datac = mysqli_query($conn, $queryc);
                                                        while ($resultc = mysqli_fetch_assoc($datac)) {
                                                            echo "<option value='{$resultc['id']}'>{$resultc['city']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="valid-feedback">Looks good!</div>
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
                                                    <input type="file" class="form-control" id="" name="aadhaar_img">
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

                                        $id = $_POST['uid'];

                                        // Sanitize input
                                    
                                        $aname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aname']));
                                        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
                                        $zone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zone']));
                                        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
                                        $aadhaar_no = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['aadhaar_no']));



                                        // File paths
                                        $photoPath = "";
                                        $aadhaarPath = "";

                                        // Photo upload
                                        if (!empty($_FILES["photo"]["name"])) {
                                            $filename = mysqli_real_escape_string($conn, $_FILES["photo"]["name"]);
                                            $tempname = $_FILES["photo"]["tmp_name"];
                                            $photoPath = "adminFiles/" . $filename;
                                            move_uploaded_file($tempname, $photoPath);
                                        }

                                        // Aadhaar upload
                                        if (!empty($_FILES["aadhaar_img"]["name"])) {
                                            $filename2 = mysqli_real_escape_string($conn, $_FILES["aadhaar_img"]["name"]);
                                            $tempname2 = $_FILES["aadhaar_img"]["tmp_name"];
                                            $aadhaarPath = "adminFiles/" . $filename2;
                                            move_uploaded_file($tempname2, $aadhaarPath);
                                        }

                                        // Build the update query
                                        $updateFields = "`name`='$aname', `phone`='$phone',`zone`='$zone', `email`='$email', `aadhaar_no`='$aadhaar_no'";
                                        if (!empty($photoPath)) {
                                            $updateFields .= ", `photo`='$photoPath'";
                                        }
                                        if (!empty($aadhaarPath)) {
                                            $updateFields .= ", `aadhaar_photo`='$aadhaarPath'";
                                        }

                                        $query2 = "UPDATE `admin` SET $updateFields WHERE `id`='$id'";
                                        $data2 = mysqli_query($conn, $query2);

                                        // Start output buffering
                                        ob_start();

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
                                    let adminZone = this.getAttribute("data-zone");
                                    let adminEmail = this.getAttribute("data-email");
                                    let aadhaarNo = this.getAttribute("data-aadhaarno");

                                    // Populate input fields
                                    document.getElementById("modaladminId").value = adminId;
                                    document.getElementById("modaladminName").value = adminName;
                                    document.getElementById("modaladminPhone").value = adminPhone;
                                    document.getElementById("modaladminZoneName").value = adminZone;
                                    document.getElementById("modaladminEmail").value = adminEmail;
                                    document.getElementById("modalaadhaarNo").value = aadhaarNo;
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
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("validationCustom05");
            const phoneInput = document.getElementById("validationCustom02");
            const ruleSpan = document.getElementById("inline-rules");
            const form = document.getElementById("addAdminForm");

            // Real-time password strength feedback
            passwordInput.addEventListener("input", () => {
                const v = passwordInput.value;
                const r = [];
                if (!/.{8,}/.test(v)) r.push("8+ chars");
                if (!/[A-Z]/.test(v)) r.push("A");
                if (!/[a-z]/.test(v)) r.push("z");
                if (!/[0-9]/.test(v)) r.push("0-9");
                if (!/[!@#$%^&*(),.?\":{}|<>]/.test(v)) r.push("@#$%");

                if (r.length === 0) {
                    ruleSpan.textContent = "[ ✅ strong ]";
                    ruleSpan.style.color = "green";
                    passwordInput.classList.remove("is-invalid");
                    passwordInput.classList.add("is-valid");
                } else {
                    ruleSpan.textContent = `{ ${r.join(", ")} }`;
                    ruleSpan.style.color = "red";
                    passwordInput.classList.remove("is-valid");
                    passwordInput.classList.add("is-invalid");
                }
            });

            // Clear validation classes when input is empty
            passwordInput.addEventListener("input", () => {
                if (!passwordInput.value) {
                    passwordInput.classList.remove("is-valid", "is-invalid");
                    ruleSpan.textContent = "[ Min 8 chars, A-Z, a-z, 0-9, @#$% ]";
                    ruleSpan.style.color = "green";
                }
            });

            // Prevent form submission if password or phone is invalid
            form.addEventListener("submit", function (e) {
                const v = passwordInput.value;
                const phone = phoneInput.value;
                const errors = [];

                // Password validation
                if (!/.{8,}/.test(v)) errors.push("Password must be at least 8 characters long.");
                if (!/[A-Z]/.test(v)) errors.push("Password must include at least one uppercase letter.");
                if (!/[a-z]/.test(v)) errors.push("Password must include at least one lowercase letter.");
                if (!/[0-9]/.test(v)) errors.push("Password must include at least one number.");
                if (!/[!@#$%^&*(),.?\":{}|<>]/.test(v)) errors.push("Password must include at least one special character.");

                // Phone number validation
                if (!/^\d{10}$/.test(phone)) errors.push("Phone number must be exactly 10 digits.");

                if (errors.length > 0) {
                    e.preventDefault(); // Stop form submission
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        html: errors.join('<br>'),
                        confirmButtonText: 'OK'
                    });
                }
            });

        // Real-time phone number validation
        phoneInput.addEventListener("input", () => {
            const phone = phoneInput.value;
            if (/^\d{10}$/.test(phone)) {
                phoneInput.classList.remove("is-invalid");
                phoneInput.classList.add("is-valid");
            } else {
                phoneInput.classList.remove("is-valid");
                phoneInput.classList.add("is-invalid");
            }
            if (!phone) {
                phoneInput.classList.remove("is-valid", "is-invalid");
            }
        });
    });
    </script>
</body>

</html>