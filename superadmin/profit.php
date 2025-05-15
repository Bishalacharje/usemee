<?php
include("../connection.php");
session_start();
include("checked-login.php");
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Profit | Super Admin | Usemee</title>
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
                                <h4 class="mb-sm-0">Profit</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Profit</li>
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
                                    <!-- Start Date -->
                                    <div class="col-md-3">
                                        <label>Start Date</label>
                                        <input class="form-control" type="date" name="start_date"
                                            value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-md-3">
                                        <label>End Date</label>
                                        <input class="form-control" type="date" name="end_date"
                                            value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                                    </div>

                                    <!-- Zone -->
                                    <div class="col-md-4">
                                        <label>Zone</label>
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

                                    <!-- Buttons -->
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                                        <a href="cod.php" class="btn btn-danger mt-2">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
                    // Get selected filters
                    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                    $zone = isset($_GET['zone']) ? $_GET['zone'] : '';

                    // Build query
                    $query_order_count = "SELECT COUNT(*) as delivered_orders FROM `orders` WHERE `status`= 'Delivered'";

                    if (!empty($start_date) && !empty($end_date)) {
                        $query_order_count .= " AND DATE(`delivered_date`) BETWEEN '$start_date' AND '$end_date'";
                    } elseif (!empty($start_date)) {
                        $query_order_count .= " AND DATE(`delivered_date`) >= '$start_date'";
                    } elseif (!empty($end_date)) {
                        $query_order_count .= " AND DATE(`delivered_date`) <= '$end_date'";
                    }

                    if (!empty($zone)) {
                        $query_order_count .= " AND `zone` = '$zone'";
                    }

                    // Run query
                    $result_order_count = mysqli_query($conn, $query_order_count);
                    $row_order_count = mysqli_fetch_assoc($result_order_count);
                    $delivered_orders = $row_order_count['delivered_orders'] ?? 0;

                    // Wallet = Delivered Orders * 20
                    $wallet_amount = $delivered_orders * 29;
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

    <?php include("./components/footscript.php"); ?>
</body>

</html>