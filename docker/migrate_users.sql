-- Run manually if your database was created before the users table existed:
--   psql -U baltaci_user -d baltaci_kitchen -f docker/migrate_users.sql

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

INSERT INTO users (username, role) VALUES
    ('admin', 'admin'),
    ('customer', 'customer')
ON CONFLICT (username) DO NOTHING;
