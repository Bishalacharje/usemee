<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Dashboard | Usemee</title>
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



                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <a href="today_order.php" class="card">
                                <?php
                                $query = "SELECT * FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Packed' AND DATE(`order_packed_date`) = CURDATE()";
                                $data = mysqli_query($conn, $query);
                                $total = mysqli_num_rows($data);

                                ?>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2 text-primary">New Orders</p>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-cart-2-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </a><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <a href="today_order.php?status=Out for Delivery" class="card">
                                <?php
                                $query = "SELECT * FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Out for Delivery' AND DATE(`order_packed_date`) = CURDATE()";
                                $data = mysqli_query($conn, $query);
                                $total = mysqli_num_rows($data);

                                ?>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2 text-warning">Out for Delivery
                                                Order</p>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-warning rounded-3">
                                                <i class="ri-e-bike-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </a><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <?php
                            $query_today_cod = "SELECT SUM(total_price) as today_cod FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Delivered' AND DATE(`delivered_date`) = CURDATE()";
                            $result_today_cod = mysqli_query($conn, $query_today_cod);
                            $row_today_cod = mysqli_fetch_assoc($result_today_cod);
                            $today_cod_amount = $row_today_cod['today_cod'] ? $row_today_cod['today_cod'] : 0;
                            ?>
                            <a href="today_cod.php" class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2 text-dark">COD Today</p>
                                            <h4 class="mb-2"><?php echo $today_cod_amount; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-dark rounded-3">
                                                <i class="ri-wallet-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </a><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <a href="order.php" class="card">
                                <?php
                                $query = "SELECT * FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Delivered'";
                                $data = mysqli_query($conn, $query);
                                $total = mysqli_num_rows($data);

                                ?>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2 text-success">Order Delivered</p>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                <i class="ri-check-double-fill font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </a><!-- end card -->
                        </div><!-- end col -->

                    </div><!-- end row -->

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
                                        <p class="m-0 mb-4"><?php echo $delivery_phone; ?></p>
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