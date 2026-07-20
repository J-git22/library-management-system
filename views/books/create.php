<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Book</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/book/create" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title: <sup>*</sup></label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>">
                            <span class="invalid-feedback"><?php echo $data['title_err'] ?? ''; ?></span>
                        </div>
                        <div class="col-md-6">
                            <label for="isbn" class="form-label">ISBN: <sup>*</sup></label>
                            <input type="text" name="isbn" class="form-control <?php echo (!empty($data['isbn_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['isbn'] ?? ''); ?>">
                            <span class="invalid-feedback"><?php echo $data['isbn_err'] ?? ''; ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="author_id" class="form-label">Author: <sup>*</sup></label>
                            <select name="author_id" class="form-select">
                                <?php foreach($data['authors'] as $author): ?>
                                    <option value="<?php echo $author->id; ?>"><?php echo htmlspecialchars($author->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="publisher_id" class="form-label">Publisher: <sup>*</sup></label>
                            <select name="publisher_id" class="form-select">
                                <?php foreach($data['publishers'] as $pub): ?>
                                    <option value="<?php echo $pub->id; ?>"><?php echo htmlspecialchars($pub->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="category_id" class="form-label">Category: <sup>*</sup></label>
                            <select name="category_id" class="form-select">
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="published_year" class="form-label">Published Year:</label>
                            <input type="number" name="published_year" class="form-control" value="<?php echo htmlspecialchars($data['published_year'] ?? date('Y')); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="total_copies" class="form-label">Total Copies: <sup>*</sup></label>
                            <input type="number" name="total_copies" min="1" class="form-control <?php echo (!empty($data['total_copies_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['total_copies'] ?? '1'); ?>">
                            <span class="invalid-feedback"><?php echo $data['total_copies_err'] ?? ''; ?></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/book" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Save Book" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
