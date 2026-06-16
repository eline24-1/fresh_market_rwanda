<?php
/**
 * Helper Functions
 * Fresh Market Rwanda
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/** Format a number as Rwandan Francs */
function formatPrice($amount) {
    return number_format($amount, 0) . ' RWF';
}

/** Sanitize output */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect helper */
function redirect($url) {
    header("Location: $url");
    exit;
}

/** Get current cart from session */
function getCart() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

/** Add item to cart */
function addToCart($productId, $quantity = 1) {
    $cart = getCart();
    if (isset($cart[$productId])) {
        $cart[$productId] += $quantity;
    } else {
        $cart[$productId] = $quantity;
    }
    $_SESSION['cart'] = $cart;
}

/** Update item quantity */
function updateCartItem($productId, $quantity) {
    $cart = getCart();
    if ($quantity <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = $quantity;
    }
    $_SESSION['cart'] = $cart;
}

/** Remove item from cart */
function removeFromCart($productId) {
    $cart = getCart();
    unset($cart[$productId]);
    $_SESSION['cart'] = $cart;
}

/** Get total number of items in cart */
function getCartCount() {
    $cart = getCart();
    return array_sum($cart);
}

/** Get cart items with product details */
function getCartItems($pdo) {
    $cart = getCart();
    if (empty($cart)) return [];

    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $items = [];
    foreach ($products as $product) {
        $qty = $cart[$product['product_id']];
        $items[] = [
            'product'  => $product,
            'quantity' => $qty,
            'line_total' => $product['price'] * $qty,
        ];
    }
    return $items;
}

/** Get cart subtotal */
function getCartSubtotal($pdo) {
    $items = getCartItems($pdo);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['line_total'];
    }
    return $total;
}

/** Check if customer is logged in */
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']);
}

/** Check if admin is logged in */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/** Require admin login or redirect */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect('login.php');
    }
}

/** Generate a unique order number */
function generateOrderNumber() {
    return 'FMR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}
