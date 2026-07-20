<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Publisher</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/publisher/create" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-4">
                        <label for="contact_email" class="form-label">Contact Email:</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($data['contact_email'] ?? ''); ?>">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/publisher" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Save Publisher" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
