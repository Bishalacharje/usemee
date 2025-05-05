<?php include("connection.php");
session_start();
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
    <title>My Cart - eCommerce Website</title>

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
                                                <span class="status"><?php echo $result['status']; ?></span>
                                <?php
                            }

                            ?>
                        </h5>
                        <h2>₹<?php echo $result['total_price']; ?></h2>
                        <p>Payment Mode: <?php echo $result['payment_mode']; ?></p>

                        <?php
                        if ($result['status'] == 'Pending' || $result['status'] == 'Confirmed' || $result['status'] == 'Packed') {
                            ?>
                            <form action="">
                                <input type="text" value="<?php echo $order_id; ?>" readonly hidden>
                                <button type="submit">Cancel Order</button>
                            </form>
                            <?php
                        }
                        ?>


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

                        $queryp = "SELECT * FROM `product` WHERE `id`='$product_id'";
                        $datap = mysqli_query($conn, $queryp);
                        $resultp = mysqli_fetch_assoc($datap);
                        $productImage = $resultp['image'];
                        ?>
                        <div class="orderProductBox">
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
                        </div>

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


    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>











</body>

</html>