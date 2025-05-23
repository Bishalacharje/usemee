<?php
include("../connection.php");
session_start();
include("checked-login.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if ID is set
if (!isset($_GET['id'])) {
    die("No ID parameter found.");
}

$id = $_GET['id'];

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to delete this seller?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete-seller.php?confirm_delete=1&id=$id';
            } else {
                window.location.href = 'seller.php';
            }
        });
    });
</script>";

// Execute the delete query only if the user confirmed
if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 1) {
    $query2 = "DELETE FROM `seller` WHERE `id`='$id'";
    $data2 = mysqli_query($conn, $query2);

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '" . ($data2 ? 'Deleted!' : 'Error!') . "',
                text: '" . ($data2 ? 'Seller Deleted Successfully.' : 'Failed to Delete. Please try again.') . "',
                icon: '" . ($data2 ? 'success' : 'error') . "',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'seller.php';
            });
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <?php include("./components/headlink.php"); ?>
    </head>
</head>

<body>

    <!-- JAVASCRIPT -->
    <?php include("./components/footscript.php"); ?>
</body>

</html>