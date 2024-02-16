-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2019 at 09:16 PM
-- Server version: 10.2.20-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mdt`
--
CREATE DATABASE IF NOT EXISTS `mdt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mdt`;

-- --------------------------------------------------------

--
-- Table structure for table `calgary`
--

CREATE TABLE `calgary` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity_reserved` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calgary`
--

INSERT INTO `calgary` (`id`, `product_id`, `quantity`, `quantity_reserved`) VALUES
(1, 1, '14.00', '0.00'),
(2, 2, '55.00', '0.00'),
(3, 3, '65.00', '0.00'),
(4, 4, '40.00', '0.00'),
(5, 5, '50.00', '0.00'),
(6, 6, '40.00', '0.00'),
(7, 7, '30.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `edmonton`
--

CREATE TABLE `edmonton` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity_reserved` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `edmonton`
--

INSERT INTO `edmonton` (`id`, `product_id`, `quantity`, `quantity_reserved`) VALUES
(1, 1, '11.00', '10.00'),
(2, 2, '50.00', '0.00'),
(3, 3, '50.00', '0.00'),
(4, 4, '50.00', '0.00'),
(5, 5, '50.00', '0.00'),
(6, 6, '50.00', '0.00'),
(7, 7, '50.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status_id` tinyint(1) NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp(),
  `month` tinyint(2) NOT NULL DEFAULT 0,
  `year` int(4) NOT NULL DEFAULT 0,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `type`, `supplier_id`, `user_id`, `amount`, `status_id`, `date`, `update_date`, `month`, `year`, `note`) VALUES
(14, 0, 1, 2, '650.00', 3, '2019-05-01', '2019-05-08 01:14:22', 5, 2019, ''),
(15, 1, 0, 3, '2110.00', 3, '2019-05-08', '2019-05-08 01:29:59', 5, 2019, ''),
(16, 1, 0, 5, '1400.00', 3, '2019-05-05', '2019-05-08 01:41:37', 5, 2019, ''),
(17, 0, 4, 2, '350.00', 3, '2019-05-10', '2019-05-08 02:48:06', 5, 2019, ''),
(18, 1, 0, 6, '650.00', 3, '2019-05-01', '2019-05-08 02:49:55', 5, 2019, '');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `supplier_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_products`
--

INSERT INTO `invoice_products` (`id`, `invoice_id`, `product_id`, `quantity`, `price`, `supplier_price`) VALUES
(36, 14, 1, '10.00', '40.00', '0.00'),
(37, 14, 4, '10.00', '25.00', '0.00'),
(38, 15, 4, '10.00', '100.00', '25.00'),
(39, 15, 1, '10.00', '111.00', '40.00'),
(40, 16, 1, '10.00', '140.00', '40.00'),
(41, 17, 1, '10.00', '35.00', '0.00'),
(42, 18, 1, '5.00', '130.00', '38.33');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_status`
--

CREATE TABLE `invoice_status` (
  `id` tinyint(1) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_status`
--

INSERT INTO `invoice_status` (`id`, `status`) VALUES
(1, 'processing'),
(2, 'pending'),
(3, 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `type`, `supplier_id`, `user_id`, `amount`, `date`) VALUES
(1, 0, 4, 2, '1000.00', '2019-04-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `active`) VALUES
(1, 'Swiss Gold', 1),
(2, 'Black Kush', 1),
(3, 'Purple Urkle', 1),
(4, 'Blue Mcgoo', 1),
(5, 'Gorilla Bomb', 1),
(6, 'Smokehouse Kush', 1),
(7, 'Holy Grail', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity_reserved` decimal(10,2) NOT NULL DEFAULT 0.00,
  `supplier_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `quantity`, `quantity_reserved`, `supplier_price`) VALUES
(1, 1, '25.00', '10.00', '38.33'),
(2, 2, '105.00', '0.00', '15.00'),
(3, 3, '115.00', '0.00', '20.00'),
(4, 4, '90.00', '0.00', '25.00'),
(5, 5, '100.00', '0.00', '30.00'),
(6, 6, '90.00', '0.00', '10.00'),
(7, 7, '80.00', '0.00', '30.00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `active`, `balance`) VALUES
(1, 'Ken', 1, '1130.00'),
(2, 'Mike', 1, '400.00'),
(3, 'John', 1, '0.00'),
(4, 'Marry', 1, '1100.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `inventory` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `active`, `balance`, `inventory`) VALUES
(2, 'administrator', '$2y$10$sfQlkgN4UMMrOQN5VCHtQuFLJYaRwJDn7UPszByNnqIzHyChAm3ei', 'administrator@administrator.com', 1, 1, '0.00', 1),
(3, 'Tom', '$2y$10$sfQlkgN4UMMrOQN5VCHtQuFLJYaRwJDn7UPszByNnqIzHyChAm3ei', 'user1@user1.com', 2, 1, '9260.00', 1),
(4, 'Peter', '$2y$10$sfQlkgN4UMMrOQN5VCHtQuFLJYaRwJDn7UPszByNnqIzHyChAm3ei', 'user2@user2.com', 2, 1, '13650.00', 1),
(5, 'Storm', '$2y$10$sfQlkgN4UMMrOQN5VCHtQuFLJYaRwJDn7UPszByNnqIzHyChAm3ei', 'user3@user3.com', 2, 1, '3080.00', 0),
(6, 'Duke', '$2y$10$sfQlkgN4UMMrOQN5VCHtQuFLJYaRwJDn7UPszByNnqIzHyChAm3ei', 'user4@user4.com', 2, 1, '650.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_prices`
--

CREATE TABLE `users_prices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_prices`
--

INSERT INTO `users_prices` (`id`, `user_id`, `product_id`, `price`) VALUES
(1, 3, 1, '111.00'),
(2, 3, 2, '80.00'),
(3, 3, 3, '70.00'),
(4, 3, 4, '100.00'),
(5, 3, 5, '50.00'),
(6, 3, 6, '80.00'),
(7, 3, 7, '100.00'),
(8, 4, 1, '135.00'),
(9, 4, 2, '75.00'),
(10, 4, 3, '60.00'),
(11, 4, 4, '90.00'),
(12, 4, 5, '60.00'),
(13, 4, 6, '70.00'),
(14, 4, 7, '120.00'),
(15, 5, 1, '140.00'),
(16, 5, 2, '90.00'),
(17, 5, 3, '100.00'),
(18, 5, 4, '60.00'),
(19, 5, 5, '88.00'),
(20, 5, 6, '60.00'),
(21, 5, 7, '110.00'),
(22, 6, 1, '130.00'),
(23, 6, 2, '160.00'),
(24, 6, 3, '80.00'),
(25, 6, 4, '70.00'),
(26, 6, 5, '105.00'),
(27, 6, 6, '75.00'),
(28, 6, 7, '130.00');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` tinyint(1) NOT NULL,
  `role` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role`) VALUES
(1, 'Administrator'),
(2, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calgary`
--
ALTER TABLE `calgary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edmonton`
--
ALTER TABLE `edmonton`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `month` (`month`),
  ADD KEY `year` (`year`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_status`
--
ALTER TABLE `invoice_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_prices`
--
ALTER TABLE `users_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calgary`
--
ALTER TABLE `calgary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `edmonton`
--
ALTER TABLE `edmonton`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `invoice_status`
--
ALTER TABLE `invoice_status`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_prices`
--
ALTER TABLE `users_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
