<?php
error_reporting(0);
session_start();
include("connection.php");

$old_name  = '';
$old_phone = '';
$old_email = '';
$errors    = [];

// Handle form submission
if (isset($_POST['submit'])) {
    // Keep typed values for redisplay on error
    $old_name  = htmlspecialchars($_POST['name']);
    $old_phone = htmlspecialchars($_POST['phone']);
    $old_email = htmlspecialchars($_POST['email']);
    $password  = $_POST['password'];

    // Sanitize inputs
    $name     = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
    $phone    = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
    $email    = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Password validation
    if (!preg_match('@[A-Z]@', $password))   $errors[] = "Password must include at least one uppercase letter.";
    if (!preg_match('@[a-z]@', $password))   $errors[] = "Password must include at least one lowercase letter.";
    if (!preg_match('@[0-9]@', $password))   $errors[] = "Password must include at least one number.";
    if (!preg_match('@[^\w]@', $password))   $errors[] = "Password must include at least one special character.";
    if (strlen($password) < 8)               $errors[] = "Password must be at least 8 characters long.";

    // If validation passed, insert user
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql    = "INSERT INTO `user` (`name`,`phone`,`email`,`password`)
                   VALUES ('$name','$phone','$email','$hashed')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['signup_success'] = true;
            $_SESSION['email']          = $email;
        } else {
            $_SESSION['signup_error'] = mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./components/headlink.php"); ?>
    <title>Signup | Usemee</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div><?php include("./components/header.php"); ?></div>

  <section class="conSection otherPageSection loginSection">
    <div class="container">
      <div class="loginTitle">
        <h2>Sign Up</h2>
        <h2>To Your Account</h2>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
      </div>
      <div class="signupCon scrollToReveal">
        <form class="form-horizontal mt-3" method="post">
          <div class="formGrid grid1">
            <input type="text" name="name" required placeholder="Full name*" value="<?= $old_name ?>">
            <input type="tel" name="phone" required placeholder="Phone Number*" 
                   pattern="\d{10}" title="Enter exactly 10 digits" value="<?= $old_phone ?>">
            <input type="email" name="email" required placeholder="Email*" value="<?= $old_email ?>">
            <div style="position: relative;">
              <label for="password">Strong Password 
                <span id="inline-rules" style="font-size:12px;color:green;margin-left:10px;">
                  [ Min 8 chars, A–Z, a–z, 0–9, @#$% ]
                </span>
              </label>
              <input type="password" name="password" id="password" required placeholder="Password">
            </div>
            <div class="chekboxCon">
              <input type="checkbox" id="c1" required>
              <label for="c1">I consent to marketing per privacy policy.</label>
            </div>
            <div class="chekboxCon">
              <input type="checkbox" id="c2" required>
              <label for="c2">I consent to privacy policy.</label>
            </div>
            <button class="btn btn-info w-100" name="submit" type="submit">
              Sign Up
            </button>
          </div>
        </form>

        <!-- Show server-side validation errors if any -->
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger mt-3">
            <ul>
              <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="signUpFooter">
          <p>By creating an account, you agree to our:</p>
          <div class="signupFlex">
            <a href="#">Terms & Condition</a> |
            <a href="#">Privacy Policy</a>
          </div>
          <a href="login.php">ALREADY HAVE AN ACCOUNT? LOG IN</a>
        </div>
      </div>
    </div>
  </section>

  <?php include("./components/footer.php"); ?>
  <?php include("./components/footscript.php"); ?>

  <!-- Inline password-strength JS (unchanged) -->
  <script>
    const passwordInput = document.getElementById("password");
    const ruleSpan = document.getElementById("inline-rules");
    passwordInput.addEventListener("input", () => {
      const v = passwordInput.value, r = [];
      if (!/.{8,}/.test(v))     r.push("8+ chars");
      if (!/[A-Z]/.test(v))     r.push("A");
      if (!/[a-z]/.test(v))     r.push("z");
      if (!/[0-9]/.test(v))     r.push("0-9");
      if (!/[!@#$%^&*(),.?\":{}|<>]/.test(v)) r.push("@#$%");
      if (r.length === 0) {
        ruleSpan.textContent = "[ ✅ strong ]";
        ruleSpan.style.color = "green";
      } else {
        ruleSpan.textContent = `{ ${r.join(", ")} }`;
        ruleSpan.style.color = "red";
      }
    });
  </script>

  <!-- SweetAlert2 (fires only after body exists) -->
  <?php if (isset($_SESSION['signup_success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
          target: 'body',
          icon: 'success',
          title: 'Account Created',
          showConfirmButton: false,
          timer: 2000
        }).then(() => {
          window.location.href = 'index.php';
        });
      });
    </script>
    <?php unset($_SESSION['signup_success']); ?>
  <?php elseif (isset($_SESSION['signup_error'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
          target: 'body',
          icon: 'error',
          title: 'Signup Failed',
          text: <?= json_encode($_SESSION['signup_error']) ?>,
          confirmButtonText: 'OK'
        });
      });
    </script>
    <?php unset($_SESSION['signup_error']); ?>
  <?php endif; ?>
</body>
</html>
