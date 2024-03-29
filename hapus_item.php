<?php
include('koneksi/config.php');

// Periksa apakah parameter id_item telah diterima
if (isset($_GET['id_item'])) {
    // Tangkap nilai id_item dari parameter URL
    $id_item = $_GET['id_item'];

    // Buat query SQL untuk menghapus item berdasarkan id_item
    $query = "DELETE FROM item WHERE id_item='$id_item'";

    // Jalankan query
    if ($koneksi->query($query) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman item.php
        header("Location: item.php");
        exit();
    } else {
        // Jika terjadi kesalahan saat menjalankan query, tampilkan pesan kesalahan
        echo "Error: " . $koneksi->error;
    }
} else {
    // Jika parameter id_item tidak diterima, tampilkan pesan kesalahan
    echo "Error: ID Item tidak ditemukan.";
}

// Tutup koneksi database
$koneksi->close();
