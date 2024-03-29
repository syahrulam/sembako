<?php include('koneksi/config.php'); ?>

<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<?php
// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari form
    $kategori_id = $_POST['kategori_id'];
    $nama_item = $_POST['nama_item'];
    $jenis_satuan_besar = $_POST['jenis_satuan_besar'];
    $jenis_satuan_kecil = $_POST['jenis_satuan_kecil'];
    $jumlah_satuan_besar = $_POST['jumlah_satuan_besar'];
    $jumlah_isi_satuan_besar = $_POST['jumlah_isi_satuan_besar'];
    $harga_kulak = intval(unformatRupiah($_POST['harga_kulak']));
    $harga_jual_satuan_besar = intval(unformatRupiah($_POST['harga_jual_satuan_besar']));
    $harga_jual_satuan_kecil = intval(unformatRupiah($_POST['harga_jual_satuan_kecil']));
    $tanggal = $_POST['tanggal'];

    // Query untuk menyimpan data ke database
    $query = "INSERT INTO item (kategori_id, nama_item, jenis_satuan_besar, jenis_satuan_kecil, jumlah_satuan_besar, jumlah_isi_satuan_besar, harga_kulak, harga_jual_satuan_besar, harga_jual_satuan_kecil, tanggal) 
              VALUES ('$kategori_id', '$nama_item', '$jenis_satuan_besar', '$jenis_satuan_kecil', '$jumlah_satuan_besar', '$jumlah_isi_satuan_besar', '$harga_kulak', '$harga_jual_satuan_besar', '$harga_jual_satuan_kecil', '$tanggal')";
    if ($koneksi->query($query) === TRUE) {
        header("Location: item.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
}

// Function untuk menghapus format "Rp" dan koma
function unformatRupiah($str)
{
    return preg_replace("/[^0-9]/", "", $str);
}
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

            <!-- Bagian Utama -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <!-- Form Tambah Item -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Tambah Item</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="tambah_item.php" id="form_item">
                                        <div class="form-group">
                                            <label for="kategori_id">Kategori:</label>
                                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                                <option value="">Pilih Kategori</option>
                                                <?php
                                                // Query untuk mendapatkan data kategori
                                                $sql = "SELECT id, kategori FROM kategori";
                                                $result = $koneksi->query($sql);

                                                // Jika data kategori tersedia
                                                if ($result->num_rows > 0) {
                                                    // Tampilkan data sebagai opsi pada dropdown
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='" . $row['id'] . "'>" . $row['kategori'] . "</option>";
                                                    }
                                                } else {
                                                    echo "<option disabled>Tidak ada kategori</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_item">Nama Item:</label>
                                            <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_satuan_besar">Satuan Jenis Besar:</label>
                                            <input type="text" class="form-control" id="jenis_satuan_besar" name="jenis_satuan_besar" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_satuan_kecil">Satuan Jenis Kecil:</label>
                                            <input type="text" class="form-control" id="jenis_satuan_kecil" name="jenis_satuan_kecil" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah_satuan_besar">Jumlah Satuan Besar:</label>
                                            <input type="number" class="form-control" id="jumlah_satuan_besar" name="jumlah_satuan_besar" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah_isi_satuan_besar">Isi Satuan Besar:</label>
                                            <input type="number" class="form-control" id="jumlah_isi_satuan_besar" name="jumlah_isi_satuan_besar" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_kulak">Harga Kulak:</label>
                                            <input type="text" class="form-control" id="harga_kulak" name="harga_kulak" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_jual_satuan_besar">Harga Jual Satuan Besar:</label>
                                            <input type="text" class="form-control" id="harga_jual_satuan_besar" name="harga_jual_satuan_besar" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_jual_satuan_kecil">Harga Jual Satuan Kecil:</label>
                                            <input type="text" class="form-control" id="harga_jual_satuan_kecil" name="harga_jual_satuan_kecil" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal:</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                        </div>
                                        <!-- Tambahkan bagian lain sesuai kebutuhan -->
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="item.php" class="btn btn-secondary">Batal</a>
                                    </form>
                                </div>
                            </div>
                            <!-- End Form Tambah Item -->
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Bagian Utama -->
        </div>
    </div>
</body>

</html>
