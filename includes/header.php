<?php
require_once __DIR__ . '/functions.php';
$cartCount = getCartCount();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Fresh Market Rwanda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= isset($baseUrl) ? $baseUrl : '' ?>assets/css/style.css">
</head>
<body>

<div class="topbar">
    <div class="container">
        <div><i class="fa-solid fa-phone"></i>&nbsp; +250 788 123 456 &nbsp; | &nbsp; <i class="fa-solid fa-location-dot"></i>&nbsp; Kigali, Rwanda</div>
        <div>
            <?php if (isCustomerLoggedIn()): ?>
                Welcome, <?= e($_SESSION['customer_name']) ?> | <a href="<?= $baseUrl ?? '' ?>account.php">My Account</a> | <a href="<?= $baseUrl ?? '' ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= $baseUrl ?? '' ?>login.php">Login</a> | <a href="<?= $baseUrl ?? '' ?>register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<header class="header">
    <div class="container header-inner">
        <a href="<?= $baseUrl ?? '' ?>index.php" class="logo">
            <i class="fa-solid fa-leaf"></i> Fresh Market <span>Rwanda</span>
        </a>

        <nav class="navbar">
            <a href="<?= $baseUrl ?? '' ?>index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
            <a href="<?= $baseUrl ?? '' ?>products.php" class="<?= $currentPage === 'products.php' ? 'active' : '' ?>">Shop</a>
            <a href="<?= $baseUrl ?? '' ?>products.php?category=all" class="<?= $currentPage === 'categories.php' ? 'active' : '' ?>">Categories</a>
            <a href="<?= $baseUrl ?? '' ?>about.php" class="<?= $currentPage === 'about.php' ? 'active' : '' ?>">About</a>
            <a href="<?= $baseUrl ?? '' ?>contact.php" class="<?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a>
        </nav>

        <div class="header-actions">
            <a href="<?= $baseUrl ?? '' ?>cart.php" class="cart-link">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <button class="menu-toggle"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>
