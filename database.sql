-- Database: it_asset_tracker

CREATE DATABASE IF NOT EXISTS `it_asset_tracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `it_asset_tracker`;

-- Table: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','it_staff','requester') NOT NULL DEFAULT 'requester',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: categories
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: assets
CREATE TABLE `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_tag` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` int(11) NOT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('Available','Checked Out','Under Repair','Retired') NOT NULL DEFAULT 'Available',
  `notes` text,
  `qr_code_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_tag` (`asset_tag`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: transactions
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Requester',
  `admin_id` int(11) NOT NULL COMMENT 'IT Staff processing',
  `action` enum('checkout','checkin') NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expected_return_date` datetime DEFAULT NULL,
  `actual_return_date` datetime DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SAMPLE DATA
-- Password is 'password123' for all sample users using BCRYPT
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'IT Staff John', 'it@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'it_staff'),
(3, 'Jane Employee', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'requester');

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Laptops', 'Notebooks and portable computers'),
(2, 'Monitors', 'External displays'),
(3, 'Peripherals', 'Keyboards, mice, headsets'),
(4, 'Mobile Devices', 'Phones and tablets');

INSERT INTO `assets` (`id`, `asset_tag`, `name`, `category_id`, `serial_no`, `model`, `status`, `notes`) VALUES
(1, 'AST-001', 'Dell Latitude 5420', 1, 'DL123456', 'Latitude 5420', 'Available', 'New unit'),
(2, 'AST-002', 'HP EliteDisplay', 2, 'HPE98765', 'E243', 'Available', 'Spare monitor');
