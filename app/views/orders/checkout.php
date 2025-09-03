<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <h2><?php echo $data['title']; ?></h2>

    <?php if (empty($data['items'])): ?>
        <p>Your cart is empty.</p>
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">Shop Now</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total: $<?php echo number_format($data['total'], 2); ?></h4>

        <form action="<?php echo URLROOT; ?>/orders/place" method="post">
            <button type="submit" class="btn btn-success">Place Order</button>
            <a href="<?php echo URLROOT; ?>/cart" class="btn btn-secondary">Back to Cart</a>
        </form>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
