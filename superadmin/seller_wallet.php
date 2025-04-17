<?php
include("../connection.php");
session_start();
include("checked-login.php");
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Seller Wallet | Usemee</title>
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Seller Wallet</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Seller Wallet</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-4">
                        <!-- Zone Filter -->
                        <div class="col-md-3">
                            <form method="GET" id="filterForm">
                                <label>Zone</label>
                                <select class="form-select" name="zone"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">All Zones</option>
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

                        <!-- Start Date -->
                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date"
                                value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>"
                                onchange="document.getElementById('filterForm').submit();">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="end_date"
                                value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>"
                                onchange="document.getElementById('filterForm').submit();">
                        </div>
                        </form>
                    </div>

                    <!-- Total Price Card -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Price</p>
                                            <h4 class="mb-2">
                                                <?php
                                                $total_sql = "SELECT SUM(od.price * od.quantity) AS TotalPrice
                                                              FROM orders o
                                                              JOIN order_details od ON o.id = od.order_id
                                                              WHERE o.status = 'Delivered'";

                                                if (isset($_GET['zone']) && $_GET['zone'] !== "") {
                                                    $zone = $_GET['zone'];
                                                    $total_sql .= " AND o.zone = '$zone'";
                                                }

                                                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                    $start_date = $_GET['start_date'];
                                                    $end_date = $_GET['end_date'];
                                                    $total_sql .= " AND DATE(o.delivered_date) BETWEEN '$start_date' AND '$end_date'";
                                                }

                                                $total_result = mysqli_query($conn, $total_sql);
                                                $total_row = mysqli_fetch_assoc($total_result);
                                                echo number_format($total_row['TotalPrice'], 2);
                                                ?>
                                            </h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-cart-2-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- End card-body -->
                            </div><!-- End card -->
                        </div><!-- End col -->
                    </div>

                    <!-- Sellers Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Sellers</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Seller Name</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT s.name AS SellerName, s.category AS Category, SUM(od.price * od.quantity) AS TotalPrice
                                                        FROM orders o
                                                        JOIN order_details od ON o.id = od.order_id
                                                        JOIN seller s ON od.seller = s.id
                                                        WHERE o.status = 'Delivered'";

                                                if (isset($_GET['zone']) && $_GET['zone'] !== "") {
                                                    $zone = $_GET['zone'];
                                                    $sql .= " AND o.zone = '$zone'";
                                                }

                                                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                    $start_date = $_GET['start_date'];
                                                    $end_date = $_GET['end_date'];
                                                    $sql .= " AND DATE(o.delivered_date) BETWEEN '$start_date' AND '$end_date'";
                                                }

                                                $sql .= " GROUP BY s.id, s.category";
                                                $result = $conn->query($sql);

                                                while ($row = $result->fetch_assoc()) {

                                                    echo "<tr>
                                                            <td>" . htmlspecialchars($row['SellerName']) . "</td>
                                                            <td>" . number_format($row['TotalPrice'], 2) . "</td>
                                                          </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->

            <?php include("./components/footer.php"); ?>
        </div> <!-- main-content -->
    </div> <!-- layout-wrapper -->

    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>
</body>

</html>