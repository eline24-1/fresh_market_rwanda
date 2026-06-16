<?php
require_once __DIR__ . '/../includes/functions.php';

if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Fresh Market Rwanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background: linear-gradient(135deg, var(--green-dark), var(--green)); }
    </style>
</head>
<body>
    <div class="form-card">
        <h2 class="form-title"><i class="fa-solid fa-leaf"></i> Fresh Market Rwanda</h2>
        <p class="form-subtitle">Admin Dashboard Login</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= e($username) ?>" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="form-footer-link"><a href="../index.php">&larr; Back to Store</a></p>
        <p style="text-align:center;margin-top:10px;font-size:0.8rem;color:var(--gray-500);">Default: admin / admin123</p>
    </div>
</body>
</html>
