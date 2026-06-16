<?php
requireAdmin();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Admin | Fresh Market Rwanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="brand"><i class="fa-solid fa-leaf"></i> Fresh Market</div>
        <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>"><i class="fa-solid fa-gauge"></i> &nbsp; Dashboard</a>
        <a href="products.php" class="<?= $currentPage === 'products.php' ? 'active' : '' ?>"><i class="fa-solid fa-box"></i> &nbsp; Products</a>
        <a href="add-product.php" class="<?= $currentPage === 'add-product.php' ? 'active' : '' ?>" style="padding-left:2.4rem;font-size:.88rem;"><i class="fa-solid fa-plus"></i> &nbsp; Add Product</a>
        <a href="categories.php" class="<?= $currentPage === 'categories.php' ? 'active' : '' ?>"><i class="fa-solid fa-tags"></i> &nbsp; Categories</a>
        <a href="orders.php" class="<?= $currentPage === 'orders.php' ? 'active' : '' ?>"><i class="fa-solid fa-receipt"></i> &nbsp; Orders</a>
        <a href="customers.php" class="<?= $currentPage === 'customers.php' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> &nbsp; Customers</a>
        <a href="../index.php"><i class="fa-solid fa-store"></i> &nbsp; View Store</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> &nbsp; Logout</a>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <button class="admin-menu-toggle btn btn-outline btn-sm" style="display:none;"><i class="fa-solid fa-bars"></i></button>
            <h1><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h1>
            <div>Welcome, <strong><?= e($_SESSION['admin_name']) ?></strong></div>
        </div>
