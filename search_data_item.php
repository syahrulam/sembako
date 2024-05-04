<?php
include('koneksi/config.php');

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

function filterByDate($tanggalFilter) {
    global $koneksi;
    if (!empty($tanggalFilter)) {
        $tanggal = date('d', strtotime($tanggalFilter));
        $bulan = date('m', strtotime($tanggalFilter));
        $tahun = date('Y', strtotime($tanggalFilter));
    } else {
        $tanggal = date('d');
        $bulan = date('m');
        $tahun = date('Y');
    }

    $query = "SELECT item.*, kategori.kategori 
              FROM item 
              INNER JOIN kategori ON item.kategori_id = kategori.id 
              WHERE DAY(tanggal) = $tanggal AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun";

    $result = $koneksi->query($query);

    if ($result) {
        return $result; 
    } else {
        return false;
    }
}

$tanggalFilter = isset($_POST['tanggalFilter']) ? $_POST['tanggalFilter'] : '';

$result = filterByDate($tanggalFilter);

if ($result) {
    if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['kategori'] . "</td>";
            echo "<td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>"; 
            echo "<td>" . $row['nama_item'] . "</td>";
             $stok_satuan_besar = floatval($row['jumlah_satuan_besar']) . ' ' . $row['jenis_satuan_besar'];
             $isi_dalam_satuan_besar = $row['jumlah_isi_satuan_besar'] . ' ' . $row['jenis_satuan_kecil'];
 
             $total = $row['jumlah_satuan_besar'] * $row['jumlah_isi_satuan_besar'];
 
             echo "<td>" . $stok_satuan_besar . "</td>";
             echo "<td>" . $isi_dalam_satuan_besar . "</td>";
             echo "<td>" . $total . ' ' . $row['jenis_satuan_kecil'] . "</td>";
             echo "<td>";
             echo "<a href='edit_item.php?id_item=" . $row['id_item'] . "' class='btn btn-warning btn-sm'>Perbarui Data</a>";
             echo "<a href='hapus_item.php?id_item=" . $row['id_item'] . "' class='btn btn-danger btn-sm'>Hapus</a>";
             echo "<a href='print_item.php?id_item=" . $row['id_item'] . "' class='btn btn-success btn-sm'>Print</a>";
             echo "<button onclick='showRestockField(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Restok</button>"; 
             echo "<a href='detail_item.php?id_item=" . $row['id_item'] . "' class='btn btn-info btn-sm'>Detail</a>";
             echo "<div id='restockField_" . $row['id_item'] . "' style='display: none;'>";
             echo "<input type='number' id='restockQuantity_" . $row['id_item'] . "' placeholder='Jumlah Restok'>";
             echo "<button onclick='submitRestock(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Submit</button>"; // Tombol Submit Restok
             echo "</div>"; 
             echo "</td>";
             echo "</tr>";
       
        }
    } else {
        echo "<tr><td colspan='4'>Tidak ada data yang ditemukan.</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>Error: Terjadi kesalahan dalam pengambilan data.</td></tr>";
}
$koneksi->close();
?>
