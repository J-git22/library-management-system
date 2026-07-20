<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Publisher Management</h2>
    <a href="<?php echo URL_ROOT; ?>/publisher/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Publisher</a>
</div>

<?php flash('publisher_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['publishers'] as $publisher) : ?>
                    <tr>
                        <td><?php echo $publisher->id; ?></td>
                        <td><?php echo htmlspecialchars($publisher->name); ?></td>
                        <td><?php echo htmlspecialchars($publisher->contact_email); ?></td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/publisher/edit/<?php echo $publisher->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo URL_ROOT; ?>/publisher/delete/<?php echo $publisher->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this publisher?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
