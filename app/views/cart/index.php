<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <?php flash('cart_error'); ?>
    <h2>Your Cart</h2>

    <?php if (empty($data['items'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo '$' . $item['price']; ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/cart/update/<?php echo $item['id']; ?>/decrease" class="btn btn-sm btn-outline-secondary">-</a>
                            <?php echo $item['quantity']; ?>
                            <a href="<?php echo URLROOT; ?>/cart/update/<?php echo $item['id']; ?>/increase" class="btn btn-sm btn-outline-secondary">+</a>
                        </td>
                        <td><?php echo '$' . (float)$item['price'] * (int)$item['quantity']; ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/cart/remove/<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total: <?php echo '$' . $data['total']; ?></h4>
        <a href="<?php echo URLROOT; ?>/cart/clear" class="btn btn-warning">Clear Cart</a>
        <a href="<?php echo URLROOT; ?>/orders/checkout" class="btn btn-success">Proceed to Checkout</a>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
