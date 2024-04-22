<?php include('layout/head.php'); ?>

<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// include koneksi database
include('koneksi/config.php');

// Query untuk mendapatkan data detail transaksi dan menghitung total akumulasi satuan kecil
$sql = "SELECT
            trans.nama_pelanggan,
            item.nama_item,
            det.jenis_satuan,
            det.jumlah_satuan,
            item.jumlah_isi_satuan_besar
        FROM 
            detail_transaksi AS det
        INNER JOIN 
            transaksi AS trans ON det.id_transaksi = trans.id_transaksi
        INNER JOIN 
            item ON det.id_item = item.id_item";

$result = $koneksi->query($sql);

// Array untuk menyimpan informasi akumulasi dengan key gabungan nama pelanggan dan nama item
$details = [];

// Loop untuk mengelompokkan data berdasarkan nama pelanggan dan nama item
while ($row = $result->fetch_assoc()) {
    $key = $row['nama_pelanggan'] . '_' . $row['nama_item'];

    if (!isset($details[$key])) {
        $details[$key] = [
            'nama_pelanggan' => $row['nama_pelanggan'],
            'nama_item' => $row['nama_item'],
            'total_besar' => 0,
            'total_kecil' => 0,
            'total_akumulasi_kecil' => 0
        ];
    }

    if ($row['jenis_satuan'] == 'Besar') {
        $details[$key]['total_besar'] += $row['jumlah_satuan'];
        $details[$key]['total_akumulasi_kecil'] += $row['jumlah_satuan'] * $row['jumlah_isi_satuan_besar'];
    } else {
        $details[$key]['total_kecil'] += $row['jumlah_satuan'];
        $details[$key]['total_akumulasi_kecil'] += $row['jumlah_satuan'];
    }
}
?>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <?php include('layout/navbar.php'); ?>
            </nav>
            <div class="main-sidebar sidebar-style-2" style="overflow-y: auto;">
                <?php include('layout/sidebar.php'); ?>
            </div>

            <div id="app">
                <!-- Bagian Utama -->
                <div class="main-content">
                    <section class="section">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <!-- Tabel Detail Item Terjual -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Detail Item Pembelian</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Nama Item</th>
                                                        <th>Beli Dalam Satuan Besar</th>
                                                        <th>Beli Dalam Satuan Kecil</th>
                                                        <th>Total Akumulasi dalam Satuan Kecil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($details) > 0) {
                                                        $no = 1;
                                                        foreach ($details as $detail) {
                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $detail['nama_pelanggan'] . "</td>";
                                                            echo "<td>" . $detail['nama_item'] . "</td>";
                                                            echo "<td>" . $detail['total_besar'] . "</td>";
                                                            echo "<td>" . $detail['total_kecil'] . "</td>";
                                                            echo "<td>" . $detail['total_akumulasi_kecil'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>Tidak ada detail item pembelian.</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tabel Detail Item Terjual -->

                            </div>
                        </div>
                    </section>
                </div>
                <!-- End Bagian Utama -->

            </div>

            <?php include('layout/js.php'); ?>
        </div>
    </div>
</body>

</html>

<?php
// Tutup koneksi database
$koneksi->close();
?>
