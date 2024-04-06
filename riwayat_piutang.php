<?php
include('layout/head.php');
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Menggunakan tanda == untuk perbandingan dalam query SQL
include('koneksi/config.php');

// Ambil data piutang dari tabel Piutang dan informasi transaksi dari tabel Transaksi
$query_data = "SELECT Transaksi.no_transaksi, Transaksi.nama_pelanggan, Piutang.tanggal AS tanggal, MAX(Piutang.status) AS status FROM Piutang INNER JOIN Transaksi ON Piutang.id_transaksi = Transaksi.id_transaksi GROUP BY Transaksi.id_transaksi";
$result_data = mysqli_query($koneksi, $query_data);
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

            <!-- Bagian Utama -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Riwayat Piutang</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="transaksiTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>No. Transaksi</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Tanggal Pelunasan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result_data) > 0) {
                                                    $no = 1;
                                                    while ($row = mysqli_fetch_assoc($result_data)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>";
                                                        echo "<td>" . $row['no_transaksi'] . "</td>";
                                                        echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                                        echo "<td>" . $row['tanggal'] . "</td>";
                                                        echo "<td>" . $row['status'] . "</td>";

                                                        echo "</tr>";
                                                        $no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>Tidak ada data piutang</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Bagian Utama -->
        </div>
        <?php include('layout/js.php'); ?>
    </div>
</body>

</html>