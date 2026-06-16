<?php
$baseUrl = '';
$pageTitle = 'Register';
require_once __DIR__ . '/includes/functions.php';

if (isCustomerLoggedIn()) {
    redirect('account.php');
}

$errors = [];
$fullName = $email = $phone = $address = $district = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') $errors[] = 'Full name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if ($phone === '') $errors[] = 'Phone number is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT customer_id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with this email already exists.';
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, email, phone, password, address, district) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $email, $phone, $hashed, $address, $district]);

        $_SESSION['customer_id'] = $pdo->lastInsertId();
        $_SESSION['customer_name'] = $fullName;

        redirect('account.php');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-card">
            <h2 class="form-title">Create an Account</h2>
            <p class="form-subtitle">Join Fresh Market Rwanda for faster checkout</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" value="<?= e($fullName) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?= e($email) ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="tel" name="phone" value="<?= e($phone) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <input type="text" name="district" value="<?= e($district) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?= e($address) ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>
            <p class="form-footer-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
