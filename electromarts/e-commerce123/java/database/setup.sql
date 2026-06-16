-- ElectroMart Database Setup
CREATE DATABASE IF NOT EXISTS electromart;
USE electromart;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    stock_quantity INT DEFAULT 0,
    image VARCHAR(255),
    specifications TEXT,
    brand VARCHAR(100),
    model VARCHAR(100),
    warranty VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Shopping cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Wishlist table
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- Reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payment transactions table
CREATE TABLE payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(100),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Newsletter subscribers
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'unsubscribed') DEFAULT 'active'
);

-- Coupons table
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0,
    max_uses INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    expires_at DATETIME,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Smartphones', 'Latest smartphones and mobile devices', 'smartphones.jpg'),
('Laptops', 'Laptops and notebooks for all needs', 'laptops.jpg'),
('Tablets', 'Tablets and iPad devices', 'tablets.jpg'),
('Audio', 'Headphones, speakers, and audio equipment', 'audio.jpg'),
('Gaming', 'Gaming consoles and accessories', 'gaming.jpg'),
('Accessories', 'Phone cases, chargers, and accessories', 'accessories.jpg');

-- Insert sample products with more variety
INSERT INTO products (name, description, price, category_id, stock_quantity, image, brand, model, warranty) VALUES
-- Smartphones
('iPhone 15 Pro', 'Latest iPhone with A17 Pro chip and titanium design. Features advanced camera system and Action Button.', 999.99, 1, 50, 'iphone15pro.jpg', 'Apple', 'iPhone 15 Pro', '1 Year'),
('Samsung Galaxy S24', 'Flagship Android phone with AI features and improved camera capabilities.', 899.99, 1, 30, 'galaxys24.jpg', 'Samsung', 'Galaxy S24', '1 Year'),
('Google Pixel 8', 'Pure Android experience with exceptional camera and AI features.', 699.99, 1, 25, 'pixel8.jpg', 'Google', 'Pixel 8', '1 Year'),
('OnePlus 12', 'Flagship killer with fast charging and premium design.', 799.99, 1, 20, 'oneplus12.jpg', 'OnePlus', 'OnePlus 12', '1 Year'),
('iPhone 14', 'Previous generation iPhone with excellent performance and camera.', 799.99, 1, 35, 'iphone14.jpg', 'Apple', 'iPhone 14', '1 Year'),

-- Laptops
('MacBook Pro 14"', 'Professional laptop with M3 chip, perfect for creative professionals.', 1999.99, 2, 20, 'macbookpro14.jpg', 'Apple', 'MacBook Pro', '1 Year'),
('Dell XPS 13', 'Ultra-portable Windows laptop with premium build quality.', 1299.99, 2, 25, 'dellxps13.jpg', 'Dell', 'XPS 13', '1 Year'),
('MacBook Air M2', 'Lightweight laptop with impressive battery life and performance.', 1199.99, 2, 30, 'macbookair.jpg', 'Apple', 'MacBook Air', '1 Year'),
('HP Spectre x360', 'Convertible laptop with stunning design and versatility.', 1399.99, 2, 15, 'hpspectre.jpg', 'HP', 'Spectre x360', '1 Year'),
('Lenovo ThinkPad X1', 'Business laptop with legendary keyboard and durability.', 1599.99, 2, 18, 'thinkpadx1.jpg', 'Lenovo', 'ThinkPad X1', '1 Year'),

-- Tablets
('iPad Air', 'Versatile tablet for work and play with M1 chip.', 599.99, 3, 40, 'ipadair.jpg', 'Apple', 'iPad Air', '1 Year'),
('iPad Pro 12.9"', 'Professional tablet with M2 chip and Liquid Retina display.', 1099.99, 3, 25, 'ipadpro.jpg', 'Apple', 'iPad Pro', '1 Year'),
('Samsung Galaxy Tab S9', 'Android tablet with S Pen and premium features.', 799.99, 3, 20, 'galaxytab.jpg', 'Samsung', 'Galaxy Tab S9', '1 Year'),
('Microsoft Surface Pro', 'Laptop replacement tablet with Windows 11.', 999.99, 3, 15, 'surface.jpg', 'Microsoft', 'Surface Pro', '1 Year'),

-- Audio
('Sony WH-1000XM5', 'Premium noise-canceling headphones with exceptional sound quality.', 399.99, 4, 60, 'sonywh1000xm5.jpg', 'Sony', 'WH-1000XM5', '1 Year'),
('AirPods Pro', 'Wireless earbuds with active noise cancellation and spatial audio.', 249.99, 4, 80, 'airpodspro.jpg', 'Apple', 'AirPods Pro', '1 Year'),
('Bose QuietComfort', 'Comfortable noise-canceling headphones for all-day wear.', 329.99, 4, 45, 'boseqc.jpg', 'Bose', 'QuietComfort', '1 Year'),
('Sony WF-1000XM4', 'Premium wireless earbuds with industry-leading noise cancellation.', 279.99, 4, 55, 'sonywf.jpg', 'Sony', 'WF-1000XM4', '1 Year'),

-- Gaming
('PlayStation 5', 'Next-gen gaming console with lightning-fast loading and haptic feedback.', 499.99, 5, 15, 'ps5.jpg', 'Sony', 'PlayStation 5', '1 Year'),
('Xbox Series X', 'Most powerful Xbox console with 4K gaming and Quick Resume.', 499.99, 5, 12, 'xboxseriesx.jpg', 'Microsoft', 'Xbox Series X', '1 Year'),
('Nintendo Switch OLED', 'Hybrid gaming console with vibrant OLED screen.', 349.99, 5, 25, 'switch.jpg', 'Nintendo', 'Switch OLED', '1 Year'),
('Steam Deck', 'Handheld gaming PC for playing Steam games on the go.', 649.99, 5, 10, 'steamdeck.jpg', 'Valve', 'Steam Deck', '1 Year'),

-- Accessories
('MagSafe Charger', 'Wireless charger for iPhone with magnetic alignment.', 39.99, 6, 100, 'magsafe.jpg', 'Apple', 'MagSafe', '1 Year'),
('Anker PowerBank', 'High-capacity portable charger for all your devices.', 49.99, 6, 75, 'powerbank.jpg', 'Anker', 'PowerCore', '1 Year'),
('Logitech MX Master 3', 'Advanced wireless mouse for productivity and creativity.', 99.99, 6, 50, 'mxmaster.jpg', 'Logitech', 'MX Master 3', '1 Year'),
('Apple Magic Keyboard', 'Wireless keyboard with scissor mechanism and numeric keypad.', 179.99, 6, 40, 'magickeyboard.jpg', 'Apple', 'Magic Keyboard', '1 Year');

-- Insert admin user
INSERT INTO admin_users (username, email, password, full_name, role) VALUES
('admin', 'admin@electromart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Insert sample coupons
INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_uses, expires_at) VALUES
('WELCOME10', 'percentage', 10.00, 50.00, 100, '2024-12-31 23:59:59'),
('SAVE50', 'fixed', 50.00, 200.00, 50, '2024-12-31 23:59:59'),
('ELECTRONICS20', 'percentage', 20.00, 100.00, 200, '2024-12-31 23:59:59');