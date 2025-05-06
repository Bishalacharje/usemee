<?php
include("connection.php");
session_start();
include("checked-login.php");



// Pagination variables
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$offset = ($page - 1) * $limit;

// Count total orders
$count_query = "SELECT COUNT(*) AS total FROM `orders` WHERE `user_id`='$userid'";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_orders = $count_row['total'];
$total_pages = ceil($total_orders / $limit);

// Fetch orders for current page
$query = "SELECT * FROM `orders` WHERE `user_id`='$userid' ORDER BY `id` DESC LIMIT $limit OFFSET $offset";
$data = mysqli_query($conn, $query);
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
                            <tbody>
                                <?php while ($result = mysqli_fetch_assoc($data)) { ?>
                                    <tr>
                                        <td>
                                            <span class="date"><?php echo $result['order_date']; ?></span>
                                            <h2>₹<?php echo $result['total_price']; ?></h2>
                                        </td>
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
                                                                <span class="status">Placed</span>
                                                <?php
                                            }

                                            ?>
                                        </td>
                                        <td>
                                            <a href="track-order.php?id=<?php echo $result['id']; ?>">
                                                <button>View Order</button>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>">«</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active-page' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>">»</a>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>
</body>

</html>