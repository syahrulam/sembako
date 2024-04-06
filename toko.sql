-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Apr 2024 pada 18.02
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int(100) NOT NULL,
  `id_transaksi` varchar(10) NOT NULL,
  `id_item` int(10) NOT NULL,
  `jenis_satuan` varchar(100) DEFAULT NULL,
  `harga_satuan` int(11) NOT NULL,
  `jumlah_satuan` int(100) NOT NULL,
  `total` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `id_transaksi`, `id_item`, `jenis_satuan`, `harga_satuan`, `jumlah_satuan`, `total`) VALUES
(5, '13', 6, 'Besar', 55000, 5, 275000),
(6, '14', 6, 'Kecil', 8000, 5, 40000),
(7, '15', 6, 'Besar', 55000, 5, 275000),
(8, '16', 6, 'Kecil', 8000, 5, 40000),
(9, '17', 6, 'Besar', 55000, 5, 275000),
(10, '18', 6, 'Kecil', 8000, 5, 40000),
(11, '19', 6, 'Kecil', 8000, 5, 40000),
(12, '20', 6, 'Kecil', 8000, 7, 56000),
(13, '21', 6, 'Kecil', 8000, 5, 40000),
(14, '22', 6, 'Kecil', 8000, 5, 40000),
(15, '23', 6, 'Kecil', 8000, 5, 40000),
(16, '24', 6, 'Kecil', 8000, 5, 40000),
(17, '25', 6, 'Besar', 55000, 2, 110000),
(18, '26', 7, 'Besar', 55000, 1, 55000),
(19, '27', 7, 'Kecil', 5000, 10, 50000),
(20, '28', 6, 'Kecil', 8000, 5, 40000),
(22, '30', 6, 'Besar', 55000, 2, 110000),
(23, '31', 6, 'Besar', 55000, 3, 165000),
(24, '32', 6, 'Besar', 55000, 1, 55000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
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
  `harga_jual_satuan_besar` int(11) DEFAULT NULL,
  `harga_jual_satuan_kecil` int(11) DEFAULT NULL,
  `total_kulak` int(11) DEFAULT 0,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id_item`, `kategori_id`, `nama_item`, `jenis_satuan_besar`, `jenis_satuan_kecil`, `jumlah_satuan_besar`, `jumlah_isi_satuan_besar`, `total_isi_satuan_kecil`, `harga_satuan_kulak`, `total_harga_kulak`, `harga_jual_satuan_besar`, `harga_jual_satuan_kecil`, `total_kulak`, `tanggal`) VALUES
(6, 1, 'Teh Pucuk', 'Dus', 'Botol', 0.50, 10, 5, 50000, 250000, 55000, 8000, 20, '2024-04-03'),
(7, 1, 'Lee Mineral ', 'Dus', 'Botol', 23.60, 25, 590, 50000, 1000000, 55000, 5000, 25, '2024-03-03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`) VALUES
(1, 'Minuman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `opname`
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

--
-- Dumping data untuk tabel `opname`
--

INSERT INTO `opname` (`id_opname`, `id_item`, `stok_opname`, `balance`, `balance_small`, `keterangan`, `tanggal`) VALUES
(38, 6, 50, 'Benar', '', 'Tulis Keterangan', '2024-04-04'),
(39, 7, 500, 'Kurang 3.6', '', 'Tulis Keterangan', '2024-04-04'),
(40, 7, 300, 'Kurang 11.6', '', 'Tulis Keterangan', '2024-04-04'),
(41, 6, 40, 'Kurang 1', '40', 'Tulis Keterangan', '2024-04-04'),
(42, 7, 20, 'Kurang 22.8', '', 'Tulis Keterangan', '2024-04-04'),
(43, 6, 10, 'Kurang 4', '-40', 'Tulis Keterangan', '2024-04-04'),
(44, 6, 35, 'Kurang 1.5', '-15', 'Tulis Keterangan', '2024-04-04'),
(45, 6, 45, 'Kurang 0.5', 'Kurang 5', 'Tulis Keterangan', '2024-04-04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `nomor`) VALUES
(1, 'adi', 'jl kemuning', '085156545458'),
(12, 'azky', 'Jl. Mawar', '085125456953');

-- --------------------------------------------------------

--
-- Struktur dari tabel `piutang`
--

CREATE TABLE `piutang` (
  `id_piutang` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `piutang`
--

INSERT INTO `piutang` (`id_piutang`, `id_transaksi`, `tanggal`, `status`) VALUES
(6576, 31, '2024-04-06', 'Lunas'),
(6577, 31, '2024-04-06', 'Belum Lunas'),
(6578, 31, '2024-04-06', 'Belum Lunas'),
(6579, 31, '2024-04-06', 'Lunas'),
(6580, 32, '2024-04-06', 'Belum Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nomor` varchar(13) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`id`, `nama`, `nomor`, `alamat`) VALUES
(1, 'Sales A', '085156665599', 'Jl Anggrek');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
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
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `no_transaksi`, `tanggal`, `nama_pelanggan`, `total_harga`, `total_bayar`, `kembalian`, `tipe_pembayaran`, `kekurangan`, `sales`) VALUES
(13, 'TR11564037', '2023-11-22', 'adi', 275000, 300000, 25000, 'Cash', '0', 'Sales A'),
(14, 'TR14072698', '2024-01-10', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(15, 'TR16667332', '2024-02-21', 'adi', 275000, 300000, 25000, 'Cash', '0', 'Sales A'),
(16, 'TR11859818', '2024-03-12', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(17, 'TR16971893', '2024-04-03', 'adi', 275000, 500000, 225000, 'Cash', '0', 'Sales A'),
(18, 'TR10049373', '2024-05-08', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(19, 'TR10049373', '2024-04-03', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(20, 'TR88360256', '2024-04-03', 'adi', 56000, 60000, 4000, 'Cash', '0', 'Sales A'),
(21, 'TR14214483', '2024-04-03', 'adi', 40000, 40000, 0, 'Cash', '0', 'Sales A'),
(22, 'TR71927130', '2024-08-09', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(23, 'TR71927130', '2024-04-03', 'adi', 40000, 50000, 10000, 'Cash', '0', 'Sales A'),
(24, 'TR12476853', '2024-07-11', 'adi', 40000, 500000, 460000, 'Cash', '0', 'Sales A'),
(25, 'TR15928077', '2024-06-07', 'adi', 110000, 1100000, 990000, 'Cash', '0', 'Sales A'),
(26, 'TR29540811', '2024-03-21', 'adi', 55000, 60000, 5000, 'Cash', '0', 'Sales A'),
(27, 'TR16986973', '2024-03-03', 'adi', 50000, 50000, 0, 'Cash', '0', 'Sales A'),
(28, 'TR98934129', '2024-04-04', 'adi', 40000, 3535345, 3495345, 'Cash', '0', 'Sales A'),
(30, 'TR15990347', '2024-04-05', 'adi', 110000, 5, 0, 'Debit', '0', 'Sales A'),
(31, 'TR10230300', '2024-04-05', 'adi', 165000, 5000, 0, 'Debit', '0', 'Sales A'),
(32, 'TR52793905', '2024-04-05', 'adi', 55000, 9, 0, 'Debit', '991', 'Sales A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_users` int(5) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_users`, `username`, `role`, `password`) VALUES
(1, 'admin', 'Admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `opname`
--
ALTER TABLE `opname`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `id_item` (`id_item`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id_piutang`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `opname`
--
ALTER TABLE `opname`
  MODIFY `id_opname` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id_piutang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6581;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
