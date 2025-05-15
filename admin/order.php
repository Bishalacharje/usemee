<?php

include("../connection.php");
session_start();
include("checked-login.php");



// Handle AJAX request in the same file
if (isset($_GET['fetch_sellers'])) {
    $category_id = $_GET['category_id'];
    $zone = $_GET['zone'];

    $query = "SELECT id, name FROM seller WHERE category = '$category_id' AND zone = '$zone'";
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
    <title>Orders | Admin | Usemee</title>
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
                    // Get status filter from URL, default to 'Pending'
                    $status_filter = "AND `status` = 'Pending'";
                    $selected_status = 'Pending';
                    if (isset($_GET['status']) && !empty($_GET['status'])) {
                        $selected_status = mysqli_real_escape_string($conn, $_GET['status']);
                        $status_filter = "AND `status` = '$selected_status'";
                    }

                    $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' $status_filter ORDER BY `id` DESC";
                    $data = mysqli_query($conn, $query);
                    $total = mysqli_num_rows($data);

                    function isActive($status, $selected_status)
                    {
                        return ($selected_status == $status) ? 'btn-dark' : '';
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <a href="?status=Pending"
                                class="btn btn-white <?php echo isActive('Pending', $selected_status); ?>">Pending</a>
                            <a href="?status=Confirmed"
                                class="btn btn-white <?php echo isActive('Confirmed', $selected_status); ?>">Confirmed</a>
                            <a href="?status=Packed"
                                class="btn btn-white <?php echo isActive('Packed', $selected_status); ?>">Packed</a>
                            <a href="?status=Out for Delivery"
                                class="btn btn-white <?php echo isActive('Out for Delivery', $selected_status); ?>">Out
                                for Delivery</a>
                            <a href="?status=Delivered"
                                class="btn btn-white <?php echo isActive('Delivered', $selected_status); ?>">Delivered</a>
                            <a href="?status=Cancelled"
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
                                                                <?php
                                                                $deliveryId = $result['delivery'];

                                                                $queryd = "SELECT * FROM `delivery` WHERE `id`='$deliveryId'";
                                                                $datad = mysqli_query($conn, $queryd);
                                                                $resultd = mysqli_fetch_assoc($datad);


                                                                if ($result['status'] == 'Confirmed' || $result['status'] == 'Packed') {
                                                                    ?>
                                                                    <div>
                                                                        <?php




                                                                        if ($result['delivery'] != '') {
                                                                            $deliveryName = $resultd['name'];
                                                                            echo $deliveryName;
                                                                        } else {
                                                                            echo "Not Assigned";
                                                                        }


                                                                        ?>
                                                                        <br>
                                                                        <button type="button"
                                                                            class="btn btn-dark waves-effect waves-light btn-sm assignDeliveryBtn"
                                                                            data-id="<?php echo $result['id']; ?>"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target=".bs-example-modal-lg-delivery">
                                                                            Assign / Edit Delivery Boy
                                                                        </button>
                                                                    </div>
                                                                    <?php
                                                                } else if ($result['status'] == 'Out for Delivery' || $result['status'] == 'Delivered' || $result['status'] == 'Cancelled') {

                                                                    if ($result['delivery'] != '') {
                                                                        $deliveryName = $resultd['name'];
                                                                        echo $deliveryName;
                                                                    } else {
                                                                        echo "Not Assigned";
                                                                    }
                                                                }
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
                                            <?php } else {
                                                echo "<tr><td colspan='7'>No Order found today</td></tr>";
                                            } ?>
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



                                        <button type="submit" class="btn btn-warning btn-lg"
                                            name="confirmStatus">Confirmed</button>

                                    </form>

                                    <?php
                                    if (isset($_POST['confirmStatus'])) {



                                        // Sanitize input
                                        $id = $_POST['orderId'];


                                        $query2 = "UPDATE `orders` SET `status`='Confirmed', `order_confirmed_date`=NOW() WHERE `id`='$id'";


                                        // Execute the query
                                        $data2 = mysqli_query($conn, $query2);

                                        // Start output buffering
                                        ob_start();

                                        // Success alert
                                        if ($data2) {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Order Confirmed.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = 'order.php'; 
            });
        </script>";
                                        } else {
                                            // Error alert
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
                                        }

                                        // Flush output buffer
                                        ob_end_flush();
                                    }
                                    ?>






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
                                <div class="col-md-4">
                                
                                    <p><strong>Name:</strong> ${order.name}</p>
                                    <p><strong>Phone:</strong> ${order.phone}</p>
                                    <p><strong>Address:</strong> ${order.address} - ${order.pin}</p>
                                    
                                </div>
                                <div class="col-md-4">
                                    
                                       <p><strong>Order Date:</strong> ${order.order_date}</p>
                                     <p><strong>Confirm Date:</strong> ${order.order_confirmed_date}</p>
                                     <p><strong>Order Packed Date:</strong> ${order.order_packed_date}</p>
                                      <p><strong>Out for Delivery Date:</strong> ${order.out_for_delivery_date}</p>
                                       <p><strong>Delivered Date:</strong> ${order.delivered_date}</p>
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
                                                ${p.seller_name ? p.seller_name : '<span class="text-danger">Not Assigned</span>'}<br>
                                                <button type="button"
                                                    class="btn btn-warning waves-effect waves-light btn-sm assignSellerBtn"
                                                    data-order-detail-id="${p.id}" 
                                                    data-order-id="${p.order_id}"
                                                    data-product-id="${p.product_id}"
                                                    data-product-name="${p.product_name}"
                                                    data-variant-name="${p.variant_name}"
                                                    data-price="${p.price}"
                                                    data-quantity="${p.quantity}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignSellerModal">
                                                    Assign / Edit Seller
                                                </button>
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





                    <!-- Assign Seller Modal -->
                    <div class="modal fade" id="assignSellerModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Assign Seller</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="assignSellerForm" method="post">

                                        <input type="hidden" id="modalOrderId" name="order_id" readonly>
                                        <input type="hidden" id="modalProductId" name="product_id" readonly>
                                        <input type="hidden" id="modalProductName" readonly>
                                        <input type="hidden" class="myTextInput" id="modalVariantName"
                                            name="variant_name" readonly>
                                        <input type="hidden" class="myTextInput price" id="modalPrice" name="price"
                                            readonly>
                                        <input type="hidden" class="myTextInput quantity" id="modalQuantity"
                                            name="quantity" readonly>
                                        <input type="hidden" class="myTextInput totalPrice" id="modalTotalPrice"
                                            name="totalprice" readonly>

                                        <div class="row mb-3">

                                            <input type="hidden" id="modalOrderDetailsId" name="order_details_id"
                                                readonly>
                                            <div class="col-md-3">
                                                <label class="form-label">Select Category</label>
                                                <select id="categorySelect" class="form-select" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php
                                                    $queryc = "SELECT * FROM `category`";
                                                    $datac = mysqli_query($conn, $queryc);
                                                    while ($resultc = mysqli_fetch_assoc($datac)) {
                                                        echo "<option value='{$resultc['id']}'>{$resultc['name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-9 mb-3">
                                                <label class="form-label">Select Seller</label>
                                                <select id="sellerSelect" name="seller" class="form-select" required>
                                                    <option value="">-- Choose --</option>
                                                    <!-- Sellers will load here -->
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="assignSeller" class="btn btn-dark">Assign</button>
                                    </form>

                                    <?php
                                    if (isset($_POST['assignSeller'])) {
                                        include("../connection.php");
                                        $id = $_POST['order_details_id'];
                                        $order_id = $_POST['order_id']; // Make sure this is sent from the form!
                                        $sellerId = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['seller']));

                                        $query2 = "UPDATE `order_details` SET `seller`='$sellerId' WHERE `id`='$id'";
                                        $data2 = mysqli_query($conn, $query2);

                                        ob_start();

                                        if ($data2) {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Seller Assigned.',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500 
            }).then(() => {
                // Call a JS function to reload modal content
                reloadOrderViewModal($order_id);
            });
        </script>";
                                        } else {
                                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
                                        }

                                        ob_end_flush();
                                    }
                                    ?>


                                </div>
                                <!-- Add your seller select and submit button here -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ------------------------------  assign seller modal  ------------------------ -->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let currentOrderId = null;
                        let viewOrderModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-lg-view'));

                        // When clicking the view order button, store the order ID
                        document.addEventListener("click", function (e) {
                            if (e.target.classList.contains("viewOrderBtn")) {
                                currentOrderId = e.target.getAttribute("data-id");
                            }

                            // When opening assign seller modal, also hide view order modal
                            if (e.target.classList.contains("assignSellerBtn")) {
                                currentOrderId = e.target.getAttribute("data-order-id");
                                const viewOrderModalInstance = bootstrap.Modal.getInstance(document.querySelector('.bs-example-modal-lg-view'));
                                if (viewOrderModalInstance) {
                                    viewOrderModalInstance.hide();
                                }

                                // Fill modal form fields
                                document.getElementById("modalOrderDetailsId").value = e.target.getAttribute("data-order-detail-id");
                                document.getElementById("modalOrderId").value = e.target.getAttribute("data-order-id");
                                document.getElementById("modalProductId").value = e.target.getAttribute("data-product-id");
                                document.getElementById("modalProductName").value = e.target.getAttribute("data-product-name");
                                document.getElementById("modalVariantName").value = e.target.getAttribute("data-variant-name");
                                document.getElementById("modalPrice").value = e.target.getAttribute("data-price");
                                document.getElementById("modalQuantity").value = e.target.getAttribute("data-quantity");
                            }
                        });

                        // On assign seller form submit


                        // If user closes assign seller modal without submit, also reopen view order modal
                        document.getElementById('assignSellerModal').addEventListener('hidden.bs.modal', function () {
                            if (currentOrderId) {
                                document.querySelector(`.viewOrderBtn[data-id="${currentOrderId}"]`).click();
                            }
                        });
                    });
                </script>

                <!-- ------------------------------   Auto calculate total price for saller ------------------------ -->

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        function calculateTotal() {
                            const price = parseFloat(document.querySelector(".price").value) || 0;
                            const quantity = parseInt(document.querySelector(".quantity").value) || 0;
                            const total = price * quantity;
                            document.querySelector(".totalPrice").value = total.toFixed(2);
                        }

                        // In case values change dynamically (optional if needed)
                        document.querySelectorAll(".price, .quantity").forEach(input => {
                            input.addEventListener("input", calculateTotal);
                        });

                        // Also calculate on showing modal (if values are set programmatically)
                        document.addEventListener("click", function (e) {
                            if (e.target.classList.contains("assignSellerBtn")) {
                                setTimeout(() => {
                                    calculateTotal();
                                }, 100); // slight delay to ensure values are filled
                            }
                        });
                    });
                </script>


                <!-- ------------------------------   fatch saller based on category ------------------------ -->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const categorySelect = document.getElementById('categorySelect');
                        const sellerSelect = document.getElementById('sellerSelect');
                        const adminZone = "<?php echo $admin_zone; ?>";

                        categorySelect.addEventListener('change', function () {
                            const categoryId = this.value;
                            if (categoryId) {
                                fetch(`?fetch_sellers=1&category_id=${categoryId}&zone=${adminZone}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        sellerSelect.innerHTML = `<option value="">-- Choose Seller --</option>`;
                                        if (data.length === 0) {
                                            sellerSelect.innerHTML += `<option disabled>No sellers found in this category</option>`;
                                        }
                                        data.forEach(seller => {
                                            sellerSelect.innerHTML += `<option value="${seller.id}">${seller.name}</option>`;
                                        });
                                    });
                            } else {
                                sellerSelect.innerHTML = `<option value="">-- First Select Category --</option>`;
                            }
                        });
                    });
                </script>


                <!-- ------------------------------   Re open view order modal after assign a seller  ------------------------ -->

                <script>

                    function reloadOrderViewModal(orderId) {
                        fetch("get_order_details.php?order_id=" + orderId)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById("viewOrderTitle").textContent = "Order #order2025" + orderId;

                                let order = data.order;
                                let products = data.products;
                                let hasUnassignedSeller = products.some(p => p.seller == 0);

                                // Set order ID inside modal form input
                                document.querySelector('.modalOrderId').value = orderId;

                                let orderInfoHTML = `
                <h5>Customer Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> ${order.name}</p>
                        <p><strong>Phone:</strong> ${order.phone}</p>
                        <p><strong>Address:</strong> ${order.address} - ${order.pin}</p>
                        <p><strong>Date:</strong> ${order.order_date}</p>
                    </div>
                    <div class="col-md-6">
                        
                        <p><strong>Total Amount:</strong> ₹${order.total_price}</p>
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
                                    ${p.seller_name ? p.seller_name : '<span class="text-danger">Not Assigned</span>'}<br>
                                    <button type="button"
                                        class="btn btn-warning waves-effect waves-light btn-sm assignSellerBtn"
                                        data-order-detail-id="${p.id}" 
                                        data-order-id="${p.order_id}"
                                        data-product-id="${p.product_id}"
                                        data-product-name="${p.product_name}"
                                        data-variant-name="${p.variant_name}"
                                        data-price="${p.price}"
                                        data-quantity="${p.quantity}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignSellerModal">
                                        Assign / Edit Seller
                                    </button>
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

                                // Re-open the modal
                                var orderViewModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-lg-view'));
                                orderViewModal.show();
                            });
                    }

                </script>


                <!-- ------------------------------   Assign Delivery Modal  ------------------------ -->

                <div class="modal fade bs-example-modal-lg-delivery" tabindex="-1" role="dialog"
                    aria-labelledby="assignDeliveryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Delivery Boy</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <input type="hidden" id="deliveryOrderId" name="orderId" class="form-control"
                                        readonly>
                                    <div class="mb-3">
                                        <label for="deliveryBoy" class="form-label">Select Delivery Boy</label>
                                        <select name="delivery_boy_id" class="form-select" required>
                                            <option value="">-- Choose --</option>
                                            <?php
                                            $queryc = "SELECT * FROM `delivery` WHERE `zone`='$admin_zone'";
                                            $datac = mysqli_query($conn, $queryc);
                                            while ($resultc = mysqli_fetch_assoc($datac)) {
                                                echo "<option value='{$resultc['id']}'>{$resultc['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="assignDelivery" class="btn btn-success">Assign
                                        Delivery</button>
                                </form>

                                <?php
                                if (isset($_POST['assignDelivery'])) {



                                    // Sanitize input
                                    $id = $_POST['orderId'];
                                    $delivery = $_POST['delivery_boy_id'];


                                    $query2 = "UPDATE `orders` SET `status`='Packed',`delivery`='$delivery', `order_packed_date`=NOW() WHERE `id`='$id'";

                                    // Execute the query
                                    $data2 = mysqli_query($conn, $query2);

                                    // Start output buffering
                                    ob_start();

                                    // Success alert
                                    if ($data2) {
                                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Order Packed.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = 'order.php'; 
            });
        </script>";
                                    } else {
                                        // Error alert
                                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
                                    }

                                    // Flush output buffer
                                    ob_end_flush();
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        document.querySelectorAll(".assignDeliveryBtn").forEach(button => {
                            button.addEventListener("click", function () {
                                let orderId = this.getAttribute("data-id");
                                document.getElementById("deliveryOrderId").value = orderId;
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