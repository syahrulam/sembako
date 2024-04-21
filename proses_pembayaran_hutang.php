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

        // Periksa apakah setelah pembayaran kekurangan menjadi 0 atau negatif
        if ($kekurangan_terbaru <= 0) {
            // Jika kekurangan kurang dari atau sama dengan 0, maka piutang dianggap lunas
            $status_piutang = 'Lunas';
        } else {
            // Jika kekurangan masih lebih dari 0, maka piutang belum lunas
            $status_piutang = 'Belum Lunas';
        }

        // Perbarui nilai kekurangan di database
        $query_update = "UPDATE transaksi SET kekurangan = ? WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($koneksi, $query_update);
        mysqli_stmt_bind_param($stmt, "ii", $kekurangan_terbaru, $id_transaksi);
        $result_update = mysqli_stmt_execute($stmt);

        if ($result_update) {
            // Pernyataan SQL untuk menyimpan informasi pembayaran ke dalam tabel Piutang
            $query_insert_piutang = "INSERT INTO Piutang (id_transaksi, tanggal, status, nominal) VALUES (?, NOW(), ?, ?)";
            $stmt_piutang = mysqli_prepare($koneksi, $query_insert_piutang);
            mysqli_stmt_bind_param($stmt_piutang, "iss", $id_transaksi, $status_piutang, $jumlah_pembayaran);
            $result_insert_piutang = mysqli_stmt_execute($stmt_piutang);

            if ($result_insert_piutang) {
                // Pesan alert
                echo "<script>alert('Pembayaran hutang berhasil.')</script>";
                // Redirect ke halaman sebelumnya
                echo "<script>window.history.go(-1);</script>";
                exit(); // Keluar dari skrip PHP
            } else {
                echo "Terjadi kesalahan dalam menyimpan informasi pembayaran ke dalam tabel Piutang.";
            }
        } else {
            echo "Terjadi kesalahan dalam memperbarui nilai kekurangan.";
        }
    } else {
        echo "ID transaksi tidak ditemukan atau tipe pembayarannya bukan 'Debit'.";
    }
} else {
    echo "Akses tidak sah.";
}
