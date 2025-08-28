<?php
require 'db.php';
require 'Product.php';

// Fetch products
$products = Product::getAll($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">📦 Product List</h2>
                <a href="add_product.php" class="btn btn-success">Add New Product</a>
            </div>

            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Price ($)</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id']; ?></td>
                                <td><?= htmlspecialchars($product['name']); ?></td>
                                <td><?= htmlspecialchars($product['email']); ?></td>
                                <td class="fw-bold text-capitalize"><?= $product['product_type']; ?></td>
                                <td><?= number_format($product['price'], 2); ?></td>
                                <td><?= $product['category'] ?? 'Uncategorized'; ?></td>
                                <td>
                                    <?php if ($product['product_type'] === 'physical'): ?>
                                        Weight: <?= $product['weight'] ?> kg<br>
                                        Shipping: $<?= $product['shipping_cost']; ?>
                                    <?php elseif ($product['product_type'] === 'digital'): ?>
                                        File: <?= $product['file_size'] ?> MB<br>
                                        <a href="<?= htmlspecialchars($product['download_link']); ?>" target="_blank">
                                            Download
                                        </a>
                                    <?php else: ?>
                                        <em>No details</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="edit_product.php?id=<?= $product['id']; ?>"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <a href="delete_product.php?id=<?= $product['id']; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>