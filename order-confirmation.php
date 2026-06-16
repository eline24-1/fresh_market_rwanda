<?php
$baseUrl = '';
$pageTitle = 'Order Confirmation';
require_once __DIR__ . '/includes/functions.php';

$orderNumber = $_GET['order'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$orderNumber]);
$order = $stmt->fetch();

if (!$order) {
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order['order_id']]);
$items = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="confirmation-box">
            <div class="icon"><i class="fa-solid fa-check"></i></div>
            <h1>Thank You, <?= e($order['full_name']) ?>!</h1>
            <p style="color:var(--gray-500);">Your order has been placed successfully. We'll deliver it to you soon.</p>

            <div class="order-number-box">
                Order Number: <?= e($order['order_number']) ?>
            </div>

            <div style="text-align:left;max-width:400px;margin:0 auto 20px;">
                <?php foreach ($items as $item): ?>
                    <div class="order-item-row">
                        <span><?= e($item['product_name']) ?> &times; <?= $item['quantity'] ?></span>
                        <span><?= formatPrice($item['line_total']) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="cart-summary-row total">
                    <span>Total</span>
                    <span><?= formatPrice($order['total_amount']) ?></span>
                </div>
            </div>

            <?php if ($order['payment_method'] === 'mobile_money'): ?>
                <div class="alert alert-info" style="text-align:left;">
                    <i class="fa-solid fa-circle-info"></i> A Mobile Money payment request for <strong><?= formatPrice($order['total_amount']) ?></strong>
                    will be sent to <strong><?= e($order['momo_number']) ?></strong>. Please confirm the prompt on your phone to complete payment.
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="text-align:left;">
                    <i class="fa-solid fa-circle-info"></i> You chose Cash on Delivery. Please have <strong><?= formatPrice($order['total_amount']) ?></strong> ready upon delivery.
                </div>
            <?php endif; ?>

            <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            <a href="index.php" class="btn btn-outline">Back to Home</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
