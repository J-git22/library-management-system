<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Author Management</h2>
    <a href="<?php echo URL_ROOT; ?>/author/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Author</a>
</div>

<?php flash('author_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Bio</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['authors'] as $author) : ?>
                    <tr>
                        <td><?php echo $author->id; ?></td>
                        <td><?php echo htmlspecialchars($author->name); ?></td>
                        <td><?php echo htmlspecialchars(substr($author->bio, 0, 50)) . '...'; ?></td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/author/edit/<?php echo $author->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo URL_ROOT; ?>/author/delete/<?php echo $author->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this author?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
