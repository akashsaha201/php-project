<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <?php flash('register_success');?>
                <h2 class="text-center mb-4">Login</h2>
                <form action="<?php echo URLROOT; ?>/users/authenticate" method="POST">
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <sup class="text-danger">*</sup></label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['email']); ?>">
                        <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <sup class="text-danger">*</sup></label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['password']); ?>">
                        <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                    <!-- Link to Register -->
                    <p class="mt-3 mb-0 text-center">Don’t have an account? <a href="<?php echo URLROOT; ?>/users/register">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
