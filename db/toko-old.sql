-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2024 at 01:27 AM
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
-- Table structure for table `cicilan_piutang`
--

CREATE TABLE `cicilan_piutang` (
  `id_cicilan` int(11) NOT NULL,
  `id_transaksi` int(5) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `cicilan` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cicilan_piutang`
--

INSERT INTO `cicilan_piutang` (`id_cicilan`, `id_transaksi`, `tanggal`, `cicilan`) VALUES
(1, 1, '2024-05-04', 5000),
(2, 7, '2024-05-04', 5000),
(3, 8, '2024-05-05', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` varchar(10) NOT NULL,
  `id_item` int(10) NOT NULL,
  `jenis_satuan` varchar(100) DEFAULT NULL,
  `harga_satuan` int(11) NOT NULL,
  `jumlah_satuan` int(100) NOT NULL,
  `total` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `id_transaksi`, `id_item`, `jenis_satuan`, `harga_satuan`, `jumlah_satuan`, `total`) VALUES
(1, '1', 1, 'Besar', 50000, 1, 50000),
(2, '2', 1, 'Kecil', 5100, 2, 10200),
(3, '3', 1, 'Besar', 50000, 1, 50000),
(4, '4', 2, 'Kecil', 3500, 3, 10500),
(5, '5', 2, 'Besar', 45000, 1, 45000),
(6, '6', 2, 'Kecil', 3000, 2, 6000),
(7, '7', 1, 'Kecil', 5000, 3, 15000),
(8, '8', 1, 'Besar', 50000, 1, 50000),
(9, '8', 2, 'Kecil', 3000, 2, 6000),
(10, '9', 1, 'Kecil', 5100, 2, 10200),
(11, '9', 2, 'Besar', 45000, 1, 45000),
(12, '10', 1, 'Kecil', 5000, 2, 10000),
(13, '11', 2, 'Kecil', 3000, 1, 3000),
(14, '12', 1, 'Kecil', 5100, 2, 10200),
(15, '13', 2, 'Kecil', 3000, 1, 3000),
(16, '14', 1, 'Kecil', 5000, 2, 10000),
(17, '15', 1, 'Kecil', 5000, 2, 10000),
(18, '16', 1, 'Kecil', 5000, 5, 25000),
(19, '17', 1, 'Kecil', 5000, 3, 15000),
(20, '18', 1, 'Kecil', 5200, 2, 10400),
(21, '18', 2, 'Besar', 50000, 1, 50000),
(22, '19', 1, 'Kecil', 5000, 5, 25000),
(23, '20', 1, 'Kecil', 5000, 4, 20000),
(24, '21', 1, 'Besar', 50000, 3, 150000),
(25, '21', 1, 'Kecil', 5000, 50, 250000),
(26, '22', 2, 'Besar', 45000, 45, 2025000),
(27, '22', 2, 'Kecil', 3000, 50, 150000),
(28, '23', 2, 'Besar', 45000, 6, 270000),
(29, '23', 2, 'Besar', 45000, 6, 270000),
(30, '23', 2, 'Besar', 45000, 6, 270000),
(31, '24', 2, 'Besar', 45000, 100, 4500000),
(32, '24', 2, 'Kecil', 3000, 250, 750000),
(33, '25', 2, 'Besar', 45000, 10, 450000),
(34, '25', 2, 'Kecil', 3000, 100, 300000),
(35, '26', 2, 'Besar', 45000, 10, 450000);

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
  `jumlah_satuan_besar` decimal(10,2) DEFAULT NULL,
  `jumlah_isi_satuan_besar` int(11) DEFAULT NULL,
  `total_isi_satuan_kecil` int(11) NOT NULL,
  `harga_satuan_kulak` int(11) NOT NULL,
  `total_harga_kulak` int(11) DEFAULT NULL,
  `harga_jual_satuan_besar1` int(11) DEFAULT NULL,
  `harga_jual_satuan_besar2` int(11) DEFAULT NULL,
  `harga_jual_satuan_besar3` int(11) DEFAULT NULL,
  `harga_jual_satuan_kecil1` int(11) DEFAULT NULL,
  `harga_jual_satuan_kecil2` int(11) DEFAULT NULL,
  `harga_jual_satuan_kecil3` int(11) DEFAULT NULL,
  `total_kulak` int(11) DEFAULT 0,
  `tanggal` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id_item`, `kategori_id`, `nama_item`, `jenis_satuan_besar`, `jenis_satuan_kecil`, `jumlah_satuan_besar`, `jumlah_isi_satuan_besar`, `total_isi_satuan_kecil`, `harga_satuan_kulak`, `total_harga_kulak`, `harga_jual_satuan_besar1`, `harga_jual_satuan_besar2`, `harga_jual_satuan_besar3`, `harga_jual_satuan_kecil1`, `harga_jual_satuan_kecil2`, `harga_jual_satuan_kecil3`, `total_kulak`, `tanggal`) VALUES
(1, 1, 'Teh Pucuk', 'Dus', 'Botol', 56.16, 100, 5616, 50000, 2500000, 50000, 51000, 52000, 5000, 5100, 5200, 50, '2024-05-02'),
(2, 1, 'Lee Mineral ', 'Dus', 'Botol', 1000.00, 10, 10000, 40000, 2000000, 45000, 50000, 55000, 3000, 3500, 4000, 77, '2024-05-03');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`) VALUES
(1, 'Minuman');

-- --------------------------------------------------------

--
-- Table structure for table `opname`
--

CREATE TABLE `opname` (
  `id_opname` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `stok_opname` int(11) NOT NULL,
  `balance` varchar(255) NOT NULL,
  `balance_small` varchar(225) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tanggal` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `nomor`) VALUES
(1, 'adi', 'Jl Mawar', '087234723468'),
(2, 'azky', 'Jl Melati', '085675756555'),
(3, 'Budi', 'jl sultan agung', '087234234433'),
(4, 'Toni', 'Jl Mangga', '087234234433'),
(5, 'Yudi', 'Jl Duren', '087234234411'),
(6, 'Dini', 'Jl Cempaka', '087234234123');

-- --------------------------------------------------------

--
-- Table structure for table `piutang`
--

CREATE TABLE `piutang` (
  `id_piutang` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `bayar` int(11) DEFAULT NULL,
  `kurangan_hutang` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `piutang`
--

INSERT INTO `piutang` (`id_piutang`, `id_transaksi`, `bayar`, `kurangan_hutang`, `tanggal`, `status`) VALUES
(1, 1, 0, 40000, '2024-05-04', 'Belum Lunas'),
(2, 3, 0, 50000, '2024-05-04', 'Belum Lunas'),
(3, 4, 0, 10000, '2024-05-04', 'Belum Lunas'),
(4, 5, 0, 40000, '2024-05-04', 'Belum Lunas'),
(5, 6, 0, 5000, '2024-05-04', 'Belum Lunas'),
(6, 2, 0, 200, '2024-05-04', 'Belum Lunas'),
(7, 7, 5000, 5000, '2024-05-04', 'Belum Lunas'),
(8, 8, 6000, 45000, '2024-05-05', 'Belum Lunas'),
(9, 12, 11, 10189, '2024-08-30', 'Belum Lunas'),
(10, 16, 3, 24997, '2024-08-30', 'Belum Lunas'),
(11, 17, 5, 14995, '2024-08-30', 'Belum Lunas'),
(12, 18, 400, 60000, '2024-08-31', 'Belum Lunas'),
(13, 20, 1000, 19000, '2024-08-31', 'Belum Lunas'),
(14, 21, 0, 400000, '2024-09-09', 'Belum Lunas'),
(15, 22, 0, 2175000, '2024-09-09', 'Belum Lunas'),
(16, 23, 4353, 805647, '2024-09-09', 'Belum Lunas'),
(17, 24, 0, 5250000, '2024-09-10', 'Belum Lunas'),
(18, 25, 0, 750000, '2024-09-10', 'Belum Lunas'),
(19, 26, 0, 450000, '2024-09-10', 'Belum Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `restock`
--

CREATE TABLE `restock` (
  `id_restock` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `jumlah_restock` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_item` varchar(255) NOT NULL,
  `stok_satuan_besar` decimal(10,2) NOT NULL,
  `isi_satuan_besar` int(11) NOT NULL,
  `totalnya` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`id_restock`, `id_item`, `jumlah_restock`, `kategori`, `tanggal`, `nama_item`, `stok_satuan_besar`, `isi_satuan_besar`, `totalnya`) VALUES
(1, 2, 0, 'Minuman', '2024-09-10', 'Lee Mineral ', 15.00, 10, 150),
(2, 2, 0, 'Minuman', '2024-09-10', 'Lee Mineral ', 1015.00, 10, 10150),
(3, 2, 0, 'Minuman', '2024-09-10', 'Lee Mineral ', 1025.00, 10, 10250),
(4, 2, 0, 'Minuman', '2024-09-10', 'Lee Mineral ', 1000.00, 10, 10000),
(5, 1, 0, 'Minuman', '2024-09-10', 'Teh Pucuk', 46.16, 100, 4616),
(6, 2, 10, 'Minuman', '2024-09-10', 'Lee Mineral ', 1020.00, 10, 10200),
(7, 2, 10, 'Minuman', '2024-09-10', 'Lee Mineral ', 1010.00, 10, 10100);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nomor` varchar(13) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `nama`, `nomor`, `alamat`) VALUES
(1, 'Anwar', '087234723468', 'Jl Mawar');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `no_transaksi` varchar(10) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `nama_pelanggan` varchar(100) NOT NULL,
  `total_harga` int(100) NOT NULL,
  `total_bayar` int(100) NOT NULL,
  `kembalian` int(100) NOT NULL,
  `tipe_pembayaran` varchar(50) NOT NULL,
  `kekurangan` varchar(100) DEFAULT NULL,
  `sales` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `no_transaksi`, `tanggal`, `nama_pelanggan`, `total_harga`, `total_bayar`, `kembalian`, `tipe_pembayaran`, `kekurangan`, `sales`) VALUES
(1, 'TR15641803', '2024-05-04', 'adi', 50000, 10000, 0, 'Kredit', '40000', 'Anwar'),
(2, 'TR19120521', '2024-05-04', 'adi', 10200, 10000, 0, 'Kredit', '200', 'Anwar'),
(3, 'TR40141070', '2024-05-04', 'azky', 50000, 0, 0, 'Kredit', '50000', 'Anwar'),
(4, 'TR19214592', '2024-05-04', 'Budi', 10500, 500, 0, 'Kredit', '10000', 'Anwar'),
(5, 'TR87195515', '2024-05-04', 'Budi', 45000, 5000, 0, 'Kredit', '40000', 'Anwar'),
(6, 'TR13260015', '2024-05-04', 'Budi', 6000, 1000, 0, 'Kredit', '5000', 'Anwar'),
(7, 'TR21334237', '2024-05-04', 'Toni', 15000, 5000, 0, 'Kredit', '10000', 'Anwar'),
(8, 'TR14254056', '2024-05-05', 'Yudi', 56000, 6000, 0, 'Kredit', '50000', 'Anwar'),
(9, 'TR83037392', '2024-08-30', 'adi', 55200, 60000, 4800, 'Cash', '0', 'Anwar'),
(10, 'TR16335794', '2024-08-30', 'adi', 10000, 20000, 10000, 'Cash', '0', 'Anwar'),
(11, 'TR20662411', '2024-08-30', 'adi', 3000, 5000, 2000, 'Cash', '0', 'Anwar'),
(12, 'TR13312396', '2024-08-30', 'adi', 10200, 11, 0, 'Kredit', '10189', 'Anwar'),
(13, 'TR19946289', '2024-08-30', 'adi', 3000, 111111, 108111, 'Cash', '0', 'Anwar'),
(14, 'TR14629019', '2024-08-30', 'adi', 10000, 22222, 12222, 'Cash', '0', 'Anwar'),
(15, 'TR81699817', '2024-08-30', 'adi', 10000, 222222, 212222, 'Cash', '0', 'Anwar'),
(16, 'TR17727054', '2024-08-30', 'adi', 25000, 3, 0, 'Kredit', '24997', 'Anwar'),
(17, 'TR14766878', '2024-08-30', 'adi', 15000, 5, 0, 'Kredit', '14995', 'Anwar'),
(18, 'TR19382804', '2024-08-31', 'adi', 60400, 400, 0, 'Kredit', '60000', 'Anwar'),
(19, 'TR21257224', '2024-08-31', 'adi', 25000, 12313123, 12288123, 'Cash', '0', 'Anwar'),
(20, 'TR16468796', '2024-08-31', 'adi', 20000, 1000, 0, 'Kredit', '19000', 'Anwar'),
(21, 'TR15280314', '2024-09-09', 'adi', 400000, 0, 0, 'Kredit', '400000', 'Anwar'),
(22, 'TR64458417', '2024-09-09', 'adi', 2175000, 0, 0, 'Kredit', '2175000', 'Anwar'),
(23, 'TR20870662', '2024-09-09', 'adi', 810000, 4353, 0, 'Kredit', '805647', 'Anwar'),
(24, 'TR12247237', '2024-09-10', 'adi', 5250000, 0, 0, 'Kredit', '5250000', 'Anwar'),
(25, 'TR19260300', '2024-09-10', 'adi', 750000, 0, 0, 'Kredit', '750000', 'Anwar'),
(26, 'TR79122767', '2024-09-10', 'adi', 450000, 0, 0, 'Kredit', '450000', 'Anwar');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(5) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `role`, `password`) VALUES
(1, 'admin', 'Admin', 'admin'),
(2, 'Kasir', 'Kasir', 'Kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cicilan_piutang`
--
ALTER TABLE `cicilan_piutang`
  ADD PRIMARY KEY (`id_cicilan`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opname`
--
ALTER TABLE `opname`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `id_item` (`id_item`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id_piutang`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `restock`
--
ALTER TABLE `restock`
  ADD PRIMARY KEY (`id_restock`),
  ADD KEY `id_item` (`id_item`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cicilan_piutang`
--
ALTER TABLE `cicilan_piutang`
  MODIFY `id_cicilan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `opname`
--
ALTER TABLE `opname`
  MODIFY `id_opname` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id_piutang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `id_restock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `restock`
--
ALTER TABLE `restock`
  ADD CONSTRAINT `restock_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
