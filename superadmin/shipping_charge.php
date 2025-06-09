<?php

include("../connection.php");
session_start();
include("checked-login.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Shipping Charge | Super Admin | Usemee</title>
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
                                <h4 class="mb-sm-0">Shipping Charge </h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Usemee</a></li>
                                        <li class="breadcrumb-item active">Shipping Charge </li>
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
                                    <h5 class="mb-0 text-dark">Shipping Charge </h5>

                                </div>
                                <div class="card-body">

                                    <div class="table-con">
                                        <table id="datatable-buttons"
                                            class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <?php
                                            $query = "SELECT * FROM `delivery_charge` LIMIT 1";
                                            $data = mysqli_query($conn, $query);
                                            $total = mysqli_num_rows($data);


                                            if ($total != 0) {
                                                ?>
                                                <thead>
                                                    <tr>
                                                        <th>Shipping Name</th>
                                                        <th>Shipping Charge</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    while ($result = mysqli_fetch_assoc($data)) { ?>

                                                        <tr>
                                                            <td><?php echo $result['shipping_name']; ?></td>
                                                            <td><?php echo $result['shipping_charge']; ?></td>


                                                            <td>

                                                                <button type="button"
                                                                    class="btn btn-info waves-effect waves-light btn-sm editShippingBtn"
                                                                    data-id="<?php echo $result['id']; ?>"
                                                                    data-name="<?php echo $result['shipping_name']; ?>"
                                                                    data-charge="<?php echo $result['shipping_charge']; ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target=".bs-example-modal-lg-edit">
                                                                    <i class="ri-pencil-fill"></i>
                                                                </button>

                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                            } else {
                                                echo "No Category found";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                    <!--  Modal content for edit Shipping -->
                    <div class="modal fade bs-example-modal-lg-edit" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myLargeModalLabel">Update Shipping Charge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">


                                    <form method="POST" enctype="multipart/form-data"
                                        class="needs-validation was-validated" novalidate="">

                                        <input type="hidden" name="uid" id="modalShippingChargeId">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="modalShippingChargeNameInput"
                                                        class="form-label">Shipping
                                                        Name</label>
                                                    <input type="text" name="name" id="modalShippingChargeNameInput"
                                                        class="form-control">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="modalShippingChargeInput" class="form-label">Shipping
                                                        Charge
                                                    </label>
                                                    <input type="number" name="charge" id="modalShippingChargeInput"
                                                        class="form-control">
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div>
                                            <button class="btn btn-dark" type="submit" name="updateShipping">Update
                                                Shipping Charge</button>
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['updateShipping'])) {


                                        // Sanitize input
                                        $id = $_POST['uid'];
                                        $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
                                        $charge = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['charge']));

                                        $query2 = "UPDATE `delivery_charge` SET 
                                            `shipping_name`='$name',
                                            `shipping_charge`='$charge'
                                            WHERE `id`='$id'";

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
                                                text: 'Shipping Charge Updated.',
                                                icon: 'success',
                                                showConfirmButton: false,
                                                timer: 2000 
                                            }).then(() => {
                                                window.location.href = 'shipping_charge.php'; 
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
                            document.querySelectorAll(".editShippingBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    let shippingChargeId = this.getAttribute("data-id");
                                    let shippingChargeName = this.getAttribute("data-name");
                                    let shippingCharge = this.getAttribute("data-charge");
                                    // Populate input fields
                                    document.getElementById("modalShippingChargeId").value = shippingChargeId;
                                    document.getElementById("modalShippingChargeNameInput").value = shippingChargeName;
                                    document.getElementById("modalShippingChargeInput").value = shippingCharge;
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