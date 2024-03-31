<?php
// include file koneksi
include('koneksi/config.php');

if (isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];

    // Query untuk mencari nama item berdasarkan kata kunci
    $query = "SELECT nama_item FROM item WHERE nama_item LIKE '%$searchTerm%'";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<ul>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>' . $row['nama_item'] . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'Item tidak ditemukan';
        }
    } else {
        echo 'Error: ' . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
