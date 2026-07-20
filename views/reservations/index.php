<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reservation Management</h2>
    <a href="<?php echo URL_ROOT; ?>/reservation/create" class="btn btn-primary"><i class="fas fa-bookmark"></i> Place Reservation</a>
</div>

<?php flash('reservation_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['reservations'] as $res) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($res->book_title); ?></td>
                        <td><?php echo htmlspecialchars(empty($res->last_name) ? $res->first_name : $res->last_name . ', ' . $res->first_name); ?></td>
                        <td><?php echo $res->reservation_date; ?></td>
                        <td>
                            <?php if($res->status == 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Reserved</span>
                            <?php elseif($res->status == 'Fulfilled'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Canceled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($res->status == 'Pending'): ?>
                            <form action="<?php echo URL_ROOT; ?>/reservation/fulfill/<?php echo $res->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-success">Fulfill</button>
                            </form>
                            <form action="<?php echo URL_ROOT; ?>/reservation/cancel/<?php echo $res->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this reservation?');">Cancel</button>
                            </form>
                            <?php else: ?>
                                <span class="text-muted">No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
