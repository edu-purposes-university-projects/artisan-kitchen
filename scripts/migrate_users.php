<?php

/**
 * Creates the `users` table and seeds admin + customer (one-time / safe to re-run).
 *
 * Usage (from project root):
 *   php scripts/migrate_users.php
 *
 * Uses the same DB settings as the app (env vars or defaults in App\Database).
 */

declare(strict_types=1);

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

use App\Database;

$db = Database::connection();
if ($db === null) {
    fwrite(STDERR, "Database connection failed. Start Postgres (e.g. docker compose up -d db) and check DB_* env vars.\n");
    exit(1);
}

$create = <<<'SQL'
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
)
SQL;

if (!pg_query($db, $create)) {
    fwrite(STDERR, 'CREATE TABLE failed: ' . pg_last_error($db) . "\n");
    exit(1);
}
echo "OK: users table exists.\n";

$seed = <<<'SQL'
INSERT INTO users (username, role) VALUES
    ('admin', 'admin'),
    ('customer', 'customer')
ON CONFLICT (username) DO NOTHING
SQL;

if (!pg_query($db, $seed)) {
    fwrite(STDERR, 'INSERT failed: ' . pg_last_error($db) . "\n");
    exit(1);
}
echo "OK: demo users seeded (admin, customer) where missing.\n";
echo "Done. Refresh /admin/users in the browser.\n";
