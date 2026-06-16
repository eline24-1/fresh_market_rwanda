<?php
$baseUrl = '';
$pageTitle = 'Login';
require_once __DIR__ . '/includes/functions.php';

if (isCustomerLoggedIn()) {
    redirect('account.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer && password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['full_name'];
            redirect('account.php');
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-card">
            <h2 class="form-title">Welcome Back</h2>
            <p class="form-subtitle">Login to your Fresh Market Rwanda account</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= e($email) ?>" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="form-footer-link">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
