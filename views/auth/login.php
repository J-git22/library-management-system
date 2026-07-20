<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-white mt-5 shadow-sm">
            <?php flash('register_success'); ?>
            <h2 class="text-center mb-4">Library Management System</h2>
            <p class="text-center">Please fill in your credentials to log in.</p>
            <form action="<?php echo URL_ROOT; ?>/auth/login" method="post">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-label">Password: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['password'] ?? ''); ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Login" class="btn btn-primary w-100">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
