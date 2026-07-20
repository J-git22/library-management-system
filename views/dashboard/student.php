<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php flash('dashboard_msg'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body py-5 text-center bg-primary text-white rounded">
                <h1 class="display-4 fw-bold">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <?php if ($data['member']): ?>
                    <p class="h5 mb-3 text-white-50">Member ID: <?php echo htmlspecialchars($data['member']->member_id_string); ?></p>
                <?php endif; ?>
                <p class="lead">Here is your student library portal.</p>
                <?php if ($data['user']->last_login): ?>
                    <p class="mb-0 text-white-50"><small><i class="fas fa-clock"></i> Last signed in: <?php echo date('F j, Y, g:i a', strtotime($data['user']->last_login)); ?></small></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 bg-danger text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i> Outstanding Fines</h5>
                    <small>Amount owed for overdue books</small>
                </div>
                <h2 class="fw-bold mb-0">GH₵<?php echo number_format($data['total_fines'], 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <?php 
            $activeBorrows = array_filter($data['transactions'], function($t) { return $t->status == 'Borrowed'; });
            $historyBorrows = array_filter($data['transactions'], function($t) { return $t->status == 'Returned'; });
        ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold"><i class="fas fa-book-reader text-primary me-2"></i> My Borrowed Books</h4>
            </div>
            <div class="card-body">
                <?php if (empty($activeBorrows)): ?>
                    <p class="text-muted text-center my-4">No borrowed books</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Book</th>
                                    <th>Borrowed On</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($activeBorrows as $trans): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($trans->book_title); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($trans->borrow_date)); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php 
                                                    $due = strtotime($trans->due_date);
                                                    $now = strtotime(date('Y-m-d'));
                                                    if ($now > $due) {
                                                        echo '<span class="badge bg-danger rounded-pill"><i class="fas fa-exclamation-circle"></i> Overdue</span>';
                                                    } else {
                                                        echo '<span class="badge bg-warning text-dark rounded-pill">Borrowed</span>';
                                                    }
                                                ?>
                                                <form action="<?php echo URL_ROOT; ?>/dashboard/returnBook" method="POST" class="m-0">
                                                    <input type="hidden" name="transaction_id" value="<?php echo $trans->id; ?>">
                                                    <input type="hidden" name="book_id" value="<?php echo $trans->book_id; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">Return</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold"><i class="fas fa-history text-secondary me-2"></i> Borrowing History</h4>
            </div>
            <div class="card-body">
                <?php if (empty($historyBorrows)): ?>
                    <p class="text-muted text-center my-4">No borrowing history</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Book</th>
                                    <th>Returned On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($historyBorrows as $trans): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($trans->book_title); ?></td>
                                        <td>
                                            <span class="badge bg-success rounded-pill mb-1">Returned</span>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <?php echo date('M j, Y, g:i a', strtotime($trans->return_date)); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold"><i class="fas fa-bookmark text-success me-2"></i> My Reservations</h4>
            </div>
            <div class="card-body">
                <?php if (empty($data['reservations'])): ?>
                    <p class="text-muted text-center my-5">You have no active reservations.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Book</th>
                                    <th>Reserved On</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['reservations'] as $res): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($res->book_title); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($res->reservation_date)); ?></td>
                                        <td>
                                            <?php if($res->status == 'Pending'): ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                                    <form action="<?php echo URL_ROOT; ?>/dashboard/cancelReservation" method="POST" class="m-0">
                                                        <input type="hidden" name="reservation_id" value="<?php echo $res->id; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2" style="font-size: 0.75rem;">Cancel</button>
                                                    </form>
                                                </div>
                                            <?php elseif($res->status == 'Fulfilled'): ?>
                                                <span class="badge bg-success rounded-pill">Fulfilled</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill"><?php echo htmlspecialchars($res->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold"><i class="fas fa-magic text-info me-2"></i> Recommended For You</h4>
                <small class="text-muted">Based on your past borrowing patterns</small>
            </div>
            <div class="card-body">
                <?php if (empty($data['recommended_books'])): ?>
                    <p class="text-muted">Borrow some books to start seeing recommendations!</p>
                <?php else: ?>
                    <div class="row">
                        <?php foreach($data['recommended_books'] as $book): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="fw-bold text-dark"><?php echo htmlspecialchars($book->title); ?></h5>
                                    <p class="text-muted mb-2"><small>By <?php echo htmlspecialchars($book->author_name); ?></small></p>
                                    <span class="badge <?php echo $book->available_copies > 0 ? 'bg-success' : 'bg-danger'; ?> rounded-pill mb-3 d-inline-block">
                                        <?php echo $book->available_copies > 0 ? 'Available' : 'Out of Stock'; ?>
                                    </span>
                                    
                                    <?php if($book->available_copies > 0): ?>
                                    <form action="<?php echo URL_ROOT; ?>/dashboard/borrowRecommended" method="POST">
                                        <input type="hidden" name="book_id" value="<?php echo $book->id; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-hand-holding-heart me-1"></i> Borrow Book</button>
                                    </form>
                                    <?php else: ?>
                                        <button disabled class="btn btn-secondary btn-sm w-100"><i class="fas fa-ban me-1"></i> Unavailable</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold"><i class="fas fa-user-cog text-dark me-2"></i> Profile Settings</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/dashboard/updateProfile" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($data['user']->name); ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password">
                            <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
