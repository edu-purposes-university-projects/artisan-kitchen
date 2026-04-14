<?php
// Database connection using PostgreSQL
// Default values are aligned with docker-compose.yml.
// You can override them with environment variables if needed.

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '5432';
$dbName = getenv('DB_NAME') ?: 'baltaci_kitchen';
$dbUser = getenv('DB_USER') ?: 'baltaci_user';
$dbPassword = getenv('DB_PASSWORD') ?: 'baltaci_password';

$connectionString = sprintf(
    "host=%s port=%s dbname=%s user=%s password=%s",
    $dbHost,
    $dbPort,
    $dbName,
    $dbUser,
    $dbPassword
);

$db = pg_connect($connectionString);

if (!$db) {
    die('Database connection failed.');
}


