<?php
include("../connection.php");
session_start();
include("checked-login.php");
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Banner | Usemee</title>
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Manage Banners</h4>
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addBannerModal">Add Banner</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Position</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM banners ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                $count = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$count}</td>";
                                    echo "<td>{$row['title']}</td>";
                                    echo "<td>{$row['banner_type']}</td>";
                                    echo "<td>{$row['position']}</td>";
                                    echo "<td><img src='{$row['image']}' style='max-width:100px;'></td>";
                                    echo "<td>{$row['description']}</td>";
                                    echo "<td>
                                            <button class='btn btn-info btn-sm viewBannerBtn' data-bs-toggle='modal' data-bs-target='#viewBannerModal'
                                                data-id='{$row['id']}'
                                                data-title='{$row['title']}'
                                                data-banner_type='{$row['banner_type']}'
                                                data-position='{$row['position']}'
                                                data-image='{$row['image']}'
                                                data-description='{$row['description']}'>View</button>
                                            <button class='btn btn-warning btn-sm editBannerBtn' data-bs-toggle='modal' data-bs-target='#editBannerModal'
                                                data-id='{$row['id']}'
                                                data-title='{$row['title']}'
                                                data-banner_type='{$row['banner_type']}'
                                                data-position='{$row['position']}'
                                                data-image='{$row['image']}'
                                                data-description='{$row['description']}'>Edit</button>
                                            <a href='?delete={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                        </td>";
                                    echo "</tr>";
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Banner Modal -->
                    <div class="modal fade" id="addBannerModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data" class="needs-validation was-validated" novalidate>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Banner</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Title</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Type</label>
                                                <select name="banner_type" class="form-control" required>
                                                    <option value="header">Header</option>
                                                    <option value="center">Center</option>
                                                    <option value="footer">Footer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" name="position" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Image</label>
                                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="addBanner" class="btn btn-dark">Add Banner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Banner Modal -->
                    <div class="modal fade" id="viewBannerModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewBannerName"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <img id="viewBannerImage" src="" class="img-fluid rounded mb-3" style="max-width: 200px;">
                                    <p><strong>Type:</strong> <span id="viewBannerType"></span></p>
                                    <p><strong>Position:</strong> <span id="viewBannerPosition"></span></p>
                                    <p id="viewBannerDescription"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Banner Modal -->
                    <div class="modal fade" id="editBannerModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data" class="needs-validation was-validated" novalidate>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Banner</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="editBannerId" name="banner_id">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Title</label>
                                                <input type="text" class="form-control" id="editBannerTitle" name="title" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Type</label>
                                                <select name="banner_type" class="form-control" id="editBannerType" required>
                                                    <option value="header">Header</option>
                                                    <option value="center">Center</option>
                                                    <option value="footer">Footer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" id="editBannerPosition" name="position" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Banner Image</label>
                                                <input type="file" class="form-control" name="image" accept="image/*">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" id="editBannerDescription" name="description" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="editBanner" class="btn btn-dark">Update Banner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($_POST['addBanner'])) {
                        $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
                        $banner_type = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['banner_type']));
                        $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
                        $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));
                        $imageName = $_FILES['image']['name'];
                        $imageTmp = $_FILES['image']['tmp_name'];
                        $imagePath = "banner/" . mysqli_real_escape_string($conn, $imageName);

                        if (move_uploaded_file($imageTmp, $imagePath)) {
                            $insert = "INSERT INTO banners (title, banner_type, position, image, description) 
                                       VALUES ('$title', '$banner_type', '$position', '$imagePath', '$description')";
                            $run = mysqli_query($conn, $insert);
                            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                            if ($run) {
                                echo "<script>Swal.fire({icon: 'success', title: 'Success!', text: 'Banner added successfully!', timer: 2000, showConfirmButton: false}).then(() => { window.location.href = 'banner.php'; });</script>";
                            } else {
                                echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to add banner!'});</script>";
                            }
                        }
                    }

                    if (isset($_GET['delete'])) {
                        $bannerId = $_GET['delete'];
                        $deleteQuery = "DELETE FROM banners WHERE id = $bannerId";
                        if (mysqli_query($conn, $deleteQuery)) {
                            echo "<script>Swal.fire({icon: 'success', title: 'Deleted!', text: 'The banner has been deleted.', timer: 1500, showConfirmButton: false}).then(() => { window.location.href = 'banner.php'; });</script>";
                        } else {
                            echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to delete the banner.'});</script>";
                        }
                    }

                    if (isset($_POST['editBanner'])) {
                        $bannerId = $_POST['banner_id'];
                        $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
                        $banner_type = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['banner_type']));
                        $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
                        $description = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));
                        $imageName = $_FILES['image']['name'];
                        $imageTmp = $_FILES['image']['tmp_name'];
                        $imagePath = "banner/" . mysqli_real_escape_string($conn, $imageName);

                        if ($imageName) {
                            move_uploaded_file($imageTmp, $imagePath);
                            $updateQuery = "UPDATE banners SET title = '$title', banner_type = '$banner_type', position = '$position', image = '$imagePath', description = '$description' WHERE id = $bannerId";
                        } else {
                            $updateQuery = "UPDATE banners SET title = '$title', banner_type = '$banner_type', position = '$position', description = '$description' WHERE id = $bannerId";
                        }

                        if (mysqli_query($conn, $updateQuery)) {
                            echo "<script>Swal.fire({icon: 'success', title: 'Updated!', text: 'The banner has been updated.', timer: 1500, showConfirmButton: false}).then(() => { window.location.href = 'banner.php'; });</script>";
                        } else {
                            echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to update the banner.'});</script>";
                        }
                    }
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewBannerBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    document.getElementById("viewBannerName").textContent = this.getAttribute("data-title");
                                    document.getElementById("viewBannerImage").src = this.getAttribute("data-image");
                                    document.getElementById("viewBannerType").textContent = this.getAttribute("data-banner_type");
                                    document.getElementById("viewBannerPosition").textContent = this.getAttribute("data-position");
                                    document.getElementById("viewBannerDescription").textContent = this.getAttribute("data-description");
                                });
                            });

                            document.querySelectorAll(".editBannerBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    document.getElementById("editBannerId").value = this.getAttribute("data-id");
                                    document.getElementById("editBannerTitle").value = this.getAttribute("data-title");
                                    document.getElementById("editBannerType").value = this.getAttribute("data-banner_type");
                                    document.getElementById("editBannerPosition").value = this.getAttribute("data-position");
                                    document.getElementById("editBannerDescription").value = this.getAttribute("data-description");
                                });
                            });
                        });
                    </script>

                </div>
            </div>
            <?php include("./components/footer.php"); ?>
        </div>
    </div>
    <?php include("./components/footscript.php"); ?>
</body>

</html>
