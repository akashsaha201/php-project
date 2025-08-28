<?php
require 'db.php';
require 'validation.php';
require 'PhysicalProduct.php';
require 'DigitalProduct.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid ID");

$errors = [];
$message = "";

// Fetch product
$product = Product::getById($conn, $id);
if (!$product) die("Product not found");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' 
    ? trim($_POST['category_id']) 
    : null;
    $email = trim($_POST['email']);
    $type = trim($_POST['type']);

    // Extra fields
    $weight = $_POST['weight'] ?? null;
    $shipping_cost = $_POST['shipping_cost'] ?? null;
    $download_link = $_POST['download_link'] ?? null;
    $file_size = $_POST['file_size'] ?? null;

    // Validate all
    $errors = validateProduct([
        'name' => $name,
        'price' => $price,
        'email' => $email,
        'type' => $type,
        'weight' => $weight,
        'shipping_cost' => $shipping_cost,
        'download_link' => $download_link,
        'file_size' => $file_size
    ]);

    if (empty($errors)) {
        if ($type === "physical") {
            $productObj = new PhysicalProduct($conn, $name, $price, $category_id, $email, $shipping_cost, $weight, $id);
        } elseif ($type === "digital") {
            $productObj = new DigitalProduct($conn, $name, $price, $category_id, $email, $file_size, $download_link, $id);
        }

        if (isset($productObj)) {
            $productObj->updateProduct();
            $message = "✅ Product updated successfully!";
            $product = Product::getById($conn, $id);
        }
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

        <form method="post">
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? $product['name']); ?>"
                       class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" 
                       value="<?php echo htmlspecialchars($_POST['price'] ?? $product['price']); ?>"
                       class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['price'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Type -->
            <div class="mb-3">
                <label class="form-label">Product Type</label>
                <select name="type" id="productType"
                        class="form-select <?php echo isset($errors['type']) ? 'is-invalid' : ''; ?>">
                    <option value="">-- Select Type --</option>
                    <option value="physical" <?php echo (($product['type'] ?? '')=='physical') ? 'selected' : ''; ?>>Physical</option>
                    <option value="digital" <?php echo (($product['type'] ?? '')=='digital') ? 'selected' : ''; ?>>Digital</option>
                </select>
                <?php if (isset($errors['type'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['type']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" id="categorySelect" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                data-type="<?php echo htmlspecialchars($cat['type']); ?>"
                            <?php echo (($product['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- DIGITAL -->
            <div id="digitalFields" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Download Link</label>
                    <input type="url" name="download_link"
                           value="<?php echo htmlspecialchars($_POST['download_link'] ?? $product['download_link'] ?? ''); ?>"
                           class="form-control <?php echo isset($errors['download_link']) ? 'is-invalid' : ''; ?>">
                    <?php if (isset($errors['download_link'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['download_link']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">File Size (MB)</label>
                    <input type="number" step="0.01" name="file_size"
                           value="<?php echo htmlspecialchars($_POST['file_size'] ?? $product['file_size'] ?? ''); ?>"
                           class="form-control <?php echo isset($errors['file_size']) ? 'is-invalid' : ''; ?>">
                    <?php if (isset($errors['file_size'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['file_size']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- PHYSICAL -->
            <div id="physicalFields" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight"
                           value="<?php echo htmlspecialchars($_POST['weight'] ?? $product['weight'] ?? ''); ?>"
                           class="form-control <?php echo isset($errors['weight']) ? 'is-invalid' : ''; ?>">
                    <?php if (isset($errors['weight'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['weight']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Shipping Cost ($)</label>
                    <input type="number" step="0.01" name="shipping_cost"
                           value="<?php echo htmlspecialchars($_POST['shipping_cost'] ?? $product['shipping_cost'] ?? ''); ?>"
                           class="form-control <?php echo isset($errors['shipping_cost']) ? 'is-invalid' : ''; ?>">
                    <?php if (isset($errors['shipping_cost'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['shipping_cost']; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Supplier Email</label>
                <input type="email" name="email"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? $product['email']); ?>"
                       class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>">
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

<script>
function toggleFields() {
    let selectedType = document.getElementById('productType').value;
    document.getElementById('digitalFields').style.display = (selectedType === "digital") ? "block" : "none";
    document.getElementById('physicalFields').style.display = (selectedType === "physical") ? "block" : "none";

    let categorySelect = document.getElementById('categorySelect');
    let options = categorySelect.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === "") return;
        if (opt.getAttribute('data-type') === selectedType) {
            opt.style.display = "block";
        } else {
            opt.style.display = "none";
            if (opt.selected) opt.selected = false;
        }
    });
}

toggleFields();
document.getElementById('productType').addEventListener('change', toggleFields);
</script>

</body>
</html>
