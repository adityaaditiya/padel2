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
  `id_user` int(11) NOT NULL,
  `id_court` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `durasi` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status_booking` enum('pending','confirmed','batal','selesai') DEFAULT 'pending',
  `keterangan` text,
  `status_pembayaran` enum('belum_bayar','lunas') DEFAULT 'belum_bayar',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `id_user`, `id_court`, `tanggal_booking`, `jam_mulai`, `jam_selesai`, `durasi`, `total_harga`, `status_booking`, `keterangan`, `status_pembayaran`, `created_at`) VALUES
(1, 1, 1, '2025-08-25', '13:00:00', '14:00:00', 1, '300000.00', 'pending', NULL, 'belum_bayar', '2025-08-26 01:59:44'),
(2, 1, 1, '2025-08-27', '13:00:00', '14:00:00', 1, '300000.00', 'pending', NULL, 'belum_bayar', '2025-08-26 02:00:29'),
(3, 3, 2, '2025-08-25', '13:00:00', '14:00:00', 1, '300000.00', 'pending', NULL, 'belum_bayar', '2025-08-25 02:27:04'),
(4, 3, 3, '2025-08-25', '13:00:00', '14:00:00', 1, '300000.00', 'batal', '', 'belum_bayar', '2025-08-25 02:33:16'),
(5, 1, 1, '2025-08-25', '14:00:00', '15:00:00', 1, '300000.00', 'batal', '', 'belum_bayar', '2025-08-26 02:38:42'),
(6, 1, 2, '2025-08-24', '13:00:00', '14:00:00', 1, '300000.00', 'pending', NULL, 'belum_bayar', '2025-08-26 02:39:50');

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`id`, `nama_lapangan`, `harga_per_jam`, `status`, `created_at`) VALUES
(1, 'lapangan 1', '300000.00', 'tersedia', '2025-08-26 01:47:49'),
(2, 'lapangan 2', '300000.00', 'tersedia', '2025-08-26 01:47:57'),
(3, 'lapangan 3', '300000.00', 'tersedia', '2025-08-26 01:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `member_data`
--

CREATE TABLE `member_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kode_member` char(10) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_data`
--

INSERT INTO `member_data` (`id`, `user_id`, `kode_member`, `alamat`, `kecamatan`, `kota`, `provinsi`) VALUES
(2, 9, '0000000009', 'kemiriamba rt 001 rw 001 no 22', 'Jatibarang', 'Kab. Brebes', 'Jawa Tengah');

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
(4, 'Hydro Coco', '10000.00', 100, 'minuman', '2025-08-26 06:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `nomor_nota` varchar(50) NOT NULL,
  `total_belanja` decimal(10,2) NOT NULL,
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_kasir`, `nomor_nota`, `total_belanja`, `tanggal_transaksi`) VALUES
(1, 1, 'INV-1756190933', '25000.00', '2025-08-26 13:48:53');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
