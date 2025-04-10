<?php

include("../connection.php");
session_start();
include("checked-login.php");

?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Seller | Usemee</title>
</head>

<body data-topbar="dark">

    <div id="layout-wrapper">

        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Seller</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                                        <li class="breadcrumb-item active">Seller</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <form method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select class="form-select" name="zone">
                                            <option value="">All Zone</option>
                                            <?php
                                            $query = "SELECT * FROM `zone`";
                                            $data = mysqli_query($conn, $query);
                                            while ($result = mysqli_fetch_assoc($data)) {
                                                $selected = (isset($_GET['zone']) && $_GET['zone'] == $result['id']) ? "selected" : "";
                                                echo "<option value='{$result['id']}' $selected>{$result['city']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-select" name="category"> <!-- Corrected name attribute -->
                                            <option value="">All Category</option>
                                            <?php
                                            $query = "SELECT * FROM `category`";
                                            $data = mysqli_query($conn, $query);
                                            while ($result = mysqli_fetch_assoc($data)) {
                                                $selected = (isset($_GET['category']) && $_GET['category'] == $result['id']) ? "selected" : "";
                                                echo "<option value='{$result['id']}' $selected>{$result['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="seller.php" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-dark">Seller</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Category</th>
                                                    <th>Address</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Filtering logic
                                                $filters = [];

                                                if (isset($_GET['zone']) && !empty($_GET['zone'])) {
                                                    $zone = mysqli_real_escape_string($conn, $_GET['zone']);
                                                    $filters[] = "`zone` = '$zone'";
                                                }

                                                if (isset($_GET['category']) && !empty($_GET['category'])) {
                                                    $category = mysqli_real_escape_string($conn, $_GET['category']);
                                                    $filters[] = "`category` = '$category'";
                                                }

                                                $whereClause = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";

                                                // Fetch sellers based on filters
                                                $query = "SELECT * FROM `seller` $whereClause";
                                                $data = mysqli_query($conn, $query);

                                                if (mysqli_num_rows($data) > 0) {
                                                    while ($result = mysqli_fetch_assoc($data)) {
                                                        $cid = $result['category'];
                                                        $queryn = "SELECT * FROM `category` WHERE `id`='$cid'";
                                                        $datan = mysqli_query($conn, $queryn);
                                                        $resultn = mysqli_fetch_assoc($datan);
                                                        $categoryName = $resultn['name'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $result['name']; ?></td>
                                                            <td><?php echo $result['phone']; ?></td>
                                                            <td><?php echo $categoryName; ?></td>
                                                            <td><?php echo $result['address'] . " - " . $result['pin']; ?></td>
                                                            <td>
                                                                <a href="seller-bill-report.php?id=<?php echo $result['id']; ?>"
                                                                    class="btn btn-dark btn-sm">
                                                                    <i class="ri-file-paper-2-fill"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No Seller found</td></tr>";
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
            </div>

            <?php include("./components/footer.php"); ?>
        </div>
    </div>
    <?php include("./components/footscript.php"); ?>
</body>

</html>