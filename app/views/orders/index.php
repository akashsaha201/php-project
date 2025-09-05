<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <?php flash('order_invalid'); ?>
    <h2><?php echo $data['title']; ?></h2>

    <?php if (empty($data['orders'])): ?>
        <p>You have not placed any orders yet.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Placed On</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['orders'] as $order): ?>
                    <tr>
                        <td>#<?php echo $order->getId(); ?></td>
                        <td>$<?php echo number_format($order->getTotalAmount(), 2); ?></td>
                        <td>
                            <?php 
                            $status = ucfirst($order->getStatus()); 
                            $color = match ($order->getStatus()) {
                                'successful' => 'green',
                                'failed'     => 'red',
                                'pending'      => 'orange'
                            };
                            ?>
                            <span style="color: <?php echo $color; ?>;">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($order->getCreatedAt()); ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/orders/show/<?php echo $order->getId(); ?>" class="btn btn-sm btn-info">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
