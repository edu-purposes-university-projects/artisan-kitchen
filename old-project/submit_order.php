<?php
session_start();

require_once(__DIR__ . '/auth/jwt.php');
require_role('customer');

include __DIR__ . '/config/db_connect.php';

// Current logged-in customer (from JWT)
$user = current_user();
$customerUsername = isset($user['username']) ? $user['username'] : null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$customerName = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
$customerPhone = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
$customerAddress = isset($_POST['customer_address']) ? trim($_POST['customer_address']) : '';
$paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'door';

// If coming from online payment page, use details stored in session
if ($customerName === '' || $customerPhone === '' || $customerAddress === '') {
    if (!empty($_SESSION['pending_order'])) {
        $pending = $_SESSION['pending_order'];
        $customerName = $pending['customer_name'] ?? $customerName;
        $customerPhone = $pending['customer_phone'] ?? $customerPhone;
        $customerAddress = $pending['customer_address'] ?? $customerAddress;
    }
}

if ($customerName === '' || $customerPhone === '' || $customerAddress === '') {
    header('Location: checkout.php');
    exit;
}

$cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cartItems)) {
    header('Location: menu.php');
    exit;
}

$counts = array_count_values(array_map('intval', $cartItems));
$productIds = array_keys($counts);

if (empty($productIds)) {
    header('Location: menu.php');
    exit;
}

// Fetch product details to ensure prices are trusted
$placeholders = [];
$params = [];
foreach ($productIds as $index => $id) {
    $placeholders[] = '$' . ($index + 1);
    $params[] = $id;
}

$query = 'SELECT id, name, price FROM products WHERE id IN (' . implode(', ', $placeholders) . ')';
$result = pg_query_params($db, $query, $params);

if (!$result || pg_num_rows($result) === 0) {
    header('Location: menu.php');
    exit;
}

$orderItems = [];
$totalPrice = 0.00;

while ($row = pg_fetch_assoc($result)) {
    $id = (int) $row['id'];
    if (!isset($counts[$id])) {
        continue;
    }
    $quantity = $counts[$id];
    $price = (float) $row['price'];
    $lineTotal = $price * $quantity;

    $orderItems[] = [
        'product_id' => $id,
        'quantity' => $quantity,
        'price' => $price,
    ];

    $totalPrice += $lineTotal;
}

if (empty($orderItems)) {
    header('Location: menu.php');
    exit;
}

// Insert into orders table
$orderQuery = "INSERT INTO orders (customer_name, customer_phone, customer_username, customer_address, total_price, payment_method, status) VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING order_id";
$orderParams = [$customerName, $customerPhone, $customerUsername, $customerAddress, $totalPrice, $paymentMethod, 'Pending'];
$orderResult = pg_query_params($db, $orderQuery, $orderParams);

if (!$orderResult) {
    header('Location: checkout.php');
    exit;
}

$orderRow = pg_fetch_assoc($orderResult);
$orderId = isset($orderRow['order_id']) ? (int) $orderRow['order_id'] : 0;

if ($orderId <= 0) {
    header('Location: checkout.php');
    exit;
}

// Insert order items
foreach ($orderItems as $item) {
    $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price_at_time_of_purchase) VALUES ($1, $2, $3, $4)";
    $itemParams = [
        $orderId,
        $item['product_id'],
        $item['quantity'],
        $item['price'],
    ];
    pg_query_params($db, $itemQuery, $itemParams);
}

// Clear cart and pending order details
unset($_SESSION['cart']);
unset($_SESSION['pending_order']);

// Remember last payment method for thank-you message
$_SESSION['last_payment_method'] = $paymentMethod;
$_SESSION['last_order_phone'] = $customerPhone;

header('Location: thank_you.php');
exit;


