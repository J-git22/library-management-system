<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p class="text-muted">Here is an overview of the library's current status.</p>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-book fa-3x mb-3"></i>
                <h5 class="card-title">Total Books</h5>
                <h2 class="display-6 fw-bold m-0"><?php echo $data['total_books']; ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5 class="card-title">Registered Members</h5>
                <h2 class="display-6 fw-bold m-0"><?php echo $data['total_members']; ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-dark">
                <i class="fas fa-hand-holding-heart fa-3x mb-3"></i>
                <h5 class="card-title">Active Borrows</h5>
                <h2 class="display-6 fw-bold m-0"><?php echo $data['active_borrows']; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                <h5 class="card-title">Fines Collected</h5>
                <h2 class="display-6 fw-bold m-0">GH₵<?php echo number_format($data['total_fines'], 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Circulation Activity</h5>
                <a href="<?php echo URL_ROOT; ?>/transaction" class="btn btn-sm btn-outline-light">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['recent_transactions'])) : ?>
                            <tr><td colspan="4" class="text-center py-4">No recent activity.</td></tr>
                        <?php else : ?>
                            <?php foreach($data['recent_transactions'] as $trans) : ?>
                                <tr>
                                    <td><i class="fas fa-book text-muted me-2"></i> <?php echo htmlspecialchars($trans->book_title); ?></td>
                                    <td><?php echo htmlspecialchars(empty($trans->last_name) ? $trans->first_name : $trans->last_name . ', ' . $trans->first_name); ?></td>
                                    <td><?php echo ($trans->status == 'Borrowed') ? 'Borrowed on ' . date('M j, Y, g:i a', strtotime($trans->borrow_date)) : 'Returned on ' . date('M j, Y, g:i a', strtotime($trans->return_date)); ?></td>
                                    <td>
                                        <?php if($trans->status == 'Borrowed'): ?>
                                            <span class="badge bg-warning text-dark">Borrowed</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Returned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
