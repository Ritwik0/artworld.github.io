<div class="trx">
    <center>
    <h1>My Order</h1>
    <p>Shipping and additional costs are calculated based on the values you have entered</p>
    </center>
    <hr>
    <div class="tae-responve">
        <table class="tab">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Customer Name</th> <!-- New column header -->
                    <th>Due Amount</th>
                    <th>Invoice Number</th>
                    <th>Quantity</th>
                    <th>Ref No</th>
                    <th>Order Date</th>
                    <th>Paid/Unpaid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $customer_session= $_SESSION['customer_name'];
                $get_customer="SELECT * FROM customers WHERE customer_name='$customer_session'";
                $run_cust=mysqli_query($con, $get_customer);
                $row_cust=mysqli_fetch_array($run_cust);
                $customer_id=$row_cust['customer_id'];
                $customer_name=$row_cust['customer_name']; // Fetch customer name
                $get_order="SELECT * FROM customer_order WHERE customer_id='$customer_id'";
                $run_order=mysqli_query($con, $get_order);
                $i=0;
                while ($row_order=mysqli_fetch_array($run_order)) {
                    $order_id=$row_order['order_id'];
                    $order_due_amount=$row_order['due_amount'];
                    $order_invoice=$row_order['invoice_no'];
                    $order_qty=$row_order['qty'];
                    $order_ref=$row_order['ref_no'];
                    $order_date=substr($row_order['order_date'], 0, 11);
                    $order_status=$row_order['order_status'];
                    $i++;
                    if ($order_status == 'pending') {
                        $order_status_display = 'Unpaid';
                        $button_text = 'Confirm Now';
                    } else {
                        $order_status_display = 'Paid';
                        $button_text = 'Completed';
                    }
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $customer_name ?></td> <!-- Display customer name -->
                    <td><?php echo $order_due_amount ?></td>
                    <td><?php echo $order_invoice ?></td>
                    <td><?php echo $order_qty ?></td>
                    <td><?php echo $order_ref ?></td>
                    <td><?php echo $order_date ?></td>
                    <td><?php echo $order_status_display ?></td>
                    <td><a href="confirm.php?order_id=<?php echo $order_id ?>" target="_blank" class="btn btn-primary btn-sm"><?php echo $button_text ?></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
