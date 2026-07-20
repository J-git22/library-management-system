<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Category Management</h2>
    <a href="<?php echo URL_ROOT; ?>/category/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
</div>

<?php flash('category_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['categories'] as $category) : ?>
                    <tr>
                        <td><?php echo $category->id; ?></td>
                        <td><?php echo htmlspecialchars($category->name); ?></td>
                        <td><?php echo htmlspecialchars($category->description); ?></td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/category/edit/<?php echo $category->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo URL_ROOT; ?>/category/delete/<?php echo $category->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
