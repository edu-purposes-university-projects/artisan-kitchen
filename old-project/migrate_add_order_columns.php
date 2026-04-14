<?php
// One-time migration script to add missing columns to the orders table.
// Visit this file in your browser once: http://localhost:3000/migrate_add_order_columns.php

include __DIR__ . '/config/db_connect.php';

$sql = "
ALTER TABLE orders
    ADD COLUMN IF NOT EXISTS customer_address TEXT,
    ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) NOT NULL DEFAULT 'door',
    ADD COLUMN IF NOT EXISTS customer_username VARCHAR(255);
";

$result = pg_query($db, $sql);

if ($result === false) {
    echo 'Error altering orders table: ' . htmlspecialchars(pg_last_error($db));
} else {
    echo 'Orders table updated successfully. Columns customer_address and payment_method are now present.';
}


