<?php
include('koneksi/config.php');

$koneksi->close();
?>

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

<?php include('layout/head.php'); ?>

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

                                <!-- Form Tambah Member -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Bayar Cicilan</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="tambah_pelanggan.php">
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat">Bayar Cicilan</label>
                                                <input type="text" class="form-control" id="alamat" name="alamat" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nomor">Tanggal</label>
                                                <input type="text" class="form-control" id="nomor" name="nomor" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="piutang.php" class="btn btn-secondary">Batal</a>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Form Tambah Member -->

                            </div>
                        </div>
                    </section>
                </div>
                <!-- End Bagian Utama -->

            </div>

            <?php include('layout/js.php'); ?>

</body>

</html>