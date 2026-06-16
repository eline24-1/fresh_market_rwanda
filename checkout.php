<?php
$baseUrl = '';
$pageTitle = 'Checkout';
require_once __DIR__ . '/includes/functions.php';

$cartItems = getCartItems($pdo);
if (empty($cartItems)) {
    redirect('cart.php');
}

$subtotal = getCartSubtotal($pdo);
$deliveryFee = 1500;
$total = $subtotal + $deliveryFee;

$errors = [];

// Pre-fill if logged in
$fullName = $_SESSION['customer_name'] ?? '';
$email = '';
$phone = '';
$address = '';
$district = '';

if (isCustomerLoggedIn()) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$_SESSION['customer_id']]);
    $c = $stmt->fetch();
    if ($c) {
        $fullName = $c['full_name'];
        $email = $c['email'];
        $phone = $c['phone'];
        $address = $c['address'];
        $district = $c['district'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'mobile_money';
    $momoNumber = trim($_POST['momo_number'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($fullName === '') $errors[] = 'Full name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if ($phone === '') $errors[] = 'Phone number is required.';
    if ($address === '') $errors[] = 'Delivery address is required.';
    if ($district === '') $errors[] = 'District is required.';
    if ($paymentMethod === 'mobile_money' && $momoNumber === '') {
        $errors[] = 'Mobile Money number is required for Mobile Money payment.';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $orderNumber = generateOrderNumber();
            $customerId = $_SESSION['customer_id'] ?? null;

            $stmt = $pdo->prepare("INSERT INTO orders
                (customer_id, order_number, full_name, email, phone, address, district, payment_method, momo_number, subtotal, delivery_fee, total_amount, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $customerId, $orderNumber, $fullName, $email, $phone, $address, $district,
                $paymentMethod, $paymentMethod === 'mobile_money' ? $momoNumber : null,
                $subtotal, $deliveryFee, $total, $notes
            ]);
            $orderId = $pdo->lastInsertId();

            foreach ($cartItems as $item) {
                $p = $item['product'];
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, line_total)
                                        VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$orderId, $p['product_id'], $p['name'], $p['price'], $item['quantity'], $item['line_total']]);

                // Reduce stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = GREATEST(stock_quantity - ?, 0) WHERE product_id = ?");
                $stmt->execute([$item['quantity'], $p['product_id']]);
            }

            $pdo->commit();

            // Clear cart
            $_SESSION['cart'] = [];

            redirect('order-confirmation.php?order=' . $orderNumber);

        } catch (Exception $ex) {
            $pdo->rollBack();
            $errors[] = 'Something went wrong while placing your order. Please try again.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Checkout</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin-left:18px;">
                    <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="checkout-grid">
            <div class="form-card" style="max-width:none;">
                <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:18px;">Delivery Details</h3>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="full_name" value="<?= e($fullName) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?= e($email) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" value="<?= e($phone) ?>" placeholder="07XXXXXXXX" required>
                        </div>
                        <div class="form-group">
                            <label>District *</label>
                            <input type="text" name="district" value="<?= e($district) ?>" placeholder="e.g. Gasabo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Delivery Address *</label>
                        <input type="text" name="address" value="<?= e($address) ?>" placeholder="Street, sector, cell" required>
                    </div>
                    <div class="form-group">
                        <label>Order Notes (optional)</label>
                        <textarea name="notes" rows="3" placeholder="Delivery instructions..."></textarea>
                    </div>

                    <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin:24px 0 12px;">Payment Method</h3>
                    <div class="payment-options">
                        <label class="payment-option selected">
                            <input type="radio" name="payment_method" value="mobile_money" checked>
                            <i class="fa-solid fa-mobile-screen-button"></i> Mobile Money (MTN / Airtel)
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cash_on_delivery">
                            <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery
                        </label>
                    </div>
                    <div class="form-group" id="momo-fields">
                        <label>Mobile Money Number *</label>
                        <input type="tel" name="momo_number" placeholder="07XXXXXXXX">
                        <small style="color:var(--gray-500);">You will receive a payment prompt on this number after placing your order.</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-lock"></i> Place Order</button>
                </form>
            </div>

            <div class="order-summary-box">
                <h3>Order Summary</h3>
                <?php foreach ($cartItems as $item): $p = $item['product']; ?>
                    <div class="order-item-row">
                        <span><?= e($p['name']) ?> &times; <?= $item['quantity'] ?></span>
                        <span><?= formatPrice($item['line_total']) ?></span>
                    </div>
                <?php endforeach; ?>
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
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
