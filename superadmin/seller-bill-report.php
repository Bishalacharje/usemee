<?php

include("../connection.php");
session_start();
include("checked-login.php");

// Get seller ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No ID parameter found.";
    exit;
}

// Get seller info
$query = "SELECT * FROM `seller` WHERE `id`='$id'";
$data = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($data);
$seller_id = $result['id'];
$seller_name = $result['name'];

// Initialize total price and product list
$total_price = 0;
$products = [];

// Get date filters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build query
$query = "SELECT order_details.*, orders.status, orders.delivered_date 
          FROM `order_details` 
          JOIN `orders` ON order_details.order_id = orders.id 
          WHERE order_details.seller = '$seller_id' AND orders.status = 'Delivered'";

if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND DATE(orders.delivered_date) BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $query .= " AND DATE(orders.delivered_date) >= '$start_date'";
} elseif (!empty($end_date)) {
    $query .= " AND DATE(orders.delivered_date) <= '$end_date'";
}

// Execute query
$data = mysqli_query($conn, $query);

// Process data
while ($result2 = mysqli_fetch_assoc($data)) {
    $products[] = $result2;
    $total_price += $result2['price'] * $result2['quantity'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title><?php echo $seller_name; ?> | Seller Bill | Usemee</title>
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
                                <h4 class="mb-sm-0"><?php echo $seller_name; ?> Bill</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item active"><?php echo $seller_name; ?> Bill</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="input-group">
                                    <input class="form-control" type="date" name="start_date"
                                        value="<?php echo $start_date; ?>" placeholder="Start Date">
                                    <input class="form-control" type="date" name="end_date"
                                        value="<?php echo $end_date; ?>" placeholder="End Date">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="seller-bill-report.php?id=<?php echo $id; ?>"
                                        class="btn btn-danger">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Date Range Summary -->
                    <?php if ($start_date || $end_date): ?>
                        <div class="row mb-3">
                            <div class="col">
                                <p class="text-muted">
                                    Showing results
                                    <?php if ($start_date): ?> from
                                        <strong><?php echo $start_date; ?></strong><?php endif; ?>
                                    <?php if ($end_date): ?> to <strong><?php echo $end_date; ?></strong><?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Total Price Card -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Price</p>
                                            <h4 class="mb-2">₹<?php echo number_format($total_price, 2); ?></h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-cart-2-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($products)) { ?>
                                                    <?php foreach ($products as $row) {
                                                        $row_total = $row['price'] * $row['quantity']; ?>
                                                        <tr>
                                                            <td><?php echo $row['product_name']; ?>
                                                                (<?php echo $row['variant_name']; ?>)</td>
                                                            <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                                            <td><?php echo $row['quantity']; ?></td>
                                                            <td>₹<?php echo number_format($row_total, 2); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No products found</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include("./components/footer.php"); ?>
        </div>
    </div>

    <?php include("./components/footscript.php"); ?>

</body>

</html>