<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Member Management</h2>
    <a href="<?php echo URL_ROOT; ?>/member/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</a>
</div>

<?php flash('member_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['members'] as $member) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member->member_id_string ?? $member->id); ?></td>
                        <td><?php echo htmlspecialchars(empty($member->last_name) ? $member->first_name : $member->last_name . ', ' . $member->first_name); ?></td>
                        <td><?php echo htmlspecialchars($member->email); ?></td>
                        <td><?php echo htmlspecialchars($member->phone); ?></td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/member/edit/<?php echo $member->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo URL_ROOT; ?>/member/delete/<?php echo $member->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
