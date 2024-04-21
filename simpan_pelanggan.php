<?php
include('koneksi/config.php');

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nomor = $_POST['nomor'];

    // Periksa apakah nama pelanggan sudah ada di database
    $check_query = "SELECT * FROM pelanggan WHERE nama = '$nama'";
    $result = $koneksi->query($check_query);

    // Jika nama pelanggan sudah ada, tampilkan pesan error menggunakan alert JavaScript
    if ($result->num_rows > 0) {
        echo "<script>alert('Error: Nama pelanggan sudah ada.');</script>";
    } else {
        // Jika nama pelanggan belum ada, lakukan operasi INSERT
        $insert_query = "INSERT INTO pelanggan (nama, alamat, nomor) VALUES ('$nama', '$alamat', '$nomor')";
        if ($koneksi->query($insert_query) === TRUE) {
            header("Location: transaksi.php");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $koneksi->error;
        }
    }
}

$koneksi->close();
?>