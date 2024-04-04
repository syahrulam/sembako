<?php include('layout/head.php'); ?>

<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// include koneksi database
include('koneksi/config.php');

// Query untuk mengambil jumlah total dari setiap jenis satuan untuk setiap pelanggan dengan nama yang sama
$sql = "SELECT 
            trans.nama_pelanggan,
            item.nama_item,
            SUM(CASE WHEN det.jenis_satuan = 'Besar' THEN det.jumlah_satuan ELSE 0 END) AS total_besar,
            SUM(CASE WHEN det.jenis_satuan = 'Kecil' THEN det.jumlah_satuan ELSE 0 END) AS total_kecil
        FROM 
            detail_transaksi AS det
        INNER JOIN 
            transaksi AS trans ON det.id_transaksi = trans.id_transaksi
        INNER JOIN 
            item ON det.id_item = item.id_item
        GROUP BY 
            trans.nama_pelanggan, item.nama_item";

$result = $koneksi->query($sql);
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
                                                        <th>Total Akumulasi /Satuan Kecil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Cek apakah ada data yang ditemukan
                                                    if ($result->num_rows > 0) {
                                                        // Looping data dan tampilkan dalam tabel
                                                        $no = 1;
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                                            echo "<td>" . $row['nama_item'] . "</td>";
                                                            echo "<td>" . $row['total_besar'] . "</td>";
                                                            echo "<td>" . $row['total_kecil'] . "</td>";
                                                            echo "<td>" . ($row['total_besar'] * $row['total_kecil']) . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        // Jika tidak ada data yang ditemukan
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