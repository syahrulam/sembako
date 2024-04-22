<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna telah masuk
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('koneksi/config.php'); // Koneksi database

// Periksa apakah permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_transaksi = $_POST['id_transaksi'];
    $cicilan = $_POST['cicilan'];

    // Ambil jumlah hutang saat ini untuk transaksi
    $query_kurangan = "SELECT id_transaksi, kurangan_hutang 
                       FROM piutang
                       WHERE id_transaksi = ?";
    $stmt = mysqli_prepare($koneksi, $query_kurangan);
    mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $kurangan_hutang_sekarang = $row['kurangan_hutang'];

        // Hitung jumlah hutang baru setelah pembayaran
        $kurangan_hutang_terbaru = $kurangan_hutang_sekarang - $cicilan;

        // Perbarui tabel hutang dengan jumlah hutang baru
        $status_piutang = $kurangan_hutang_terbaru <= 0 ? 'Lunas' : 'Belum Lunas';
        $query_update_piutang = "UPDATE piutang SET kurangan_hutang = ?, status = ? WHERE id_transaksi = ?";
        $stmt_update = mysqli_prepare($koneksi, $query_update_piutang);
        mysqli_stmt_bind_param($stmt_update, "dss", $kurangan_hutang_terbaru, $status_piutang, $id_transaksi);
        $result_update = mysqli_stmt_execute($stmt_update);

        // Masukkan detail pembayaran ke dalam tabel pembayaran hutang
        if ($result_update) {
            $query_insert_cicilan = "INSERT INTO cicilan_piutang (id_transaksi, cicilan) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query_insert_cicilan);
            mysqli_stmt_bind_param($stmt_insert, "id", $id_transaksi, $cicilan);
            $result_insert = mysqli_stmt_execute($stmt_insert);

            if ($result_insert) {
                // Pengalihan setelah logika PHP selesai
                header("Location: piutang.php");
                exit();
            } else {
                echo "Terjadi kesalahan saat menyimspan pembayaran ke dalam tabel cicilan piutang.";
            }
        } else {
            echo "Terjadi kesalahan saat memperbarui data piutang.";
        }
    } else {
        echo "Tidak ditemukan data piutang untuk transaksi ini.";
    }
} else {
    echo "Akses tidak sah.";
}
