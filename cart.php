<?php
session_start();
include "includes/db.php";

if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $product_id => $qty) {
        $qty = (int)$qty;
        if (isset($_SESSION['cart'][$product_id])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } else {
                $_SESSION['cart'][$product_id]['quantity'] = $qty;
            }
        }
    }
    header("Location: cart.php");
    exit;
}

/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-8">

            <h1 class="page-header">
                Shopping Cart
                <small>Your items</small>
            </h1>

            <?php
            if (isset($_SESSION['checkout_message'])) {
                echo "<div class='alert alert-warning'>" . $_SESSION['checkout_message'] . "</div>";
                unset($_SESSION['checkout_message']);
            }
            if (empty($_SESSION['cart'])) {
                echo "<h3 class='text-center'>Your cart is empty.</h3>";
                echo "<p class='text-center'><a class='btn btn-primary' href='products.php'>Browse Products</a></p>";
            } else {
                $cart_total = 0;
                ?>
                <form action="" method="post">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($_SESSION['cart'] as $product_id => $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $cart_total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td>₱<?php echo $item['price']; ?></td>
                                <td><input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px;" onchange="this.form.submit()"></td>
                                <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                <td><a href="includes/remove_from_cart.php?product_id=<?php echo $product_id ?>">Remove</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </form>
                <p class="lead"><strong>Total: ₱<?php echo number_format($cart_total, 2); ?></strong></p>
                <a class="btn btn-primary" href="products.php">Continue Shopping</a>
                <a class="btn btn-success" href="checkout.php">Proceed to Checkout</a>
            <?php } ?>

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
