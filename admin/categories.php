<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Categories';

$errors = [];

// Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    redirect('categories.php');
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);

    if ($name === '') {
        $errors[] = 'Category name is required.';
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        if ($categoryId) {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, description=? WHERE category_id=?");
            $stmt->execute([$name, $slug, $description, $categoryId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $description]);
        }
        redirect('categories.php');
    }
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id) AS product_count
                            FROM categories c ORDER BY c.name")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="checkout-grid">
    <div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Name</th><th>Slug</th><th>Products</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= e($cat['name']) ?></td>
                        <td><?= e($cat['slug']) ?></td>
                        <td><?= $cat['product_count'] ?></td>
                        <td class="action-links">
                            <a href="categories.php?edit=<?= $cat['category_id'] ?>" class="edit">Edit</a>
                            <a href="categories.php?delete=<?= $cat['category_id'] ?>" class="delete confirm-delete">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-form-card" style="max-width:none;">
        <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:14px;">
            <?= $editCategory ? 'Edit Category' : 'Add Category' ?>
        </h3>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <?php if ($editCategory): ?>
                <input type="hidden" name="category_id" value="<?= $editCategory['category_id'] ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" value="<?= e($editCategory['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= e($editCategory['description'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><?= $editCategory ? 'Update' : 'Add' ?> Category</button>
            <?php if ($editCategory): ?>
                <a href="categories.php" class="btn btn-outline btn-block" style="margin-top:8px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
