<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Book Management</h2>
    <a href="<?php echo URL_ROOT; ?>/book/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Book</a>
</div>

<?php flash('book_msg'); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Copies (Avail/Total)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['books'] as $book) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book->title); ?></td>
                        <td><?php echo htmlspecialchars($book->isbn); ?></td>
                        <td><?php echo htmlspecialchars($book->author_name); ?></td>
                        <td><?php echo htmlspecialchars($book->category_name); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($book->available_copies > 0) ? 'success' : 'danger'; ?>">
                                <?php echo $book->available_copies; ?> / <?php echo $book->total_copies; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo URL_ROOT; ?>/book/edit/<?php echo $book->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo URL_ROOT; ?>/book/delete/<?php echo $book->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
