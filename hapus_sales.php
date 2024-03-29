<?php
include('koneksi/config.php');

// Jika parameter id ada dalam URL, artinya kita ingin menghapus member
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM sales WHERE id='$id'";
    if ($koneksi->query($query) === TRUE) {
        header("Location: sales.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
} else {
    // Jika parameter id tidak ada, kembali ke halaman member.php
    header("Location: sales.php");
    exit();
}

$koneksi->close();
