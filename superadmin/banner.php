<?php
include("../connection.php");
session_start();
include("checked-login.php");

// Determine banner type from query parameter
$type = isset($_GET['type']) ? $_GET['type'] : 'header';
$table = '';
$title = '';
$has_position = false;
$has_visibility = false;

switch ($type) {
    case 'header':
        $table = 'header_banners';
        $title = 'Header Banners';
        $has_visibility = true;
        break;
    case 'center':
        $table = 'center_cards';
        $title = 'Center Cards';
        $has_position = true;
        $has_visibility = true;
        break;
    case 'footer':
        $table = 'footer_cards';
        $title = 'Footer Cards';
        break;
    default:
        die("Invalid banner type");
}
?>

<!doctype html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title><?php echo $title; ?> | Usemee</title>
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <?php include("./components/header.php"); ?>
        <?php include("./components/sidebar.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Manage <?php echo $title; ?></h4>
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addBannerModal">Add <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Link</th>
                                    <?php if ($has_position): ?>
                                        <th>Position</th>
                                    <?php endif; ?>
                                    <?php if ($has_visibility): ?>
                                        <th>Visibility</th>
                                    <?php endif; ?>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM $table ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                $count = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>$count</td>";
                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td><img src='" . htmlspecialchars($row['image_path']) . "' style='max-width:100px;'></td>";
                                    echo "<td>" . htmlspecialchars($row['link']) . "</td>";
                                    if ($has_position) {
                                        echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                                    }
                                    if ($has_visibility) {
                                        echo "<td>" . ($row['visibility'] ? 'Visible' : 'Hidden') . "</td>";
                                    }
                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                    echo "<td>
                                            <button class='btn btn-info btn-sm viewBannerBtn' data-bs-toggle='modal' data-bs-target='#viewBannerModal'
                                                data-id='{$row['id']}'
                                                data-title='" . htmlspecialchars($row['title']) . "'
                                                data-image='" . htmlspecialchars($row['image_path']) . "'
                                                data-link='" . htmlspecialchars($row['link']) . "'
                                                " . ($has_position ? "data-position='" . htmlspecialchars($row['position']) . "'" : '') . "
                                                " . ($has_visibility ? "data-visibility='" . $row['visibility'] . "'" : '') . "
                                                data-created_at='" . htmlspecialchars($row['created_at']) . "'>View</button>
                                            <button class='btn btn-warning btn-sm editBannerBtn' data-bs-toggle='modal' data-bs-target='#editBannerModal'
                                                data-id='{$row['id']}'
                                                data-title='" . htmlspecialchars($row['title']) . "'
                                                data-image='" . htmlspecialchars($row['image_path']) . "'
                                                data-link='" . htmlspecialchars($row['link']) . "'
                                                " . ($has_position ? "data-position='" . htmlspecialchars($row['position']) . "'" : '') . "
                                                " . ($has_visibility ? "data-visibility='" . $row['visibility'] . "'" : '') . "
                                                data-created_at='" . htmlspecialchars($row['created_at']) . "'>Edit</button>
                                            <a href='?type=$type&delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
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
                                        <h5 class="modal-title">Add <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Link</label>
                                                <input type="url" class="form-control" name="link">
                                            </div>
                                            <?php if ($has_position): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Position</label>
                                                    <input type="number" class="form-control" name="position" value="0" required>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($has_visibility): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Visibility</label>
                                                    <select name="visibility" class="form-control" required>
                                                        <option value="1">Visible</ contrats: <option value="0">Hidden</option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="addBanner" class="btn btn-dark">Add <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></button>
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
                                    <p><strong>Link:</strong> <span id="viewBannerLink"></span></p>
                                    <?php if ($has_position): ?>
                                        <p><strong>Position:</strong> <span id="viewBannerPosition"></span></p>
                                    <?php endif; ?>
                                    <?php if ($has_visibility): ?>
                                        <p><strong>Visibility:</strong> <span id="viewBannerVisibility"></span></p>
                                    <?php endif; ?>
                                    <p><strong>Created At:</strong> <span id="viewBannerCreatedAt"></span></p>
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
                                        <h5 class="modal-title">Edit <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="editBannerId" name="banner_id">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" id="editBannerTitle" name="title" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" class="form-control" name="image" accept="image/*">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Link</label>
                                                <input type="url" class="form-control" id="editBannerLink" name="link">
                                            </div>
                                            <?php if ($has_position): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Position</label>
                                                    <input type="number" class="form-control" id="editBannerPosition" name="position" required>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($has_visibility): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Visibility</label>
                                                    <select name="visibility" class="form-control" id="editBannerVisibility" required>
                                                        <option value="1">Visible</option>
                                                        <option value="0">Hidden</option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="editBanner" class="btn btn-dark">Update <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($_POST['addBanner'])) {
                        $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
                        $link = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['link']));
                        $image_name = $_FILES['image']['name'];
                        $image_tmp = $_FILES['image']['tmp_name'];
                        $image_path = "banner/" . mysqli_real_escape_string($conn, $image_name);

                        $columns = "title, image_path, link";
                        $values = "'$title', '$image_path', '$link'";
                        $placeholders = "?, ?, ?";

                        $params = [$title, $image_path, $link];
                        $types = "sss";

                        if ($has_position) {
                            $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
                            $columns .= ", position";
                            $values .= ", '$position'";
                            $placeholders .= ", ?";
                            $params[] = $position;
                            $types .= "i";
                        }
                        if ($has_visibility) {
                            // Type cast to integer (0 or 1)
                            $visibility = isset($_POST['visibility']) ? (int)$_POST['visibility'] : 0;
                            // Ensure it's either 0 or 1
                            $visibility = ($visibility == 1) ? 1 : 0;
                            
                            $columns .= ", visibility";
                            $values .= ", $visibility";
                            $placeholders .= ", ?";
                            $params[] = $visibility;
                            $types .= "i";
                        }
                        if (move_uploaded_file($image_tmp, $image_path)) {
                            $insert = "INSERT INTO $table ($columns) VALUES ($values)";
                            $stmt = mysqli_prepare($conn, "INSERT INTO $table ($columns) VALUES ($placeholders)");
                            mysqli_stmt_bind_param($stmt, $types, ...$params);

                            if (mysqli_stmt_execute($stmt)) {
                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                                echo "<script>Swal.fire({icon: 'success', title: 'Success!', text: '" . ($type === 'footer' ? 'Card' : 'Banner') . " added successfully!', timer: 2000, showConfirmButton: false}).then(() => { window.location.href = 'banner.php?type=$type'; });</script>";
                            } else {
                                echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to add " . ($type === 'footer' ? 'card' : 'banner') . "!'});</script>";
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }

                    if (isset($_GET['delete'])) {
                        $banner_id = $_GET['delete'];
                        $delete_query = "DELETE FROM $table WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $delete_query);
                        mysqli_stmt_bind_param($stmt, "i", $banner_id);

                        if (mysqli_stmt_execute($stmt)) {
                            echo "<script>Swal.fire({icon: 'success', title: 'Deleted!', text: 'The " . ($type === 'footer' ? 'card' : 'banner') . " has been deleted.', timer: 1500, showConfirmButton: false}).then(() => { window.location.href = 'banner.php?type=$type'; });</script>";
                        } else {
                            echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to delete the " . ($type === 'footer' ? 'card' : 'banner') . ".'});</script>";
                        }
                        mysqli_stmt_close($stmt);
                    }

                    if (isset($_POST['editBanner'])) {
                        $banner_id = $_POST['banner_id'];
                        $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
                        $link = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['link']));

                        $updates = "title = ?, image_path = ?, link = ?";
                        $params = [$title, null, $link];
                        $types = "sss";

                        if ($_FILES['image']['name']) {
                            $image_name = $_FILES['image']['name'];
                            $image_tmp = $_FILES['image']['tmp_name'];
                            $image_path = "banner/" . mysqli_real_escape_string($conn, $image_name);
                            move_uploaded_file($image_tmp, $image_path);
                            $params[1] = $image_path;
                        } else {
                            $params[1] = $_POST['current_image'] ?? null;
                        }

                        if ($has_position) {
                            $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
                            $updates .= ", position = ?";
                            $params[] = $position;
                            $types .= "i";
                        }
                        if ($has_visibility) {
                            // Type cast to integer (0 or 1)
                            $visibility = isset($_POST['visibility']) ? (int)$_POST['visibility'] : 0;
                            // Ensure it's either 0 or 1
                            $visibility = ($visibility == 1) ? 1 : 0;
                            
                            $updates .= ", visibility = ?";
                            $params[] = $visibility;
                            $types .= "i";
                        }

                        $params[] = $banner_id;
                        $types .= "i";

                        $update_query = "UPDATE $table SET $updates WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $update_query);
                        mysqli_stmt_bind_param($stmt, $types, ...$params);

                        if (mysqli_stmt_execute($stmt)) {
                            echo "<script>Swal.fire({icon: 'success', title: 'Updated!', text: 'The " . ($type === 'footer' ? 'card' : 'banner') . " has been updated.', timer: 1500, showConfirmButton: false}).then(() => { window.location.href = 'banner.php?type=$type'; });</script>";
                        } else {
                            echo "<script>Swal.fire({icon: 'error', title: 'Error!', text: 'Failed to update the " . ($type === 'footer' ? 'card' : 'banner') . ".'});</script>";
                        }
                        mysqli_stmt_close($stmt);
                    }
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            document.querySelectorAll(".viewBannerBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    document.getElementById("viewBannerName").textContent = this.getAttribute("data-title");
                                    document.getElementById("viewBannerImage").src = this.getAttribute("data-image");
                                    document.getElementById("viewBannerLink").textContent = this.getAttribute("data-link");
                                    <?php if ($has_position): ?>
                                        document.getElementById("viewBannerPosition").textContent = this.getAttribute("data-position");
                                    <?php endif; ?>
                                    <?php if ($has_visibility): ?>
                                        document.getElementById("viewBannerVisibility").textContent = this.getAttribute("data-visibility") == "1" ? "Visible" : "Hidden";
                                    <?php endif; ?>
                                    document.getElementById("viewBannerCreatedAt").textContent = this.getAttribute("data-created_at");
                                });
                            });

                            document.querySelectorAll(".editBannerBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    document.getElementById("editBannerId").value = this.getAttribute("data-id");
                                    document.getElementById("editBannerTitle").value = this.getAttribute("data-title");
                                    document.getElementById("editBannerLink").value = this.getAttribute("data-link");
                                    <?php if ($has_position): ?>
                                        document.getElementById("editBannerPosition").value = this.getAttribute("data-position");
                                    <?php endif; ?>
                                    <?php if ($has_visibility): ?>
                                        document.getElementById("editBannerVisibility").value = this.getAttribute("data-visibility");
                                    <?php endif; ?>
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