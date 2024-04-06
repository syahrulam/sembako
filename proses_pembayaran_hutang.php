<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_transaksi = $_POST['id_transaksi'];
    $jumlah_pembayaran = $_POST['jumlah'];

    // Persiapkan pernyataan SQL untuk mendapatkan kekurangan berdasarkan ID transaksi
    $query_kekurangan = "SELECT kekurangan FROM transaksi WHERE id_transaksi = ? AND tipe_pembayaran = 'Debit'";
    $stmt = mysqli_prepare($koneksi, $query_kekurangan);
    mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
    mysqli_stmt_execute($stmt);
    $result_kekurangan = mysqli_stmt_get_result($stmt);
    $row_kekurangan = mysqli_fetch_assoc($result_kekurangan);

    // Periksa apakah data ditemukan
    if ($row_kekurangan) {
        // Ambil kekurangan saat ini
        $kekurangan_sekarang = $row_kekurangan['kekurangan'];

        // Hitung kekurangan terbaru setelah pembayaran
        $kekurangan_terbaru = $kekurangan_sekarang - $jumlah_pembayaran;

        // Perbarui nilai kekurangan di database
        $query_update = "UPDATE transaksi SET kekurangan = ? WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($koneksi, $query_update);
        mysqli_stmt_bind_param($stmt, "ii", $kekurangan_terbaru, $id_transaksi);
        $result_update = mysqli_stmt_execute($stmt);

        if ($result_update) {
            // Pesan alert
            echo "<script>alert('Pembayaran hutang berhasil.')</script>";
            // Redirect ke halaman sebelumnya
            echo "<script>window.history.go(-1);</script>";
            exit(); // Keluar dari skrip PHP
        } else {
            echo "Terjadi kesalahan dalam memperbarui nilai kekurangan.";
        }
    } else {
        echo "ID transaksi tidak ditemukan atau tipe pembayarannya bukan 'Debit'.";
    }
} else {
    echo "Akses tidak sah.";
}
?>
