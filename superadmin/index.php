<?php
include("../connection.php");
session_start();
include("checked-login.php");

// Get date filters and zone
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$selected_zone = isset($_GET['zone']) ? $_GET['zone'] : '';

// If no dates are selected, default to current month
if (empty($start_date) && empty($end_date)) {
    $start_date = date('Y-m-01'); // First day of current month
    $end_date = date('Y-m-d'); // Today
}

// Cache zones query result
$zones_query = "SELECT DISTINCT o.zone, z.city FROM orders o 
                LEFT JOIN zone z ON o.zone = z.id 
                ORDER BY z.city";
$zones_result = mysqli_query($conn, $zones_query);
$zones_data = [];
while ($zone_row = mysqli_fetch_assoc($zones_result)) {
    $zones_data[] = $zone_row;
}
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Dashboard | Super Admin | Usemee</title>
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .filter-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        

        
    </style>
</head>

<body data-topbar="dark">

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
                        <div class="col-12">
                            <div class="filter-container">

                                 <form method="GET" action="" class="row" id="filterForm">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Select Zone</label>
                                            <select class="form-select" name="zone">
                                                <option value="">All Zones</option>
                                                <?php foreach($zones_data as $zone_row): ?>
                                                    <option value="<?php echo htmlspecialchars($zone_row['zone']); ?>" 
                                                        <?php echo ($selected_zone == $zone_row['zone']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($zone_row['city'] ?? 'Zone ' . $zone_row['zone']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label" style="opacity: 0;">Action</label>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ri-search-line me-1"></i> Apply Filter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label" style="opacity: 0;">Reset</label>
                                            <a href="index.php" class="btn btn-secondary w-100">
                                                <i class="ri-refresh-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Order Status Statistics -->
                   
                        <div class="row">
                            <!-- Pending Orders -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-pending"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Pending Orders</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Pending'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-warning rounded-3">
                                                    <i class="ri-time-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmed Orders -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-confirmed"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Confirmed Orders</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Confirmed'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-info rounded-3">
                                                    <i class="ri-shopping-cart-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Packed Orders -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-packed"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Orders Packed</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Packed'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-shopping-bag-3-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Out for Delivery -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-delivery"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Out for Delivery</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Out for Delivery'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-info rounded-3">
                                                    <i class="ri-truck-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivered Orders -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-delivered"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Orders Delivered</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Delivered'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(delivered_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
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

                            <!-- Cancelled Orders -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card position-relative">
                                    <div class="status-indicator status-cancelled"></div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Orders Cancelled</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= 'Cancelled'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-danger rounded-3">
                                                    <i class="ri-close-circle-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row">

                         <div class="col-xl-4 col-md-4">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">COD</p>
                                           
                                            <h4 class="mb-2">₹56555</h4>
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
                        <div class="col-xl-4 col-md-4">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Admin Wallet</p>

                                          
                                            <h4 class="mb-2">₹680</h4>
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
                        <div class="col-xl-4 col-md-4">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Profit</p>

                                          
                                            <h4 class="mb-2">₹3680</h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                <i class="fas fa-rupee-sign font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                    </div>

                    <!-- Business Statistics -->
                    
                        <div class="row">
                            <!-- Total Sellers -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Total Sellers</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `seller`";
                                                if (!empty($selected_zone)) {
                                                    $query .= " WHERE `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-store-2-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Delivery Boys -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Total Delivery Partners</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `delivery`";
                                                if (!empty($selected_zone)) {
                                                    $query .= " WHERE `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-info rounded-3">
                                                    <i class="ri-user-3-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Users/Customers -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Total Customers</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `user`";
                                                if (!empty($selected_zone)) {
                                                    $query .= " WHERE `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-success rounded-3">
                                                    <i class="ri-user-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Admins -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Total Admins</p>
                                                <?php
                                                $query = "SELECT COUNT(*) as total FROM `admin`";
                                                if (!empty($selected_zone)) {
                                                    $query .= " WHERE `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-warning rounded-3">
                                                    <i class="ri-admin-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Zones -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Active Service Zones</p>
                                                <?php
                                                $query = "SELECT COUNT(DISTINCT zone) as total FROM `orders`";
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $total = $row['total'];
                                                ?>
                                                <h4 class="mb-2 text-truncate"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-warning rounded-3">
                                                    <i class="ri-map-pin-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   

                  

                </div>
                <!-- container-fluid -->
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