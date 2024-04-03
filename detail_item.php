<?php include('koneksi/config.php'); ?>

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
                            <!-- Detail Item -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Detail Item</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Periksa apakah parameter ID item ada di URL
                                    if (isset($_GET['id_item'])) {
                                        // Ambil ID item dari parameter URL
                                        $id_item = $_GET['id_item'];

                                        // Query untuk mengambil informasi detail item berdasarkan ID
                                        $query = "SELECT * FROM item WHERE id_item = $id_item";
                                        $result = $koneksi->query($query);

                                        // Periksa apakah query berhasil dieksekusi
                                        if ($result) {
                                            // Periksa apakah ada data yang ditemukan
                                            if ($result->num_rows > 0) {
                                                // Ambil baris data dari hasil query
                                                $row = $result->fetch_assoc();

                                                // Tampilkan informasi detail item
                                    ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Nama Item</th>
                                                                <td><?php echo $row['nama_item']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Kategori</th>
                                                                <td>
                                                                    <?php
                                                                    // Query untuk mendapatkan nama kategori
                                                                    $kategori_id = $row['kategori_id'];
                                                                    $kategori_query = "SELECT kategori FROM kategori WHERE id = $kategori_id";
                                                                    $kategori_result = $koneksi->query($kategori_query);
                                                                    if ($kategori_result && $kategori_result->num_rows > 0) {
                                                                        $kategori_row = $kategori_result->fetch_assoc();
                                                                        echo $kategori_row['kategori'];
                                                                    } else {
                                                                        echo "Kategori tidak ditemukan";
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Stok Satuan Besar</th>
                                                                <td><?php echo floatval($row['jumlah_satuan_besar']) . ' ' . $row['jenis_satuan_besar']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Isi dalam Satuan Besar</th>
                                                                <td><?php echo $row['jumlah_isi_satuan_besar']; ?> <?php echo $row['jenis_satuan_kecil']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Isi Satuan Kecil</th>
                                                                <td><?php echo $row['total_isi_satuan_kecil']; ?> <?php echo $row['jenis_satuan_kecil']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Harga Satuan Kulak</th>
                                                                <td>Rp. <?php echo number_format($row['harga_satuan_kulak'], 0, ',', '.'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Harga Kulak</th>
                                                                <td>Rp. <?php echo number_format($row['total_harga_kulak'], 0, ',', '.'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Harga Jual PerSatuan Besar</th>
                                                                <td>Rp. <?php echo number_format($row['harga_jual_satuan_besar'], 0, ',', '.'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Harga Jual PerSatuan Kecil</th>
                                                                <td>Rp. <?php echo number_format($row['harga_jual_satuan_kecil'], 0, ',', '.'); ?></td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <td><?php echo $row['tanggal']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Tombol Kembali ke Halaman Item -->
                                                <a href="item.php" class="btn btn-primary float-right">Kembali</a>

                                    <?php
                                            } else {
                                                echo "Data item tidak ditemukan.";
                                            }
                                        } else {
                                            echo "Error: " . $koneksi->error;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- End Detail Item -->
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Bagian Utama -->
        </div>
    </div>

    <?php include('layout/js.php'); ?>
</body>

</html>