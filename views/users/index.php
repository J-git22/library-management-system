<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>User Management</h2>
    <a href="<?php echo URL_ROOT; ?>/user/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>
</div>

<?php flash('user_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['users'] as $user) : ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo htmlspecialchars($user->name); ?></td>
                        <td><?php echo htmlspecialchars($user->email); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($user->role_id == 1) ? 'danger' : 'info'; ?>">
                                <?php echo htmlspecialchars($user->role_name); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/user/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php if ($_SESSION['user_id'] != $user->id): ?>
                            <form action="<?php echo URL_ROOT; ?>/user/delete/<?php echo $user->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
