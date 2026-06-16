-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 03:43 PM
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
-- Database: `fresh_market_rwanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `full_name`, `created_at`) VALUES
(19588, 'loriana', 'admin123', 'Loriana Eline', '2026-06-13 15:19:59'),
(19589, 'admin', '$2y$10$daGOVt2FIQQjm2BEIWGxvOVBlhme51tEz9W1ZYt0HloFCMr/WYX1C', 'Fresh Market Admin', '2026-06-14 13:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `slug`, `description`, `image`, `created_at`) VALUES
(1, 'Fruits', 'fruits', 'Fresh seasonal fruits sourced from local farms', 'fruits.jpg', '2026-06-13 12:49:43'),
(2, 'Vegetables', 'vegetables', 'Farm-fresh vegetables harvested daily', 'vegetables.jpg', '2026-06-13 12:49:43'),
(3, 'Dairy & Eggs', 'dairy-eggs', 'Milk, cheese, yogurt and fresh eggs', 'dairy.jpg', '2026-06-13 12:49:43'),
(4, 'Grains & Cereals', 'grains-cereals', 'Rice, maize flour, beans and more', 'grains.jpg', '2026-06-13 12:49:43'),
(5, 'Beverages', 'beverages', 'Juices, water and refreshing drinks', 'beverages.jpg', '2026-06-13 12:49:43'),
(6, 'Meat & Poultry', 'meat-poultry', 'Fresh meat, chicken and fish', 'meat.jpg', '2026-06-13 12:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `full_name`, `email`, `phone`, `password`, `address`, `district`, `created_at`) VALUES
(1, 'ELIWA Loriana', 'elinemougoula@yahoo.com', '0781828447', '$2y$10$5basqbC87QLIdYI.NMVI5un8KSqJwrplv1pvXdCdjXuw0eiXrJEMa', '', '', '2026-06-13 15:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_number` varchar(30) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(100) NOT NULL,
  `payment_method` enum('mobile_money','cash_on_delivery') NOT NULL DEFAULT 'mobile_money',
  `momo_number` varchar(20) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_number`, `full_name`, `email`, `phone`, `address`, `district`, `payment_method`, `momo_number`, `payment_status`, `order_status`, `subtotal`, `delivery_fee`, `total_amount`, `notes`, `created_at`) VALUES
(1, NULL, 'FMR-20260614-D65FD', 'Any Maria', 'maniaany23@gmail.com', '0782567812', 'kanombe, kk 301st', 'gasabo', 'mobile_money', '0782567812', 'pending', 'pending', 10500.00, 1500.00, 12000.00, '', '2026-06-14 14:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `line_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `line_total`) VALUES
(1, 1, 2, 'Avocados', 700.00, 1, 700.00),
(2, 1, 3, 'Pineapple', 1500.00, 1, 1500.00),
(3, 1, 5, 'Mangoes', 1800.00, 1, 1800.00),
(4, 1, 11, 'Fresh Milk', 1500.00, 1, 1500.00),
(5, 1, 12, 'Farm Eggs (Tray)', 5000.00, 1, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(30) DEFAULT 'kg',
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `slug`, `description`, `price`, `unit`, `stock_quantity`, `image`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Fresh Bananas', 'fresh-bananas', 'Sweet ripe bananas, perfect for snacking or smoothies.', 1200.00, 'bunch', 50, 'prod_6a2eb47f986c4.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:02:39'),
(2, 1, 'Avocados', 'avocados', 'Creamy Hass avocados grown locally in the highlands.', 700.00, 'piece', 99, 'prod_6a2eb46d7768c.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:16:03'),
(3, 1, 'Pineapple', 'pineapple', 'Juicy sweet pineapples from Eastern Province.', 1500.00, 'piece', 39, 'prod_6a2eb457b84d8.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 14:16:03'),
(4, 1, 'Passion Fruit', 'passion-fruit', 'Tangy and aromatic passion fruit, sold by the kg.', 2000.00, 'kg', 30, 'prod_6a2eb442e7b9a.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 14:01:38'),
(5, 1, 'Mangoes', 'mangoes', 'Sweet and juicy mangoes, in season now.', 1800.00, 'kg', 59, 'prod_6a2eb42c79d2e.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:16:03'),
(6, 2, 'Fresh Tomatoes', 'fresh-tomatoes', 'Vine-ripened tomatoes, great for sauces and salads.', 900.00, 'kg', 80, 'prod_6a2eb4112296a.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:00:49'),
(7, 2, 'Carrots', 'carrots', 'Crisp and sweet carrots, locally grown.', 700.00, 'kg', 70, 'prod_6a2eb3f4eec13.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 14:00:20'),
(8, 2, 'Irish Potatoes', 'irish-potatoes', 'High quality potatoes from Musanze.', 1000.00, 'kg', 150, 'prod_6a2eb3d882cec.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 13:59:52'),
(9, 2, 'Onions', 'onions', 'Fresh red onions, essential for every kitchen.', 800.00, 'kg', 90, 'prod_6a2eb3ae07c7f.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:59:10'),
(10, 2, 'Spinach (Dodo)', 'spinach-dodo', 'Leafy green dodo, harvested daily.', 500.00, 'bunch', 60, 'prod_6a2eb3860dace.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:58:30'),
(11, 3, 'Fresh Milk', 'fresh-milk', 'Pasteurized cow milk, 1 litre bottle.', 1500.00, 'litre', 99, 'prod_6a2eb34208d59.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:16:03'),
(12, 3, 'Farm Eggs (Tray)', 'farm-eggs-tray', 'Tray of 30 fresh free-range eggs.', 5000.00, 'tray', 39, 'prod_6a2eb324d4171.png', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 14:16:03'),
(13, 3, 'Natural Yogurt', 'natural-yogurt', 'Creamy plain yogurt, 250ml.', 1000.00, 'piece', 50, 'prod_6a2eb30711a6c.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:56:23'),
(14, 4, 'Rice (5kg)', 'rice-5kg', 'Premium quality rice, 5kg bag.', 6500.00, 'bag', 35, 'prod_6a2eb2dde7394.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 13:55:41'),
(15, 4, 'Maize Flour (5kg)', 'maize-flour-5kg', 'Finely milled maize flour for ugali.', 3000.00, 'bag', 45, 'prod_6a2eb2bce9cdd.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:55:08'),
(16, 4, 'Beans (5kg)', 'beans-5kg', 'Sorted dry beans, 5kg bag.', 7000.00, 'bag', 30, 'prod_6a2eb2a3d8540.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:54:43'),
(17, 5, 'Mineral Water (1.5L)', 'mineral-water-1-5l', 'Bottled mineral water, 1.5 litres.', 700.00, 'bottle', 120, 'prod_6a2eb2954a3c3.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:54:29'),
(18, 5, 'Fresh Juice - Mixed Fruit', 'fresh-juice---mixed-fruit', 'Locally made mixed fruit juice, 1 litre.', 2500.00, 'bottle', 25, 'prod_6a2eb27d42b4d.png', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 13:54:05'),
(19, 6, 'Chicken (Whole)', 'chicken-whole', 'Fresh whole chicken, approx 1.5kg.', 6000.00, 'piece', 20, 'prod_6a2eb26b861a5.jpg', 1, 'active', '2026-06-13 12:49:43', '2026-06-14 13:53:47'),
(20, 6, 'Beef (kg)', 'beef-kg', 'Quality fresh beef cuts.', 5500.00, 'kg', 25, 'prod_6a2eb25b9b9e7.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:53:31'),
(21, 6, 'Tilapia Fish', 'tilapia-fish', 'Fresh tilapia from Lake Kivu.', 4500.00, 'kg', 18, 'prod_6a2eb181820ea.jpg', 0, 'active', '2026-06-13 12:49:43', '2026-06-14 13:49:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19590;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
