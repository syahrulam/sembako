<?php
session_start(); // Memulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('koneksi/config.php'); // Koneksi ke database

// Pastikan bahwa permintaan POST diterima
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_transaksi = $_POST['id_transaksi'];
    $jumlah_pembayaran = $_POST['jumlah']; // Jumlah yang dibayarkan

    // Dapatkan data piutang terakhir berdasarkan ID transaksi untuk mendapatkan sisa hutang
    $query_kurangan = "SELECT kurangan_hutang FROM piutang WHERE id_transaksi = ? ORDER BY tanggal DESC LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query_kurangan);
    mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Jika data ditemukan, hitung kurangan terbaru setelah pembayaran
    if ($row) {
        $kurangan_hutang_sekarang = $row['kurangan_hutang'];
        $kurangan_hutang_terbaru = $kurangan_hutang_sekarang - $jumlah_pembayaran;

        // Tentukan status piutang berdasarkan kekurangan terbaru
        $status_piutang = $kurangan_hutang_terbaru <= 0 ? 'Lunas' : 'Belum Lunas';

        // Simpan informasi pembayaran ke dalam tabel piutang
        $query_insert_piutang = "INSERT INTO piutang (id_transaksi, bayar, kurangan_hutang, tanggal, status) VALUES (?, ?, ?, NOW(), ?)";
        $stmt_insert = mysqli_prepare($koneksi, $query_insert_piutang);
        mysqli_stmt_bind_param($stmt_insert, "iiis", $id_transaksi, $jumlah_pembayaran, $kurangan_hutang_terbaru, $status_piutang);
        $result_insert = mysqli_stmt_execute($stmt_insert);

        if ($result_insert) {
            echo "<script>alert('Pembayaran berhasil disimpan.');</script>";
            echo "<script>window.history.go(-1);</script>"; // Redirect ke halaman sebelumnya
        } else {
            echo "Terjadi kesalahan saat menyimpan pembayaran ke dalam tabel piutang.";
        }
    } else {
        echo "Tidak ditemukan data piutang untuk ID transaksi ini.";
    }
} else {
    echo "Akses tidak sah.";
}
