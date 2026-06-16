<?php
$baseUrl = '';
$pageTitle = 'About Us';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding:50px 0;">
    <div class="container">
        <h1>About Fresh Market Rwanda</h1>
        <p>Connecting local farmers with families and businesses across Rwanda through a simple, reliable online marketplace.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="product-detail" style="align-items:center;">
            <img src="https://placehold.co/600x400/e8f5e9/2e7d4f?text=Fresh+Market+Rwanda" alt="About Fresh Market Rwanda">
            <div>
                <h2 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:14px;">Our Story</h2>
                <p style="color:var(--gray-500);margin-bottom:14px;">
                    Fresh Market Rwanda started with a simple goal: make it easy for people in Kigali and beyond to access fresh,
                    high-quality groceries without leaving home. We partner directly with farmers and trusted suppliers across
                    Rwanda's provinces to bring you fruits, vegetables, dairy, grains, meat and beverages at fair prices.
                </p>
                <p style="color:var(--gray-500);margin-bottom:14px;">
                    With our online platform, you can browse products, add them to your cart, and pay conveniently using
                    Mobile Money or cash on delivery. Our delivery team ensures your order reaches you quickly and in great condition.
                </p>
                <p style="color:var(--gray-500);">
                    Whether you're a busy professional, a family, or a restaurant owner, Fresh Market Rwanda is committed to bringing
                    the market to your doorstep.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:var(--white);">
    <div class="container">
        <h2 class="section-title">Our Mission</h2>
        <p class="section-subtitle">Quality, freshness and convenience — every single time</p>
        <div class="category-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px,1fr));">
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-handshake"></i></div>
                <h3>Support Local Farmers</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Fair prices for the people who grow our food</p>
            </div>
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-shield-heart"></i></div>
                <h3>Quality Assurance</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Every item checked before delivery</p>
            </div>
            <div class="category-card">
                <div class="icon"><i class="fa-solid fa-bolt"></i></div>
                <h3>Convenience First</h3>
                <p style="font-size:0.85rem;color:var(--gray-500);margin-top:6px;">Order in minutes, delivered same day</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
