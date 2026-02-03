<?php
session_start();
include "includes/db.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-8">

            <?php
            if (isset($_GET['order_id']) && isset($_SESSION['last_order_id']) && $_GET['order_id'] == $_SESSION['last_order_id']) {
                $order_id = $_SESSION['last_order_id'];
                $total = $_SESSION['last_order_total'];
                $query = "SELECT * FROM users WHERE user_id=(SELECT user_id FROM orders WHERE order_id=$order_id)";
                $result = mysqli_query($connection, $query);
                $user = mysqli_fetch_assoc($result);
                ?>
                <h1 class="page-header">
                    Order Confirmation
                    <small>Thank you for your order!</small>
                </h1>
                <div class="alert alert-success">Your order has been placed successfully. A confirmation email has been sent to <?php echo $user['user_email']; ?>.</div>
                <h3>Order #<?php echo $order_id; ?></h3>
                <p><strong>Customer:</strong> <?php 
                $customer_name = trim($user['user_firstname'] . ' ' . $user['user_lastname']);
                echo empty($customer_name) ? $user['user_name'] : $customer_name;
                ?></p>
                <p><strong>Email:</strong> <?php echo $user['user_email']; ?></p>
                <p><strong>Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($_SESSION['last_order_items'] as $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₱<?php echo $item['price']; ?></td>
                                <td>₱<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p class="lead"><strong>Total: ₱<?php echo number_format($total, 2); ?></strong></p>
                <a class="btn btn-primary" href="products.php">Continue Shopping</a>
                <?php
                unset($_SESSION['last_order_id'], $_SESSION['last_order_items'], $_SESSION['last_order_total']);
            } else {
                echo "<h3 class='text-center'>Order not found.</h3>";
                echo "<p class='text-center'><a class='btn btn-primary' href='products.php'>Browse Products</a></p>";
            }
            ?>

        </div>

        <?php
        include "includes/sidebar.php"
        ?>
    </div>
    <!-- /.row -->

    <hr>
    <?php
    /* Page Footer */
    include "includes/footer.php"
    ?>
