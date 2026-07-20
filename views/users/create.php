<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New User</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/user/create" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email: <sup>*</sup></label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="member_id_string" class="form-label">Custom Member ID (Optional):</label>
                        <input type="text" name="member_id_string" class="form-control <?php echo (!empty($data['member_id_err'])) ? 'is-invalid' : ''; ?>" placeholder="e.g. MEM-1234-24" value="<?php echo htmlspecialchars($data['member_id_string'] ?? ''); ?>">
                        <span class="invalid-feedback"><?php echo $data['member_id_err'] ?? ''; ?></span>
                        <small class="text-muted">Leave blank to auto-generate</small>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password: <sup>*</sup></label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="form-label">Role: <sup>*</sup></label>
                        <select name="role_id" class="form-select">
                            <option value="3" <?php echo ($data['role_id'] == 3) ? 'selected' : ''; ?>>Student</option>
                            <option value="2" <?php echo ($data['role_id'] == 2) ? 'selected' : ''; ?>>Librarian</option>
                            <option value="1" <?php echo ($data['role_id'] == 1) ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/user" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Save User" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
