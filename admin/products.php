<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Products';

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    redirect('products.php');
}

$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p
                          JOIN categories c ON p.category_id = c.category_id
                          ORDER BY p.product_id DESC")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="admin-topbar">
    <div></div>
    <a href="product-form.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Product</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="8" style="text-align:center;">No products yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="../assets/images/products/<?= e($p['image']) ?>" onerror="this.src='https://placehold.co/44x44/e8f5e9/2e7d4f?text=%20'" alt=""></td>
                <td><?= e($p['name']) ?></td>
                <td><?= e($p['category_name']) ?></td>
                <td><?= formatPrice($p['price']) ?> / <?= e($p['unit']) ?></td>
                <td><?= $p['stock_quantity'] ?></td>
                <td><span class="status-pill status-<?= e($p['status']) ?>"><?= e(ucfirst($p['status'])) ?></span></td>
                <td><?= $p['is_featured'] ? 'Yes' : 'No' ?></td>
                <td class="action-links">
                    <a href="product-form.php?id=<?= $p['product_id'] ?>" class="edit">Edit</a>
                    <a href="products.php?delete=<?= $p['product_id'] ?>" class="delete confirm-delete">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
