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
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jumlah_pembayaran = $_POST['jumlah'];

    // Persiapkan pernyataan SQL
    $query_kekurangan = "SELECT kekurangan FROM transaksi WHERE nama_pelanggan = ? AND tipe_pembayaran = 'Debit'";
    
    // Persiapkan dan eksekusi pernyataan
    $stmt = mysqli_prepare($koneksi, $query_kekurangan);
    mysqli_stmt_bind_param($stmt, "s", $nama_pelanggan);
    mysqli_stmt_execute($stmt);
    
    // Ambil hasil query
    $result_kekurangan = mysqli_stmt_get_result($stmt);
    $row_kekurangan = mysqli_fetch_assoc($result_kekurangan);

    // Periksa apakah ada baris yang ditemukan
    if ($row_kekurangan) {
        $kekurangan_sekarang = $row_kekurangan['kekurangan'];
      
        $kekurangan_terbaru = $kekurangan_sekarang - $jumlah_pembayaran;
        // Update kekurangan di database
        $query_update = "UPDATE transaksi SET kekurangan = ? WHERE nama_pelanggan = ? AND tipe_pembayaran = 'Debit'";
        
        // Persiapkan dan eksekusi pernyataan update
        $stmt = mysqli_prepare($koneksi, $query_update);
        mysqli_stmt_bind_param($stmt, "is", $kekurangan_terbaru, $nama_pelanggan);
        $result_update = mysqli_stmt_execute($stmt);

        if ($result_update) {
            // Redirect dengan pesan sukses jika pembayaran berhasil
            header("Location: $redirect_url?status=payment_success");
            exit();
        } 
        
    } else {
        // Redirect jika nama pelanggan tidak ditemukan dalam database
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=customer_not_found");
        exit();
    }
} else {
    // Redirect jika tidak ada akses POST
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
