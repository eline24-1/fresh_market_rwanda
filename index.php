<?php
$baseUrl = '';
$pageTitle = 'Home';
require_once __DIR__ . '/includes/functions.php';

// Featured products
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p
                        JOIN categories c ON p.category_id = c.category_id
                        WHERE p.is_featured = 1 AND p.status = 'active'
                        ORDER BY p.created_at DESC LIMIT 8");
$stmt->execute();
$featuredProducts = $stmt->fetchAll();

// Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$categoryIcons = [
    'fruits' => 'fa-apple-whole',
    'vegetables' => 'fa-carrot',
    'dairy-eggs' => 'fa-egg',
    'grains-cereals' => 'fa-wheat-awn',
    'beverages' => 'fa-bottle-water',
    'meat-poultry' => 'fa-drumstick-bite',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Fresh From the Farm, Delivered to Your Door</h1>
        <p>Shop the best quality fruits, vegetables, dairy, grains and more — sourced locally across Rwanda and delivered fast with Mobile Money payment.</p>
        <a href="products.php" class="btn btn-accent">Shop Now</a>
        <a href="#categories" class="btn btn-outline" style="background:transparent;border-color:#fff;color:#fff;">Browse Categories</a>
    </div>
</section>

<section class="section" id="categories">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <p class="section-subtitle">Find exactly what you need from our wide range of fresh products</p>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="products.php?category=<?= e($cat['slug']) ?>" class="category-card">
                    <div class="icon"><i class="fa-solid <?= $categoryIcons[$cat['slug']] ?? 'fa-store' ?>"></i></div>
                    <h3><?= e($cat['name']) ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background:var(--white);">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">Hand-picked fresh items, popular with our customers this week</p>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <span class="badge-featured">Featured</span>
                    <img src="assets/images/products/<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>" class="product-img"
                         onerror="this.src='https://placehold.co/300x200/e8f5e9/2e7d4f?text=<?= urlencode($product['name']) ?>'">
                    <div class="product-info">
                        <span class="product-category"><?= e($product['category_name']) ?></span>
                        <h3 class="product-name"><?= e($product['name']) ?></h3>
                        <div class="product-price"><?= formatPrice($product['price']) ?> <small>/ <?= e($product['unit']) ?></small></div>
                        <div class="product-actions">
                            <a href="product-detail.php?slug=<?= e($product['slug']) ?>" class="btn btn-outline btn-sm">View</a>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:30px;">
            <a href="products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="category-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px,1fr));">
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Fast Delivery</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Same-day delivery across Kigali</p>
            </div>
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <h3>Mobile Money</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Pay easily with MTN &amp; Airtel Money</p>
            </div>
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-leaf"></i></div>
                <h3>100% Fresh</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Sourced directly from local farmers</p>
            </div>
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-rotate-left"></i></div>
                <h3>Easy Returns</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Hassle-free replacements on issues</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
