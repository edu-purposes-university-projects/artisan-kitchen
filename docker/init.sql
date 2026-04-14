-- Initial SQL for BALTACI Artisan Kitchen project
-- This runs automatically when the Postgres container is first created.

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255)
);

-- Orders table
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

-- Registered / known accounts (aligned with demo login; extend as needed)
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

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    item_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL REFERENCES orders(order_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES products(id),
    quantity INT NOT NULL,
    price_at_time_of_purchase DECIMAL(10, 2) NOT NULL
);

-- Optional: seed some example products
INSERT INTO products (name, description, price, image_url) VALUES
('Creamy Chicken Pasta', 'Tender chicken breast in a rich, creamy sauce with fresh herbs and al dente pasta.', 150.00, 'https://placehold.co/600x400?text=Creamy+Chicken+Pasta'),
('Artisan Grilled Chicken', 'Perfectly grilled chicken marinated with Mediterranean spices, served with seasonal vegetables.', 180.00, 'https://placehold.co/600x400?text=Artisan+Grilled+Chicken'),
('Spicy Tomato Pesto', 'A bold combination of sun-dried tomatoes, fresh basil, and a hint of spice over premium pasta.', 140.00, 'https://placehold.co/600x400?text=Spicy+Tomato+Pesto'),
('Boutique Burger', 'Gourmet beef patty with artisan cheese, fresh vegetables, and our signature sauce on a brioche bun.', 160.00, 'https://placehold.co/600x400?text=Boutique+Burger')
ON CONFLICT DO NOTHING;


