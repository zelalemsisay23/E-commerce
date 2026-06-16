-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2026 at 07:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `electromart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(2, 1, 5, 1, '2025-12-27 06:24:07');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `created_at`) VALUES
(1, 'Smartphones', 'Latest smartphones and mobile devices', 'smartphones.jpg', '2025-12-27 06:00:57'),
(2, 'Laptops', 'Laptops and notebooks for all needs', 'laptops.jpg', '2025-12-27 06:00:57'),
(3, 'Tablets', 'Tablets and iPad devices', 'tablets.jpg', '2025-12-27 06:00:57'),
(4, 'Audio', 'Headphones, speakers, and audio equipment', 'audio.jpg', '2025-12-27 06:00:57'),
(5, 'Gaming', 'Gaming consoles and accessories', 'gaming.jpg', '2025-12-27 06:00:57'),
(6, 'Accessories', 'Phone cases, chargers, and accessories', 'accessories.jpg', '2025-12-27 06:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `payment_method`, `created_at`) VALUES
(1, 1, 1079.99, 'pending', 'welcome', 'cash_on_delivery', '2025-12-27 06:22:10'),
(2, 2, 3131.97, 'pending', 'fff', 'paypal', '2025-12-31 23:32:30'),
(3, 2, 1403.99, 'pending', 'dese', 'cash_on_delivery', '2025-12-31 23:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 999.99),
(2, 2, 2, 2, 899.99),
(3, 2, 10, 1, 1099.99),
(4, 3, 4, 1, 1299.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `stock_quantity`, `image`, `specifications`, `brand`, `model`, `warranty`, `status`, `created_at`) VALUES
(1, 'iPhone 15 Pro', 'Latest iPhone with A17 Pro chip and titanium design', 999.99, 1, 49, 'iphone15pro.jpg', NULL, 'Apple', 'iPhone 15 Pro', '1 Year', 'active', '2025-12-27 06:00:57'),
(2, 'Samsung Galaxy S24', 'Flagship Android phone with AI features', 899.99, 1, 28, 'galaxys24.jpg', NULL, 'Samsung', 'Galaxy S24', '1 Year', 'active', '2025-12-27 06:00:57'),
(3, 'MacBook Pro 14\"', 'Professional laptop with M3 chip', 1999.99, 2, 20, 'macbookpro14.jpg', NULL, 'Apple', 'MacBook Pro', '1 Year', 'active', '2025-12-27 06:00:57'),
(4, 'Dell XPS 13', 'Ultra-portable Windows laptop', 1299.99, 2, 24, 'dellxps13.jpg', NULL, 'Dell', 'XPS 13', '1 Year', 'active', '2025-12-27 06:00:57'),
(5, 'iPad Air', 'Versatile tablet for work and play', 599.99, 3, 40, 'ipadair.jpg', NULL, 'Apple', 'iPad Air', '1 Year', 'active', '2025-12-27 06:00:57'),
(6, 'Sony WH-1000XM5', 'Premium noise-canceling headphones', 399.99, 4, 60, 'sonywh1000xm5.jpg', NULL, 'Sony', 'WH-1000XM5', '1 Year', 'active', '2025-12-27 06:00:57'),
(7, 'PlayStation 5', 'Next-gen gaming console', 499.99, 5, 15, 'ps5.jpg', NULL, 'Sony', 'PlayStation 5', '1 Year', 'active', '2025-12-27 06:00:57'),
(8, 'AirPods Pro', 'Wireless earbuds with active noise cancellation', 249.99, 6, 80, 'airpodspro.jpg', NULL, 'Apple', 'AirPods Pro', '1 Year', 'active', '2025-12-27 06:00:57'),
(9, 'iPhone 17', 'Latest iPhone with A18 chip, advanced camera system, and all-day battery life. Features titanium design and Action Button.', 999.99, 1, 30, 'iphone17.jpg', NULL, 'Apple', 'iPhone 17', '1 Year', 'active', '2025-12-30 23:14:12'),
(10, 'iPhone 17 Plus', 'Larger iPhone 17 with 6.7-inch display, enhanced battery life, and premium camera features.', 1099.99, 1, 24, 'iphone17plus.jpg', NULL, 'Apple', 'iPhone 17 Plus', '1 Year', 'active', '2025-12-30 23:14:12'),
(11, 'Samsung Galaxy S25', 'Next-generation Galaxy with AI features, improved camera, and sleek design.', 899.99, 1, 35, 'galaxys25.jpg', NULL, 'Samsung', 'Galaxy S25', '1 Year', 'active', '2025-12-30 23:14:12'),
(12, 'Samsung Galaxy S25+', 'Enhanced Galaxy S25 with larger display and extended battery life.', 999.99, 1, 30, 'galaxys25plus.jpg', NULL, 'Samsung', 'Galaxy S25+', '1 Year', 'active', '2025-12-30 23:14:12'),
(17, 'HP All-in-One 27\"', 'Sleek all-in-one desktop with 27-inch Full HD display, Intel Core i5 processor, 8GB RAM, and 512GB SSD. Perfect for home office and productivity tasks.', 1199.99, 2, 15, 'hp-allinone27.jpg', NULL, 'HP', 'All-in-One 27\"', '1 Year', 'active', '2025-12-31 00:13:04'),
(18, 'Alienware m16', 'Premium gaming laptop with Intel Core i9, NVIDIA RTX 4080, 32GB RAM, and 1TB SSD. Features 16-inch QHD+ display with 240Hz refresh rate for ultimate gaming performance.', 2499.99, 2, 8, 'alienware-m16.jpg', NULL, 'Alienware', 'm16', '1 Year', 'active', '2025-12-31 00:13:04'),
(19, 'HP EliteBook 840', 'Professional business laptop with Intel Core i7, 16GB RAM, 512GB SSD, and enterprise-grade security features. Lightweight design with 14-inch display perfect for business professionals.', 1799.99, 2, 12, 'hp-elitebook840.jpg', NULL, 'HP', 'EliteBook 840', '1 Year', 'active', '2025-12-31 00:13:04'),
(20, 'JBL Charge 5', 'Portable Bluetooth speaker with powerful sound, built-in powerbank, and IP67 waterproof rating. Perfect for outdoor adventures with 20 hours of playtime.', 149.99, 4, 35, 'jbl-charge5.jpg', NULL, 'JBL', 'Charge 5', '1 Year', 'active', '2025-12-31 00:30:00'),
(21, 'Logitech G Pro X Superlight', 'Ultra-lightweight wireless gaming mouse weighing less than 63 grams. HERO 25K sensor with zero additives for pure performance.', 149.99, 5, 30, 'logitech-gprox.jpg', NULL, 'Logitech', 'G Pro X Superlight', '1 Year', 'active', '2025-12-31 00:30:00'),
(22, 'SteelSeries Apex Pro', 'Mechanical gaming keyboard with adjustable OmniPoint switches, OLED Smart Display, and premium aluminum construction.', 199.99, 5, 20, 'steelseries-apex.jpg', NULL, 'SteelSeries', 'Apex Pro', '1 Year', 'active', '2025-12-31 00:30:00'),
(23, 'ASUS ROG Swift PG279QM', '27-inch gaming monitor with 240Hz refresh rate, 1ms response time, G-SYNC compatibility, and Fast IPS technology.', 699.99, 5, 12, 'asus-rog-swift.jpg', NULL, 'ASUS', 'ROG Swift PG279QM', '1 Year', 'active', '2025-12-31 00:30:00'),
(24, 'Samsung Galaxy Tab A8', 'Affordable 10.5-inch Android tablet with quad speakers, long-lasting battery, and expandable storage up to 1TB.', 229.99, 3, 25, 'galaxy-taba8.jpg', NULL, 'Samsung', 'Galaxy Tab A8', '1 Year', 'active', '2025-12-31 00:30:00'),
(25, 'iPad mini 6', 'Compact 8.3-inch tablet with A15 Bionic chip, all-screen design, and support for Apple Pencil 2nd generation. Perfect for portability.', 499.99, 3, 30, 'ipad-mini6.jpg', NULL, 'Apple', 'iPad mini 6', '1 Year', 'active', '2025-12-31 00:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(200) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `title`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Amazing phone!', 'The iPhone 15 Pro is incredible. The camera quality is outstanding and the performance is smooth.', '2025-12-31 01:34:10'),
(2, 1, 2, 4, 'Great Android phone', 'Samsung Galaxy S24 has excellent features. The AI capabilities are impressive.', '2025-12-31 01:34:10'),
(3, 1, 6, 5, 'Perfect laptop', 'MacBook Pro 14 is exactly what I needed for my work. Fast and reliable.', '2025-12-31 01:34:10'),
(4, 1, 19, 5, 'Best gaming console', 'PlayStation 5 delivers amazing gaming experience. The graphics are stunning.', '2025-12-31 01:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `created_at`) VALUES
(1, 'toni', 'toni031215teddy@gmail.com', '$2y$10$k8Egwy/yc.JM/mS7AT8yvunlRBoznNYyeBIGtDjq9ZZQ6WtP620fq', 'toni', '0989463293', NULL, '2025-12-27 06:16:28'),
(2, 'teddy', 'helengetwey@gmail.com', '$2y$10$w0BZrpfPcuC/UCCoucAZ7.ymi5OdKx7eQN1OMY25dNlfiR5ckCh16', 'teddy', '0900538078', NULL, '2025-12-30 03:43:24'),
(3, 'hjjj', 'helgetwey@gmail.com', '$2y$10$IklhkrXVRle7Al7FKNW/IubpUeUnIB4iPU750yW0IBAHa/FJ5JOW6', 'tet', '098', NULL, '2025-12-30 21:47:51'),
(4, 'seya', 'seidali45@gmail.com', '$2y$10$j/5zNAcEtMgUfhG.ugmytun34RQCc9rducOLbfXaY1B44yXO/p4Dm', 'seid', '0948667371', NULL, '2025-12-31 21:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(3, 3, 12, '2025-12-31 21:58:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
