<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Book</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/book/edit/<?php echo $data['id']; ?>" method="post">
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
                                    <option value="<?php echo $author->id; ?>" <?php echo ($data['author_id'] == $author->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($author->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="publisher_id" class="form-label">Publisher: <sup>*</sup></label>
                            <select name="publisher_id" class="form-select">
                                <?php foreach($data['publishers'] as $pub): ?>
                                    <option value="<?php echo $pub->id; ?>" <?php echo ($data['publisher_id'] == $pub->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pub->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="category_id" class="form-label">Category: <sup>*</sup></label>
                            <select name="category_id" class="form-select">
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>" <?php echo ($data['category_id'] == $cat->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="published_year" class="form-label">Published Year:</label>
                            <input type="number" name="published_year" class="form-control" value="<?php echo htmlspecialchars($data['published_year'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="total_copies" class="form-label">Total Copies: <sup>*</sup></label>
                            <input type="number" name="total_copies" min="1" class="form-control <?php echo (!empty($data['total_copies_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['total_copies'] ?? '1'); ?>">
                            <span class="invalid-feedback"><?php echo $data['total_copies_err'] ?? ''; ?></span>
                        </div>
                        <div class="col-md-3">
                            <label for="available_copies" class="form-label">Available Copies: <sup>*</sup></label>
                            <input type="number" name="available_copies" min="0" class="form-control" value="<?php echo htmlspecialchars($data['available_copies'] ?? '1'); ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/book" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Update Book" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
