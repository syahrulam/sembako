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
                                <!-- Form Cek Stock Opname -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Cek Stock Opname</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="tambah_opname.php">
                                            <div class="item-container">
                                                <div class="form-group">
                                                    <label for="nama_item">Nama Item:</label>
                                                    <input class="form-control id_item" type="text" name="id_item" style="display: none;" />
                                                    <input class="form-control jumlah_satuan" type="text" name="jumlah_satuan"  style="display: none;"/>
                                                    <input class="form-control nama_item" type="text" name="nama_item" placeholder="Nama Item" required />
                                                    <div class="result"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="jumlah_satuan">Stok Opname:</label>
                                                            <input class="form-control" id="nilai" value="0" min="0" type="number" name="stok_opname" required />
                                                        </div>
                                                        <!-- <div class="col-6">
                                                            <button type="button" onclick="tambahNilai()" class="btn btn-danger">Tambah</button>
                                                            <button type="button" onclick="kurangNilai()" class="btn btn-success">Kurang</button>
                                                        </div> -->
                                            </div>
                                            <button type="submit" class="btn btn-primary">Cek Stok Opname</button>
                                        </form>
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
    </div>
</body>

</html>