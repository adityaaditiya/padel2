-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 09:34 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kresnog2_padel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_court` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `durasi` int(11) NOT NULL,
  `harga_booking` decimal(10,2) NOT NULL,
  `diskon` decimal(10,2) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `poin_member` int(11) NOT NULL DEFAULT 0,
  `status_booking` enum('pending','confirmed','batal','selesai') DEFAULT 'pending',
  `keterangan` text,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('belum_bayar','lunas') DEFAULT 'belum_bayar',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `id_user`, `id_court`, `tanggal_booking`, `jam_mulai`, `jam_selesai`, `durasi`, `harga_booking`, `diskon`, `total_harga`, `poin_member`, `status_booking`, `keterangan`, `bukti_pembayaran`, `status_pembayaran`, `created_at`, `confirmed_at`) VALUES
(1, '250826-0001', 1, 1, '2025-08-25', '13:00:00', '14:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'pending', NULL, NULL, 'belum_bayar', '2025-08-26 01:59:44', NULL),
(2, '250826-0002', 1, 1, '2025-08-27', '13:00:00', '14:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'pending', NULL, NULL, 'belum_bayar', '2025-08-26 02:00:29', NULL),
(3, '250825-0001', 3, 2, '2025-08-25', '13:00:00', '14:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'pending', NULL, NULL, 'belum_bayar', '2025-08-25 02:27:04', NULL),
(4, '250825-0002', 3, 3, '2025-08-25', '13:00:00', '14:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'batal', '', NULL, 'belum_bayar', '2025-08-25 02:33:16', NULL),
(5, '250826-0003', 1, 1, '2025-08-25', '14:00:00', '15:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'batal', '', NULL, 'belum_bayar', '2025-08-26 02:38:42', NULL),
(6, '250826-0004', 1, 2, '2025-08-24', '13:00:00', '14:00:00', 60, '300000.00', '0.00', '300000.00', 0, 'pending', NULL, NULL, 'belum_bayar', '2025-08-26 02:39:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `type` enum('in','out') NOT NULL,
  `category` enum('BON OPERASIONAL','BON TRANSFER BANK','DEBIT CREDIT CARD','MODAL') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `id` int(11) NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `harga_per_jam` decimal(10,2) NOT NULL,
  `status` enum('tersedia','maintenance') NOT NULL DEFAULT 'tersedia',
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`id`, `nama_lapangan`, `harga_per_jam`, `status`, `gambar`, `created_at`) VALUES
(1, 'lapangan 1', '300000.00', 'tersedia', 'lapangan1.jpg', '2025-08-26 01:47:49'),
(2, 'lapangan 2', '300000.00', 'tersedia', 'lapangan2.jpg', '2025-08-26 01:47:57'),
(3, 'lapangan 3', '300000.00', 'tersedia', 'lapangan3.jpg', '2025-08-26 01:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `member_data`
--

CREATE TABLE `member_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kode_member` char(10) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nomor_ktp` char(16) DEFAULT NULL,
  `alamat` varchar(255) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `poin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_data`
--

INSERT INTO `member_data` (`id`, `user_id`, `kode_member`, `tanggal_lahir`, `nomor_ktp`, `alamat`, `kecamatan`, `kota`, `provinsi`, `poin`) VALUES
(2, 9, '0000000009', NULL, NULL, 'kemiriamba rt 001 rw 001 no 22', 'Jatibarang', 'Kab. Brebes', 'Jawa Tengah', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `id_sale` int(11) DEFAULT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `tanggal_pembayaran` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `id_booking`, `id_sale`, `jumlah_bayar`, `metode_pembayaran`, `id_kasir`, `tanggal_pembayaran`) VALUES
(1, NULL, 1, '25000.00', 'tunai', 1, '2025-08-26 13:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga_jual`, `stok`, `kategori`, `created_at`) VALUES
(1, 'Hydro Coco', '10000.00', 999, 'minuman', '2025-08-26 01:45:49'),
(2, 'Pocari Sweet', '10000.00', 999, 'minuman', '2025-08-26 01:46:01'),
(3, 'Biskuit Imperial Creme', '5000.00', 99, 'makanan', '2025-08-26 01:47:17'),
(4, 'Hydro Coco', '10000.00', 100, 'minuman', '2025-08-26 06:20:32'),
(5, 'Raket Padel', '500000.00', 50, 'perlengkapan padel', '2025-08-26 06:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `nomor_nota` varchar(50) NOT NULL,
  `total_belanja` decimal(10,2) NOT NULL,
  `poin_member` int(11) NOT NULL DEFAULT 0,
  `status` enum('selesai','dibatalkan') NOT NULL DEFAULT 'selesai',
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_kasir`, `customer_id`, `nomor_nota`, `total_belanja`, `poin_member`, `status`, `tanggal_transaksi`) VALUES
(1, 1, NULL, 'INV-1756190933', '25000.00', 0, 'selesai', '2025-08-26 13:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

CREATE TABLE `sale_details` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `id_sale`, `id_product`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 1, '10000.00'),
(2, 1, 2, 1, '10000.00'),
(3, 1, 3, 1, '5000.00');

-- --------------------------------------------------------

--
-- Table structure for table `reward_products`
--

CREATE TABLE `reward_products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `poin` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reward_redemptions`
--

CREATE TABLE `reward_redemptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `point_awal` int(11) NOT NULL,
  `point_akhir` int(11) NOT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `point_usages`
--

CREATE TABLE `point_usages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `point_awal` int(11) NOT NULL,
  `point_used` int(11) NOT NULL,
  `point_akhir` int(11) NOT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `point_rules`
--

CREATE TABLE `point_rules` (
  `id` int(11) NOT NULL,
  `product_rate` int(11) NOT NULL DEFAULT 200,
  `booking_rate` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `point_rules`
--

INSERT INTO `point_rules` (`id`, `product_rate`, `booking_rate`) VALUES
(1, 200, 100);

-- --------------------------------------------------------

--
-- Table structure for table `store_status`
--

CREATE TABLE `store_status` (
  `id` int(11) NOT NULL,
  `store_date` date NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `closed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_status`
--

INSERT INTO `store_status` (`id`, `store_date`, `is_open`, `closed_at`, `created_at`) VALUES
(10, '2025-08-26', 1, NULL, '2025-08-26 04:50:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `role` enum('pelanggan','kasir','admin_keuangan','owner') NOT NULL DEFAULT 'pelanggan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `password`, `no_telepon`, `role`, `created_at`) VALUES
(1, 'kasir', 'kasir@gmail.com', '$2y$10$PO9KkYCPEXl.T2Ku2.C6ve668Q2rvYOGdXrgXY0TgXAqV2H5BViUu', '1', 'kasir', '2025-08-26 01:40:19'),
(2, 'pelanggan', 'pelanggan@gmail.com', '$2y$10$PO9KkYCPEXl.T2Ku2.C6ve668Q2rvYOGdXrgXY0TgXAqV2H5BViUu', '2', 'pelanggan', '2025-08-26 01:40:19'),
(3, 'owner', 'owner@gmail.com', '$2y$10$PO9KkYCPEXl.T2Ku2.C6ve668Q2rvYOGdXrgXY0TgXAqV2H5BViUu', '4', 'owner', '2025-08-26 01:40:19'),
(4, 'admin', 'admin@gmail.com', '$2y$10$PO9KkYCPEXl.T2Ku2.C6ve668Q2rvYOGdXrgXY0TgXAqV2H5BViUu', '3', 'admin_keuangan', '2025-08-26 01:40:19'),
(9, 'MUHAMMAD ADITYA', 'adityamuhammad9@gmail.com', '$2y$10$Xbd..QRaE.EYCRkev6lUYOoGLMZhYbWUR8CjHFpHGDmC8AYt6Eoc.', '085159959994', 'pelanggan', '2025-08-26 06:28:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_court` (`id_court`);

--
-- Indexes for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_data`
--
ALTER TABLE `member_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

-- Indexes for table `reward_products`
--
ALTER TABLE `reward_products`
  ADD PRIMARY KEY (`id`);

-- Indexes for table `reward_redemptions`
--
ALTER TABLE `reward_redemptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reward_id` (`reward_id`);

--
-- Indexes for table `point_usages`
--
ALTER TABLE `point_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `point_rules`
--
ALTER TABLE `point_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `id_sale` (`id_sale`),
  ADD KEY `id_kasir` (`id_kasir`);

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
  ADD UNIQUE KEY `nomor_nota` (`nomor_nota`),
  ADD KEY `id_kasir` (`id_kasir`);

--
-- Indexes for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sale` (`id_sale`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `store_status`
--
ALTER TABLE `store_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member_data`
--
ALTER TABLE `member_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reward_products`
--
ALTER TABLE `reward_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_redemptions`
--
ALTER TABLE `reward_redemptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_usages`
--
ALTER TABLE `point_usages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_rules`
--
ALTER TABLE `point_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store_status`
--
ALTER TABLE `store_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`id_court`) REFERENCES `courts` (`id`);

--
-- Constraints for table `member_data`
--
ALTER TABLE `member_data`
  ADD CONSTRAINT `member_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`id_sale`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`id_kasir`) REFERENCES `users` (`id`);

--
-- Constraints for table `reward_redemptions`
--
ALTER TABLE `reward_redemptions`
  ADD CONSTRAINT `reward_redemptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reward_redemptions_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `reward_products` (`id`);

--
-- Constraints for table `point_usages`
--
ALTER TABLE `point_usages`
  ADD CONSTRAINT `point_usages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`id_kasir`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD CONSTRAINT `sale_details_ibfk_1` FOREIGN KEY (`id_sale`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_details_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Table structure for table `stock_opnames`
--
CREATE TABLE `stock_opnames` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stok_sistem` int(11) NOT NULL,
  `stok_fisik` int(11) NOT NULL,
  `selisih` int(11) NOT NULL,
  `opname_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  ADD CONSTRAINT `stock_opnames_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

-- --------------------------------------------------------

-- Table structure for table `manual_stock_logs`
--
CREATE TABLE `manual_stock_logs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('tambah','ambil') NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `total_stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Indexes for table `manual_stock_logs`
ALTER TABLE `manual_stock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

-- AUTO_INCREMENT for table `manual_stock_logs`
ALTER TABLE `manual_stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Constraints for table `manual_stock_logs`
ALTER TABLE `manual_stock_logs`
  ADD CONSTRAINT `manual_stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
