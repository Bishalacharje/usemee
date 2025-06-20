<?php
// Enable error reporting for debugging (comment out in production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include("../connection.php");
session_start();
include("checked-login.php");

// Get date filters and zone
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$selected_zone = isset($_GET['zone']) ? $_GET['zone'] : '';

// Initialize message variables
$success_message = "";
$error_message = "";
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Handle status update for contact_us
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_contact_status'])) {
    $contact_id = (int)$_POST['contact_id'];
    $new_status = $_POST['status'];
    
    // Validate status
    $valid_statuses = ['Pending', 'In Progress', 'Resolved'];
    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['error_message'] = "Invalid status selected.";
    } else {
        // Update status
        $query = "UPDATE `contact_us` SET `status` = ? WHERE `id` = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $contact_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Contact status updated successfully.";
            } else {
                $_SESSION['error_message'] = "Error updating contact status: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error_message'] = "Database error: " . mysqli_error($conn);
        }
        
        // Redirect to prevent resubmission
        header("Location: index.php?start_date=" . urlencode($start_date) . "&end_date=" . urlencode($end_date) . "&zone=" . urlencode($selected_zone));
        exit();
    }
}

// Cache zones query result
$zones_query = "SELECT id, city FROM zone ORDER BY city";
$zones_result = mysqli_query($conn, $zones_query);
$zones_data = [];
if ($zones_result) {
    while ($zone_row = mysqli_fetch_assoc($zones_result)) {
        $zones_data[] = $zone_row;
    }
}

// Fetch contact_us data with filters (limit to 100 for performance)
$contact_query = "SELECT id, name, email, phone_no, message, status, created_at FROM `contact_us` WHERE 1=1";
$params = [];
$types = "";
if (!empty($start_date) && !empty($end_date)) {
    $contact_query .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
}
$contact_query .= " ORDER BY created_at DESC LIMIT 100";
if ($stmt = mysqli_prepare($conn, $contact_query)) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $contact_result = mysqli_stmt_get_result($stmt);
    $contact_data = [];
    while ($contact_row = mysqli_fetch_assoc($contact_result)) {
        $contact_data[] = $contact_row;
    }
    mysqli_stmt_close($stmt);
} else {
    $error_message = "Error fetching contact messages: " . mysqli_error($conn);
    $contact_data = [];
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
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .alert-success {
            background-color: #4CAF50;
        }
        .alert-error {
            background-color: #f44336;
        }
        .close-btn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .close-btn:hover {
            opacity: 0.7;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .status-select {
            width: 150px;
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

                    <!-- Success/Error Messages -->
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" id="success-alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <span class="close-btn" onclick="this.parentElement.style.display='none';">×</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-error" id="error-alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <span class="close-btn" onclick="this.parentElement.style.display='none';">×</span>
                        </div>
                    <?php endif; ?>

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
                                            <select name="zone" id="zone" class="form-control">
                                                <option value="">All Zones</option>
                                                <?php foreach ($zones_data as $zone): ?>
                                                    <option value="<?php echo htmlspecialchars($zone['id']); ?>" 
                                                        <?php echo ($selected_zone == $zone['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($zone['city']); ?>
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
                                            <?php
                                            $total_cod = 0;
                                            $query = "SELECT SUM(total_price) as total_cod FROM `orders` WHERE `status`= 'Delivered'";
                                            if (!empty($selected_zone)) {
                                                $query .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                            }
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= " AND DATE(delivered_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                            }
                                            $data = mysqli_query($conn, $query);
                                            if ($row = mysqli_fetch_assoc($data)) {
                                                $total_cod = $row['total_cod'] ?? 0;
                                            }
                                            ?>
                                            <h4 class="mb-2">₹<?php echo number_format($total_cod); ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-dark rounded-3">
                                                <i class="fas fa-rupee-sign font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-4">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Admin Wallet</p>
                                            <?php
                                            $delivered_orders = 0;
                                            $query_order_count = "SELECT COUNT(*) as delivered_orders FROM `orders` WHERE `status`= 'Delivered'";
                                            if (!empty($selected_zone)) {
                                                $query_order_count .= " AND `zone` = '" . mysqli_real_escape_string($conn, $selected_zone) . "'";
                                            }
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query_order_count .= " AND DATE(delivered_date) BETWEEN '" . mysqli_real_escape_string($conn, $start_date) . "' AND '" . mysqli_real_escape_string($conn, $end_date) . "'";
                                            }
                                            $result_order_count = mysqli_query($conn, $query_order_count);
                                            $row_order_count = mysqli_fetch_assoc($result_order_count);
                                            $delivered_orders = $row_order_count['delivered_orders'] ?? 0;
                                            $wallet_amount = $delivered_orders * 20;
                                            ?>
                                            <h4 class="mb-2">₹<?php echo number_format($wallet_amount); ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-wallet-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-4">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Profit</p>
                                            <?php
                                            $profit = $total_cod - $wallet_amount;
                                            ?>
                                            <h4 class="mb-2">₹<?php echo number_format($profit); ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                <i class="fas fa-rupee-sign font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <h4 class="mb-4 text-truncate"><?php echo number_format($total); ?></h4>
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
                                        // Show all zones (if no is_active column exists)
                                        $query = "SELECT COUNT(*) as total FROM `zone`";
                                        $data = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($data);
                                        $total = $row['total'];
                                        ?>
                                        <h4 class="mb-4 text-truncate"><?php echo number_format($total); ?></h4>
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

                    <!-- Contact Us Management -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Contact Messages</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th">ID</th>
                                                    <th class="Name">Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Message</th>
                                                    <th class="Status">Status</th>
                                                    <th>Created At</th> At
                                                    <th class="Action">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($contact_data)): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No contact messages found.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($contact_data as $row): ?>
                                                        <tr>
                                                            <td"><?php echo htmlspecialchars($row['id']); ?></td>
                                                            <td class="text"><?php echo htmlspecialchars($row['name']); ?></td>
                                                            <td class="2"><?php echo htmlspecialchars($row['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                                                            <td class="text"><?php echo htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : ''); ?></td>
                                                            <td class="text"><?php echo htmlspecialchars($row['status']); ?></td>
                                                            <td class="text"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                            <td>
                                                                <form method="POST" action="index.php" class="d-inline">
                                                        <input type="hidden" name="contact_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                        <select name="status" class="form-control status-select" onchange="this.form.submit()">
                                                            <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="In Progress" <?php echo ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                            <option value="Resolved" <?php echo ($row['status'] == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
                                                        </select>
                                                        <input type="hidden" name="update_contact_status" value="1">
                                                    </form>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- container-fluid -->

            <!-- End Page-content -->

            <?php include("./components/footer.php"); ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>

    <script>
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            var successAlert = document.getElementById('success_alert');
            var errorAlert = document.getElementsById('error_alert');
            if (successAlert) {
                successAlert.style.display = 'none';
            }
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000);
    </script>

</body>
</html>