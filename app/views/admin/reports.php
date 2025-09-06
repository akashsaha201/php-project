<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4"><?= $data['title']; ?></h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="display-6"><?= $data['totalOrders']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="display-6 text-success">$<?= number_format($data['totalRevenue'], 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Top Selling Products</h3>
    <table class="table table-bordered table-striped text-center align-middle mt-3">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['topProducts'])): ?>
                <?php foreach($data['topProducts'] as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']); ?></td>
                        <td><?= $p['total_sold']; ?></td>
                        <td>$<?= number_format($p['revenue'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-4">
        <a href="<?= URLROOT; ?>/reports/downloadPdf" class="btn btn-primary">
            <i class="bi bi-file-earmark-arrow-down"></i> Download PDF
        </a>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
