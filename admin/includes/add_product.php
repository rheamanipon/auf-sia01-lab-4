<?php
if (isset($_POST['create_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $status = $_POST['status'];

    $query = "INSERT INTO products (name, description, price, stock_quantity, status) ";
    $query .= "VALUES('{$name}', '{$description}', {$price}, {$stock_quantity}, '{$status}')";
    $create_query = mysqli_query($connection, $query);
    $the_product_id = mysqli_insert_id($connection);
    if (!$create_query) {
        die("Query Failed: " . mysqli_error($connection));
    }

    echo "<p class='alert alert-success'>Product added successfully. <a href='products.php'>View Products</a></p>";
}
?>

<form action="" method="post">
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" rows="5" required></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" class="form-control" name="price" required>
    </div>

    <div class="form-group">
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" class="form-control" name="stock_quantity" value="0" required>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" name="status">
            <option value='active'>active</option>
            <option value='inactive'>inactive</option>
        </select>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_product" value="Add Product">
    </div>
</form>
