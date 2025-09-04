<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <?php flash('order_success'); ?>
    <?php flash('order_error'); ?>
    <h2>Order #<?php echo $data['order']->getId(); ?></h2>
    <p>
        <strong>Status:</strong> 
        <?php 
            $status = ucfirst($data['order']->getStatus()); 
            $color = match ($data['order']->getStatus()) {
                'successful' => 'green',
                'failed'     => 'red',
                'pending'      => 'yellow'
            };
        ?>
        <span style="color: <?php echo $color; ?>;">
            <?php echo htmlspecialchars($status); ?>
        </span>
    </p>

    <p><strong>Total:</strong> $<?php echo number_format($data['order']->getTotalAmount(), 2); ?></p>
    <p><strong>Placed On:</strong> <?php echo htmlspecialchars($data['order']->getCreatedAt()); ?></p>

    <h4>Items:</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['order']->getItems() as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item->getProductName()); ?></td>
                    <td><?php echo $item->getQuantity(); ?></td>
                    <td>$<?php echo number_format($item->getPrice(), 2); ?></td>
                    <td>$<?php echo number_format($item->getPrice() * $item->getQuantity(), 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?php echo URLROOT; ?>/orders" class="btn btn-primary">Back to Orders</a>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
