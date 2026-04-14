<?php
include('../config/db_connect.php');
require_once('../auth/jwt.php');

// Only admins can delete products
require_role('admin');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id > 0) {
        $query = "DELETE FROM products WHERE id = $1";
        $params = [$id];

        pg_query_params($db, $query, $params);
    }
}

header('Location: products.php');
exit;

