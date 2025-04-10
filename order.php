<?php
include("connection.php");
session_start();
include("checked-login.php");
?>

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
            <div class="myAccountGrid">
                <div class="myAccountSidebar">
                    <a href="my_account.php" class="myAccountSidebarMenu">
                        My Account
                        <img src="./assets/images/imgicon/user-dark.png" alt="">
                    </a>
                    <a href="order.php" class="myAccountSidebarMenu active">
                        Orders
                        <img src="./assets/images/imgicon/order.png" alt="">
                    </a>
                    <a href="address.php" class="myAccountSidebarMenu">
                        Address
                        <img src="./assets/images/imgicon/location-dark.png" alt="">
                    </a>
                    <a href="logout.php" class="myAccountSidebarMenu">
                        Logout
                        <img src="./assets/images/imgicon/power-off.png" alt="">
                    </a>
                </div>
                <div class="myAccountCon">
                    <div class="title">
                        <div>
                            <h2>Orders</h2>
                            <p><a href="index.php">Home</a> - <a href="my_account.php">My Account</a> -
                                <span>Orders</span>
                            </p>
                        </div>
                    </div>

                    <div class="ordersCon">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order_ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>View</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM `orders` WHERE `user_id`='$userid' ORDER BY `id` DESC";
                                $data = mysqli_query($conn, $query);
                                $total = mysqli_num_rows($data);


                                while ($result = mysqli_fetch_assoc($data)) {
                                    ?>
                                    <tr>
                                        <td>order2025<?php echo $result['id']; ?></td>
                                        <td><?php echo $result['order_date']; ?></td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <h3>₹<?php echo $result['total_price']; ?></h3>
                                        </td>
                                        <td>
                                            <a href="<?php echo "track-order.php?id=$result[id]" ?>">View</a>
                                        </td>
                                    </tr>
                                    <?php
                                }


                                ?>
                            </tbody>
                        </table>
                    </div>









                </div>
            </div>


        </div>
    </section>


    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>











</body>

</html>