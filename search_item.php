<?php
include('koneksi/config.php');

if (isset($_POST['searchItem'])) {
    $searchTerm = $_POST['searchItem'];

    // Query untuk mencari item beserta harga dan satuannya
    $query = "SELECT id_item, nama_item, 
                     jenis_satuan_besar, jenis_satuan_kecil, 
                     harga_jual_satuan_besar1, harga_jual_satuan_besar2, harga_jual_satuan_besar3, 
                     harga_jual_satuan_kecil1, harga_jual_satuan_kecil2, harga_jual_satuan_kecil3 
              FROM item 
              WHERE nama_item LIKE '%" . $searchTerm . "%' LIMIT 10";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah ada hasil
    if (mysqli_num_rows($result) > 0) {
        // Tampilkan setiap nama item yang ditemukan beserta satuan dan harga jualnya
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li data-id='" . $row['id_item'] . "' 
                     data-besar='" . $row['jenis_satuan_besar'] . "' 
                     data-kecil='" . $row['jenis_satuan_kecil'] . "' 
                     data-harga-besar1='" . $row['harga_jual_satuan_besar1'] . "' 
                     data-harga-besar2='" . $row['harga_jual_satuan_besar2'] . "' 
                     data-harga-besar3='" . $row['harga_jual_satuan_besar3'] . "' 
                     data-harga-kecil1='" . $row['harga_jual_satuan_kecil1'] . "' 
                     data-harga-kecil2='" . $row['harga_jual_satuan_kecil2'] . "' 
                     data-harga-kecil3='" . $row['harga_jual_satuan_kecil3'] . "'>" 
                     . $row['nama_item'] . "</li>";
        }
    } else {
        echo "<li>Item tidak ditemukan</li>";
    }
}
?>
