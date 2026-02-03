<?php
session_start();
include "includes/db.php";

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

if (!isset($_SESSION['username'])) {
    $_SESSION['checkout_message'] = 'Please login to checkout.';
    header("Location: cart.php");
    exit;
}

$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE user_name='$username'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

$query = "INSERT INTO orders (user_id, total_amount, status) VALUES($user_id, $total_amount, 'pending')";
$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($connection));
}
$order_id = mysqli_insert_id($connection);

foreach ($_SESSION['cart'] as $product_id => $item) {
    $price = $item['price'];
    $quantity = $item['quantity'];
    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES($order_id, $product_id, $quantity, $price)";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    }
    
    // Reduce stock quantity
    $update_stock_query = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id";
    $update_result = mysqli_query($connection, $update_stock_query);
    if (!$update_result) {
        die("Query Failed: " . mysqli_error($connection));
    }
}

$_SESSION['last_order_id'] = $order_id;
$_SESSION['last_order_items'] = $_SESSION['cart'];
$_SESSION['last_order_total'] = $total_amount;
unset($_SESSION['cart']);

include "includes/send_order_email.php";

header("Location: order_confirmation.php?order_id=$order_id");
exit;
?>
