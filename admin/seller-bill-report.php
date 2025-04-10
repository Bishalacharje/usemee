<?php

include("../connection.php");
session_start();
include("checked-login.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No ID parameter found.";
    exit;
}

$query = "SELECT * FROM `seller` WHERE `id`='$id'";
$data = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($data);
$seller_id = $result['id'];
$seller_name = $result['name'];

// Initialize total price
$total_price = 0;
$products = []; // Store fetched products here

// Check if a date is selected
$selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';

// Fetch order details where seller matches and order is "Delivered"
$query = "SELECT order_details.*, orders.status, orders.delivered_date 
          FROM `order_details` 
          JOIN `orders` ON order_details.order_id = orders.id 
          WHERE order_details.seller = '$seller_id' AND orders.status = 'Delivered'";

if (!empty($selected_date)) {
    $query .= " AND DATE(orders.delivered_date) = '$selected_date'"; // Ensure date matches
}

$data = mysqli_query($conn, $query);

// Fetch and store results in an array
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

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0"><?php echo $seller_name; ?> Bill</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active"><?php echo $seller_name; ?> Bill</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Date Filter -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <form method="GET">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="input-group">
                                    <input class="form-control" type="date" name="selected_date"
                                        value="<?php echo isset($_GET['selected_date']) ? $_GET['selected_date'] : ''; ?>"
                                        onchange="this.form.submit();">
                                    <a href="seller-bill-report.php?id=<?php echo $id; ?>"
                                        class="btn btn-danger">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Total Price Card -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Price</p>
                                            <h4 class="mb-2"><?php echo number_format($total_price, 2); ?></h4>
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
                                                    <?php foreach ($products as $result2) {
                                                        $row_total = $result2['price'] * $result2['quantity']; ?>
                                                        <tr>
                                                            <td><?php echo $result2['product_name']; ?>
                                                                (<?php echo $result2['variant_name']; ?>)</td>
                                                            <td><?php echo number_format($result2['price'], 2); ?></td>
                                                            <td><?php echo $result2['quantity']; ?></td>
                                                            <td><?php echo number_format($row_total, 2); ?></td>
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