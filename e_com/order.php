<?php
session_start();
include("includes/db.php");
include("functions/functions.php");

if (isset($_GET['c_id'])) {
    $customer_id = $_GET['c_id'];
	
}

// Fetch customer details
$get_customer = "SELECT customer_name FROM customers WHERE customer_id='$customer_id'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);
$customer_name = $row_customer['customer_name'];

$ip_add = getUserIp();
$status = "pending";
$invoice_no = mt_rand();

$select_cart = "SELECT * FROM cart WHERE ip_add='$ip_add'";
$run_cart = mysqli_query($con, $select_cart);

while ($row_cart = mysqli_fetch_array($run_cart)) {
    $pro_id = $row_cart['p_id'];
    $ref_no = $row_cart['ref_no'];
    $qty = $row_cart['qty'];

    $get_product = "SELECT * FROM products WHERE product_id='$pro_id'";
    $run_pro = mysqli_query($con, $get_product);

    while ($row_pro = mysqli_fetch_array($run_pro)) {
        $sub_total = $row_pro['product_price'] * $qty;

        $insert_customer_order = "INSERT INTO customer_order (customer_id,customer_name, product_id, due_amount, invoice_no, qty, ref_no, order_date, order_status) VALUES ('$customer_id','$customer_name','$pro_id','$sub_total','$invoice_no','$qty','$ref_no',NOW(),'$status')";
        $run_cust_order = mysqli_query($con, $insert_customer_order);

        // Optionally, insert into pending order table if needed
        // $insert_pending_order = "INSERT INTO pending_order (customer_id,invoice_no,product_id,qty,size,order_status) VALUES ('$customer_id','$invoice_no','$pro_id','$qty','$size','$status')";
        // $run_pending_order = mysqli_query($con, $insert_pending_order);

        $delete_cart = "DELETE FROM cart WHERE ip_add='$ip_add'";
        $run_del = mysqli_query($con, $delete_cart);

        echo "<script>alert('Your order has been submitted, Thanks $customer_name')</script>";
        echo "<script>window.open('customer/my_account.php?my_order','_self')</script>";
    }
}
