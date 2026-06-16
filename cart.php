<?php
$baseUrl = '';
$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);

    switch ($action) {
        case 'add':
            $quantity = max(1, (int)($_POST['quantity'] ?? 1));
            // Check stock
            $stmt = $pdo->prepare("SELECT stock_quantity, name FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $p = $stmt->fetch();
            if ($p && $p['stock_quantity'] > 0) {
                addToCart($productId, $quantity);
                $message = e($p['name']) . ' added to cart.';
            }
            break;

        case 'update':
            $quantity = max(0, (int)($_POST['quantity'] ?? 1));
            updateCartItem($productId, $quantity);
            break;

        case 'remove':
            removeFromCart($productId);
            break;
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // not used, but kept for potential AJAX extension
    }
    redirect('cart.php');
}

$cartItems = getCartItems($pdo);
$subtotal = getCartSubtotal($pdo);
$deliveryFee = $subtotal > 0 ? 1500 : 0;
$total = $subtotal + $deliveryFee;

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Your Shopping Cart</h1>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Your cart is empty</h3>
                <p style="margin:10px 0 20px;color:var(--gray-500);">Looks like you haven't added anything yet.</p>
                <a href="products.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item):
                        $p = $item['product']; ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="assets/images/products/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"
                                         onerror="this.src='https://placehold.co/60x60/e8f5e9/2e7d4f?text=%20'">
                                    <div>
                                        <strong><?= e($p['name']) ?></strong><br>
                                        <small style="color:var(--gray-500);">per <?= e($p['unit']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= formatPrice($p['price']) ?></td>
                            <td>
                                <form action="cart.php" method="post" class="cart-qty-form">
                                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                                    <input type="hidden" name="action" value="update">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $p['stock_quantity'] ?>">
                                    <button type="submit" class="btn btn-outline btn-sm">Update</button>
                                </form>
                            </td>
                            <td><strong><?= formatPrice($item['line_total']) ?></strong></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Remove"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <div class="cart-summary">
                <div class="cart-summary-row">
                    <span>Subtotal</span>
                    <span><?= formatPrice($subtotal) ?></span>
                </div>
                <div class="cart-summary-row">
                    <span>Delivery Fee</span>
                    <span><?= formatPrice($deliveryFee) ?></span>
                </div>
                <div class="cart-summary-row total">
                    <span>Total</span>
                    <span><?= formatPrice($total) ?></span>
                </div>
                <a href="checkout.php" class="btn btn-primary btn-block" style="margin-top:16px;">Proceed to Checkout</a>
                <a href="products.php" class="btn btn-outline btn-block" style="margin-top:10px;">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
