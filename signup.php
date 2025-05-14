<?php
error_reporting(0);
include("connection.php");
session_start();

$old_name = $old_phone = $old_email = '';
$errors = [];

if (isset($_POST['submit'])) {
    // Retain old values
    $old_name  = htmlspecialchars($_POST['name']);
    $old_phone = htmlspecialchars($_POST['phone']);
    $old_email = htmlspecialchars($_POST['email']);
    $password  = $_POST['password'];

    // Sanitize inputs
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Password validation
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $minLength = strlen($password) >= 8;

    if (!$uppercase) {
        $errors[] = "Password must include at least one uppercase letter.";
    }
    if (!$lowercase) {
        $errors[] = "Password must include at least one lowercase letter.";
    }
    if (!$number) {
        $errors[] = "Password must include at least one number.";
    }
    if (!$specialChars) {
        $errors[] = "Password must include at least one special character.";
    }
    if (!$minLength) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $query2 = "INSERT INTO `user`(`name`, `phone`, `email`, `password`) VALUES ('$name','$phone','$email','$hashedPassword')";
        $data2 = mysqli_query($conn, $query2);

        if ($data2) {
            $_SESSION['email'] = $email;
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Account Created',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
        } else {
            $errorMessage = mysqli_error($conn);
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create user. Error: $errorMessage',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Usemee - Signup</title>
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
            <div class="signupCon">
                <form class="form-horizontal mt-3" method="post">
                    <div class="formGrid grid1">
                        <div>
                            <input type="text" required name="name" placeholder="Full name*" value="<?= $old_name ?>">
                        </div>
                        <input type="tel" required name="phone" placeholder="Phone Number*" 
                        value="<?= $old_phone ?>" pattern="\d{10}" title="Enter exactly 10 digits">
                        <div>
                            <input type="email" required name="email" placeholder="Email*" value="<?= $old_email ?>">
                        </div>
                        <div style="position: relative;">
                        <label for="password">For Strong Password 
                            <span id="inline-rules" style="font-size: 12px; color: green; margin-left: 10px;">
                            [ Min 8 - chars, A, z, 0-9, @#$% ]
                            </span>
                        </label>
                        <input type="password" required name="password" id="password" placeholder="Password">
                        </div>

                        <div class="chekboxCon">
                            <input type="checkbox" id="c1" required>
                            <label for="c1">
                                I consent to Usemee processing my personal data to send me personalized marketing content,
                                in accordance with the consent form and privacy policy.
                            </label>
                        </div>

                        <div class="chekboxCon">
                            <input type="checkbox" id="c2" required>
                            <label for="c2">
                                By clicking "create account", I consent to the privacy policy.
                            </label>
                        </div>

                        <button class="btn btn-info w-100 waves-effect waves-light" name="submit" type="submit">Sign Up</button>
                    </div>
                </form>

                <div class="signUpFooter">
                    <p>By creating an account, you agree to our:</p>
                    <div class="signupFlex">
                        <a href="#">Terms & Condition</a> |
                        <a href="#">Privacy Policy</a>
                    </div>
                    <a href="login.php">ALREADY HAVE AN ACCOUNT ? LOG IN</a>
                </div>
            </div>
        </div>
    </section>

    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>
    


    <script>
  const passwordInput = document.getElementById("password");
  const ruleSpan = document.getElementById("inline-rules");

  passwordInput.addEventListener("input", () => {
    const value = passwordInput.value;

    const rules = [];

    if (!/.{8,}/.test(value)) rules.push("8+ chars");
    if (!/[A-Z]/.test(value)) rules.push("A");
    if (!/[a-z]/.test(value)) rules.push("z");
    if (!/[0-9]/.test(value)) rules.push("0-9");
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(value)) rules.push("@#$%");

    if (rules.length === 0) {
      ruleSpan.innerHTML = "[ ✅ strong ]";
      ruleSpan.style.color = "green";
    } else {
      ruleSpan.innerHTML = `{ ${rules.join(', ')} }`;
      ruleSpan.style.color = "red";
    }
  });
</script>


</body>

</html>
