<?php

include("../connection.php");
session_start();
include("checked-login.php");



// Handle AJAX request in the same file
if (isset($_GET['fetch_sellers'])) {
    $category_id = $_GET['category_id'];
    $district = $_GET['district'];

    $query = "SELECT id, name FROM seller WHERE category = '$category_id' AND district = '$district'";
    $result = mysqli_query($conn, $query);

    $sellers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sellers[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($sellers);
    exit;
}


?>




<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Orders | Usemee</title>
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
                                <h4 class="mb-sm-0">Orders</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active">Orders</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Orders</h5>

                                </div>
                                <div class="card-body">

                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `orders` WHERE `delivery`= '$delivery_id'";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th> Order ID</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Total Price</th>
                                                        <th>Status</th>
                                                        <th>Products</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>


                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) { ?>



                                                        <tr>
                                                            <td>order2025<?php echo $result['id']; ?></td>
                                                            <td><?php echo $result['name']; ?></td>
                                                            <td><?php echo $result['phone']; ?></td>
                                                            <td>
                                                                <h5><?php echo $result['total_price']; ?></h5>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($result['status'] == 'Delivered') {
                                                                    ?>
                                                                    <span
                                                                        class="badge rounded-pill bg-success float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                } else if ($result['status'] == 'Confirmed') {
                                                                    ?>
                                                                        <span
                                                                            class="badge rounded-pill bg-info float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                } else if ($result['status'] == 'Packed') {
                                                                    ?>
                                                                            <span
                                                                                class="badge rounded-pill bg-primary float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                } else if ($result['status'] == 'Out for Delivery') {
                                                                    ?>
                                                                                <span
                                                                                    class="badge rounded-pill bg-warning float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                } else if ($result['status'] == 'Cancelled') {
                                                                    ?>
                                                                                    <span
                                                                                        class="badge rounded-pill bg-danger float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                                    <span
                                                                                        class="badge rounded-pill bg-secondary float-end"><?php echo $result['status']; ?></span>
                                                                    <?php
                                                                }

                                                                ?>


                                                            </td>
                                                            <td>


                                                                <button type="button"
                                                                    class="btn btn-dark waves-effect waves-light btn-sm viewOrderBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-status="<?php echo $result['status']; ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-view">
                                                                    <i class="ri-eye-fill"></i>
                                                                </button>

                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($result['status'] == 'Packed') {
                                                                    ?>
                                                                    <a href="<?php echo "out-for-delivery.php?id=$result[id]" ?>"
                                                                        class="btn btn-sm btn-warning">Out for Delivery</a>

                                                                    <?php
                                                                } else if ($result['status'] == 'Out for Delivery') {
                                                                    ?>
                                                                        <a href="<?php echo "delivered.php?id=$result[id]" ?>"
                                                                            class="btn btn-sm btn-success">Delivered</a>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm waves-effect waves-light btn-sm orderCancelledBtn"
                                                                            data-id="<?php echo $result['id']; ?>"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target=".bs-example-modal-cancalled">
                                                                            Cancel
                                                                        </button>
                                                                    <?php
                                                                }
                                                                ?>


                                                            </td>

                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Order found";
                                            }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>











                    <!--  Modal content for view order -->
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
                                <div class="modal-footer d-flex justify-content-center">

                                    <form id="confirmForm" action="" method="post">
                                        <input type="hidden" value="" name="orderId" class="modalOrderId" readonly>



                                    </form>








                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- /.modal -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewOrderBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let orderId = this.getAttribute("data-id");
                                    let status = this.getAttribute("data-status");


                                    // Set orderId in modal input
                                    document.querySelector('.modalOrderId').value = orderId;


                                    // Fetch order & order details
                                    fetch("get_order_details.php?order_id=" + orderId)
                                        .then(response => response.json())
                                        .then(data => {
                                            document.getElementById("viewOrderTitle").textContent = "Order #order2025" + orderId;

                                            let order = data.order;
                                            let products = data.products;
                                            let hasUnassignedSeller = products.some(p => p.seller == 0);  // Check if any seller is unassigned

                                            let orderInfoHTML = `
                            <h5>Customer Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                
                                    <p><strong>Name:</strong> ${order.name}</p>
                                    <p><strong>Phone:</strong> ${order.phone}</p>
                                    <p><strong>Address:</strong> ${order.address} - ${order.pin}</p>
                                    <h5>Total Amount: <strong>₹${order.total_price}</strong></h5>
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                    <p><strong>Order Date:</strong> ${order.order_date}</p>
                                     <p><strong>Confirm Date:</strong> ${order.order_confirmed_date}</p>
                                     <p><strong>Order Packed Date:</strong> ${order.order_packed_date}</p>
                                      <p><strong>Out for Delivery Date:</strong> ${order.out_for_delivery_date}</p>
                                       <p><strong>Delivered Date:</strong> ${order.delivered_date}</p>
                                    <p><strong>Status:</strong> ${order.status}</p>
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
                                                <h6 class="mb-0">${p.seller_name}</h6>
                                               ${p.seller_address}, 
                                               ${p.seller_pin}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;

                                            document.getElementById("orderDetailContent").innerHTML = orderInfoHTML;

                                            // Show or hide confirm form
                                            const confirmForm = document.getElementById("confirmForm");
                                            if (hasUnassignedSeller) {
                                                confirmForm.style.display = "none";
                                            } else {
                                                confirmForm.style.display = "block";
                                            }
                                        });
                                });
                            });
                        });


                    </script>





                    <!-- Order Cancel Modal -->
                    <div class="modal fade bs-example-modal-cancalled" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cancel Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="order_id" id="cancelOrderId" value="">
                                        <div class="mb-3">
                                            <label for="cancelReason" class="form-label">Reason for cancellation</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason1" value="Customer not available" required>
                                                <label class="form-check-label" for="reason1">
                                                    Customer not available
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason2" value="Customer refused to accept">
                                                <label class="form-check-label" for="reason2">
                                                    Customer refused to accept
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason3" value="Incorrect delivery address">
                                                <label class="form-check-label" for="reason3">
                                                    Incorrect delivery address
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason4" value="Damaged product">
                                                <label class="form-check-label" for="reason4">
                                                    Damaged product
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason5" value="Order placed by mistake">
                                                <label class="form-check-label" for="reason5">
                                                    Order placed by mistake
                                                </label>
                                            </div>
                                            <!-- <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason6" value="Out of stock">
                                                <label class="form-check-label" for="reason6">
                                                    Out of stock
                                                </label>
                                            </div> -->
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="cancel_reason"
                                                    id="reason7" value="Other">
                                                <label class="form-check-label" for="reason7">
                                                    Other reason
                                                </label>
                                            </div>
                                            <textarea name="other_reason_text" id="otherReasonText"
                                                class="form-control mt-2" placeholder="If other, specify reason..."
                                                style="display:none;"></textarea>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="cancelOrder" class="btn btn-danger">Confirm
                                            Cancel</button>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['cancelOrder'])) {
                                    // Get and sanitize data
                                    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
                                    $cancel_reason = mysqli_real_escape_string($conn, $_POST['cancel_reason']);
                                    $other_reason_text = mysqli_real_escape_string($conn, $_POST['other_reason_text']);

                                    // If 'Other' is selected, use textarea input
                                    if ($cancel_reason == 'Other' && !empty($other_reason_text)) {
                                        $cancel_reason = $other_reason_text;
                                    }

                                    // Update order status
                                    $query = "UPDATE `orders` 
              SET `status` = 'Cancelled', 
                  `order_cancel_date` = NOW(), 
                  `cancel_reason` = '$cancel_reason' 
              WHERE `id` = '$order_id'";

                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                        echo "<script>
        Swal.fire({
            title: 'Order Cancelled!',
            text: 'Order status updated successfully.',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'order.php'; 
        });
        </script>";
                                    } else {
                                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        </script>";
                                    }
                                }
                                ?>



                            </div>
                        </div>
                    </div>


                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".orderCancelledBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let orderId = this.getAttribute("data-id");
                                    document.getElementById("cancelOrderId").value = orderId;
                                });
                            });
                        });
                    </script>



                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll('input[name="cancel_reason"]').forEach((radio) => {
                                radio.addEventListener('change', function () {
                                    if (this.value === "Other") {
                                        document.getElementById('otherReasonText').style.display = 'block';
                                    } else {
                                        document.getElementById('otherReasonText').style.display = 'none';
                                    }
                                });
                            });
                        });
                    </script>









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