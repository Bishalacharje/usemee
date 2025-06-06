<?php

include("../connection.php");
session_start();
include("checked-login.php");

// Get date filters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// If no dates are selected, default to current month
// if (empty($start_date) && empty($end_date)) {
//     $start_date = date('Y-m-01'); // First day of current month
//     $end_date = date('Y-m-d'); // Today
// }
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Dashboard | Admin | Usemee</title>
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        .filter-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .filter-container .btn-primary {
            margin-top: 22px;
        }

        .filter-title {
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
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

                    <!-- Date Filter Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="filter-container">
                                <form method="GET" action="" class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="start_date"
                                                value="<?php echo htmlspecialchars($start_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="end_date"
                                                value="<?php echo htmlspecialchars($end_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="index.php" class="btn btn-secondary w-100 mt-3 mt-md-4">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Pending Order</p>
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Pending'";

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(delivered_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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

                                            // Add date range filter if set
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

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
                                            <p class="text-truncate font-size-14 mb-2"> COD</p>
                                            <?php
                                            $total_cod = 0;
                                            $query = "SELECT SUM(total_price) as total_cod FROM `orders` WHERE `zone` = '$admin_zone' AND `status`= 'Delivered'";

                                            // Apply date filter if both dates are provided
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(delivered_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

                                            $data = mysqli_query($conn, $query);
                                            if ($row = mysqli_fetch_assoc($data)) {
                                                $total_cod = $row['total_cod'] ?? 0;
                                            }
                                            ?>
                                            <h4 class="mb-2">₹<?php echo $total_cod; ?></h4>

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
                                            <p class="text-truncate font-size-14 mb-2">Delivery Wallet</p>

                                            <?php
                                            $delivered_orders = 0;
                                            $query_order_count = "SELECT COUNT(*) as delivered_orders FROM `orders` WHERE `zone` = '$admin_zone' AND `status`= 'Delivered'";

                                            // Apply date filter if both dates are provided
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query_order_count .= " AND DATE(delivered_date) BETWEEN '$start_date' AND '$end_date'";
                                            }

                                            // Optional: delivery boy filter (unchanged)
                                            $delivery = isset($_GET['delivery']) ? $_GET['delivery'] : '';
                                            if (!empty($delivery)) {
                                                $query_order_count .= " AND `delivery` = '$delivery'";
                                            }

                                            $result_order_count = mysqli_query($conn, $query_order_count);
                                            $row_order_count = mysqli_fetch_assoc($result_order_count);
                                            $delivered_orders = $row_order_count['delivered_orders'] ?? 0;

                                            // Calculate Wallet Amount (e.g., ₹20 per delivery)
                                            $wallet_amount = $delivered_orders * 20;
                                            ?>
                                            <h4 class="mb-2">₹<?php echo $wallet_amount; ?></h4>

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
    <!-- Bootstrap Datepicker JS -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>