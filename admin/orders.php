<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Orders';

$statusFilter = $_GET['status'] ?? '';

$sql = "SELECT * FROM orders";
$params = [];
if ($statusFilter !== '') {
    $sql .= " WHERE order_status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="filter-bar" style="justify-content:flex-start;margin-bottom:20px;">
    <a href="orders.php" class="<?= $statusFilter === '' ? 'active' : '' ?>">All</a>
    <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $st): ?>
        <a href="orders.php?status=<?= $st ?>" class="<?= $statusFilter === $st ? 'active' : '' ?>"><?= ucfirst($st) ?></a>
    <?php endforeach; ?>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="8" style="text-align:center;">No orders found.</td></tr>
            <?php endif; ?>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= e($o['order_number']) ?></td>
                <td><?= e($o['full_name']) ?></td>
                <td><?= e($o['phone']) ?></td>
                <td><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
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
