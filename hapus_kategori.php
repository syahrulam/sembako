<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Mengambil id kategori dari URL
    $id = $_GET['id'];

    // Query untuk menghapus kategori
    $query = "DELETE FROM kategori WHERE id='$id'";

    if ($koneksi->query($query) === TRUE) {
        // Jika berhasil dihapus, redirect ke halaman kategori
        header("Location: kategori.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
}

$koneksi->close();
