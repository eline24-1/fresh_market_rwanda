<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Dashboard';

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE order_status != 'cancelled'")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= 5")->fetchColumn();

$recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 6")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total Products</div>
        <div class="value"><?= $totalProducts ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Total Orders</div>
        <div class="value"><?= $totalOrders ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Pending Orders</div>
        <div class="value"><?= $pendingOrders ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Registered Customers</div>
        <div class="value"><?= $totalCustomers ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Total Revenue</div>
        <div class="value"><?= formatPrice($totalRevenue) ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Low Stock Products</div>
        <div class="value"><?= $lowStock ?></div>
    </div>
</div>

<h3 style="font-family:var(--font-heading);color:var(--green-dark);margin-bottom:14px;">Recent Orders</h3>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="7" style="text-align:center;">No orders yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($recentOrders as $o): ?>
            <tr>
                <td><?= e($o['order_number']) ?></td>
                <td><?= e($o['full_name']) ?></td>
                <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                <td><?= formatPrice($o['total_amount']) ?></td>
                <td><span class="status-pill status-<?= e($o['payment_status']) ?>"><?= e(ucfirst($o['payment_status'])) ?></span></td>
                <td><span class="status-pill status-<?= e($o['order_status']) ?>"><?= e(ucfirst($o['order_status'])) ?></span></td>
                <td class="action-links"><a href="order-detail.php?id=<?= $o['order_id'] ?>" class="edit">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
