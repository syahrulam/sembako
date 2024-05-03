<?php
include('koneksi/config.php');

$selectedPelanggan = $_POST['selectedPelanggan'];

$queryPiutang = "SELECT transaksi.nama_pelanggan, SUM(piutang.kurangan_hutang) AS total_hutang
FROM piutang 
INNER JOIN transaksi ON transaksi.id_transaksi = piutang.id_transaksi 
WHERE transaksi.nama_pelanggan = '$selectedPelanggan'";

$result = mysqli_query($koneksi, $queryPiutang);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    // Check if total_hutang is null, set it to 0 if it is
    $total_hutang = isset($row['total_hutang']) ? $row['total_hutang'] : 0;
    // Prepare the response array
    $response = array(
        'nama' => $row['nama_pelanggan'],
        'total_hutang' => $total_hutang
    );
    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'Query gagal'));
}

mysqli_close($koneksi);
?>
