<!DOCTYPE html>
<html>
<head>
    <title>Admin Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background: #f2f2f2; }
        p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Admin Report</h1>
    <p><strong>Total Orders:</strong> <?= $data['totalOrders']; ?></p>
    <p><strong>Total Revenue:</strong> $<?= number_format($data['totalRevenue'], 2); ?></p>

    <h3>Top Selling Products</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['topProducts'] as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']); ?></td>
                    <td><?= $p['total_sold']; ?></td>
                    <td>$<?= number_format($p['revenue'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
