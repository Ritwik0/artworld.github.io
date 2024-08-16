<?php

session_start();
if (isset($_SESSION['customer_name'])) {
  // Clear the cart data
  unset($_SESSION['cart']); 
session_unset();
session_destroy();
echo "<script>alert('Logout Succesfully')</script>";
echo "<script>window.open('index.php','_self')</script>";
exit();
}else{
  echo "<script>window.open('index.php','_self')</script>";
  exit();
}
  ?>