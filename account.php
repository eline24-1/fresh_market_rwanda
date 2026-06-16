<?php
$baseUrl = '';
$pageTitle = 'My Account';
require_once __DIR__ . '/includes/functions.php';

if (!isCustomerLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$_SESSION['customer_id']]);
$customer = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">My Account</h1>

        <div class="checkout-grid">
            <div>
                <h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:16px;">Order History</h3>
                <?php if (empty($orders)): ?>
                    <div class="empty-cart">
                        <i class="fa-solid fa-receipt"></i>
                        <h3>No orders yet</h3>
                        <p style="margin:10px 0 20px;color:var(--gray-500);">Your past orders will appear here.</p>
                        <a href="products.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td><?= e($o['order_number']) ?></td>
                                    <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                                    <td><?= formatPrice($o['total_amount']) ?></td>
                                    <td><span class="status-pill status-<?= e($o['payment_status']) ?>"><?= e(ucfirst($o['payment_status'])) ?></span></td>
                                    <td><span class="status-pill status-<?= e($o['order_status']) ?>"><?= e(ucfirst($o['order_status'])) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="order-summary-box">
                <h3>Account Details</h3>
                <p style="margin-bottom:8px;"><strong>Name:</strong> <?= e($customer['full_name']) ?></p>
                <p style="margin-bottom:8px;"><strong>Email:</strong> <?= e($customer['email']) ?></p>
                <p style="margin-bottom:8px;"><strong>Phone:</strong> <?= e($customer['phone']) ?></p>
                <p style="margin-bottom:8px;"><strong>Address:</strong> <?= e($customer['address'] ?: 'Not set') ?></p>
                <p style="margin-bottom:16px;"><strong>District:</strong> <?= e($customer['district'] ?: 'Not set') ?></p>
                <a href="logout.php" class="btn btn-outline btn-block">Logout</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
