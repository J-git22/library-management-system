<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Circulation Management</h2>
    <a href="<?php echo URL_ROOT; ?>/transaction/borrow" class="btn btn-primary"><i class="fas fa-book"></i> Issue Book</a>
</div>

<?php flash('transaction_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['transactions'] as $trans) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($trans->book_title); ?></td>
                        <td><?php echo htmlspecialchars(empty($trans->last_name) ? $trans->first_name : $trans->last_name . ', ' . $trans->first_name); ?></td>
                        <td><?php echo $trans->borrow_date; ?></td>
                        <td><?php echo $trans->due_date; ?></td>
                        <td>
                            <?php if($trans->status == 'Borrowed'): ?>
                                <?php 
                                    $due = strtotime($trans->due_date);
                                    $now = strtotime(date('Y-m-d'));
                                    if ($now > $due) {
                                        echo '<span class="badge bg-danger">Overdue</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark">Borrowed</span>';
                                    }
                                ?>
                            <?php else: ?>
                                <span class="badge bg-success">Returned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($trans->status == 'Borrowed'): ?>
                            <form action="<?php echo URL_ROOT; ?>/transaction/return/<?php echo $trans->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm book return?');">Return Book</button>
                            </form>
                            <?php else: ?>
                                <span class="text-muted">Returned on <?php echo $trans->return_date; ?></span>
                                <?php if($trans->fine_amount > 0): ?>
                                    <br><small class="text-danger">Fine: GH₵<?php echo number_format($trans->fine_amount, 2); ?></small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
