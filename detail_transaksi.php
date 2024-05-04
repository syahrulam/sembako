<?php
// Include necessary files and start session
include('layout/head.php');
include('koneksi/config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve transaction ID from GET parameters
$id_transaksi = $_GET['id_transaksi'];

// Fetch transaction information
$query_transaksi = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
$result_transaksi = $koneksi->query($query_transaksi);

if ($result_transaksi->num_rows == 0) {
    die("Transaksi tidak ditemukan.");
}

$row_transaksi = $result_transaksi->fetch_assoc();

// Fetch detailed transaction information along with related items
$query = "
    SELECT 
        detail_transaksi.*, 
        item.*, 
        detail_transaksi.jumlah_satuan AS jumlah,
        item.jenis_satuan_besar,
        item.jenis_satuan_kecil
    FROM 
        detail_transaksi
    INNER JOIN 
        item 
    ON 
        detail_transaksi.id_item = item.id_item
    WHERE 
        detail_transaksi.id_transaksi = '$id_transaksi'
";

$result = $koneksi->query($query);
?>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <?php include('layout/navbar.php'); ?>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <?php include('layout/sidebar.php'); ?>
        </div>

        <div class="main-content">
            <section class="section">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Detail Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <!-- Transaction Header Information -->
                        <div class="row">
                            <div class="col-2"><strong>No Transaksi</strong></div>
                            <div class="col-3">: <?php echo $row_transaksi['no_transaksi']; ?></div>
                            <div class="col-2"><strong>Nama Pelanggan</strong></div>
                            <div class="col-5">: <?php echo $row_transaksi['nama_pelanggan']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><strong>Tanggal</strong></div>
                            <div class="col-3">: <?php echo date('d F Y', strtotime($row_transaksi['tanggal'])); ?></div>
                            <div class="col-2"><strong>Sales</strong></div>
                            <div class="col-5">: <?php echo $row_transaksi['sales']; ?></div>
                        </div>

                        <!-- Transaction Detail Table -->
                        <div class="row mt-2">
                            <table class="table">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Jenis Satuan</th>
                                        <th>Jumlah Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Total Per Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($data = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$data['nama_item']}</td>";
                                            
                                            // Display correct unit type
                                            $jenis_satuan = ($data['jenis_satuan'] === 'Besar') ? $data['jenis_satuan_besar'] : $data['jenis_satuan_kecil'];
                                            echo "<td>{$jenis_satuan}</td>";
                                            
                                            echo "<td>{$data['jumlah']}</td>";
                                            echo "<td>Rp." . number_format($data['harga_satuan'], 0, ',', '.') . "</td>";
                                            echo "<td>Rp." . number_format($data['total'], 0, ',', '.') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Tidak ada detail transaksi.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Additional Payment Information -->
                        <div class="mb-4">
                            <div class="row mb-2">
                                <div class="col-2"><strong>Harga Total</strong></div>
                                <div class="col-3">: Rp. <?php echo number_format($row_transaksi['total_harga'], 0, ',', '.'); ?></div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-2"><strong>Pembayaran</strong></div>
                                <div class="col-3">: <?php echo $row_transaksi['tipe_pembayaran']; ?></div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-2"><strong>Uang diterima</strong></div>
                                <div class="col-3">: Rp. <?php echo number_format($row_transaksi['total_bayar'], 0, ',', '.'); ?></div>
                            </div>

                            <div class="row mb-2">
                                <?php
                                if ($row_transaksi['tipe_pembayaran'] === 'Cash') {
                                    echo "<div class='col-2'><strong>Kembalian</strong></div>";
                                } elseif ($row_transaksi['tipe_pembayaran'] === 'Kredit') {
                                    echo "<div class='col-2'><strong>Kekurangan</strong></div>";
                                }
                                ?>
                                <div class="col-3">: Rp. <?php echo number_format(($row_transaksi['tipe_pembayaran'] === 'Cash') ? $row_transaksi['kembalian'] : $row_transaksi['kekurangan'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- JavaScript Includes -->
        <?php include('layout/js.php'); ?>
    </div>
</div>
</body>
</html>
