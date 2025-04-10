<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="assets/images/users/user.png" alt="" class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1"><?php echo $admin_name ?></h4>
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
                        $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Pending' AND DATE(`order_date`) = CURDATE()";
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
                        <?php
                        $query = "SELECT * FROM `orders` WHERE `zone`= '$admin_zone' AND `status`= 'Pending'";
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
                        <span>Order History</span>
                    </a>
                </li>





                <li class="menu-title">Seller</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shopping-bag-line"></i>
                        <span>Seller</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="seller.php">Manage Seller</a></li>
                    </ul>
                </li>



                <li class="menu-title">Delivery</li>

                <li>
                    <a href="delivery.php" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Manage Delivery</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shopping-bag-line"></i>
                        <span>COD</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="today_cod.php">Today COD</a></li>
                        <li><a href="cod.php">COD History</a></li>
                    </ul>
                </li>



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>