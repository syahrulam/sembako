<?php
include('koneksi/config.php');

// Cek apakah parameter id_item tersedia di URL
if (!isset($_GET['id_item'])) {
    echo "Parameter id_item tidak tersedia.";
    exit; // Hentikan eksekusi script
}

// Ambil id_item dari parameter GET
$id_item = $_GET['id_item'];

// Query untuk mendapatkan detail item dari database berdasarkan ID
$query = "SELECT item.*, kategori.kategori 
            FROM item 
            INNER JOIN kategori ON item.kategori_id = kategori.id
            WHERE item.id_item = '$id_item'";
$result = $koneksi->query($query);

// Periksa apakah query berhasil dieksekusi
if (!$result) {
    echo "Gagal mengambil data item: " . $koneksi->error;
    exit; // Hentikan eksekusi script
}

// Periksa apakah ada data item yang ditemukan
if ($result->num_rows === 0) {
    echo "Item tidak ditemukan.";
    exit; // Hentikan eksekusi script
}

// Ambil data item dari hasil query
$row = $result->fetch_assoc();

// Fungsi untuk memformat stok satuan besar dan isi dalam satuan besar
function formatStok($jumlah, $jenis)
{
    return floatval($jumlah) . ' ' . $jenis;
}

// Fungsi untuk memformat total
function formatTotal($jumlah_satuan_besar, $jumlah_isi_satuan_besar, $jenis_satuan_kecil)
{
    $total = $jumlah_satuan_besar * $jumlah_isi_satuan_besar;
    return $total . ' ' . $jenis_satuan_kecil;
}

// Tutup koneksi database
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Item - Print</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1>Detail Item</h1>
    <table>
        <tr>
            <th>Kategori</th>
            <td><?php echo $row['kategori']; ?></td>
        </tr>
        <tr>
            <th>Nama Item</th>
            <td><?php echo $row['nama_item']; ?></td>
        </tr>
        <tr>
            <th>Stok Satuan Besar</th>
            <td><?php echo formatStok($row['jumlah_satuan_besar'], $row['jenis_satuan_besar']); ?></td>
        </tr>
        <tr>
            <th>Isi dalam Satuan Besar</th>
            <td><?php echo $row['jumlah_isi_satuan_besar'] . ' ' . $row['jenis_satuan_kecil']; ?></td>
        </tr>
        <tr>
            <th>Total</th>
            <td><?php echo formatTotal($row['jumlah_satuan_besar'], $row['jumlah_isi_satuan_besar'], $row['jenis_satuan_kecil']); ?></td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>