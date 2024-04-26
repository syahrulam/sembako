<?php
include('koneksi/config.php');

session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];
$role = $_SESSION['role'];


// Pastikan elemen $_POST['bulanTahun'] terdefinisi sebelum mengaksesnya
$bulanTahun = isset($_POST['bulanTahun']) ? $_POST['bulanTahun'] : '';


// Pisahkan nilai bulan dan tahun dari input bulanTahun
if (!empty($bulanTahun)) {
    list($tahun, $bulan) = explode('-', $bulanTahun);
}

$query = "SELECT item.*, kategori.kategori 
FROM item 
INNER JOIN kategori ON item.kategori_id = kategori.id WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun  ";

$result = $koneksi->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $no = 1;
        // Output data dari setiap baris
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['kategori'] . "</td>"; // Menggunakan kolom 'kategori' dari tabel kategori
            echo "<td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>"; // Menggunakan kolom 'kategori' dari tabel kategori
            echo "<td>" . $row['nama_item'] . "</td>";

            // Menghitung Stok Satuan Besar dan Isi dalam Satuan Besar
            $stok_satuan_besar = floatval($row['jumlah_satuan_besar']) . ' ' . $row['jenis_satuan_besar'];
            $isi_dalam_satuan_besar = $row['jumlah_isi_satuan_besar'] . ' ' . $row['jenis_satuan_kecil'];

            // Menghitung Totalnya
            $total = $row['jumlah_satuan_besar'] * $row['jumlah_isi_satuan_besar'];

            echo "<td>" . $stok_satuan_besar . "</td>";
            echo "<td>" . $isi_dalam_satuan_besar . "</td>";
            echo "<td>" . $total . ' ' . $row['jenis_satuan_kecil'] . "</td>";
            echo "<td>";
            echo "<a href='edit_item.php?id_item=" . $row['id_item'] . "' class='btn btn-warning btn-sm'>Perbarui Data</a>";
            echo "<a href='hapus_item.php?id_item=" . $row['id_item'] . "' class='btn btn-danger btn-sm'>Hapus</a>";
            echo "<a href='print_item.php?id_item=" . $row['id_item'] . "' class='btn btn-success btn-sm'>Print</a>";
            echo "<button onclick='showRestockField(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Restok</button>"; // Tombol Restok

            // Tombol Detail
            echo "<a href='detail_item.php?id_item=" . $row['id_item'] . "' class='btn btn-info btn-sm'>Detail</a>";

            // Field restok dimasukkan ke dalam tabel di bawah tombol Restok
            echo "<div id='restockField_" . $row['id_item'] . "' style='display: none;'>";
            echo "<input type='number' id='restockQuantity_" . $row['id_item'] . "' placeholder='Jumlah Restok'>";
            echo "<button onclick='submitRestock(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Submit</button>"; // Tombol Submit Restok
            echo "</div>"; // Penutup div untuk restockField
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Tidak ada data yang ditemukan.</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>Error: " . $koneksi->error . "</td></tr>";
}

// Menutup koneksi database
$koneksi->close();
