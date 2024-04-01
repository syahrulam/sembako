-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2024 at 01:52 PM
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
-- Database: `toko`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama_item` varchar(255) NOT NULL,
  `jenis_satuan_besar` varchar(50) DEFAULT NULL,
  `jenis_satuan_kecil` varchar(50) DEFAULT NULL,
  `jumlah_satuan_besar` int(11) DEFAULT NULL,
  `jumlah_isi_satuan_besar` int(11) DEFAULT NULL,
  `total_isi_satuan_kecil` int(11) NOT NULL,
  `harga_satuan_kulak` int(11) NOT NULL,
  `total_harga_kulak` int(11) DEFAULT NULL,
  `harga_jual_satuan_besar` int(11) DEFAULT NULL,
  `harga_jual_satuan_kecil` int(11) DEFAULT NULL,
  `total_kulak` int(11) DEFAULT 0,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id_item`, `kategori_id`, `nama_item`, `jenis_satuan_besar`, `jenis_satuan_kecil`, `jumlah_satuan_besar`, `jumlah_isi_satuan_besar`, `total_isi_satuan_kecil`, `harga_satuan_kulak`, `total_harga_kulak`, `harga_jual_satuan_besar`, `harga_jual_satuan_kecil`, `total_kulak`, `tanggal`) VALUES
(4, 1, 'Green Tea', 'Dus', 'Botol', 5, 20, 100, 50000, 250000, 55000, 8000, 0, '2024-03-30'),
(5, 1, 'White Coffe', 'Dus', 'Sachet', 4, 25, 100, 40000, 160000, 56000, 5000, 0, '2024-03-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
