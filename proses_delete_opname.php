<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil ID opname dari data yang dikirim
    $idOpname = $_POST['id_opname'];

    // Query untuk menghapus data opname berdasarkan ID
    $query_delete = "DELETE FROM opname WHERE id_opname = '$idOpname'";
    if ($koneksi->query($query_delete) === TRUE) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Error: " . $query_delete . "<br>" . $koneksi->error;
    }

    // Tutup koneksi database
    $koneksi->close();
}
?>
