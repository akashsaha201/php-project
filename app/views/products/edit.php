<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <h2>Edit <?php echo ucfirst($data['type']); ?> Product</h2>
    <form action="<?php echo URLROOT; ?>/products/edit/<?php echo $data['id']; ?>" method="post">
        
        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" 
                value="<?php echo htmlspecialchars($data['form']['name'] ?? ''); ?>">
            <span class="text-danger"><?php echo $data['errors']['name'] ?? ''; ?></span>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control" 
                value="<?php echo htmlspecialchars($data['form']['email'] ?? ''); ?>">
            <span class="text-danger"><?php echo $data['errors']['email'] ?? ''; ?></span>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="text" name="price" class="form-control" 
                value="<?php echo htmlspecialchars($data['form']['price'] ?? ''); ?>">
            <span class="text-danger"><?php echo $data['errors']['price'] ?? ''; ?></span>
        </div>
        
        <!-- Quantity -->
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="text" name="quantity" class="form-control" 
                value="<?php echo htmlspecialchars($data['form']['quantity'] ?? ''); ?>">
            <span class="text-danger"><?php echo $data['errors']['quantity'] ?? ''; ?></span>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control">
                <option value="">Select Category</option>
                <?php foreach ($data['categories'] as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                        <?php echo ($data['form']['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="text-danger"><?php echo $data['errors']['category_id'] ?? ''; ?></span>
        </div>

        <!-- Details -->
        <h4>Details</h4>

        <?php if ($data['type'] === 'digital'): ?>
            <div class="mb-3">
                <label class="form-label">File Size (MB)</label>
                <input type="text" name="file_size" class="form-control" 
                    value="<?php echo htmlspecialchars($data['form']['file_size'] ?? ''); ?>">
                <span class="text-danger"><?php echo $data['errors']['file_size'] ?? ''; ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Download Link</label>
                <input type="text" name="download_link" class="form-control" 
                    value="<?php echo htmlspecialchars($data['form']['download_link'] ?? ''); ?>">
                <span class="text-danger"><?php echo $data['errors']['download_link'] ?? ''; ?></span>
            </div>
        <?php elseif ($data['type'] === 'physical'): ?>
            <div class="mb-3">
                <label class="form-label">Weight (kg)</label>
                <input type="text" name="weight" class="form-control" 
                    value="<?php echo htmlspecialchars($data['form']['weight'] ?? ''); ?>">
                <span class="text-danger"><?php echo $data['errors']['weight'] ?? ''; ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Shipping Cost</label>
                <input type="text" name="shipping_cost" class="form-control" 
                    value="<?php echo htmlspecialchars($data['form']['shipping_cost'] ?? ''); ?>">
                <span class="text-danger"><?php echo $data['errors']['shipping_cost'] ?? ''; ?></span>
            </div>
        <?php endif; ?>

        <!-- Submit -->
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
