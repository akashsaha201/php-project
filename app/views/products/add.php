<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <h2>Add Product</h2>
    <form action="<?php echo URLROOT; ?>/products/store" method="POST">

        <!-- Type -->
        <div class="mb-3">
            <label class="form-label">Product Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="digital" <?php echo ($data['form']['type'] ?? '') === 'digital' ? 'selected' : ''; ?>>Digital</option>
                <option value="physical" <?php echo ($data['form']['type'] ?? '') === 'physical' ? 'selected' : ''; ?>>Physical</option>
            </select>
            <span class="text-danger"><?php echo $data['errors']['type'] ?? ''; ?></span>
        </div>

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $data['form']['name'] ?? ''; ?>">
            <span class="text-danger"><?php echo $data['errors']['name'] ?? ''; ?></span>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Contact Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $data['form']['email'] ?? ''; ?>">
            <span class="text-danger"><?php echo $data['errors']['email'] ?? ''; ?></span>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="1" name="price" class="form-control" value="<?php echo $data['form']['price'] ?? ''; ?>">
            <span class="text-danger"><?php echo $data['errors']['price'] ?? ''; ?></span>
        </div>
        
        <!-- Quantity -->
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" step="1" name="quantity" class="form-control" value="<?php echo $data['form']['quantity'] ?? ''; ?>">
            <span class="text-danger"><?php echo $data['errors']['quantity'] ?? ''; ?></span>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" id="category" class="form-control">
                <option value="">Select Category</option>
                <?php foreach ($data['categories'] as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                        data-type="<?php echo $cat['type']; ?>" 
                        <?php echo ($data['form']['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="text-danger"><?php echo $data['errors']['category_id'] ?? ''; ?></span>
        </div>
        <!-- Digital fields -->
        <div id="digitalFields" style="display: none;">
            <div class="mb-3">
                <label class="form-label">File Size (MB)</label>
                <input type="number" name="file_size" class="form-control" value="<?php echo $data['form']['file_size'] ?? ''; ?>">
                <span class="text-danger"><?php echo $data['errors']['file_size'] ?? ''; ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Download Link</label>
                <input type="url" name="download_link" class="form-control" value="<?php echo $data['form']['download_link'] ?? ''; ?>">
                <span class="text-danger"><?php echo $data['errors']['download_link'] ?? ''; ?></span>
            </div>
        </div>

        <!-- Physical fields -->
        <div id="physicalFields" style="display: none;">
            <div class="mb-3">
                <label class="form-label">Weight (kg)</label>
                <input type="number" step="0.01" name="weight" class="form-control" value="<?php echo $data['form']['weight'] ?? ''; ?>">
                <span class="text-danger"><?php echo $data['errors']['weight'] ?? ''; ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Shipping Cost</label>
                <input type="number" step="0.01" name="shipping_cost" class="form-control" value="<?php echo $data['form']['shipping_cost'] ?? ''; ?>">
                <span class="text-danger"><?php echo $data['errors']['shipping_cost'] ?? ''; ?></span>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save Product</button>
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
const typeSelect = document.getElementById('type');
const categorySelect = document.getElementById('category');
const digitalFields = document.getElementById('digitalFields');
const physicalFields = document.getElementById('physicalFields');

// Store all category options for reset
const allOptions = [...categorySelect.options];
function filterCategories() {
    const selectedType = typeSelect.value;

    // Keep existing placeholder, so skip adding one
    const placeholder = categorySelect.options[0];

    categorySelect.innerHTML = '';
    categorySelect.appendChild(placeholder);

    allOptions.forEach(opt => {
        if (!opt.dataset.type || opt.dataset.type === selectedType) {
            categorySelect.appendChild(opt);
        }
    });

    digitalFields.style.display = selectedType === 'digital' ? 'block' : 'none';
    physicalFields.style.display = selectedType === 'physical' ? 'block' : 'none';
}


// Run once on load (for edit form pre-filled values)
filterCategories();

// Re-run whenever type changes
typeSelect.addEventListener('change', filterCategories);
</script>


<?php require APPROOT . '/views/inc/footer.php'; ?>
