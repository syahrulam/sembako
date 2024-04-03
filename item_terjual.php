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

// Query untuk mengambil jumlah jenis item yang terjual oleh setiap pelanggan
$sql = "SELECT 
            trans.nama_pelanggan,
            COUNT(DISTINCT det.id_item) AS total_jenis_item
        FROM 
            detail_transaksi AS det
        INNER JOIN 
            transaksi AS trans ON det.id_transaksi = trans.id_transaksi
        WHERE 
            trans.nama_pelanggan IS NOT NULL
        GROUP BY 
            trans.nama_pelanggan";

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

                                <!-- Tabel Detail Penjualan -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Detail Penjualan Item</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="empTable" class="table mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Total Jenis Item Terjual</th>
                                                        <th>Aksi</th>
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
                                                            echo "<td>" . $row['total_jenis_item'] . "</td>";
                                                            echo "<td><a href='detail_item_terjual.php?nama_pelanggan=" . $row['nama_pelanggan'] . "' class='btn btn-warning btn-sm'>Detail</a></td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        // Jika tidak ada data yang ditemukan
                                                        echo "<tr><td colspan='4'>Tidak ada detail item terjual untuk pelanggan ini.</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- End Tabel Detail Penjualan -->

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