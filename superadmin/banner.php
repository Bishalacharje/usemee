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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <table class="table table-bordered" id="bannerTable">
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
                                    echo "<tr data-id='{$row['id']}'>";
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
                                            <button class='btn btn-danger btn-sm deleteBannerBtn' data-id='{$row['id']}'>Delete</button>
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
                                <form id="addBannerForm" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                                                        <option value="1">Visible</option>
                                                        <option value="0">Hidden</option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-dark">Add <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></button>
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
                                <form id="editBannerForm" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                                                <label class="form-label">Image (optional)</label>
                                                <input type="file" class="form-control" name="image" accept="image/*">
                                                <small class="form-text text-muted">Leave blank to keep current image.</small>
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
                                        <button type="submit" class="btn btn-dark">Update <?php echo $type === 'footer' ? 'Card' : 'Banner'; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Common SweetAlert2 configuration
                        const swalConfig = {
                            icon: 'success',
                            title: 'Success!',
                            timer: 1500,
                            showConfirmButton: false
                        };

                        // Function to refresh table row
                        function refreshTableRow(data) {
                            const row = document.querySelector(`#bannerTable tr[data-id='${data.id}']`) || document.createElement('tr');
                            row.setAttribute('data-id', data.id);
                            row.innerHTML = `
                                <td>${document.querySelectorAll('#bannerTable tbody tr').length + 1}</td>
                                <td>${data.title}</td>
                                <td><img src='${data.image_path}' style='max-width:100px;'></td>
                                <td>${data.link}</td>
                                <?php if ($has_position): ?>
                                    <td>${data.position}</td>
                                <?php endif; ?>
                                <?php if ($has_visibility): ?>
                                    <td>${data.visibility == 1 ? 'Visible' : 'Hidden'}</td>
                                <?php endif; ?>
                                <td>${data.created_at}</td>
                                <td>
                                    <button class='btn btn-info btn-sm viewBannerBtn' data-bs-toggle='modal' data-bs-target='#viewBannerModal'
                                        data-id='${data.id}'
                                        data-title='${data.title}'
                                        data-image='${data.image_path}'
                                        data-link='${data.link}'
                                        <?php if ($has_position): ?>data-position='${data.position}'<?php endif; ?>
                                        <?php if ($has_visibility): ?>data-visibility='${data.visibility}'<?php endif; ?>
                                        data-created_at='${data.created_at}'>View</button>
                                    <button class='btn btn-warning btn-sm editBannerBtn' data-bs-toggle='modal' data-bs-target='#editBannerModal'
                                        data-id='${data.id}'
                                        data-title='${data.title}'
                                        data-image='${data.image_path}'
                                        data-link='${data.link}'
                                        <?php if ($has_position): ?>data-position='${data.position}'<?php endif; ?>
                                        <?php if ($has_visibility): ?>data-visibility='${data.visibility}'<?php endif; ?>
                                        data-created_at='${data.created_at}'>Edit</button>
                                    <button class='btn btn-danger btn-sm deleteBannerBtn' data-id='${data.id}'>Delete</button>
                                </td>`;
                            if (!row.parentNode) {
                                document.querySelector('#bannerTable tbody').prepend(row);
                            }
                            attachButtonListeners();
                        }

                        // Function to attach event listeners
                        function attachButtonListeners() {
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

                            document.querySelectorAll(".deleteBannerBtn").forEach(button => {
                                button.addEventListener("click", function () {
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "You won't be able to revert this!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, delete it!'
                                    }).then(result => {
                                        if (result.isConfirmed) {
                                            fetch(`banner_action.php?type=<?php echo $type; ?>&delete=${this.getAttribute('data-id')}`, {
                                                method: "POST"
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    Swal.fire({ ...swalConfig, text: '<?php echo $type === 'footer' ? 'Card' : 'Banner'; ?> deleted successfully!' });
                                                    document.querySelector(`#bannerTable tr[data-id='${this.getAttribute('data-id')}']`).remove();
                                                } else {
                                                    Swal.fire({ icon: 'error', title: 'Error!', text: data.message });
                                                }
                                            })
                                            .catch(() => Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' }));
                                        }
                                    });
                                });
                            });
                        }

                        // Initial attachment of event listeners
                        document.addEventListener("DOMContentLoaded", attachButtonListeners);

                        // Add banner via AJAX
                        document.getElementById("addBannerForm").addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            formData.append("addBanner", true);
                            fetch("banner_action.php?type=<?php echo $type; ?>", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({ ...swalConfig, text: '<?php echo $type === 'footer' ? 'Card' : 'Banner'; ?> added successfully!' });
                                    refreshTableRow(data.data);
                                    bootstrap.Modal.getInstance(document.getElementById('addBannerModal')).hide();
                                    this.reset();
                                    this.classList.remove('was-validated');
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Error!', text: data.message });
                                }
                            })
                            .catch(() => Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' }));
                        });

                        // Edit banner via AJAX
                        document.getElementById("editBannerForm").addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            formData.append("editBanner", true);
                            fetch("banner_action.php?type=<?php echo $type; ?>", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({ ...swalConfig, text: '<?php echo $type === 'footer' ? 'Card' : 'Banner'; ?> updated successfully!' });
                                    refreshTableRow(data.data);
                                    bootstrap.Modal.getInstance(document.getElementById('editBannerModal')).hide();
                                    this.reset();
                                    this.classList.remove('was-validated');
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Error!', text: data.message });
                                }
                            })
                            .catch(() => Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!' }));
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