<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Profile | Delivery | Usemee</title>
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
                                <h4 class="mb-sm-0">Profile</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Profile</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">

                            <div class="card">
                                <div class="card-body pb-0">
                                    <center class="p-4">
                                        <?php
                                        if (empty($delivery_photo)) {
                                            // Show default image if no photo is provided
                                            ?>
                                            <img class="rounded-circle avatar-xl mb-4" alt=""
                                            src="assets/images/users/user.png" data-holder-rendered="true">
                                            <?php
                                        } else {
                                            // Show the actual delivery person photo
                                            ?>
                                                <img class="rounded-circle avatar-xl mb-4" alt=""
                                                src="../admin/<?php echo htmlspecialchars($delivery_photo); ?>" data-holder-rendered="true">
                                            <?php
                                        }
                                        ?>
                                       

                                        <h3><?php echo $delivery_name; ?></h3>
                                        <p class="my-2"><?php echo $delivery_email; ?></p>
                                        <p class="m-0 mb-3"><?php echo $delivery_phone; ?></p>
                                        <a href="logout.php" class="btn btn-sm btn-danger waves-effect waves-light mb-4">Logout</a>
                                    </center>

                                </div>
                            </div><!-- end card -->
                        </div>
                        <!-- end col -->

                    </div>
                    <!-- end row -->

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