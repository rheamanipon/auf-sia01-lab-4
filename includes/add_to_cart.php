<?php
session_start();
include "db.php";

if (isset($_GET['product_id'], $_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = (int)$_GET['quantity'];
    if ($quantity < 1) {
        $quantity = 1;
    }

    $query = "SELECT * FROM products WHERE product_id=$product_id AND status='active'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $Row = mysqli_fetch_assoc($result);
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = array(
                'name' => $Row['name'],
                'price' => $Row['price'],
                'quantity' => $quantity
            );
        }
    }
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'products.php';
$sep = (strpos($redirect, '?') !== false) ? '&' : '?';
header("Location: ../$redirect{$sep}added=1");
exit;
?>
