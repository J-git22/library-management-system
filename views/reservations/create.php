<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Place Book on Reserve</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/reservation/create" method="post">
                    <div class="mb-4">
                        <label for="book_id" class="form-label">Select Book (must have 0 available copies): <sup>*</sup></label>
                        <select name="book_id" class="form-select <?php echo (!empty($data['book_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Choose a Book --</option>
                            <?php foreach($data['books'] as $book): ?>
                                <option value="<?php echo $book->id; ?>">
                                    <?php echo htmlspecialchars($book->title); ?> (Available: <?php echo $book->available_copies; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['book_id_err'] ?? ''; ?></span>
                    </div>

                    <div class="mb-4">
                        <label for="member_id" class="form-label">Select Member: <sup>*</sup></label>
                        <select name="member_id" class="form-select <?php echo (!empty($data['member_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Choose a Member --</option>
                            <?php foreach($data['members'] as $member): ?>
                                <option value="<?php echo $member->id; ?>">
                                    <?php echo htmlspecialchars(empty($member->last_name) ? $member->first_name : $member->last_name . ', ' . $member->first_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['member_id_err'] ?? ''; ?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URL_ROOT; ?>/reservation" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Place Reservation" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
