<?php
if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int) $_GET['order_id'];

// Fetch order with customer info
$order_query = "SELECT o.order_id, o.user_id, o.total_amount, o.order_date, o.status,
                u.user_firstname, u.user_lastname, u.user_email, u.user_name
                FROM orders o
                INNER JOIN users u ON o.user_id = u.user_id
                WHERE o.order_id = $order_id";
$order_result = mysqli_query($connection, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "<p class='alert alert-danger'>Order not found.</p>";
    echo "<a href='orders.php' class='btn btn-default'>Back to Orders</a>";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Fetch order items with product names
$items_query = "SELECT oi.order_item_id, oi.product_id, oi.quantity, oi.price,
                p.name AS product_name
                FROM order_items oi
                INNER JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = $order_id
                ORDER BY oi.order_item_id";
$items_result = mysqli_query($connection, $items_query);
?>

<div class="row">
    <div class="col-xs-12">
        <a href="orders.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Orders</a>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Order Information</h4>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr>
                        <th style="width: 40%;">Order ID</th>
                        <td><?php echo (int) $order['order_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td><?php echo date('F j, Y \a\t g:i A', strtotime($order['order_date'])); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                            // Show plain status text without colored label
                            echo htmlspecialchars($order['status']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Customer Information</h4>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr>
                        <th style="width: 40%;">Name</th>
                        <td><?php echo htmlspecialchars($order['user_firstname'] . ' ' . $order['user_lastname']); ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td><?php echo (int) $order['user_id']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Ordered Items</h4>
            </div>
            <div class="panel-body">
                <?php
                if (mysqli_num_rows($items_result) == 0) {
                    echo "<p class='text-muted'>No items in this order.</p>";
                } else {
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($item = mysqli_fetch_assoc($items_result)) {
                                $line_total = $item['quantity'] * $item['price'];
                                echo "<tr>
                                    <td>" . htmlspecialchars($item['product_name']) . " <small class='text-muted'>(ID: {$item['product_id']})</small></td>
                                    <td>{$item['quantity']}</td>
                                    <td>₱" . number_format($item['price'], 2) . "</td>
                                    <td>₱" . number_format($line_total, 2) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
