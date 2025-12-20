-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2025 at 02:07 PM
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
-- Database: `myfoodmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`) VALUES
(1, 'Sarawakian Specialties', 'Authentic local favorites from Sarawak', '2025-12-20 19:08:43'),
(2, 'Western Fusion', 'Modern western dishes with a local twist', '2025-12-20 19:08:43'),
(3, 'Beverages', 'Refreshing drinks and local brews', '2025-12-20 19:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_status` enum('Pending','Preparing','Completed') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `shipping_address`, `payment_method`, `order_status`, `created_at`) VALUES
(1, 1, 34.90, 'Bangunan Hepa', 'Cash on Delivery', 'Pending', '2025-12-20 20:42:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `price`, `image_url`, `stock_quantity`, `is_active`, `created_at`) VALUES
(1, 1, 'Laksa Sarawak', 'Creamy rice vermicelli in a spicy prawn-based broth.', 8.50, 'images/laksa.jpg', 0, 1, '2025-12-20 19:09:01'),
(2, 1, 'Mee Kolok', 'Savory dry noodles topped with minced meat and shallots.', 6.00, 'images/kolok.jpg', 0, 1, '2025-12-20 19:09:01'),
(3, 2, 'Chicken Chop', 'Grilled chicken served with black pepper sauce and fries.', 15.00, 'images/chicken.jpg', 0, 1, '2025-12-20 19:09:01'),
(4, 3, 'Teh C Peng Special', 'Three-layer tea with palm sugar (Gula Apong).', 4.50, 'images/teh-c.jpg', 0, 1, '2025-12-20 19:09:01'),
(5, 1, 'Classic Burger', 'Juicy beef patty with fresh lettuce, tomatoes, onions, and special sauce on a toasted bun. Served with crispy fries.', 8.00, 'images/burger.jpg', 0, 1, '2025-12-20 20:40:01'),
(6, 1, 'Margherita Pizza', 'Classic Italian pizza with fresh tomato sauce, mozzarella cheese, and basil leaves.', 15.90, 'images/pizza.jpg', 0, 1, '2025-12-20 20:40:01'),
(7, 2, 'Caesar Salad', 'Fresh romaine lettuce tossed with Caesar dressing, croutons, and parmesan cheese.', 9.90, 'images/salad.jpg', 0, 1, '2025-12-20 20:40:01'),
(8, 1, 'Spaghetti Carbonara', 'Creamy pasta with pancetta, egg yolk, and freshly ground black pepper.', 13.90, 'images/pasta.jpg', 0, 1, '2025-12-20 20:40:01'),
(9, 1, 'Grilled Chicken Wrap', 'Grilled chicken strips wrapped in a tortilla with fresh vegetables and dressing.', 10.90, 'images/wrap.jpg', 0, 1, '2025-12-20 20:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Wan Hazim Izzat', 'hazimizzat124@gmail.com', '12345', 'user', '2025-12-20 20:18:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
