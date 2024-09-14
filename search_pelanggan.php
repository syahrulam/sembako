<?php
include('koneksi/config.php');

if (isset($_POST['searchPelanggan'])) {
    $searchTerm = $_POST['searchPelanggan'];

    // Query untuk mencari pelanggan berdasarkan input
    $query = "SELECT nama FROM pelanggan WHERE nama LIKE '%" . $searchTerm . "%' LIMIT 10";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah ada hasil
    if (mysqli_num_rows($result) > 0) {
        // Tampilkan setiap nama yang ditemukan
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['nama'] . "</li>";
        }
    } else {
        echo "<li>Nama tidak ditemukan</li>";
    }
}
?>
