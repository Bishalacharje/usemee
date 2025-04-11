<?php
include("../connection.php");
session_start();
include("checked-login.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    die("No ID parameter found.");
}

$id = $_GET['id'];

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

echo "<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to mark this order as Out for Delivery?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'out-for-delivery.php?confirm_update=1&id=$id';
        } else {
            window.location.href = 'today_order.php?status=Packed';
        }
    });
});
</script>";

// If confirmed, update the order
if (isset($_GET['confirm_update']) && $_GET['confirm_update'] == 1) {
    $query = "UPDATE `orders` SET `status`='Out for Delivery', `out_for_delivery_date`=NOW() WHERE `id`='$id'";
    $result = mysqli_query($conn, $query);

    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '" . ($result ? 'Success!' : 'Error!') . "',
            text: '" . ($result ? 'Order marked as Out for Delivery.' : 'Update failed. Please try again.') . "',
            icon: '" . ($result ? 'success' : 'error') . "',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'today_order.php?status=Packed';
        });
    });
    </script>";
}
?>