<form action="includes/add_to_cart.php" method="get" class="form-inline" style="display: inline-block; <?php echo isset($form_style) ? $form_style : ''; ?>">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="redirect" value="<?php echo isset($redirect_url) ? $redirect_url : 'products.php'; ?>">
    <div class="form-group">
        <label for="quantity_<?php echo isset($quantity_id) ? $quantity_id : $product_id; ?>" class="sr-only">Quantity</label>
        <input
            type="number"
            name="quantity"
            id="quantity_<?php echo isset($quantity_id) ? $quantity_id : $product_id; ?>"
            value="1"
            min="1"
            max="<?php echo $stock_quantity; ?>"
            class="form-control"
            style="width: <?php echo isset($input_width) ? $input_width : '70px'; ?>; display: inline-block;">
    </div>
    <button type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart
    </button>
</form>
