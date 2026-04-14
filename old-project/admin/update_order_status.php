<?php
include('../config/db_connect.php');
require_once('../auth/jwt.php');

// Only admins can update order status
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: orders.php');
    exit;
}

$orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
$status  = isset($_POST['status']) ? trim($_POST['status']) : '';

$allowedStatuses = ['Pending', 'Preparing', 'Shipping', 'Completed', 'Cancelled'];

if ($orderId <= 0 || !in_array($status, $allowedStatuses, true)) {
    header('Location: orders.php');
    exit;
}

$query  = "UPDATE orders SET status = $1 WHERE order_id = $2";
$params = [$status, $orderId];

pg_query_params($db, $query, $params);

header('Location: orders.php');
exit;


