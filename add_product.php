<?php
require 'db.php';
require 'validation.php';
require 'Product.php';

$errors = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $email = trim($_POST['email']);

    // Validate using reusable function
    $errors = validateProduct([
        'name' => $name,
        'price' => $price,
        'email' => $email
    ]);

    if (empty($errors)) {
        $product = new Product($conn, $name, $price, $category_id, $email);
        $product->addProduct();
        $message = "✅ Product added successfully!";
        $name = $price = $category_id = $email = "";
    }
}


$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Product</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Add Product</h2>

                        <!-- Success Message -->
                        <?php if ($message): ?>
                            <div class="alert alert-success text-center">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" class="needs-validation" novalidate>
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name"
                                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                    class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                                    required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="1" name="price"
                                    value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>"
                                    class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                                    required>
                                <?php if (isset($errors['price'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['price']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"
                                            <?php echo (isset($category_id) && $category_id == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Supplier Email -->
                            <div class="mb-3">
                                <label class="form-label">Supplier Email</label>
                                <input type="email" name="email"
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                    class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                    required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['email']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="index.php" class="btn btn-link">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
