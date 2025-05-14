<?php include("connection.php");
session_start();

include("enc_dec.php");
include("checked-login.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No ID parameter found.";
}
$query = "SELECT * FROM `orders` WHERE `id`='$id'";
$data = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($data);
$order_id = $result['id']; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Track Order | Usemee - Your one-stop online store for all your shopping needs!</title>

</head>

<body>
    <div>

        <?php include("./components/header.php"); ?>
    </div>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="title">
                <div>
                    <h2>order2025<?php echo $order_id; ?></h2>
                    <p><a href="index.php">Home</a> - <a href="my_account.php">My Account</a> - <a
                            href="order.php">Orders</a> -
                        <span>order2025<?php echo $order_id; ?></span>
                    </p>
                </div>
            </div>

            <div class="trackOrderGrid">
                <div>
                    <h3>Order Details</h3>
                    <div class="trackOrderBox">


                        <h5>
                            <b>ID: order2025<?php echo $order_id; ?> </b>
                            <?php
                            if ($result['status'] == 'Delivered') {
                                ?>
                                <span class="status delivered"><?php echo $result['status']; ?></span>
                                <?php
                            } else if ($result['status'] == 'Confirmed') {
                                ?>
                                    <span class="status confirmed"><?php echo $result['status']; ?></span>
                                <?php
                            } else if ($result['status'] == 'Packed') {
                                ?>
                                        <span class="status packed"><?php echo $result['status']; ?></span>
                                <?php
                            } else if ($result['status'] == 'Out for Delivery') {
                                ?>
                                            <span class="status outForDelivery"><?php echo $result['status']; ?></span>
                                <?php
                            } else if ($result['status'] == 'Cancelled') {
                                ?>
                                                <span class="status cancelled"><?php echo $result['status']; ?></span>
                                <?php
                            } else {
                                ?>
                                                <span class="status">Placed</span>
                                <?php
                            }

                            ?>
                        </h5>
                        <h2>₹<?php echo $result['total_price']; ?></h2>
                        <p>Payment Mode: <?php echo $result['payment_mode']; ?></p>





                        <div class="trackOrderTimeline">
                            <?php
                            if ($result['status'] == 'Delivered') {
                                ?>
                                <div class="timeline">
                                    <div class="statusTrack done">Placed
                                        <span><?php echo $result['order_date'] ?></span>
                                    </div>
                                    <div class="statusTrack done">Confirmed
                                        <span><?php echo $result['order_confirmed_date'] ?></span>
                                    </div>
                                    <div class="statusTrack done">Packed
                                        <span><?php echo $result['order_packed_date'] ?></span>
                                    </div>
                                    <div class="statusTrack done">Out for Delivery
                                        <span><?php echo $result['out_for_delivery_date'] ?></span>
                                    </div>
                                    <div class="statusTrack done">Delivered
                                        <span><?php echo $result['delivered_date'] ?></span>
                                    </div>
                                </div>
                                <?php
                            } else if ($result['status'] == 'Confirmed') {
                                ?>
                                    <i>Your order is confirmed and we’re getting it ready.</i>
                                    <div class="timeline">
                                        <div class="statusTrack done">Placed
                                            <span><?php echo $result['order_date'] ?></span>
                                        </div>
                                        <div class="statusTrack done">Confirmed
                                            <span><?php echo $result['order_confirmed_date'] ?></span>
                                        </div>
                                        <div class="statusTrack">Packed
                                            <span><?php echo $result['order_packed_date'] ?></span>
                                        </div>
                                        <div class="statusTrack">Out for Delivery
                                            <span><?php echo $result['out_for_delivery_date'] ?></span>
                                        </div>
                                        <div class="statusTrack">Delivered
                                            <span><?php echo $result['delivered_date'] ?></span>
                                        </div>
                                    </div>
                                <?php
                            } else if ($result['status'] == 'Packed') {
                                ?>
                                        <i>Your order is packed. It will be handed over to our delivery partner shortly.</i>
                                        <div class="timeline">
                                            <div class="statusTrack done">Placed
                                                <span><?php echo $result['order_date'] ?></span>
                                            </div>
                                            <div class="statusTrack done">Confirmed
                                                <span><?php echo $result['order_confirmed_date'] ?></span>
                                            </div>
                                            <div class="statusTrack done">Packed
                                                <span><?php echo $result['order_packed_date'] ?></span>
                                            </div>
                                            <div class="statusTrack">Out for Delivery
                                                <span><?php echo $result['out_for_delivery_date'] ?></span>
                                            </div>
                                            <div class="statusTrack">Delivered
                                                <span><?php echo $result['delivered_date'] ?></span>
                                            </div>
                                        </div>
                                <?php
                            } else if ($result['status'] == 'Out for Delivery') {
                                ?> <i>Your order is on its way and will be delivered shortly.</i>
                                            <div class="timeline">
                                                <div class="statusTrack done">Placed
                                                    <span><?php echo $result['order_date'] ?></span>
                                                </div>
                                                <div class="statusTrack done">Confirmed
                                                    <span><?php echo $result['order_confirmed_date'] ?></span>
                                                </div>
                                                <div class="statusTrack done">Packed
                                                    <span><?php echo $result['order_packed_date'] ?></span>
                                                </div>
                                                <div class="statusTrack done">Out for Delivery
                                                    <span><?php echo $result['out_for_delivery_date'] ?></span>
                                                </div>
                                                <div class="statusTrack">Delivered
                                                    <span><?php echo $result['delivered_date'] ?></span>
                                                </div>
                                            </div>
                                <?php
                            } else if ($result['status'] == 'Cancelled') {
                                ?>
                                                <div class="timeline">
                                                    <div class="statusTrack done">Placed
                                                        <span><?php echo $result['order_date'] ?></span>
                                                    </div>
                                                    <div class="statusTrack cancel">Cancelled
                                                        <span><?php echo $result['order_cancel_date'] ?></span>
                                                    </div>
                                                </div>
                                <?php
                            } else {
                                ?> <i>We aim to deliver all orders within 30 minutes. However, in exceptional
                                                    circumstances,
                                                    the
                                                    delivery time may extend to a maximum of 6 hours.</i>
                                                <div class="timeline">
                                                    <div class="statusTrack done">Placed
                                                        <span><?php echo $result['order_date'] ?></span>
                                                    </div>
                                                    <div class="statusTrack">Confirmed
                                                        <span><?php echo $result['order_confirmed_date'] ?></span>
                                                    </div>
                                                    <div class="statusTrack">Packed
                                                        <span><?php echo $result['order_packed_date'] ?></span>
                                                    </div>
                                                    <div class="statusTrack">Out for Delivery
                                                        <span><?php echo $result['out_for_delivery_date'] ?></span>
                                                    </div>
                                                    <div class="statusTrack">Delivered
                                                        <span><?php echo $result['delivered_date'] ?></span>
                                                    </div>
                                                </div>
                                <?php
                            }
                            ?>
                        </div>


                        <?php if ($result['status'] == 'Pending' || $result['status'] == 'Confirmed' || $result['status'] == 'Packed') { ?>
                            <button type="button" class="cancelOrderBtn">Cancel
                                Order</button>
                        <?php } ?>


                    </div>

                    <h3>Items In This Order</h3>
                    <?php
                    $queryOrder = "SELECT * FROM `order_details` WHERE `order_id`='$order_id'";
                    $dataOrder = mysqli_query($conn, $queryOrder);
                    $totalOrder = mysqli_num_rows($dataOrder);


                    while ($resultOrder = mysqli_fetch_assoc($dataOrder)) {
                        // Calculate total price
                        $totalPrice = $resultOrder['price'] * $resultOrder['quantity'];
                        $product_id = $resultOrder['product_id'];
                        // Encrypt the ID
                        $encryptedPId = encryptId($product_id);

                        $queryp = "SELECT * FROM `product` WHERE `id`='$product_id'";
                        $datap = mysqli_query($conn, $queryp);
                        $resultp = mysqli_fetch_assoc($datap);
                        $productImage = $resultp['image'];
                        ?>
                        <a href="product.php?id=<?php echo $encryptedPId; ?>" class="orderProductBox">
                            <div>
                                <img src="superadmin/<?php echo $productImage ?>" alt="">
                            </div>

                            <div>
                                <h4><?php echo $resultOrder['product_name']; ?>
                                    (<?php echo $resultOrder['variant_name']; ?>)</h4>
                                <p>Price: ₹<span class="priceVeriant"><?php echo $resultOrder['price']; ?></span></p>
                                <p>Quantity: <span class="quantityVeriant"><?php echo $resultOrder['quantity']; ?></span>
                                </p>
                            </div>
                            <div>
                                <h4>Total: ₹<span class="totalVeriant"><?php echo $totalPrice; ?></span></h4>
                            </div>
                        </a>

                        <?php
                    }


                    ?>

                </div>
                <div>
                    <h3>Shipping Details</h3>
                    <div class="trackOrderBox">
                        <?php
                        $zone = $result['zone'];
                        $queryn = "SELECT * FROM `zone` WHERE `id`='$zone'";
                        $datan = mysqli_query($conn, $queryn);
                        $resultn = mysqli_fetch_assoc($datan);
                        $cityName = $resultn['city'];
                        ?>
                        <h4><?php echo $result['name'] ?> </h4>
                        <p><?php echo $result['address'] ?></p>
                        <p><span><?php echo $cityName ?></span>,
                            <span><?php echo $result['pin'] ?></span>
                        </p>
                        <p>Phone: <?php echo $result['phone'] ?></p>
                    </div>
                    <h3>Price Details</h3>
                    <div class="checkoutOrderBox">

                        <h6>Total: <span>₹<?php echo $result['subtotal']; ?></span></h6>
                        <h6>Delivery Cost: <span>₹<?php echo $result['delivery_charge']; ?></span></h6>
                        <h2>Order Total: <span>₹<?php echo $result['total_price']; ?></span></h2>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Custom Cancel Order Modal -->
    <div id="customCancelModal" class="custom-modal-overlay">
        <div class="custom-modal">
            <h3>Cancel Order</h3>
            <!-- Cancel Order Form -->
            <form action="" method="POST" id="cancelForm">
                <label for="cancel_reason">Select Reason:</label>
                <select name="cancel_reason" id="cancel_reason" required>
                    <option value="">-- Select Reason --</option>
                    <option value="User: Ordered by mistake">Ordered by mistake</option>
                    <option value="User: Expected delivery time is too long">Expected delivery time is too long</option>
                    <option value="User: Change in delivery address">Change in delivery address</option>
                    <option value="User: Product not needed anymore">Product not needed anymore</option>
                    <option value="User: Ordered wrong product or variant">Ordered wrong product or variant</option>
                    <option value="User: Placed duplicate order">Placed duplicate order</option>
                    <option value="Other">Other Reason</option>
                </select>

                <div id="other_reason_container" style="display:none; margin-top: 10px;">
                    <label for="other_reason_input">Please specify:</label>
                    <input type="text" name="other_reason_input" id="other_reason_input"
                        placeholder="Enter your reason...">
                </div>

                <div class="modal-buttons">
                    <button type="submit" name="cancelOrder" class="cancelOrderBtn">Confirm Cancel</button>
                    <button type="button" class="cancelOrderBtnClose" id="closeModalBtn">Close</button>
                </div>
            </form>

            <script>
                document.getElementById('cancel_reason').addEventListener('change', function () {
                    const otherContainer = document.getElementById('other_reason_container');
                    const otherInput = document.getElementById('other_reason_input');

                    if (this.value === 'Other') {
                        otherContainer.style.display = 'block';
                        otherInput.setAttribute('required', 'required');
                    } else {
                        otherContainer.style.display = 'none';
                        otherInput.removeAttribute('required');
                        otherInput.value = '';
                    }
                });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


            <?php
            if (isset($_POST['cancelOrder'])) {
                $cancel_reason = $_POST['cancel_reason'];

                // Check for 'Other' and use the user input instead
                if ($cancel_reason === 'Other') {
                    $cancel_reason = 'User: ' . mysqli_real_escape_string($conn, $_POST['other_reason_input']);
                } else {
                    $cancel_reason = mysqli_real_escape_string($conn, $cancel_reason);
                }

                // Example: get $order_id from query or session
                $order_id = $_GET['id'] ?? null;

                // Debug: Check received values
                var_dump($order_id, $cancel_reason);

                $query = "UPDATE `orders` 
              SET `status` = 'Cancelled', 
                  `order_cancel_date` = NOW(), 
                  `cancel_reason` = '$cancel_reason' 
              WHERE `id` = '$order_id'";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo "<script>
            Swal.fire({
                title: 'Order Cancelled!',
                text: 'Your order has been successfully cancelled.',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'track-order.php?id=$order_id';
            });
        </script>";
                } else {
                    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Please try again. " . mysqli_error($conn) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
                }
            }
            ?>



        </div>
    </div>

    <script>
        // Show modal and set order ID
        document.querySelectorAll('.cancelOrderBtn').forEach(function (button) {
            button.addEventListener('click', function () {

                document.getElementById('customCancelModal').style.display = 'flex';
            });
        });

        // Close modal with "Close" button
        document.getElementById('closeModalBtn').addEventListener('click', function () {
            document.getElementById('customCancelModal').style.display = 'none';
        });

        // Close modal when clicking outside the modal box
        document.getElementById('customCancelModal').addEventListener('click', function (e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });


    </script>





    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>











</body>

</html>