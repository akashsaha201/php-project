<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4">Register</h2>
                <form action="<?php echo URLROOT; ?>/users/store" method="POST">
                    
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <sup class="text-danger">*</sup></label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['username']); ?>">
                        <div class="invalid-feedback"><?php echo $data['username_err']; ?></div>
                    </div>

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

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['confirm_password']); ?>">
                        <div class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></div>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>

                    <!-- Link to Login -->
                    <p class="mt-3 mb-0 text-center">Already have an account? <a href="<?php echo URLROOT; ?>/users/login">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
