<?php
require 'db.php'; 

// Fetch products with category name
$sql = "SELECT p.id, p.name, p.email, p.price, c.name AS category 
        FROM products_with_email p
        LEFT JOIN categories c ON p.category_id = c.id";
$products = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
                        <th scope="col">#ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Price ($)</th>
                        <th scope="col">Category</th>
                        <th scope="col" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['email']); ?></td>
                                <td><?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['category'] ?? 'Uncategorized'; ?></td>
                                <td class="d-flex gap-2">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-sm btn-primary">Edit</a>

                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this product?');">
                                        Delete
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>