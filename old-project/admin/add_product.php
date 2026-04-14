<?php
include('../config/db_connect.php');
require_once('../auth/jwt.php');

// Only admins can add products
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : null;

    if ($name !== '' && $price !== '') {
        $query = "INSERT INTO products (name, description, price, image_url) VALUES ($1, $2, $3, $4)";
        $params = [$name, $description, $price, $image_url];

        pg_query_params($db, $query, $params);
    }
}

header('Location: products.php');
exit;

