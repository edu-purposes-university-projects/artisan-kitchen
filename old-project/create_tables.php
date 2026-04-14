<?php
// One-time script to create required PostgreSQL tables for the project.
// Make sure your database credentials in config/db_connect.php are correct,
// then visit this file in your browser (e.g., http://localhost:8000/create_tables.php).

include __DIR__ . '/config/db_connect.php';

// 1) products table
$productsSql = "
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255)
);
";

// 2) orders table
$ordersSql = "
CREATE TABLE IF NOT EXISTS orders (
    order_id SERIAL PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_username VARCHAR(255),
    customer_address TEXT,
    total_price DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL DEFAULT 'door',
    status VARCHAR(50) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
";

// 3) order_items table
$orderItemsSql = "
CREATE TABLE IF NOT EXISTS order_items (
    item_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL REFERENCES orders(order_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES products(id),
    quantity INT NOT NULL,
    price_at_time_of_purchase DECIMAL(10, 2) NOT NULL
);
";

$queries = [
    'products'    => $productsSql,
    'orders'      => $ordersSql,
    'order_items' => $orderItemsSql,
];

foreach ($queries as $name => $sql) {
    $result = pg_query($db, $sql);
    if ($result === false) {
        echo "Error creating table '{$name}': " . htmlspecialchars(pg_last_error($db)) . "<br>";
    } else {
        echo "Table '{$name}' is ready.<br>";
    }
}

echo "<br>Done. You can now remove or rename this file for security.";


