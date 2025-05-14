<?php
include("../connection.php");
header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : 'header';
$table = '';
$has_position = false;
$has_visibility = false;

switch ($type) {
    case 'header':
        $table = 'header_banners';
        $has_visibility = true;
        break;
    case 'center':
        $table = 'center_cards';
        $has_position = true;
        $has_visibility = true;
        break;
    case 'footer':
        $table = 'footer_cards';
        break;
    default:
        die(json_encode(['success' => false, 'message' => 'Invalid banner type']));
}

// Function to validate and move uploaded image
function handleImageUpload($file, $conn) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $max_size || !in_array($file['type'], $allowed_types)) {
        return false;
    }
    $image_name = mysqli_real_escape_string($conn, $file['name']);
    $image_path = "banner/" . $image_name;
    if (move_uploaded_file($file['tmp_name'], $image_path)) {
        return $image_path;
    }
    return false;
}

// Add Banner
if (isset($_POST['addBanner'])) {
    $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
    $link = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['link'] ?? ''));
    $image_path = handleImageUpload($_FILES['image'], $conn);

    if (!$image_path) {
        echo json_encode(['success' => false, 'message' => 'Invalid or failed image upload']);
        exit;
    }

    $columns = "title, image_path, link";
    $placeholders = "?, ?, ?";
    $params = [$title, $image_path, $link];
    $types = "sss";

    if ($has_position) {
        $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
        $columns .= ", position";
        $placeholders .= ", ?";
        $params[] = $position;
        $types .= "i";
    }
    if ($has_visibility) {
        $visibility = isset($_POST['visibility']) ? (int)$_POST['visibility'] : 0;
        $visibility = ($visibility == 1) ? 1 : 0;
        $columns .= ", visibility";
        $placeholders .= ", ?";
        $params[] = $visibility;
        $types .= "i";
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO $table ($columns) VALUES ($placeholders)");
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        $id = mysqli_insert_id($conn);
        $data = [
            'id' => $id,
            'title' => $title,
            'image_path' => $image_path,
            'link' => $link,
            'position' => $has_position ? $position : null,
            'visibility' => $has_visibility ? $visibility : null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add ' . ($type === 'footer' ? 'card' : 'banner')]);
    }
    mysqli_stmt_close($stmt);
    exit;
}

// Edit Banner
if (isset($_POST['editBanner'])) {
    $banner_id = (int)$_POST['banner_id'];
    $title = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['title']));
    $link = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['link'] ?? ''));

    // Fetch current banner data
    $stmt = mysqli_prepare($conn, "SELECT * FROM $table WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $banner_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $current = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$current) {
        echo json_encode(['success' => false, 'message' => 'Banner not found']);
        exit;
    }

    $updates = [];
    $params = [];
    $types = "";

    // Update title if provided
    if ($title !== $current['title']) {
        $updates[] = "title = ?";
        $params[] = $title;
        $types .= "s";
    }

    // Update link if provided
    if ($link !== $current['link']) {
        $updates[] = "link = ?";
        $params[] = $link;
        $types .= "s";
    }

    // Update image if uploaded
    $image_path = $current['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $image_path = handleImageUpload($_FILES['image'], $conn);
        if ($image_path) {
            $updates[] = "image_path = ?";
            $params[] = $image_path;
            $types .= "s";
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or failed image upload']);
            exit;
        }
    }

    // Update position if applicable
    if ($has_position) {
        $position = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['position']));
        if ($position !== $current['position']) {
            $updates[] = "position = ?";
            $params[] = $position;
            $types .= "i";
        }
    }

    // Update visibility if applicable
    if ($has_visibility) {
        $visibility = isset($_POST['visibility']) ? (int)$_POST['visibility'] : 0;
        $visibility = ($visibility == 1) ? 1 : 0;
        if ($visibility !== $current['visibility']) {
            $updates[] = "visibility = ?";
            $params[] = $visibility;
            $types .= "i";
        }
    }

    if (empty($updates)) {
        echo json_encode(['success' => true, 'data' => $current]);
        exit;
    }

    $params[] = $banner_id;
    $types .= "i";
    $update_query = "UPDATE $table SET " . implode(", ", $updates) . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        $data = [
            'id' => $banner_id,
            'title' => $title,
            'image_path' => $image_path,
            'link' => $link,
            'position' => $has_position ? $position : null,
            'visibility' => $has_visibility ? $visibility : null,
            'created_at' => $current['created_at']
        ];
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update ' . ($type === 'footer' ? 'card' : 'banner')]);
    }
    mysqli_stmt_close($stmt);
    exit;
}

// Delete Banner
if (isset($_GET['delete'])) {
    $banner_id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM $table WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $banner_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete ' . ($type === 'footer' ? 'card' : 'banner')]);
    }
    mysqli_stmt_close($stmt);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>