<?php
$baseUrl = '';
$pageTitle = 'Shop';
require_once __DIR__ . '/includes/functions.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$selectedCategory = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.status = 'active'";
$params = [];

if ($selectedCategory && $selectedCategory !== 'all') {
    $sql .= " AND c.slug = ?";
    $params[] = $selectedCategory;
}

if ($search !== '') {
    $sql .= " AND p.name LIKE ?";
    $params[] = '%' . $search . '%';
}

$sql .= " ORDER BY p.name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Our Products</h1>
        <p class="section-subtitle">Browse our full range of fresh, quality products</p>

        <form method="get" style="max-width:500px;margin:0 auto 30px;display:flex;gap:10px;">
            <input type="text" name="search" placeholder="Search products..." value="<?= e($search) ?>"
                   style="flex:1;padding:11px 16px;border:1px solid var(--gray-200);border-radius:10px;">
            <?php if ($selectedCategory): ?>
                <input type="hidden" name="category" value="<?= e($selectedCategory) ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <div class="filter-bar">
            <a href="products.php" class="<?= $selectedCategory === '' ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="products.php?category=<?= e($cat['slug']) ?>"
                   class="<?= $selectedCategory === $cat['slug'] ? 'active' : '' ?>"><?= e($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-cart">
                <i class="fa-solid fa-box-open"></i>
                <h3>No products found</h3>
                <p>Try a different search or category.</p>
            </div>
       <?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">

                <?php if ($product['is_featured']): ?>
                    <span class="badge-featured">Featured</span>
                <?php endif; ?>

                <img src="/assets/images/products/<?= e($product['image']) ?>"
                     alt="<?= e($product['name']) ?>"
                     class="product-img"
                     onerror="this.src='https://placehold.co/300x200/e8f5e9/2e7d4f?text=<?= urlencode($product['name']) ?>'">

                <div class="product-info">
                    <span class="product-category"><?= e($product['category_name']) ?></span>

                    <h3 class="product-name"><?= e($product['name']) ?></h3>

                    <div class="product-price">
                        <?= formatPrice($product['price']) ?>
                        <small>/ <?= e($product['unit']) ?></small>
                    </div>

                    <?php if ($product['stock_quantity'] <= 0): ?>
                        <p class="out-of-stock">Out of stock</p>
                    <?php endif; ?>

                    <div class="product-actions">
                        <a href="product-detail.php?slug=<?= e($product['slug']) ?>"
                           class="btn btn-outline btn-sm">View</a>

                        <?php if ($product['stock_quantity'] > 0): ?>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <input type="hidden" name="action" value="add">

                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    Add to Cart
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>