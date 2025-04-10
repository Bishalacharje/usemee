<?php
include("connection.php");
session_start();
include("checked-login.php");

$user_email = $_SESSION['email'];
$queryadmin = "SELECT * FROM `user` WHERE email ='$user_email'";
$dataadmin = mysqli_query($conn, $queryadmin);
$resultadmin = mysqli_fetch_assoc($dataadmin);
$user_id = $resultadmin['id'];

// Fetch cart items
$cartQuery = "SELECT cart.*, product.name AS product_name, product.image 
              FROM cart 
              JOIN product ON product.id = cart.product_id 
              WHERE cart.user_id = '$user_id'";
$cartData = mysqli_query($conn, $cartQuery);
$cartItems = [];
$totalAmount = 0;

while ($row = mysqli_fetch_assoc($cartData)) {
  $row['subtotal'] = $row['price'] * $row['quantity'];
  $totalAmount += $row['subtotal'];
  $cartItems[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("./components/headlink.php"); ?>
  <title>My Cart - eCommerce Website</title>

</head>

<body>
  <div>

    <?php include("./components/header.php"); ?>
  </div>

  <section class="conSection otherPageSection">
    <div class="container">

      <div class="title">
        <div>
          <h2>Cart</h2>
          <p><a href="index.php">Home</a> - <a href="shop.php">Shop</a> - <span>Cart</span> </p>
        </div>
      </div>
      <div class="cartContainer">
        <?php if (count($cartItems) > 0) { ?>
          <table border="0" cellpadding="10" cellspacing="0" width="100%">

            <tbody>
              <?php foreach ($cartItems as $item) { ?>
                <tr>
                  <td>
                    <form action="remove-cart.php" method="POST">
                      <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                      <button type="submit">x</button>
                    </form>
                  </td>
                  <td><img src=" superadmin/<?php echo $item['image']; ?>" />
                  </td>
                  <td>
                    <div class="cartProductName">
                      <h3><?php echo $item['product_name']; ?></h3>
                      <p><?php echo $item['variant_name']; ?></p>
                    </div>

                  </td>
                  <td>&#8377; <?php echo $item['price']; ?></td>

                  <td>
                    <div class="quantity-counter">
                      <button type="button" class="qty-btn minus" data-cart-id="<?php echo $item['id']; ?>">-</button>
                      <input type="number" class="cart-qty-input" data-cart-id="<?php echo $item['id']; ?>"
                        value="<?php echo $item['quantity']; ?>" min="1" max="10" readonly>
                      <button type="button" class="qty-btn plus" data-cart-id="<?php echo $item['id']; ?>">+</button>
                    </div>
                  </td>
                  <td class="cart-subtotal" data-cart-id="<?php echo $item['id']; ?>">
                    &#8377; <?php echo $item['subtotal']; ?>
                  </td>

                </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="cartFooter">
            <h3 class="cartTotal">Cart Totals : <span id="cart-total">&#8377;
                <?php echo $totalAmount; ?></span></h3>



            <a href="checkout.php"><button>Proceed
                to
                Checkout</button></a>
          </div>

        <?php } else { ?>

          <div class="emptyCartCon">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
              type="module"></script>
            <dotlottie-player src="https://lottie.host/d667559a-95f6-4410-8aaf-7fb691e2a332/Pkgx3V3xgL.lottie"
              background="transparent" speed="0.5" style="width: 160px; height: 160px" loop autoplay></dotlottie-player>
            <h2>Your cart is empty!</h2>
            <p>Add items to it now.</p>
            <a href="shop.php"><button>Shop now</button></a>
          </div>
          <p>.</p>
        <?php } ?>
      </div>
    </div>
  </section>


  <?php include("./components/footer.php"); ?>
  <?php include("./components/footscript.php"); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <?php if (isset($_GET['updated']) && $_GET['updated'] == '1') { ?>
    <script>
      toastr.success("Cart updated successfully");
    </script>
  <?php } ?>
  <?php if (isset($_GET['removed']) && $_GET['removed'] == '1') { ?>
    <script>
      toastr.info("Item removed from cart");
    </script>
  <?php } ?>




  <!-- ------------------------       Auto Update cart quantity   ---------------------------------------- -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      function updateCart(cartId, qty, price) {
        $.ajax({
          url: 'update-cart.php',
          type: 'POST',
          data: { cart_id: cartId, quantity: qty },
          success: function (response) {
            if (response.status === 'success') {
              const newSubtotal = price * qty;
              $(`td.cart-subtotal[data-cart-id="${cartId}"]`).html("&#8377; " + newSubtotal.toFixed(2));
              updateCartTotal();
              toastr.success("Cart updated");
            } else {
              toastr.error("Failed to update");
            }
          }
        });
      }

      function updateCartTotal() {
        let total = 0;
        $(".cart-subtotal").each(function () {
          total += parseFloat($(this).text().replace("₹", "").trim());
        });
        $("#cart-total").html("&#8377; " + total.toFixed(2));
      }

      $(".qty-btn").on("click", function () {
        let input = $(this).siblings(".cart-qty-input");
        let cartId = input.data("cart-id");
        let price = parseFloat($(this).closest("tr").find("td:nth-child(4)").text().replace("₹", "").trim());
        let currentValue = parseInt(input.val());
        let min = parseInt(input.attr("min"));
        let max = parseInt(input.attr("max"));
        let newValue = currentValue;

        if ($(this).hasClass("plus") && currentValue < max) {
          newValue = currentValue + 1;
        } else if ($(this).hasClass("minus") && currentValue > min) {
          newValue = currentValue - 1;
        }

        if (newValue !== currentValue) {
          input.val(newValue).trigger("change");
        }
      });

      $(".cart-qty-input").on("change", function () {
        let cartId = $(this).data("cart-id");
        let qty = parseInt($(this).val());
        let price = parseFloat($(this).closest("tr").find("td:nth-child(4)").text().replace("₹", "").trim());
        updateCart(cartId, qty, price);
      });
    });
  </script>







</body>

</html>