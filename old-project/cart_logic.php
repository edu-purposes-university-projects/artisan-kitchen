<?php
session_start();

require_once(__DIR__ . '/auth/jwt.php');

// Only logged-in customers can modify the cart
require_role('customer');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];

    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($productId > 0) {
        // For now, store one entry per click; quantity can be derived from counts.
        $_SESSION['cart'][] = $productId;
    }
}

header('Location: menu.php');
exit;


