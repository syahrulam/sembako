<?php
// include file koneksi
include('koneksi/config.php');

if (isset($_POST['searchSales'])) {
    $searchSales = $_POST['searchSales'];

    // Query untuk mencari nama item berdasarkan kata kunci
    $query = "SELECT nama FROM sales WHERE nama LIKE '%$searchSales%'";
    
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
