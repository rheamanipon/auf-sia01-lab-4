<?php
include "includes/db.php";
/* Page Header and navigation */
include "includes/header.php";
include "includes/navigation.php";
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Products Column -->
        <div class="col-md-8">

            <?php
            include "includes/cart_alert.php";
            
            // Check if viewing a single product detail
            if (isset($_GET['id'])) {
                $product_id = $_GET['id'];
                $query = "SELECT * FROM products WHERE product_id=$product_id AND status='active'";
                $fetch_data = mysqli_query($connection, $query);

                if (mysqli_num_rows($fetch_data) == 0) {
                    echo "<h3 class='text-center'>Product not found.</h3>";
                } else {
                    $Row = mysqli_fetch_assoc($fetch_data);
                    $name = $Row['name'];
                    $description = $Row['description'];
                    $price = $Row['price'];
                    $stock_quantity = $Row['stock_quantity'];
                    ?>
                    <p><a href="products.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back to Products</a></p>
                    <h1 class="page-header">
                        <?php echo $name ?>
                        <small>₱<?php echo $price ?></small>
                    </h1>
                    <hr>
                    <p><?php echo $description; ?></p>
                    <p><span class="glyphicon glyphicon-tag"></span> In stock: <?php echo $stock_quantity; ?></p>
                    <?php
                    $redirect_url = "products.php?id=" . $product_id;
                    $form_style = "margin-top: 10px;";
                    $quantity_id = "quantity";
                    $input_width = "80px";
                    include "includes/add_to_cart_form.php";
                    ?>
                    <hr>
            <?php
                }
            } else {
                // Show products listing
                ?>
                <h1 class="page-header">
                    Products
                    <small>Available for purchase</small>
                </h1>

                <?php
                $query = "SELECT * FROM products WHERE status='active'";
                $fetch_data = mysqli_query($connection, $query);

                if (mysqli_num_rows($fetch_data) == 0) {
                    echo "<h3 class='text-center'>No products found.</h3>";
                } else {
                    while ($Row = mysqli_fetch_assoc($fetch_data)) {
                        $product_id = $Row['product_id'];
                        $name = $Row['name'];
                        $description = strlen($Row['description']) > 270 ? substr($Row['description'], 0, 270) . "..." : $Row['description'];
                        $price = $Row['price'];
                        $stock_quantity = $Row['stock_quantity'];
                        ?>
                        <!-- Product -->
                        <h2>
                            <a href="products.php?id=<?php echo $product_id ?>"><?php echo $name ?></a>
                        </h2>
                        <p class="lead">₱<?php echo $price ?></p>
                        <hr>
                        <p><?php echo $description ?></p>
                        <p><span class="glyphicon glyphicon-tag"></span> In stock: <?php echo $stock_quantity; ?></p>
                        <a class="btn btn-primary" href="products.php?id=<?php echo $product_id ?>">View Details <span class="glyphicon glyphicon-chevron-right"></span></a>
                        <?php
                        $redirect_url = "products.php";
                        $form_style = "margin-left: 5px;";
                        include "includes/add_to_cart_form.php";
                        ?>
                        <hr>
                <?php
                    }
                }
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
