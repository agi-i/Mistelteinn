-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2026 at 06:55 PM
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
-- Database: `mistelteinn_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`) VALUES
(2, 'Alyosha', 'alyosha@snezhnaya.com', '09234828231', '2026-11-27 16:51:04'),
(3, 'Odette', 'swanlakeodette@snezhnaya.com', '09345672348', '2026-11-27 16:52:06'),
(4, 'Vodyanitsa', 'sirensong@snezhnaya.com', '09247538746', '2026-11-27 16:53:02'),
(5, 'Mitya', 'nicetomitya@snezhnaya.com', '09342743767', '2026-11-27 16:53:39'),
(6, 'Pierro', 'thejesterpierro@khaenriah.com', '09764366732', '2026-11-27 16:54:07'),
(7, 'Paimon', 'bestemergencyfood@celestia.com', '09237614389', '2026-11-27 16:54:44'),
(8, 'Sandrone', 'puloniaa@fontaine.com', '09237548972', '2026-11-27 16:55:55'),
(9, 'Arlecchino', 'theknavecchino@hearth.com', '09548273645', '2026-11-27 16:58:41'),
(10, 'Pantalone', 'feofanzandik@snezhnaya.com', '09374856112', '2026-11-27 16:59:18'),
(11, 'Ajax Tartaglia', 'rararasputin@snezhnaya.com', '09374665722', '2026-11-27 17:00:23'),
(12, 'Pulcinella', 'theroosterpulci@snezhnaya.com', '09273844657', '2026-11-27 17:00:57'),
(13, 'Rhinedottr Gold', 'naberiusrhine@celestia.com', '09228374655', '2026-11-27 17:02:15'),
(14, 'Dainsleif', 'phanesdevourer@khaenriah.com', '09337465887', '2026-11-27 17:02:52'),
(15, 'Alice', 'motherofklee@hexenzirkel.com', '09143886767', '2026-11-27 17:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock_quantity`, `image`, `created_at`) VALUES
(1, 'Frostfairy Flower', 500.00, 8, '1795786925_fd1ffafe0dd22ecafaa2.png', '0000-00-00 00:00:00'),
(2, 'Flockingweed Flower', 500.00, 8, '1795789444_e339ecac7b8616b120cf.png', '0000-00-00 00:00:00'),
(3, 'Kristallfold Flower', 700.00, 12, '1795789522_d60dbbcfdd5431e7176c.png', '0000-00-00 00:00:00'),
(4, 'Snowball Flower', 400.00, 14, '1795789596_3895abb0b3c2b9033eec.png', '0000-00-00 00:00:00'),
(5, 'Glaciafruit', 400.00, 18, '1795789637_1f56d063638d19f381ad.png', '0000-00-00 00:00:00'),
(6, 'Golden Fern', 400.00, 26, '1795789679_ef03fc1567bb761f55d3.png', '0000-00-00 00:00:00'),
(7, 'Fairy wormwood', 400.00, 28, '1795789745_df8cdda25043b5a0ce76.png', '0000-00-00 00:00:00'),
(8, 'Kresnik Kristall', 800.00, 247, '1795789973_5bbca2b412b07841ba4f.png', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `sold_by` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `note` text DEFAULT NULL,
  `payment_method` varchar(32) NOT NULL DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `customer_id`, `sold_by`, `quantity`, `total_price`, `created_at`, `note`, `payment_method`) VALUES
(8, 1, 11, 2, 1, 500.00, '2026-11-27 17:41:31', 'For Tonya', 'card'),
(9, 8, 2, 2, 1, 800.00, '2026-11-27 17:44:42', 'For Mitya', 'cash'),
(10, 6, 3, 2, 1, 400.00, '2026-11-27 17:45:07', 'For Vodyanitsa', 'ewallet'),
(11, 1, 9, 2, 1, 500.00, '2026-11-27 17:45:41', 'For Columbina', 'cash'),
(12, 4, 8, 2, 1, 400.00, '2026-11-27 17:46:03', 'For Columbina', 'cash'),
(13, 5, 4, 2, 1, 400.00, '2026-11-27 17:46:34', 'For Odette', 'card'),
(14, 2, 5, 2, 1, 500.00, '2026-11-27 17:47:01', 'So Valeriy can have sales today :P', 'cash'),
(15, 8, 6, 2, 1, 800.00, '2026-11-27 17:47:32', 'To Paimon, From Pierro', 'cash'),
(16, 5, 7, 2, 1, 400.00, '2026-11-27 17:48:02', 'SO LUMINE CAN HAVE SALES :D', 'cash'),
(17, 6, 10, 2, 3, 1200.00, '2026-11-27 17:48:32', 'Goodbye, Zandik', 'card'),
(18, 8, 12, 2, 1, 800.00, '2026-11-27 17:49:12', 'One must love himself before loving others :)', 'cash'),
(19, 2, 13, 2, 1, 500.00, '2026-11-27 17:49:37', 'For Naberius :p', 'cash'),
(20, 3, 14, 2, 3, 2100.00, '2026-11-27 17:50:32', 'May we tread the world once more, Aether.', 'cash'),
(21, 4, 15, 2, 5, 2000.00, '2026-11-27 17:51:11', 'TO MY MOST ADORABLE KLEE <3', 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(3, 8, 1, 1, 500.00, 500.00, '2026-11-27 17:41:31', '2026-11-27 17:41:31'),
(4, 9, 8, 1, 800.00, 800.00, '2026-11-27 17:44:42', '2026-11-27 17:44:42'),
(5, 10, 6, 1, 400.00, 400.00, '2026-11-27 17:45:07', '2026-11-27 17:45:07'),
(6, 11, 1, 1, 500.00, 500.00, '2026-11-27 17:45:41', '2026-11-27 17:45:41'),
(7, 12, 4, 1, 400.00, 400.00, '2026-11-27 17:46:03', '2026-11-27 17:46:03'),
(8, 13, 5, 1, 400.00, 400.00, '2026-11-27 17:46:34', '2026-11-27 17:46:34'),
(9, 14, 2, 1, 500.00, 500.00, '2026-11-27 17:47:01', '2026-11-27 17:47:01'),
(10, 15, 8, 1, 800.00, 800.00, '2026-11-27 17:47:32', '2026-11-27 17:47:32'),
(11, 16, 5, 1, 400.00, 400.00, '2026-11-27 17:48:02', '2026-11-27 17:48:02'),
(12, 17, 6, 3, 400.00, 1200.00, '2026-11-27 17:48:32', '2026-11-27 17:48:32'),
(13, 18, 8, 1, 800.00, 800.00, '2026-11-27 17:49:12', '2026-11-27 17:49:12'),
(14, 19, 2, 1, 500.00, 500.00, '2026-11-27 17:49:37', '2026-11-27 17:49:37'),
(15, 20, 3, 3, 700.00, 2100.00, '2026-11-27 17:50:32', '2026-11-27 17:50:32'),
(16, 21, 4, 5, 400.00, 2000.00, '2026-11-27 17:51:11', '2026-11-27 17:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'staff',
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `role`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(2, 'itschayka', 'Chayka', 'chaykathesecretary@zapolyarny.com', 'admin', '$2y$10$H5tA3qeffeNJM9gCfgM0d.4XLyVfMiF93yuBc23D1EdHps.I8jO6i', '1795791702_3238865f03f3f1ed84aa.png', '2026-11-27 15:01:42', NULL),
(3, 'thetraveler', 'Lumine', 'travelerlumine@descender.com', 'admin', '$2y$10$c2w6fm3m75f0PYJz2xR/SOP8Jz23Ehz2H7G4WEVCkUGGrl6gSY3nu', '1795791792_1fafb2003ca31fcccf86.png', '2026-11-27 15:03:12', NULL),
(4, 'orcadanica', 'Danica', 'orcamaid@zapolyarny.com', 'staff', '$2y$10$08hYDBQqcQ9pgG9JI1mQT.aScsBDXk80t.W3Uze57CAp/3zxeXqzK', '1795791845_4c30d7cd54d5a954bcf1.png', '2026-11-27 15:04:05', NULL),
(5, 'vesnana', 'Vesna', 'vesnadruzhna@zapolyarny.com', 'staff', '$2y$10$4RFtR/GTo.1trIghUX9LmOhYAHJESywHG3LOJR7o8UxIRs5Fv7HcG', '1795791924_9aac1cb468b3290c9dad.png', '2026-11-27 15:05:24', NULL),
(6, 'valeriyy', 'Valeriy', 'mityacatcher@zapolyarny.com', 'staff', '$2y$10$Q5c8WnrOrh.lxYQO.B44lOIEaRQS1WBTOowxPNEDFiXTWEO/r1qym', '1795792021_223eeca12bc48f287d0b.png', '2026-11-27 15:07:01', NULL),
(7, 'tsaritsa143', 'Anastasya Feodorovna Snezhnaya', 'thetsaritsa@zapolyarny.com', 'owner', '$2y$10$KDiTr/AAvC1w22JTJ6KFmuH/anugF0tlV3DpCLG4ajH/c6hKr8Y3.', '1795795046_32316a1bfaed426e626d.png', '2026-11-27 15:57:26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sold_by` (`sold_by`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sale_items_sale` (`sale_id`),
  ADD KEY `idx_sale_items_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
