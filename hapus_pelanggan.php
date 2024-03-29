<?php
include('koneksi/config.php');

// Jika parameter id ada dalam URL, artinya kita ingin menghapus pelanggan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM pelanggan WHERE id='$id'";
    if ($koneksi->query($query) === TRUE) {
        header("Location: pelanggan.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
} else {
    // Jika parameter id tidak ada, kembali ke halaman pelanggan.php
    header("Location: pelanggan.php");
    exit();
}

$koneksi->close();
