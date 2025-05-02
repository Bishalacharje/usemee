<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Dashboard | Admin | Usemee</title>
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
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Pending Order</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Pending'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>

                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-secondary rounded-3">
                                                <i class="ri-radio-button-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Confirmed Order</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Confirmed'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-warning rounded-3">
                                                <i class="ri-shopping-cart-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Order Packed</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Packed'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-bag-3-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Order Out for Delivery</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Out for Delivery'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-warning rounded-3">
                                                <i class="ri-e-bike-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->


                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Order Delivered</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Delivered'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                <i class="ri-check-double-fill font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->


                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Order Cancelled</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Cancelled'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-danger rounded-3">
                                                <i class="ri-close-circle-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Seller</p>
                                            <?php
                                            $query = "SELECT * FROM `seller` WHERE `zone` = '$admin_zone'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-dark rounded-3">
                                                <i class="ri-store-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Delivery Boy</p>


                                            <?php
                                            $query = "SELECT * FROM `delivery` WHERE `zone` = '$admin_zone'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);

                                            ?>
                                            <h4 class="mb-2"><?php echo $total; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-info rounded-3">
                                                <i class="ri-user-3-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->



                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total COD Today</p>


                                            <?php
                                            $today = date('Y-m-d'); // Get today's date in YYYY-MM-DD format
                                            
                                            $query = "SELECT SUM(total_price) as total_cod FROM `orders` WHERE `zone` = '$admin_zone' AND `status`= 'Delivered' AND DATE(`delivered_date`) = '$today'";
                                            $data = mysqli_query($conn, $query);
                                            $total_cod = 0;
                                            if ($row = mysqli_fetch_assoc($data)) {
                                                $total_cod = $row['total_cod'] ?? 0; // Use the sum from the query
                                            }
                                            ?>
                                            <h4 class="mb-2"><?php echo $total_cod; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-dark rounded-3">
                                                <i class="fas fa-rupee-sign font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Today Delivery Wallet</p>

                                            <?php
                                            // Get selected filters
                                            $selected_date = isset($_GET['selected_date']) && !empty($_GET['selected_date']) ? $_GET['selected_date'] : date('Y-m-d');
                                            $delivery = isset($_GET['delivery']) ? $_GET['delivery'] : '';

                                            // Query for count of Delivered Orders
                                            $query_order_count = "SELECT COUNT(*) as delivered_orders FROM `orders` WHERE `zone` = '$admin_zone' AND `status`= 'Delivered'";

                                            if (!empty($selected_date)) {
                                                $query_order_count .= " AND DATE(`delivered_date`) = '$selected_date'";
                                            }

                                            if (!empty($delivery)) {
                                                $query_order_count .= " AND `delivery` = '$delivery'";
                                            }

                                            $result_order_count = mysqli_query($conn, $query_order_count);
                                            $row_order_count = mysqli_fetch_assoc($result_order_count);
                                            $delivered_orders = $row_order_count['delivered_orders'] ?? 0;

                                            // Calculate Wallet Amount
                                            $wallet_amount = $delivered_orders * 20;
                                            ?>
                                            <h4 class="mb-2"><?php echo $wallet_amount; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-wallet-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->







                    </div><!-- end row -->

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