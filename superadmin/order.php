<?php

include("../connection.php");
session_start();
include("checked-login.php");





?>




<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Orders | Super Admin | Usemee</title>
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
                                <h4 class="mb-sm-0">Order History</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Orders History</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <?php

                    // Get zone filter from URL, default to empty (no filter)
                    $selected_zone = "";
                    $zone_filter = "";
                    if (isset($_GET['zone']) && $_GET['zone'] !== "") {
                        $selected_zone = mysqli_real_escape_string($conn, $_GET['zone']);
                        $zone_filter = "AND `zone` = '$selected_zone'"; // assuming orders table has 'zone' column
                    }

                    // Get status filter from URL, default to 'Pending'
                    $selected_status = 'Pending';
                    $status_filter = "AND `status` = 'Pending'";
                    if (isset($_GET['status']) && !empty($_GET['status'])) {
                        $selected_status = mysqli_real_escape_string($conn, $_GET['status']);
                        $status_filter = "AND `status` = '$selected_status'";
                    }

                    $query = "SELECT * FROM `orders` WHERE 1 $status_filter $zone_filter ORDER BY `id` DESC";
                    $data = mysqli_query($conn, $query);
                    $total = mysqli_num_rows($data);

                    function isActive($status, $selected_status)
                    {
                        return ($selected_status == $status) ? 'btn-dark' : '';
                    }
                    ?>

                    <form method="GET" action="">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <select class="form-select" name="zone" onchange="this.form.submit()">
                                    <option value="" <?php if ($selected_zone == "")
                                        echo "selected"; ?>>All Zone</option>
                                    <?php
                                    $queryZone = "SELECT * FROM `zone`";
                                    $dataZone = mysqli_query($conn, $queryZone);
                                    while ($resultZone = mysqli_fetch_assoc($dataZone)) {
                                        $selected = ($selected_zone == $resultZone['id']) ? "selected" : "";
                                        echo "<option value='{$resultZone['id']}' $selected>{$resultZone['city']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <?php
                            // Build URL for status links, preserving zone filter if set
                            $baseUrl = "?";
                            if ($selected_zone !== "") {
                                $baseUrl .= "zone=" . urlencode($selected_zone) . "&";
                            }
                            ?>
                            <a href="<?php echo $baseUrl . "status=Pending"; ?>"
                                class="btn btn-white <?php echo isActive('Pending', $selected_status); ?>">Pending</a>
                            <a href="<?php echo $baseUrl . "status=Confirmed"; ?>"
                                class="btn btn-white <?php echo isActive('Confirmed', $selected_status); ?>">Confirmed</a>
                            <a href="<?php echo $baseUrl . "status=Packed"; ?>"
                                class="btn btn-white <?php echo isActive('Packed', $selected_status); ?>">Packed</a>
                            <a href="<?php echo $baseUrl . "status=Out for Delivery"; ?>"
                                class="btn btn-white <?php echo isActive('Out for Delivery', $selected_status); ?>">Out
                                for Delivery</a>
                            <a href="<?php echo $baseUrl . "status=Delivered"; ?>"
                                class="btn btn-white <?php echo isActive('Delivered', $selected_status); ?>">Delivered</a>
                            <a href="<?php echo $baseUrl . "status=Cancelled"; ?>"
                                class="btn btn-white <?php echo isActive('Cancelled', $selected_status); ?>">Cancelled</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">All Orders</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="width: 100%;">
                                            <?php if ($total != 0) { ?>
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Order Date & Time</th>
                                                        <th>Status</th>
                                                        <th>Delivery Boy</th>
                                                        <th>Products</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($result = mysqli_fetch_assoc($data)) { ?>
                                                        <tr>
                                                            <td>order2025<?php echo $result['id']; ?></td>
                                                            <td><?php echo $result['name']; ?></td>
                                                            <td><?php echo $result['phone']; ?></td>
                                                            <td><?php echo $result['order_date']; ?></td>
                                                            <td>
                                                                <?php
                                                                $status = $result['status'];
                                                                $statusClasses = [
                                                                    'Delivered' => 'bg-success',
                                                                    'Confirmed' => 'bg-info',
                                                                    'Packed' => 'bg-primary',
                                                                    'Out for Delivery' => 'bg-warning',
                                                                    'Cancelled' => 'bg-danger',
                                                                ];
                                                                $class = isset($statusClasses[$status]) ? $statusClasses[$status] : 'bg-secondary';
                                                                ?>
                                                                <span
                                                                    class="badge rounded-pill <?php echo $class; ?> float-end"><?php echo $status; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $deliveryId = $result['delivery'];
                                                                $deliveryName = "Not Assigned";
                                                                if (!empty($deliveryId)) {
                                                                    $queryd = "SELECT * FROM `delivery` WHERE `id`='$deliveryId'";
                                                                    $datad = mysqli_query($conn, $queryd);
                                                                    $resultd = mysqli_fetch_assoc($datad);
                                                                    if ($resultd) {
                                                                        $deliveryName = $resultd['name'];
                                                                    }
                                                                }
                                                                echo $deliveryName;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-warning btn-sm viewOrderBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-view">
                                                                    <i class="ri-eye-fill"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No Orders found</td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>







                    <!-- Modal content for view order -->
                    <div class="modal fade bs-example-modal-lg-view" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewOrderTitle">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="orderDetailContent">
                                    <!-- Order details & products will load here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            // Listen for clicks on all buttons with class 'viewOrderBtn'
                            document.querySelectorAll(".viewOrderBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let orderId = this.getAttribute("data-id");

                                    // Fetch order & order details via AJAX
                                    fetch("get_order_details.php?order_id=" + orderId)
                                        .then(response => response.json())
                                        .then(data => {
                                            document.getElementById("viewOrderTitle").textContent = "Order #order2025" + orderId;

                                            let order = data.order;
                                            let products = data.products;

                                            let orderInfoHTML = `
                            <h5>Customer Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Name:</strong> ${order.name}</p>
                                    <p><strong>Phone:</strong> ${order.phone}</p>
                                    <p><strong>Address:</strong> ${order.address} - ${order.pin}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Order Date:</strong> ${order.order_date}</p>
                                    <p><strong>Confirm Date:</strong> ${order.order_confirmed_date || '-'}</p>
                                    <p><strong>Order Packed Date:</strong> ${order.order_packed_date || '-'}</p>
                                    <p><strong>Out for Delivery Date:</strong> ${order.out_for_delivery_date || '-'}</p>
                                    <p><strong>Delivered Date:</strong> ${order.delivered_date || '-'}</p>
                                    <p><strong>Status:</strong> ${order.status}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Total Amount:</strong> ₹${order.total_price}</p>
                                </div>
                            </div>
                            <hr>
                            <h5>Ordered Products</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Variant</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Seller</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${products.map(p => `
                                        <tr>
                                            <td>${p.product_name}</td>
                                            <td>${p.variant_name}</td>
                                            <td>${p.quantity}</td>
                                            <td>₹${p.price}</td>
                                            <td>₹${p.quantity * p.price}</td>
                                            <td>
                                                ${p.seller_name ? p.seller_name : '<span class="text-danger">Not Assigned</span>'}
                                                
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;

                                            document.getElementById("orderDetailContent").innerHTML = orderInfoHTML;
                                        });
                                });
                            });

                            // Fix leftover backdrop and reload on modal close
                            document.querySelectorAll('.modal').forEach(modal => {
                                modal.addEventListener('hidden.bs.modal', function () {
                                    // Remove any leftover modal backdrop overlays
                                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

                                    // Remove modal-open class and styles from body
                                    document.body.classList.remove('modal-open');
                                    document.body.style = '';

                                    // Clear modal content (optional)
                                    document.getElementById('orderDetailContent').innerHTML = '';

                                    // Reload page or redirect to specific path after modal closes
                                    window.location.reload();
                                    // OR to reload a specific URL:
                                    // window.location.href = 'your-path.php';
                                });
                            });
                        });
                    </script>








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