<?php

include("../connection.php");
session_start();
include("checked-login.php");


?>




<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Today COD | Delivery | Usemee</title>
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
                                <h4 class="mb-sm-0">Today COD</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Today COD</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <?php
                            $query_today_cod = "SELECT SUM(total_price) as today_cod FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Delivered' AND DATE(`delivered_date`) = CURDATE()";
                            $result_today_cod = mysqli_query($conn, $query_today_cod);
                            $row_today_cod = mysqli_fetch_assoc($result_today_cod);
                            $today_cod_amount = $row_today_cod['today_cod'] ? $row_today_cod['today_cod'] : 0;
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Today Total COD</p>
                                            <h4 class="mb-2"><?php echo $today_cod_amount; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-dark rounded-3">
                                                <i class="ri-wallet-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div>

                    <!-- end page title -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body p-0">

                                    <div class="table-con myTableCon">
                                        <table id="" class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Delivered' AND DATE(`delivered_date`) = CURDATE()";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th> Order ID</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                </thead>


                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) { ?>



                                                        <tr>
                                                            <td>order2025<?php echo $result['id']; ?></td>
                                                            <td>
                                                                <h5><?php echo $result['total_price']; ?></h5>
                                                            </td>

                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Today COD found";
                                            }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
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