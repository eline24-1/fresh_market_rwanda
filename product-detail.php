<?php
$baseUrl = '';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p
                        JOIN categories c ON p.category_id = c.category_id
                        WHERE p.slug = ? AND p.status = 'active'");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    redirect('products.php');
}

$pageTitle = $product['name'];

// Related products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND product_id != ? AND status = 'active' LIMIT 4");
$stmt->execute([$product['category_id'], $product['product_id']]);
$related = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <p style="margin-bottom:20px;font-size:0.9rem;color:var(--gray-500);">
            <a href="index.php">Home</a> / <a href="products.php">Shop</a> /
            <a href="products.php?category=<?= e($product['category_slug']) ?>"><?= e($product['category_name']) ?></a> /
            <?= e($product['name']) ?>
        </p>

        <div class="product-detail">
            <img src="/assets/images/products/<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>"
                 onerror="this.src='https://placehold.co/500x400/e8f5e9/2e7d4f?text=<?= urlencode($product['name']) ?>'">
            <div>
                <span class="product-category"><?= e($product['category_name']) ?></span>
                <h1><?= e($product['name']) ?></h1>
                <div class="price"><?= formatPrice($product['price']) ?> <small style="font-size:0.9rem;color:var(--gray-500);">/ <?= e($product['unit']) ?></small></div>
                <p class="desc"><?= nl2br(e($product['description'])) ?></p>

                <?php if ($product['stock_quantity'] > 0): ?>
                    <p style="color:var(--green);font-weight:600;margin-bottom:14px;"><i class="fa-solid fa-check-circle"></i> In Stock (<?= $product['stock_quantity'] ?> available)</p>

                    <form action="cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="action" value="add">
                        <div class="qty-input">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                        <a href="products.php" class="btn btn-outline">Continue Shopping</a>
                    </form>
                <?php else: ?>
                    <p class="out-of-stock" style="font-size:1rem;margin-bottom:14px;"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</p>
                    <a href="products.php" class="btn btn-outline">Continue Shopping</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($related)): ?>
        <h2 class="section-title" style="margin-top:50px;">Related Products</h2>
        <div class="product-grid">
            <?php foreach ($related as $rp): ?>
                <div class="product-card">
                    <img src="/assets/images/products/<?= e($rp['image']) ?>" alt="<?= e($rp['name']) ?>" class="product-img">
                         onerror="this.src='https://placehold.co/300x200/e8f5e9/2e7d4f?text=<?= urlencode($rp['name']) ?>'">
                    <div class="product-info">
                        <h3 class="product-name"><?= e($rp['name']) ?></h3>
                        <div class="product-price"><?= formatPrice($rp['price']) ?> <small>/ <?= e($rp['unit']) ?></small></div>
                        <div class="product-actions">
                            <a href="product-detail.php?slug=<?= e($rp['slug']) ?>" class="btn btn-outline btn-sm btn-block">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
