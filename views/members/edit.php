<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Member</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/member/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name: <sup>*</sup></label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>">
                            <span class="invalid-feedback"><?php echo $data['first_name_err'] ?? ''; ?></span>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name: <sup>*</sup></label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>">
                            <span class="invalid-feedback"><?php echo $data['last_name_err'] ?? ''; ?></span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/member" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Update Member" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
