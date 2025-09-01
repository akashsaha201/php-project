<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <?php flash('add_success');?>
    <?php flash('update_success');?>
    <?php flash('delete_success');?>
    <h2 class="mb-4">Products</h2>
    <?php if(isLoggedIn()): ?>
    <a href="<?php echo URLROOT; ?>/products/add" class="btn btn-primary mb-3">Add Product</a>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Price</th>
            <th>Type</th>
            <th>Category</th>
            <th>Details</th>
            <?php if(isLoggedIn()): ?>
            <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($data['products'])): ?>
        <?php foreach ($data['products'] as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['email']); ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo ucfirst($product['category_type']); ?></td>
                <td><?php echo htmlspecialchars($product['category_name'] ?? ''); ?></td>
                <td>
                    <?php if ($product['category_type'] === 'digital'): ?>
                        <strong>File Size:</strong> <?php echo htmlspecialchars($product['file_size']); ?><br>
                        <strong>Link:</strong> <a href="<?php echo htmlspecialchars($product['download_link']); ?>" target="_blank">Download</a>
                    <?php elseif ($product['category_type'] === 'physical'): ?>
                        <strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?><br>
                        <strong>Shipping:</strong> $<?php echo number_format($product['shipping_cost'], 2); ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <?php if(isLoggedIn()): ?>
                <td>
                    <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form action="<?php echo URLROOT; ?>/products/delete/<?php echo $product['id']; ?>" method="POST" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8" class="text-center">No products found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
