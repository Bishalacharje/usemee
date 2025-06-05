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
        :root {
            --primary-color: #556ee6;
            --secondary-color: #74788d;
            --success-color: #34c38f;
            --info-color: #50a5f1;
            --warning-color: #f1b44c;
            --danger-color: #f46a6a;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --card-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
            --border-radius: 0.75rem;
        }

        /* FIX: Enable scrolling and remove gradient */
        html, body, #layout-wrapper, .main-content {
            overflow: auto !important;
            height: auto !important;
            background: #f8f9fa !important; /* Solid light background */
        }

        .main-content {
            min-height: 100vh;
            padding-top: 20px;
        }

        .container-fluid {
            background: transparent;
        }

        .page-title-box {
            background: #ffffff;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e3e6f0;
            box-shadow: var(--card-shadow);
        }

        .page-title-box h4 {
            color: #495057;
            font-weight: 600;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            margin: 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #495057;
        }

        .filter-container {
            background: #ffffff;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #e3e6f0;
        }

        .filter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-title::before {
            content: "🔍";
            font-size: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.25);
        }

        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            box-shadow: 0 4px 15px rgba(85, 110, 230, 0.3);
        }

        .btn-primary:hover {
            background: #4c63d6;
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .stats-section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
            border-radius: var(--border-radius);
            text-align: center;
            background: #f1f3fa;
            color: #495057;
            border: 1px solid #e3e6f0;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: #ffffff;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 1.5rem;
        }

        .avatar-title {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.25rem;
        }

        .avatar-title.bg-light {
            background: #f8f9fa !important;
        }

        .text-primary { color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-info { color: var(--info-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }

        .card h4 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .card p {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 0;
        }

        .chart-container {
            background: #ffffff;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #e3e6f0;
        }

        /* FIX: Remove performance-intensive effects */
        .page-title-box,
        .filter-container,
        .section-title,
        .card,
        .chart-container {
            backdrop-filter: none !important;
        }

        @media (max-width: 768px) {
            .filter-container {
                padding: 1.5rem;
            }
            
            .btn {
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .status-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: var(--warning-color);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.8);
        }

        .status-pending { background-color: var(--warning-color); }
        .status-confirmed { background-color: var(--info-color); }
        .status-packed { background-color: var(--primary-color); }
        .status-delivery { background-color: var(--info-color); }
        .status-delivered { background-color: var(--success-color); }
        .status-cancelled { background-color: var(--danger-color); }
        
        /* FIX: Collapse sidebar by default */
        .vertical-menu .has-submenu > a:after {
            transform: rotate(0deg) !important;
        }
        .vertical-menu .submenu {
            display: none !important;
        }
        
        /* FIX: Only expand dashboard menu */
        #menu-dashboard {
            display: block !important;
        }
        #menu-dashboard + .submenu {
            display: block !important;
        }
        
        /* Performance optimization */
        * {
            will-change: auto;
            backface-visibility: hidden;
            transform: translate3d(0,0,0);
        }
        
        /* Scrolling fix */
        .vertical-menu {
            height: calc(100vh - 70px) !important;
            overflow-y: auto !important;
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

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">🚀 Super Admin Dashboard</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Date and Zone Filter Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="filter-container">
                                <h5 class="filter-title">Filter Data by Date Range & Zone</h5>
                                <form method="GET" action="" class="row" id="filterForm">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">📅 Start Date</label>
                                            <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">📅 End Date</label>
                                            <input type="date" class="form-control" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">🌍 Select Zone</label>
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
                    <div class="stats-section">
                        <h5 class="section-title">📦 Order Management Statistics</h5>
                        <div class="row">
                            <!-- Pending Orders -->
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-warning"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-info"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-info rounded-3">
                                                    <i class="ri-check-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Packed Orders -->
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-primary"><?php echo number_format($total); ?></h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-package-line font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Out for Delivery -->
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-info"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-success"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                                                <h4 class="mb-2 text-danger"><?php echo number_format($total); ?></h4>
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
                    </div>

                    <!-- Business Statistics -->
                    <div class="stats-section">
                        <h5 class="section-title">👥 Business Analytics</h5>
                        <div class="row">
                            <!-- Total Sellers -->
                            <div class="col-xl-3 col-md-6 mb-4">
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
                                                <h4 class="mb-2 text-primary"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-3 col-md-6 mb-4">
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
                                                <h4 class="mb-2 text-info"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-3 col-md-6 mb-4">
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
                                                <h4 class="mb-2 text-success"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-3 col-md-6 mb-4">
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
                                                <h4 class="mb-2 text-warning"><?php echo number_format($total); ?></h4>
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
                            <div class="col-xl-3 col-md-6 mb-4">
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
                                                <h4 class="mb-2 text-warning"><?php echo number_format($total); ?></h4>
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

                    <!-- Chart Visualizations -->
                    <div class="stats-section">
                        <h5 class="section-title">📊 Order Trends</h5>
                        <div class="row">
                            <div class="col-xl-6 mb-4">
                                <div class="chart-container">
                                    <h6 class="text-center mb-4">Order Status Distribution</h6>
                                    <canvas id="orderStatusChart"></canvas>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-4">
                                <div class="chart-container">
                                    <h6 class="text-center mb-4">Orders Over Time</h6>
                                    <canvas id="ordersOverTimeChart"></canvas>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        // Debounce function to limit rapid executions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Initialize datepickers
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Form validation
            $('#filterForm').on('submit', function(e) {
                const startDate = $('input[name="start_date"]').val();
                const endDate = $('input[name="end_date"]').val();

                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    e.preventDefault();
                    alert('End date must be after start date.');
                }
            });

            // Debounced form submission
            const debouncedSubmit = debounce(function() {
                $('#filterForm').addClass('loading');
                $('#filterForm').submit();
            }, 500);

            $('#filterForm button[type="submit"]').on('click', function(e) {
                e.preventDefault();
                debouncedSubmit();
            });

            // Lazy load charts when in viewport
            const charts = [
                { id: 'orderStatusChart', data: null, type: 'pie' },
                { id: 'ordersOverTimeChart', data: null, type: 'line' }
            ];

            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            function loadCharts() {
                charts.forEach(chart => {
                    const canvas = document.getElementById(chart.id);
                    if (canvas && isElementInViewport(canvas) && !chart.data) {
                        if (chart.id === 'orderStatusChart') {
                            chart.data = new Chart(canvas, {
                                type: 'pie',
                                data: {
                                    labels: ['Pending', 'Confirmed', 'Packed', 'Out for Delivery', 'Delivered', 'Cancelled'],
                                    datasets: [{
                                        data: <?php
                                            $statuses = ['Pending', 'Confirmed', 'Packed', 'Out for Delivery', 'Delivered', 'Cancelled'];
                                            $status_counts = [];
                                            foreach ($statuses as $status) {
                                                $query = "SELECT COUNT(*) as total FROM `orders` WHERE `status`= '" . mysqli_real_escape_string($conn, $status) . "'";
                                                if (!empty($selected_zone)) {
                                                    $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                                }
                                                if (!empty($start_date) && !empty($end_date)) {
                                                    if ($status == 'Delivered') {
                                                        $query .= " AND DATE(delivered_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                    } else {
                                                        $query .= " AND DATE(order_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                                    }
                                                }
                                                $data = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($data);
                                                $status_counts[] = $row['total'];
                                            }
                                            echo json_encode($status_counts);
                                        ?>,
                                        backgroundColor: [
                                            'rgba(241, 180, 76, 0.8)',
                                            'rgba(80, 165, 241, 0.8)',
                                            'rgba(85, 110, 230, 0.8)',
                                            'rgba(80, 165, 241, 0.8)',
                                            'rgba(52, 195, 143, 0.8)',
                                            'rgba(244, 106, 106, 0.8)'
                                        ],
                                        borderColor: [
                                            'rgba(241, 180, 76, 1)',
                                            'rgba(80, 165, 241, 1)',
                                            'rgba(85, 110, 230, 1)',
                                            'rgba(80, 165, 241, 1)',
                                            'rgba(52, 195, 143, 1)',
                                            'rgba(244, 106, 106, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: { font: { size: 12 } }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.label || '';
                                                    let value = context.raw || 0;
                                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    let percentage = ((value / total) * 100).toFixed(1);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        } else if (chart.id === 'ordersOverTimeChart') {
                            chart.data = new Chart(canvas, {
                                type: 'line',
                                data: {
                                    labels: <?php
                                        $labels = [];
                                        $order_counts = [];
                                        $date = new DateTime($start_date);
                                        $end = new DateTime($end_date);
                                        $interval = new DateInterval('P1D');
                                        $period = new DatePeriod($date, $interval, $end->modify('+1 day'));
                                        foreach ($period as $dt) {
                                            $labels[] = $dt->format('Y-m-d');
                                            $query = "SELECT COUNT(*) as total FROM `orders` WHERE DATE(order_date) = '" . mysqli_real_escape_string($conn, $dt->format('Y-m-d')) . "'";
                                            if (!empty($selected_zone)) {
                                                $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                            }
                                            $data = mysqli_query($conn, $query);
                                            $row = mysqli_fetch_assoc($data);
                                            $order_counts[] = $row['total'];
                                        }
                                        echo json_encode($labels);
                                    ?>,
                                    datasets: [{
                                        label: 'Orders',
                                        data: <?php echo json_encode($order_counts); ?>,
                                        borderColor: 'rgba(85, 110, 230, 1)',
                                        backgroundColor: 'rgba(85, 110, 230, 0.2)',
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: { display: true, text: 'Number of Orders' }
                                        },
                                        x: {
                                            title: { display: true, text: 'Date' }
                                        }
                                    },
                                    plugins: {
                                        legend: { display: false }
                                    }
                                }
                            });
                        }
                    }
                });
            }

            // Initial chart load
            loadCharts();

            // Load charts on scroll
            $(window).on('scroll', debounce(loadCharts, 100));
            
            // FIX: Collapse all sidebar menus except dashboard
            setTimeout(function() {
                $('.vertical-menu .has-submenu').each(function() {
                    const $this = $(this);
                    const isDashboard = $this.find('a').attr('href').includes('dashboard');
                    
                    if (!isDashboard) {
                        $this.removeClass('mm-active');
                        $this.find('.submenu').removeClass('show');
                        $this.find('.submenu').addClass('collapse');
                        $this.find('.has-arrow').addClass('collapsed');
                    } else {
                        $this.addClass('mm-active');
                        $this.find('.submenu').addClass('show');
                        $this.find('.has-arrow').removeClass('collapsed');
                    }
                });
            }, 300);
            
            // FIX: Enable scrolling in sidebar
            $('.vertical-menu').css('overflow-y', 'auto');
        });
    </script>

    <?php include("./components/scripts.php"); ?>
</body>
</html>