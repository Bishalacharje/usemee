<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">


                <?php
                if (empty($delivery_photo)) {
                    // Show default image if no photo is provided
                    ?>
                    <img src="assets/images/users/user.png" alt="" class="avatar-md rounded-circle">
                    <?php
                } else {
                    // Show the actual delivery person photo
                    ?>
                    <img src="../admin/<?php echo htmlspecialchars($delivery_photo); ?>" alt=""
                        class="avatar-md rounded-circle">
                    <?php
                }
                ?>
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1"><?php echo "$delivery_name" ?></h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="index.php" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="today_order.php" class="waves-effect">

                        <?php
                        $query = "SELECT * FROM `orders` WHERE `delivery` = '$delivery_id' AND `status`= 'Packed' AND DATE(`order_packed_date`) = CURDATE()";
                        $data = mysqli_query($conn, $query);
                        $total = mysqli_num_rows($data);

                        ?>
                        <i class="ri-dashboard-line"></i>
                        <?php
                        if ($total > 0) {
                            ?>
                            <span class="badge rounded-pill bg-danger float-end"><?php echo $total; ?></span>
                            <?php
                        }
                        ?>

                        <span>Today Order</span>
                    </a>
                </li>
                <li>
                    <a href="order.php" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Order History</span>
                    </a>
                </li>
                <li>
                    <a href="today_cod.php" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>COD Today</span>
                    </a>
                </li>
                <li>
                    <a href="cod.php" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>COD History</span>
                    </a>
                </li>








            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>