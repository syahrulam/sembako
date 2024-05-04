<?php
include('koneksi/config.php');

$selectedPelanggan = $_POST['selectedPelanggan'];

$queryNamaPelanggan = "SELECT nama_pelanggan FROM transaksi WHERE nama_pelanggan = '$selectedPelanggan'";
$resultNamaPelanggan = mysqli_query($koneksi, $queryNamaPelanggan);

$response = array(
    'nama' => $selectedPelanggan, 
    'total_hutang' => 0
);

if ($rowNamaPelanggan = mysqli_fetch_assoc($resultNamaPelanggan)) {
    $response['nama'] = $rowNamaPelanggan['nama_pelanggan'];
}

$queryPiutang = "SELECT SUM(kurangan_hutang) AS total_hutang FROM piutang 
                 INNER JOIN transaksi ON transaksi.id_transaksi = piutang.id_transaksi 
                 WHERE transaksi.nama_pelanggan = '$selectedPelanggan'";
$resultPiutang = mysqli_query($koneksi, $queryPiutang);

if ($rowPiutang = mysqli_fetch_assoc($resultPiutang)) {
    $response['total_hutang'] = isset($rowPiutang['total_hutang']) ? $rowPiutang['total_hutang'] : 0;
}

echo json_encode($response);

mysqli_close($koneksi);
?>
