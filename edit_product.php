<?php
require 'db.php';
require 'validation.php';
require 'Product.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid ID");

$errors = [];
$message = "";

// Fetch product
$product = Product::getById($conn, $id);
if (!$product) die("Product not found");

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $email = trim($_POST['email']);

    // Reuse same validation
    $errors = validateProduct([
        'name' => $name,
        'price' => $price,
        'email' => $email
    ]);

    if (empty($errors)) {
        $product = new Product($conn, $name, $price, $category_id, $email, $id);
        $product->updateProduct();

        $message = "✅ Product updated successfully!";
        $product = Product::getById($conn, $id);
    }
}


$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">✏️ Edit Product</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" class="needs-validation" novalidate>
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                       name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $product['name']); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" 
                       class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                       name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? $product['price']); ?>" required>
                <?php if (isset($errors['price'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                            <?php
                                $selected = ($_POST['category_id'] ?? $product['category_id']);
                                echo ($selected == $cat['id']) ? 'selected' : '';
                            ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Supplier Email</label>
                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                       name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $product['email'] ?? ''); ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
