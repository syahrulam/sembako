<?php
include('koneksi/config.php');
// Ambil nilai selectedItem dari POST
$selectedSales = $_POST['selectedSales'];

// Query untuk mendapatkan jumlah_stok berdasarkan selectedItem
$query = "SELECT * FROM pelanggan WHERE nama = '$selectedSales'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    // Mengembalikan data dalam format JSON
    echo json_encode($row);
} else {
    // Jika query gagal, kembalikan pesan error
    echo json_encode(array('error' => 'Query gagal'));
}

// Tutup koneksi database
mysqli_close($koneksi);
?>
