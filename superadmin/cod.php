<?php

include("../connection.php");
session_start();
include("checked-login.php");

?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Orders | Usemee</title>
</head>

<body data-topbar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include("./components/header.php"); ?>

        <!-- Left Sidebar Start -->
        <?php include("./components/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- Start right Content here -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- Start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">COD History</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active">COD History</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Filters Form -->
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <form method="GET">
                                <div class="row">
                                    <!-- Date Filter -->
                                    <div class="col-md-5">
                                        <input class="form-control" type="date" name="selected_date"
                                            value="<?php echo isset($_GET['selected_date']) ? $_GET['selected_date'] : ''; ?>">
                                    </div>

                                    <!-- Zone Filter -->
                                    <div class="col-md-5">
                                        <select class="form-select" name="zone">
                                            <option value="">All Zone</option>
                                            <?php
                                            $query = "SELECT * FROM `zone`";
                                            $data = mysqli_query($conn, $query);
                                            while ($result = mysqli_fetch_assoc($data)) {
                                                $selected = (isset($_GET['zone']) && $_GET['zone'] == $result['id']) ? "selected" : "";
                                                echo "<option value='{$result['id']}' $selected>{$result['city']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-2 mt-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="cod.php" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <?php
                            // Get selected filters
                            $selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
                            $zone = isset($_GET['zone']) ? $_GET['zone'] : ''; // Fixing the zone filter
                            
                            // Query for Total COD
                            $query_total_cod = "SELECT SUM(total_price) as total_cod FROM `orders` WHERE `status`= 'Delivered'";

                            if (!empty($selected_date)) {
                                $query_total_cod .= " AND DATE(`delivered_date`) = '$selected_date'";
                            }

                            if (!empty($zone)) {
                                $query_total_cod .= " AND `zone` = '$zone'";
                            }

                            $result_total_cod = mysqli_query($conn, $query_total_cod);
                            $row_total_cod = mysqli_fetch_assoc($result_total_cod);
                            $total_cod_amount = $row_total_cod['total_cod'] ? $row_total_cod['total_cod'] : 0;
                            ?>

                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total COD</p>
                                            <h4 class="mb-2"><?php echo $total_cod_amount; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-cart-2-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div>

                    <!-- Order List -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Total COD</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-con">
                                        <table id="alternative-page-datatable"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                                            <?php
                                            // Query to fetch orders
                                            $query = "SELECT * FROM `orders` WHERE `status`= 'Delivered'";

                                            if (!empty($selected_date)) {
                                                $query .= " AND DATE(`delivered_date`) = '$selected_date'";
                                            }

                                            if (!empty($zone)) {
                                                $query .= " AND `zone` = '$zone'";
                                            }

                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);
                                            ?>

                                            <?php if ($total != 0) { ?>
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php while ($result = mysqli_fetch_assoc($data)) { ?>
                                                        <tr>
                                                            <td>order2025<?php echo $result['id']; ?></td>
                                                            <td>
                                                                <h5><?php echo $result['total_price']; ?></h5>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan='3' class='text-center'>No COD found</td>
                                                </tr>
                                            <?php } ?>
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