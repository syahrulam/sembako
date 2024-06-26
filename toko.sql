-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Bulan Mei 2024 pada 03.06
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
-- Struktur dari tabel `cicilan_piutang`
--

CREATE TABLE `cicilan_piutang` (
  `id_cicilan` int(11) NOT NULL,
  `id_transaksi` int(5) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `cicilan` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cicilan_piutang`
--

INSERT INTO `cicilan_piutang` (`id_cicilan`, `id_transaksi`, `tanggal`, `cicilan`) VALUES
(1, 1, '2024-05-04', 5000),
(2, 7, '2024-05-04', 5000),
(3, 8, '2024-05-05', 5000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
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
-- Dumping data untuk tabel `detail_transaksi`
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
(9, '8', 2, 'Kecil', 3000, 2, 6000);

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
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id_item`, `kategori_id`, `nama_item`, `jenis_satuan_besar`, `jenis_satuan_kecil`, `jumlah_satuan_besar`, `jumlah_isi_satuan_besar`, `total_isi_satuan_kecil`, `harga_satuan_kulak`, `total_harga_kulak`, `harga_jual_satuan_besar1`, `harga_jual_satuan_besar2`, `harga_jual_satuan_besar3`, `harga_jual_satuan_kecil1`, `harga_jual_satuan_kecil2`, `harga_jual_satuan_kecil3`, `total_kulak`, `tanggal`) VALUES
(1, 1, 'Teh Pucuk', 'Dus', 'Botol', 46.95, 100, 4695, 50000, 2500000, 50000, 51000, 52000, 5000, 5100, 5200, 50, '2024-05-02'),
(2, 1, 'Lee Mineral ', 'Dus', 'Botol', 48.30, 10, 483, 40000, 2000000, 45000, 50000, 55000, 3000, 3500, 4000, 50, '2024-05-03');

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
(1, 'adi', 'Jl Mawar', '087234723468'),
(2, 'azky', 'Jl Melati', '085675756555'),
(3, 'Budi', 'jl sultan agung', '087234234433'),
(4, 'Toni', 'Jl Mangga', '087234234433'),
(5, 'Yudi', 'Jl Duren', '087234234411'),
(6, 'Dini', 'Jl Cempaka', '087234234123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `piutang`
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
-- Dumping data untuk tabel `piutang`
--

INSERT INTO `piutang` (`id_piutang`, `id_transaksi`, `bayar`, `kurangan_hutang`, `tanggal`, `status`) VALUES
(1, 1, 0, 40000, '2024-05-04', 'Belum Lunas'),
(2, 3, 0, 50000, '2024-05-04', 'Belum Lunas'),
(3, 4, 0, 10000, '2024-05-04', 'Belum Lunas'),
(4, 5, 0, 40000, '2024-05-04', 'Belum Lunas'),
(5, 6, 0, 5000, '2024-05-04', 'Belum Lunas'),
(6, 2, 0, 200, '2024-05-04', 'Belum Lunas'),
(7, 7, 5000, 5000, '2024-05-04', 'Belum Lunas'),
(8, 8, 6000, 45000, '2024-05-05', 'Belum Lunas');

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
(1, 'Anwar', '087234723468', 'Jl Mawar');

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
(1, 'TR15641803', '2024-05-04', 'adi', 50000, 10000, 0, 'Kredit', '40000', 'Anwar'),
(2, 'TR19120521', '2024-05-04', 'adi', 10200, 10000, 0, 'Kredit', '200', 'Anwar'),
(3, 'TR40141070', '2024-05-04', 'azky', 50000, 0, 0, 'Kredit', '50000', 'Anwar'),
(4, 'TR19214592', '2024-05-04', 'Budi', 10500, 500, 0, 'Kredit', '10000', 'Anwar'),
(5, 'TR87195515', '2024-05-04', 'Budi', 45000, 5000, 0, 'Kredit', '40000', 'Anwar'),
(6, 'TR13260015', '2024-05-04', 'Budi', 6000, 1000, 0, 'Kredit', '5000', 'Anwar'),
(7, 'TR21334237', '2024-05-04', 'Toni', 15000, 5000, 0, 'Kredit', '10000', 'Anwar'),
(8, 'TR14254056', '2024-05-05', 'Yudi', 56000, 6000, 0, 'Kredit', '50000', 'Anwar');

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
(1, 'admin', 'Admin', 'admin'),
(2, 'Kasir', 'Kasir', 'Kasir');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cicilan_piutang`
--
ALTER TABLE `cicilan_piutang`
  ADD PRIMARY KEY (`id_cicilan`);

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
-- AUTO_INCREMENT untuk tabel `cicilan_piutang`
--
ALTER TABLE `cicilan_piutang`
  MODIFY `id_cicilan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `opname`
--
ALTER TABLE `opname`
  MODIFY `id_opname` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id_piutang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
