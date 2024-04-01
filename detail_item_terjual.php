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

// Ambil parameter nama pelanggan dari URL
$nama_pelanggan = $_GET['nama_pelanggan'];

// include koneksi database
include('koneksi/config.php');

$sql = "SELECT item.nama_item, SUM(detail_transaksi.jumlah_satuan) AS jumlah_terjual
        FROM transaksi
        INNER JOIN detail_transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi
        INNER JOIN item ON detail_transaksi.id_item = item.id_item
        WHERE transaksi.nama_pelanggan = '$nama_pelanggan'
        GROUP BY item.nama_item";
$result = $koneksi->query($sql);
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

            <div id="app">
                <!-- Bagian Utama -->
                <div class="main-content">
                    <section class="section">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <!-- Tabel Detail Item Terjual -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Detail Item Pembelian <?php echo $nama_pelanggan; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Item</th>
                                                        <th>Jumlah Terjual</th>
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
                                                            echo "<td>" . $row['nama_item'] . "</td>";
                                                            echo "<td>" . $row['jumlah_terjual'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        // Jika tidak ada data yang ditemukan
                                                        echo "<tr><td colspan='3'>Tidak ada detail item terjual untuk pelanggan ini.</td></tr>";
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