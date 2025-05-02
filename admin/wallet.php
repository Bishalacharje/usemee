<?php
include("../connection.php");
session_start();
include("checked-login.php");
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Wallet | Admin | Usemee</title>
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">

        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Delivery Boy Wallet</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Delivery Boy Wallet</li>
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

                                    <!-- Delivery Boy Filter -->
                                    <div class="col-md-5">
                                        <select class="form-select" name="delivery">
                                            <option value="">All Delivery Boys</option>
                                            <?php
                                            $query = "SELECT * FROM `delivery` WHERE `zone` = '$admin_zone'";
                                            $data = mysqli_query($conn, $query);
                                            while ($result = mysqli_fetch_assoc($data)) {
                                                $selected = (isset($_GET['delivery']) && $_GET['delivery'] == $result['id']) ? "selected" : "";
                                                echo "<option value='{$result['id']}' $selected>{$result['name']}</option>";
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

                    <?php
                    // Get selected filters
                    $selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
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
                    $delivered_orders = $row_order_count['delivered_orders'] ? $row_order_count['delivered_orders'] : 0;

                    // Calculate Wallet Amount
                    $wallet_amount = $delivered_orders * 20;
                    ?>

                    <div class="row">
                        <!-- Orders Delivered Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Orders Delivered</p>
                                            <h4 class="mb-2"><?php echo $delivered_orders; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                <i class="ri-check-double-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wallet Amount Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Wallet Amount</p>
                                            <h4 class="mb-2">₹<?php echo $wallet_amount; ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-warning rounded-3">
                                                <i class="ri-wallet-3-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->

                </div>
            </div>

            <?php include("./components/footer.php"); ?>

        </div>
    </div>

    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>
</body>

</html>