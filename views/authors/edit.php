<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Author</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/author/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="form-label">Biography:</label>
                        <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($data['bio'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/author" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Update Author" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
