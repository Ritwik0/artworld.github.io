<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
include("vendor/autoload.php"); // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($c_email, $otp) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'ritwikshit9@gmail.com'; // SMTP username
        $mail->Password = 'wtkdbxeztbjudfrz'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('ritwikshit9@gmail.com', 'Art World');
        $mail->addAddress($c_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body    = '<h4>Dear ' . $_POST['c_name']. ',</h4><br><br>' .
        'Thank you for registering with Art World To complete your registration, please use the following One-Time Password (OTP):<br><br>' .
        '<h3>Your OTP is: ' . $otp . '</h3><br>' .
        'This OTP is valid for the next 15 minutes. Please enter it on the registration page to finalize your account setup.<br><br>' .
        'If you did not initiate this registration, please ignore this email or contact our support team immediately.<br><br>' .
        'Thank you for choosing Art World.<br><br>' .
        'Best regards,<br>' .
        'The Art World Team';
        $mail->send();
        echo 'OTP has been sent';
    } catch (Exception ) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['submit'])) {
    $c_name = $_POST['c_name'];
    $c_email = filter_var($_POST['c_email'], FILTER_SANITIZE_EMAIL);
    $c_password = $_POST['c_password'];
    $c_confirm_password = $_POST['c_confirm_password'];
    $c_country = $_POST['c_country'];
    $c_city = $_POST['c_city'];
    $c_contact = $_POST['c_contact'];
    $c_address = $_POST['c_address'];
    $c_image = $_FILES['c_image']['name'];
    $c_tmp_image = $_FILES['c_image']['tmp_name'];
    $c_ip = getUserIp();

    // Password validation
    if ($c_password !== $c_confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $c_password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain at least one alphabetic character, one special character, and one numeric character.');</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($c_password, PASSWORD_DEFAULT);

    $otp = rand(100000, 999999);
    $activation_code = md5(rand());

    move_uploaded_file($c_tmp_image, "customer/customer_images/$c_image");

    $insert_customer = "INSERT INTO customers (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_address, customer_image, customer_ip, otp, activation_code, status) VALUES ('$c_name', '$c_email', '$hashed_password', '$c_country', '$c_city', '$c_contact', '$c_address', '$c_image', '$c_ip', '$otp', '$activation_code', 'inactive')";
    $run_customer = mysqli_query($con,$insert_customer);

    if ($run_customer) {
        sendOtpEmail($c_email, $otp);
        $_SESSION['customer_email'] = $c_email;
        $_SESSION['activation_code'] = $activation_code;
        echo "<script>alert('Registration successful! Please check your email for the OTP.')</script>";
        echo "<script>window.open('otp_verification.php', '_self')</script>";
    } else {
        echo "<script>alert('Registration failed! Please try again.')</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reg_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <main>
        <div class="logo mx-auto col-lg-4 col-md-6 col-8 mb-3">
            <img src="somen image/logo/art-World-logo-2.png" class="img-fluid" alt="Logo">
        </div>

        <section>
            <div class="row container-fluid mt-3 mx-auto">
                <div class="main_div col-lg-6 col-md-8 col-11 mx-auto row-gap-5 p-lg-5">
                    <h3 class="text-center">Registration</h3>
                    <hr>
                    <form action="customer_registration.php" method="post" enctype="multipart/form-data" onsubmit="return validatePasswords();">
                        <div class="roup">
                            <label for="c_name">Full Name <i class="fa-solid fa-signature" style="color: #000000;"></i></label>
                            <input type="text" id="c_name" name="c_name" required class="form-control">
                        </div>
                        <div class="">
                            <label for="c_email">Enter Email <i class="fa-regular fa-envelope" style="color: #000000;"></i></label>
                            <input type="email" name="c_email" id="c_email" class="form-control" required>
                        </div>
                        <div class="roup">
                            <label for="c_password">Create Password <i class="fa-sharp fa-solid fa-key" style="color: #000000;"></i></label>
                            <input type="password" name="c_password" id="c_password" class="form-control" required>
                            <div class="wrap-check-1 m-3">
                                <input type="checkbox" id="i"  onclick="myFunction()">
                                
                                <label for="i" class="checkbox">
                                    <div class="checkbox__inner">
                                    <div class="green__ball"></div>
                                    </div>
                                </label>
                                <div class="checkbox__text">
                                    <span>Show Password</span>
                                    <div class="checkbox__text--options">
                                    <span class="off">off</span>
                                    <span class="on">on</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="roup">
                            <label for="c_confirm_password">Confirm Password <i class="fa-sharp fa-solid fa-key" style="color: #000000;"></i></label>
                            <input type="password" name="c_confirm_password" id="c_confirm_password" class="form-control" required>
                        </div>
                        
                        <div class="roup">
                            <label for="c_country">Country <i class="fa-solid fa-earth-asia" style="color: #000000;"></i></label>
                            <input type="text" name="c_country" id="c_country" class="form-control" required>
                        </div>
                        <div class="roup">
                            <label for="c_city">City <i class="fa-solid fa-city" style="color: #000000;"></i></label>
                            <input type="text" name="c_city" id="c_city" class="form-control" required>
                        </div>
                        <div class="roup">
                            <label for="c_contact">Contact Number <i class="fa-solid fa-mobile-retro" style="color: #000000;"></i></label>
                            <input type="number" name="c_contact" id="c_contact" class="form-control" required>
                        </div>
                        <div class="roup">
                            <label for="c_address">Address <i class="fa-solid fa-location-dot" style="color: #000000;"></i></label>
                            <input type="text" name="c_address" id="c_address" class="form-control" required>
                        </div>
                        <div class="roup">
                            <label for="c_image">Image <i class="fa-regular fa-file-image" style="color: #000000;"></i></label>
                            <input type="file" name="c_image" id="c_image" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Register <i class="fa-solid fa-user" style="color: #ffffff;"></i></button>
                        </div>
                        <div class="text-center">
                            <p>Already have an account? <a href="customer/customer_login.php">Sign In</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="js/registration.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzog6jKA+o03KBSjrvzYjzfjSAic9n+gim5Ruj1EwE7k" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuOm9WAX1yogE2Xo42tfjJwZ7R0VLTxCk8e0g8DMXq1hD99Yz0uXWJXr2PzK2F9T" crossorigin="anonymous"></script>
    <script>
        function myFunction() {
            var x = document.getElementById("c_password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function validatePasswords() {
            var password = document.getElementById('c_password').value;
            var confirmPassword = document.getElementById('c_confirm_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }

            var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                alert('Password must be at least 8 characters long and contain at least one alphabetic character, one special character, and one numeric character.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
