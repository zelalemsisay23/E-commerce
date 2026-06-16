-- Create the missing reviews table
USE electromart;

CREATE TABLE IF NOT EXISTS reviews (
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

-- Insert some sample reviews for testing
INSERT INTO reviews (user_id, product_id, rating, title, comment) VALUES
(1, 1, 5, 'Amazing phone!', 'The iPhone 15 Pro is incredible. The camera quality is outstanding and the performance is smooth.'),
(1, 2, 4, 'Great Android phone', 'Samsung Galaxy S24 has excellent features. The AI capabilities are impressive.'),
(1, 6, 5, 'Perfect laptop', 'MacBook Pro 14 is exactly what I needed for my work. Fast and reliable.'),
(1, 19, 5, 'Best gaming console', 'PlayStation 5 delivers amazing gaming experience. The graphics are stunning.'),
(1, 15, 4, 'Excellent headphones', 'Sony WH-1000XM5 has great noise cancellation and sound quality.');