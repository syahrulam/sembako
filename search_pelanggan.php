<?php
// include file koneksi
include('koneksi/config.php');

if (isset($_POST['searchPelanggan'])) {
    $searchPelanggan = $_POST['searchPelanggan'];

    // Query untuk mencari nama item berdasarkan kata kunci
    $query = "SELECT nama FROM pelanggan WHERE nama LIKE '%$searchPelanggan%'";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<ul>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>' . $row['nama'] . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'Pelanggan tidak ditemukan';
        }
    } else {
        echo 'Error: ' . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
