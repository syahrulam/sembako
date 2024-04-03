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

// Jika formulir dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Terima input jumlah stok opname dari admin
    $stok_opname = $_POST['stok_opname'];

    // Lakukan konversi nilai yang dimasukkan admin ke nilai yang sesuai dengan keadaan fisik yang terlihat
    $stok_fisik = ceil($stok_opname); // Menggunakan fungsi ceil() untuk pembulatan ke atas

    // Ambil nilai lainnya dari formulir
    $id_item = $_POST['id_item'];
    $jumlah_satuan = $_POST['jumlah_satuan'];

    // Lakukan penyimpanan data ke dalam database
    // Misalnya, Anda dapat menggunakan koneksi database dan query SQL untuk menyimpan nilai $stok_fisik ke dalam database
    // Pastikan Anda mengikuti praktik pengamanan data seperti penggunaan parameterized queries atau prepared statements

    // Setelah menyimpan data, Anda dapat mengarahkan pengguna ke halaman lain atau menampilkan pesan sukses
    // Misalnya, mengarahkan pengguna kembali ke halaman utama
    header("Location: index.php");
    exit();
}

$koneksi->close();
?>

<?php include('layout/head.php'); ?>

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
                                <!-- Form Cek Stock Opname -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Cek Stock Opname</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                            <div class="item-container">
                                                <div class="form-group">
                                                    <label for="nama_item">Nama Item:</label>
                                                    <input class="form-control id_item" type="text" name="id_item" style="display: none;" />
                                                    <input class="form-control jumlah_satuan" type="text" name="jumlah_satuan" style="display: none;" />
                                                    <input class="form-control nama_item" type="text" name="nama_item" placeholder="Nama Item" required />
                                                    <div class="result"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="jumlah_satuan">Stok Opname:</label>
                                                    <input class="form-control" id="stok_opname" type="number" name="stok_opname" required />
                                                </div>
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