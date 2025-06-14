<?php
error_reporting(0);
include("connection.php");
include("enc_dec.php");
session_start();
include("checked-login.php");

// Initialize message variables
$success_message = "";
$error_message = "";

// Check for session success message
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    // Get form data
    $user_id = $_POST['uid'];
    $name = trim($_POST['name']);
    $phone_no = trim($_POST['phone_no']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Validation
    $errors = array();
    
    // Validate name
    if (empty($name)) {
        $errors[] = "Full name is required";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long";
    }
    
    // Validate phone number
    if (empty($phone_no)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        $errors[] = "Phone number must be exactly 10 digits";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate message
    if (empty($message)) {
        $errors[] = "Message is required";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long";
    }
    
    // If no errors, insert into database using prepared statement
    if (empty($errors)) {
        $query = "INSERT INTO `contact_us` (`user_id`, `name`, `email`, `phone_no`, `message`, `status`, `created_at`) 
                  VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $user_id, $name, $email, $phone_no, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Thank you for contacting us! We will get back to you soon.";
            $_POST = array(); // Clear POST data
            header("Location: contact.php"); // Redirect to prevent resubmission
            exit();
        } else {
            $error_message = "Sorry, there was an error submitting your message. Please try again.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = implode("<br>", $errors);
    }
}

// Set form values (either from session or from failed submission)
$form_name = isset($_POST['name']) ? $_POST['name'] : $user_name;
$form_phone = isset($_POST['phone_no']) ? $_POST['phone_no'] : $user_phone;
$form_email = isset($_POST['email']) ? $_POST['email'] : $user_email;
$form_message = isset($_POST['message']) ? $_POST['message'] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/headlink.php"); ?>
    <title>Contact | Usemee - Your one-stop online store for all your shopping needs!</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .alert-success {
            background-color: #4CAF50;
        }
        .alert-error {
            background-color: #f44336;
        }
        .close-btn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .close-btn:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <?php include("empty-address.php"); ?>
    <!-- HEADER -->
    <div><?php include("./components/header.php"); ?></div>

    <section class="conSection otherPageSection contactPage">
        <div class="container">
            <p class="bradCrumb"><a href="index.php">Home</a> - <span>Contact Us</span>
            <h1>Contact Us <span>.</span></h1>
            <div class="contactGrid scrollToReveal">
                <div class="contactBox">
                    <img src="assets/images/imgicon/letter.png" alt="">
                    <h3>Email Address</h3>
                    <p>info@webmail.com</p>
                    <p>jobs@webexample.com</p>
                </div>
                <div class="contactBox">
                    <img src="assets/images/imgicon/support.png" alt="">
                    <h3>Phone Number</h3>
                    <p>+0123-456789</p>
                    <p>+987-6543210</p>
                </div>
                <div class="contactBox">
                    <img src="assets/images/imgicon/address.png" alt="">
                    <h3>Office Address</h3>
                    <p>18/A, New Born Town Hall</p>
                    <p>New York, US</p>
                </div>
            </div>
        </div>
    </section>

    <section class="conSection otherPageSection">
        <div class="container">
            <div class="contactFormBox scrollToReveal">
                <h3>Get a Quote</h3>
                
                <!-- Success/Error Messages -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success" id="success-alert">
                        <?php echo $success_message; ?>
                        <span class="close-btn" onclick="this.parentElement.style.display='none';">×</span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error" id="error-alert">
                        <?php echo $error_message; ?>
                        <span class="close-btn" onclick="this.parentElement.style.display='none';">×</span>
                    </div>
                <?php endif; ?>

                <form action="" method="post" id="contactForm">
                    <input type="hidden" value="<?php echo $userid; ?>" name="uid">
                    
                    <div class="formGrid grid1">
                        <div>
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($form_name); ?>" required>
                        </div>
                    </div>
                    
                    <div class="formGrid">
                        <div class="inputCon">
                            <label>Phone No <span id="pin-guidelines"
                                    style="font-size: 12px; color: green; margin-left: 10px;">[ Must be exactly 10
                                    digits ]</span></label>
                            <input type="number" name="phone_no" id="phone_no" value="<?php echo htmlspecialchars($form_phone); ?>"
                                pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($form_email); ?>" required>
                        </div>
                    </div>
                    
                    <div class="formGrid grid1">
                        <div>
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="5" placeholder="Please enter your message here..." required><?php echo htmlspecialchars($form_message); ?></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" name="submit_contact">Send Message</button>
                </form>
            </div>
        </div>
    </section>
    <br><br>

    <!-- FOOTER -->
    <?php include("./components/footer.php"); ?>
    <?php include("./components/footscript.php"); ?>

    <script>
        // Auto-hide success message and reset form after 5 seconds
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.style.display = 'none';
                document.getElementById('contactForm').reset(); // Reset the form
            }
        }, 5000);

        // Phone number validation
        document.getElementById('phone_no').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });

        // Form submission validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            var phone = document.getElementById('phone_no').value;
            var message = document.getElementById('message').value;
            
            if (phone.length !== 10) {
                alert('Phone number must be exactly 10 digits');
                e.preventDefault();
                return false;
            }
            
            if (message.length < 10) {
                alert('Message must be at least 10 characters long');
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>

</html>