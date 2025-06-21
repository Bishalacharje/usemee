<?php
include("../connection.php");
session_start();
include("checked-login.php");
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Seller Wallet | Super Admin | Usemee</title>
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Seller Wallet</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Usemee</a></li>
                                        <li class="breadcrumb-item active">Seller Wallet</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <form method="GET" id="filterForm">
                                <label>Zone</label>
                                <select class="form-select" name="zone"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">All Zones</option>
                                    <?php
                                    $zones = mysqli_query($conn, "SELECT * FROM `zone`");
                                    while ($z = mysqli_fetch_assoc($zones)) {
                                        $selected = (isset($_GET['zone']) && $_GET['zone'] == $z['id']) ? "selected" : "";
                                        echo "<option value='{$z['id']}' $selected>{$z['city']}</option>";
                                    }
                                    ?>
                                </select>
                        </div>

                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date"
                                value="<?= $_GET['start_date'] ?? '' ?>"
                                onchange="document.getElementById('filterForm').submit();">
                        </div>

                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="end_date"
                                value="<?= $_GET['end_date'] ?? '' ?>"
                                onchange="document.getElementById('filterForm').submit();">
                        </div>
                        </form>
                    </div>

                    <!-- Total Price Card -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-truncate font-size-14 mb-2">Total Price</p>
                                            <h4 class="mb-2">
                                                <?php
                                                $total_sql = "SELECT SUM(od.price * od.quantity) AS TotalPrice
                                                              FROM orders o
                                                              JOIN order_details od ON o.id = od.order_id
                                                              WHERE o.status = 'Delivered'";

                                                if (!empty($_GET['zone'])) {
                                                    $total_sql .= " AND o.zone = '{$_GET['zone']}'";
                                                }

                                                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                    $total_sql .= " AND DATE(o.delivered_date) BETWEEN '{$_GET['start_date']}' AND '{$_GET['end_date']}'";
                                                }

                                                $res = mysqli_query($conn, $total_sql);
                                                $row = mysqli_fetch_assoc($res);
                                                echo '₹' . number_format($row['TotalPrice'] ?? 0, 2);
                                                ?>
                                            </h4>
                                        </div>
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-primary rounded-3">
                                                <i class="ri-shopping-cart-2-line font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Sellers</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered dt-responsive nowrap"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Seller Name</th>
                                                <th>Total Price</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT 
                                                        s.id AS SellerID,
                                                        s.name AS SellerName,
                                                        MAX(o.delivered_date) AS LastDelivered,
                                                        sb.last_billed_at AS LastBilled,
                                                        SUM(od.price * od.quantity) AS TotalPrice
                                                    FROM orders o
                                                    JOIN order_details od ON o.id = od.order_id
                                                    JOIN seller s ON od.seller = s.id
                                                    LEFT JOIN seller_billing_status sb ON sb.seller_id = s.id
                                                    WHERE o.status = 'Delivered'";

                                            if (!empty($_GET['zone'])) {
                                                $sql .= " AND o.zone = '{$_GET['zone']}'";
                                            }

                                            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                $sql .= " AND DATE(o.delivered_date) BETWEEN '{$_GET['start_date']}' AND '{$_GET['end_date']}'";
                                            }

                                            $sql .= " GROUP BY s.id";

                                            $result = mysqli_query($conn, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $isIncomplete = !$row['LastBilled'] || $row['LastDelivered'] > $row['LastBilled'];
                                                $btnText = $isIncomplete ? "Incomplete" : "Billed";
                                                $btnClass = $isIncomplete ? "btn-danger" : "btn-success";

                                                echo "<tr>
                                                        <td>" . htmlspecialchars($row['SellerName']) . "</td>
                                                        <td>₹" . number_format($row['TotalPrice'], 2) . "</td>
                                                        <td>
                                                            <button class='btn $btnClass btn-sm toggleBillingStatus' data-seller-id='{$row['SellerID']}'>
                                                                $btnText
                                                            </button>
                                                        </td>
                                                      </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include("./components/footer.php"); ?>
        </div>
    </div>

    <?php include("./components/footscript.php"); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".toggleBillingStatus").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    const sellerId = this.getAttribute("data-seller-id");
                    fetch("update-billing-status.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "seller_id=" + sellerId
                    })
                        .then(res => res.text())
                        .then(response => {
                            if (response.trim() === "success") {
                                this.classList.remove("btn-danger");
                                this.classList.add("btn-success");
                                this.textContent = "Billed";
                            }
                        });
                });
            });
        });
    </script>
</body>

</html>