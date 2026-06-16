<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = 'Customers';

$customers = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM orders o WHERE o.customer_id = c.customer_id) AS order_count,
                           (SELECT COALESCE(SUM(total_amount),0) FROM orders o WHERE o.customer_id = c.customer_id) AS total_spent
                           FROM customers c ORDER BY c.created_at DESC")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>District</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr><td colspan="7" style="text-align:center;">No registered customers yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($customers as $c): ?>
            <tr>
                <td><?= e($c['full_name']) ?></td>
                <td><?= e($c['email']) ?></td>
                <td><?= e($c['phone']) ?></td>
                <td><?= e($c['district'] ?: '-') ?></td>
                <td><?= $c['order_count'] ?></td>
                <td><?= formatPrice($c['total_spent']) ?></td>
                <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
