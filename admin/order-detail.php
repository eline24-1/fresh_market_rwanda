<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Order Details';

$orderId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    redirect('orders.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderStatus = $_POST['order_status'] ?? $order['order_status'];
    $paymentStatus = $_POST['payment_status'] ?? $order['payment_status'];

    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE order_id = ?");
    $stmt->execute([$orderStatus, $paymentStatus, $orderId]);

    redirect('order-detail.php?id=' . $orderId);
}

$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="checkout-grid">
    <div class="admin-form-card" style="max-width:none;">
        <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:14px;">Order <?= e($order['order_number']) ?></h3>
        <p style="margin-bottom:6px;"><strong>Customer:</strong> <?= e($order['full_name']) ?></p>
        <p style="margin-bottom:6px;"><strong>Email:</strong> <?= e($order['email']) ?></p>
        <p style="margin-bottom:6px;"><strong>Phone:</strong> <?= e($order['phone']) ?></p>
        <p style="margin-bottom:6px;"><strong>Address:</strong> <?= e($order['address']) ?>, <?= e($order['district']) ?></p>
        <p style="margin-bottom:6px;"><strong>Payment Method:</strong> <?= e(str_replace('_',' ', ucfirst($order['payment_method']))) ?></p>
        <?php if ($order['momo_number']): ?>
            <p style="margin-bottom:6px;"><strong>Mobile Money Number:</strong> <?= e($order['momo_number']) ?></p>
        <?php endif; ?>
        <?php if ($order['notes']): ?>
            <p style="margin-bottom:6px;"><strong>Notes:</strong> <?= e($order['notes']) ?></p>
        <?php endif; ?>
        <p style="margin-bottom:14px;"><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>

        <h4 style="margin-bottom:10px;">Items</h4>
        <div class="admin-table-wrap" style="margin-bottom:14px;">
            <table class="admin-table">
                <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['product_name']) ?></td>
                        <td><?= formatPrice($item['price']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= formatPrice($item['line_total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary-row"><span>Subtotal</span><span><?= formatPrice($order['subtotal']) ?></span></div>
        <div class="cart-summary-row"><span>Delivery Fee</span><span><?= formatPrice($order['delivery_fee']) ?></span></div>
        <div class="cart-summary-row total"><span>Total</span><span><?= formatPrice($order['total_amount']) ?></span></div>
    </div>

    <div class="admin-form-card" style="max-width:none;align-self:start;">
        <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:14px;">Update Status</h3>
        <form method="post">
            <div class="form-group">
                <label>Order Status</label>
                <select name="order_status">
                    <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $st): ?>
                        <option value="<?= $st ?>" <?= $order['order_status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Status</label>
                <select name="payment_status">
                    <?php foreach (['pending','paid','failed'] as $st): ?>
                        <option value="<?= $st ?>" <?= $order['payment_status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update</button>
            <a href="orders.php" class="btn btn-outline btn-block" style="margin-top:8px;">Back to Orders</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
