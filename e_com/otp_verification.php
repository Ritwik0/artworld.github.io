<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['customer_email']) || !isset($_SESSION['activation_code'])) {
    echo "<script>window.open('index.php', '_self')</script>";
    exit();
}
$c_ip = $_SERVER['REMOTE_ADDR']; // or however you get the client's IP address

// Check the cart
$sel_cart = "SELECT * FROM cart WHERE ip_add='$c_ip'";
$run_cart = mysqli_query($con, $sel_cart);
$check_cart = mysqli_num_rows($run_cart);
if ($check_cart > 0) {
    // You can add additional code here if needed, e.g., log the event or update session variables
}

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $activation_code = $_SESSION['activation_code'];
    $c_email = $_SESSION['customer_email'];

    $select_customer = "SELECT * FROM customers WHERE customer_email='$c_email' AND otp='$otp' AND activation_code='$activation_code' AND status='inactive'";
    $run_customer = mysqli_query($con, $select_customer);
    $check_customer = mysqli_num_rows($run_customer);

    if ($check_customer > 0) {
        $update_customer = "UPDATE customers SET status='active' WHERE customer_email='$c_email'";
        $run_update = mysqli_query($con, $update_customer);

        if ($run_update) {
          $select_customer_name = "SELECT customer_name FROM customers WHERE customer_email='$c_email'";
            $run_customer_name = mysqli_query($con, $select_customer_name);
            $row_customer_name = mysqli_fetch_assoc($run_customer_name);
            $customer_name = $row_customer_name['customer_name'];
            unset($_SESSION['activation_code']);
            
            echo "<script>alert('Your account has been activated!')</script>";
            echo "<script>window.open('customer/customer_login.php', '_self')</script>";
        } else {
            echo "<script>alert('Account activation failed! Please try again.')</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP! Please try again.')</script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <style>
        body{
            background-image: url('otp-background.png');
            background-size: cover;
            width: 100vw;
            height: 100vh;
        }
        
        h3{
            text-transform: capitalize;
        }
        main {
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo{
            margin-top: 10px;
            height: 300px;
            filter: drop-shadow(10px 10px 4px #4444dd);
        }
        .otp_cont {
            color: white;
            height: 300px;
            max-width: 400px;
            width: 100%;
            border: 2px solid rgba(0, 0, 0, 0.632);
            padding: 20px;
            background-color: rgba(48, 45, 45, 0.455);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            box-shadow: 20px 20px 30px #2f2f35;
        }
        .otp_cont input, .otp_cont button {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
        }
        .otp_cont button {
            border: none;
            /* background-color: #007bff; */
            color: white;
            border-radius: 4px;
        }
        .form-label{
            font-size: 1.2rem;
        }

        /* button */
        .btn-76,
.btn-76 *,
.btn-76 :after,
.btn-76 :before,
.btn-76:after,
.btn-76:before {
  border: 0 solid;
  box-sizing: border-box;
}
.btn-76 {
  -webkit-tap-highlight-color: transparent;
  -webkit-appearance: button;
  background-color: transparent;
  background-image: none;
  color: #fff;
  cursor: pointer;
  font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
    Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
    Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
  font-size: 100%;
  line-height: 1.5;
  margin: 0;
  -webkit-mask-image: -webkit-radial-gradient(#000, #fff);
  padding: 0;
}
.btn-76:disabled {
  cursor: default;
}
.btn-76:-moz-focusring {
  outline: auto;
}
.btn-76 svg {
  display: block;
  vertical-align: middle;
}
.btn-76 [hidden] {
  display: none;
}
.btn-76 {
  --neon: #2089f9e0;
  box-sizing: border-box;
  display: block;
  font-weight: 900;
  -webkit-mask-image: none;
  outline: 4px solid #fff;
  outline-offset: -4px;
  overflow: hidden;
  padding: 1.2rem 3rem;
  position: relative;
  text-transform: uppercase;
  transition: 0.2s linear 0.1s;
}
.btn-76:hover {
  background: var(--neon);
  box-shadow: 0 0 5px var(--neon), 0 0 25px var(--neon), 0 0 50px var(--neon),
    0 0 100px var(--neon);
  color: #fff;
  outline-color: transparent;
  transition: 0.1s linear 0.3s;
}
.btn-76 span {
  display: block;
  inset: 0;
  position: absolute;
}
.btn-76 .top {
  border-top: 4px solid var(--neon);
  opacity: 0;
  transform: translateX(calc(-100% + var(--progress, 0%)));
  transition: none;
}
.btn-76:hover .top {
  --progress: 100%;
  opacity: 1;
  transition: transform 0.1s linear;
}
.btn-76 .right {
  border-right: 4px solid var(--neon);
  opacity: 0;
  transform: translateY(calc(-100% + var(--progress, 0%)));
  transition: none;
}
.btn-76:hover .right {
  --progress: 100%;
  opacity: 1;
  transition: transform 0.1s linear 0.2s;
}
.btn-76 .bottom {
  border-bottom: 4px solid var(--neon);
  opacity: 0;
  transform: translateX(calc(100% - var(--progress, 0%)));
  transition: none;
}
.btn-76:hover .bottom {
  --progress: 100%;
  opacity: 1;
  transition: transform 0.1s linear 0.4s;
}
.btn-76 .left {
  border-left: 4px solid var(--neon);
  opacity: 0;
  transform: translateY(calc(100% - var(--progress, 0%)));
  transition: none;
}
.btn-76:hover .left {
  --progress: 100%;
  opacity: 1;
  transition: transform 0.1s linear 0.6s;
}

    </style>    
  </head>
  <body>
   
    <main>
        <img src="art owrld logo (3).png" alt="LOGO" class="img-fluid logo mx-auto">
        
        <br>
       
        <div class="otp_cont text-center">
            <h3>OTP VERIFICATION</h3>
            <form action="otp_verification.php" method="post">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                </div>
                <button class="btn-76 btn mt-5" type="submit" name="verify">
                    verify
                    <span class="top"></span>
                    <span class="right"></span>
                    <span class="bottom"></span>
                    <span class="left"></span>
                </button>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>

