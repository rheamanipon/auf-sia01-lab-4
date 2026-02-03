<?php
if (isset($_GET["delete"])) {
    $product_id = $_GET['delete'];
    $query = "DELETE FROM products WHERE product_id=$product_id";
    $delete_query = mysqli_query($connection, $query);
    if (!$delete_query) {
        die("Query Failed: " . mysqli_error($connection));
    }
    header("Location: products.php");
    exit;
}
?>

<table class="table table-bordered table-hover">
    <div class="row">
        <div class="col-xs-4">
            <a class="btn btn-primary" href="products.php?source=add_product">Add New</a>
        </div>
    </div>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Created</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM products";
        $fetch_data = mysqli_query($connection, $query);
        if (mysqli_num_rows($fetch_data) == 0) {
            echo "<tr><td colspan='8' class='text-center'>No products found.</td></tr>";
        } else {
            while ($Row = mysqli_fetch_assoc($fetch_data)) {
                $product_id = $Row['product_id'];
                $description_preview = strlen($Row['description']) > 50 ? substr($Row['description'], 0, 50) . '...' : $Row['description'];
                echo "<tr>
                    <td>{$Row['product_id']}</td>
                    <td>{$Row['name']}</td>
                    <td>{$description_preview}</td>
                    <td>{$Row['price']}</td>
                    <td>{$Row['stock_quantity']}</td>
                    <td>{$Row['status']}</td>
                    <td>{$Row['created_at']}</td>
                    <td>
                        <a onClick=\"javascript: return confirm('Are you sure you want to delete'); \" href='products.php?delete=$product_id'>Delete</a>
                        | <a href='products.php?source=edit_product&product_id=$product_id'>Edit</a>
                    </td>
                </tr>";
            }
        }
        ?>
    </tbody>
</table>
