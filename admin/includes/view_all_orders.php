<?php
$query = "SELECT o.order_id, o.user_id, o.total_amount, o.order_date, o.status,
          u.user_firstname, u.user_lastname, u.user_email, u.user_name
          FROM orders o
          INNER JOIN users u ON o.user_id = u.user_id
          ORDER BY o.order_date DESC";
$fetch_orders = mysqli_query($connection, $query);
?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($fetch_orders) == 0) {
            echo "<tr><td colspan='7' class='text-center'>No orders found.</td></tr>";
        } else {
            while ($row = mysqli_fetch_assoc($fetch_orders)) {
                $order_id = $row['order_id'];
                $customer_username = htmlspecialchars($row['user_name']);
                $customer_email = htmlspecialchars($row['user_email']);
                $total = number_format($row['total_amount'], 2);
                $order_date = date('M j, Y g:i A', strtotime($row['order_date']));
                $status = htmlspecialchars($row['status']);
                echo "<tr>
                    <td>{$order_id}</td>
                    <td>{$customer_username}</td>
                    <td>{$customer_email}</td>
                    <td>₱{$total}</td>
                    <td>{$order_date}</td>
                    <td>{$status}</td>
                    <td>
                        <a href='orders.php?source=view_order&order_id={$order_id}' class='btn btn-sm btn-info'>View Details</a>
                    </td>
                </tr>";
            }
        }
        ?>
    </tbody>
</table>
