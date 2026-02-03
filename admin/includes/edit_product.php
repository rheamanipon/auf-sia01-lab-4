<?php
if (isset($_POST['update_product'], $_GET['product_id'])) {
    $the_product_id = $_GET['product_id'];

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $status = $_POST['status'];

    $query = "UPDATE products SET ";
    $query .= "name='{$name}', ";
    $query .= "description='{$description}', ";
    $query .= "price={$price}, ";
    $query .= "stock_quantity={$stock_quantity}, ";
    $query .= "status='{$status}' ";
    $query .= "WHERE product_id=$the_product_id";

    $update_query = mysqli_query($connection, $query);
    if (!$update_query) {
        die("Query Failed: " . mysqli_error($connection));
    }

    echo "<p class='alert alert-success'>Product updated successfully. <a href='products.php'>View Products</a></p>";
}
?>

<?php
if (isset($_GET['product_id'])) {
    $the_product_id = $_GET['product_id'];
    $query = "SELECT * FROM products WHERE product_id=$the_product_id";
    $fetch_data = mysqli_query($connection, $query);
    while ($Row = mysqli_fetch_assoc($fetch_data)) {
        $name = $Row['name'];
        $description = $Row['description'];
        $price = $Row['price'];
        $stock_quantity = $Row['stock_quantity'];
        $status = $Row['status'];
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" rows="5" required><?php echo $description; ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $price; ?>" required>
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" class="form-control" name="stock_quantity" value="<?php echo $stock_quantity; ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                    <option value='<?php echo $status; ?>'><?php echo $status; ?></option>
                    <?php if ($status === 'active') { ?>
                        <option value='inactive'>inactive</option>
                    <?php } else { ?>
                        <option value='active'>active</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="update_product" value="Update Product">
            </div>
        </form>
<?php }
}
?>
