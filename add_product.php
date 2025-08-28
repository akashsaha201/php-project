<?php
require 'db.php';
require 'validation.php';
require 'PhysicalProduct.php';
require 'DigitalProduct.php';

$errors = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' 
    ? trim($_POST['category_id']) 
    : null;
    $email = trim($_POST['email']);
    $type = trim($_POST['type']); 

    // Extra fields (only relevant if that type is chosen)
    $weight = $_POST['weight'] ?? null;
    $shipping_cost = $_POST['shipping_cost'] ?? null;
    $download_link = $_POST['download_link'] ?? null;
    $file_size = $_POST['file_size'] ?? null;

    // Validate ALL fields
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
            $product = new PhysicalProduct($conn, $name, $price, $category_id, $email, $shipping_cost, $weight);
        } elseif ($type === "digital") {
            $product = new DigitalProduct($conn, $name, $price, $category_id, $email, $file_size, $download_link);
        } else {
            $errors['type'] = "Invalid product type.";
        }

        if (empty($errors)) {
            $product->addProduct();
            $message = "✅ Product added successfully!";
            // Reset form
            $name = $price = $category_id = $email = $type = $weight = $shipping_cost = $download_link = $file_size = "";
        }
    }
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Product</title>
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
                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
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
                                <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Type -->
                        <div class="mb-3">
                            <label class="form-label">Product Type</label>
                            <select name="type" id="productType"
                                    class="form-select <?php echo isset($errors['type']) ? 'is-invalid' : ''; ?>" required>
                                <option value="">-- Select Type --</option>
                                <option value="physical" <?php echo (isset($type) && $type=='physical') ? 'selected' : ''; ?>>Physical</option>
                                <option value="digital" <?php echo (isset($type) && $type=='digital') ? 'selected' : ''; ?>>Digital</option>
                            </select>
                            <?php if (isset($errors['type'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['type']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="categorySelect" class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" required>
                                <option value="">-- Select --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                            data-type="<?php echo htmlspecialchars($cat['type']); ?>"
                                        <?php echo (isset($category_id) && $category_id == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['category_id']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- DIGITAL FIELDS -->
                        <div id="digitalFields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Download Link</label>
                                <input type="url" name="download_link"
                                    value="<?php echo isset($download_link) ? htmlspecialchars($download_link) : ''; ?>"
                                    class="form-control <?php echo isset($errors['download_link']) ? 'is-invalid' : ''; ?>">
                                <?php if (isset($errors['download_link'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['download_link']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File Size (MB)</label>
                                <input type="number" step="0.01" name="file_size"
                                    value="<?php echo isset($file_size) ? htmlspecialchars($file_size) : ''; ?>"
                                    class="form-control <?php echo isset($errors['file_size']) ? 'is-invalid' : ''; ?>">
                                <?php if (isset($errors['file_size'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['file_size']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>


                        <!-- PHYSICAL FIELDS -->
                        <div id="physicalFields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight"
                                    value="<?php echo isset($weight) ? htmlspecialchars($weight) : ''; ?>"
                                    class="form-control <?php echo isset($errors['weight']) ? 'is-invalid' : ''; ?>">
                                <?php if (isset($errors['weight'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['weight']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shipping Cost ($)</label>
                                <input type="number" step="0.01" name="shipping_cost"
                                    value="<?php echo isset($shipping_cost) ? htmlspecialchars($shipping_cost) : ''; ?>"
                                    class="form-control <?php echo isset($errors['shipping_cost']) ? 'is-invalid' : ''; ?>">
                                <?php if (isset($errors['shipping_cost'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['shipping_cost']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>


                        <!-- Supplier Email -->
                        <div class="mb-3">
                            <label class="form-label">Supplier Email</label>
                            <input type="email" name="email"
                                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                   class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleFields() {
    let selectedType = document.getElementById('productType').value;

    // Toggle extra fields
    document.getElementById('digitalFields').style.display = (selectedType === "digital") ? "block" : "none";
    document.getElementById('physicalFields').style.display = (selectedType === "physical") ? "block" : "none";

    // Filter categories
    let categorySelect = document.getElementById('categorySelect');
    let options = categorySelect.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === "") return; // keep default
        if (opt.getAttribute('data-type') === selectedType) {
            opt.style.display = "block";
        } else {
            opt.style.display = "none";
            if (opt.selected) opt.selected = false;
        }
    });
}

// Run once on page load (to restore state after validation errors)
toggleFields();

// Bind change event
document.getElementById('productType').addEventListener('change', toggleFields);
</script>


</body>
</html>
