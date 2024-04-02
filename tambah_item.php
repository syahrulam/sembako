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
    $harga_satuan_kulak = intval(unformatRupiah($_POST['harga_satuan_kulak']));
    $harga_jual_satuan_besar = intval(unformatRupiah($_POST['harga_jual_satuan_besar']));
    $harga_jual_satuan_kecil = intval(unformatRupiah($_POST['harga_jual_satuan_kecil']));
    $tanggal = $_POST['tanggal'];
    $total_harga_kulak = intval(unformatRupiah($_POST['total_harga_kulak']));

    // Hitung Total Isi Satuan Kecil
    $total_isi_satuan_kecil = $jumlah_satuan_besar * $jumlah_isi_satuan_besar;

    // Query untuk menyimpan data ke database
    $query = "INSERT INTO item (kategori_id, nama_item, jenis_satuan_besar, jenis_satuan_kecil, jumlah_satuan_besar, jumlah_isi_satuan_besar, total_isi_satuan_kecil, harga_satuan_kulak, total_harga_kulak, harga_jual_satuan_besar, harga_jual_satuan_kecil, tanggal, total_kulak) 
              VALUES ('$kategori_id', '$nama_item', '$jenis_satuan_besar', '$jenis_satuan_kecil', '$jumlah_satuan_besar', '$jumlah_isi_satuan_besar', '$total_isi_satuan_kecil', '$harga_satuan_kulak', '$total_harga_kulak', '$harga_jual_satuan_besar', '$harga_jual_satuan_kecil', '$tanggal', '$jumlah_satuan_besar')";
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
            <div class="main-sidebar sidebar-style-2" style="overflow-y: auto;">
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
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal:</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                            </div>
                                            <div class="col-md-6">

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
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="nama_item">Nama Item:</label>
                                                <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="jenis_satuan_besar">Satuan Jenis Besar:</label>
                                                <input type="text" class="form-control" id="jenis_satuan_besar" name="jenis_satuan_besar" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="jenis_satuan_kecil">Satuan Jenis Kecil:</label>
                                                <input type="text" class="form-control" id="jenis_satuan_kecil" name="jenis_satuan_kecil" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="jumlah_satuan_besar">Jumlah Satuan Besar:</label>
                                                <input type="number" class="form-control" id="jumlah_satuan_besar" name="jumlah_satuan_besar" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="jumlah_isi_satuan_besar">Isi Satuan Besar:</label>
                                                <input type="number" class="form-control" id="jumlah_isi_satuan_besar" name="jumlah_isi_satuan_besar" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total_isi_satuan_kecil">Total Isi Satuan Kecil:</label>
                                                <input type="number" class="form-control" id="total_isi_satuan_kecil" name="total_isi_satuan_kecil" required readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="harga_jual_satuan_besar">Harga Jual PerSatuan Besar:</label>
                                                <input type="text" class="form-control" id="harga_jual_satuan_besar" name="harga_jual_satuan_besar" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="harga_jual_satuan_kecil">Harga Jual PerSatuan Kecil:</label>
                                                <input type="text" class="form-control" id="harga_jual_satuan_kecil" name="harga_jual_satuan_kecil" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="harga_satuan_kulak">Harga Satuan Kulak:</label>
                                                <input type="text" class="form-control" id="harga_satuan_kulak" name="harga_satuan_kulak" placeholder="Misal 1 Dusnya Rp. 50.000" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="total_harga_kulak">Total Harga Kulak:</label>
                                                <input type="text" class="form-control" id="total_harga_kulak" name="total_harga_kulak" required>
                                            </div>
                                        </div>
                                        <!-- Tambahkan bagian lain sesuai kebutuhan -->
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <a href="item.php" class="btn btn-secondary">Batal</a>
                                            </div>
                                        </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var jumlahSatuanBesarInput = document.getElementById('jumlah_satuan_besar');
            var jumlahIsiSatuanBesarInput = document.getElementById('jumlah_isi_satuan_besar');
            var totalIsiSatuanKecilInput = document.getElementById('total_isi_satuan_kecil');
            var hargaSatuanKulakInput = document.getElementById('harga_satuan_kulak');
            var totalHargaKulakInput = document.getElementById('total_harga_kulak');

            function calculateTotalIsiSatuanKecil() {
                var jumlahSatuanBesar = parseFloat(jumlahSatuanBesarInput.value);
                var jumlahIsiSatuanBesar = parseFloat(jumlahIsiSatuanBesarInput.value);

                var totalIsiSatuanKecil = jumlahSatuanBesar * jumlahIsiSatuanBesar;
                totalIsiSatuanKecilInput.value = totalIsiSatuanKecil;
            }

            function calculateTotalHargaKulak() {
                var jumlahSatuanBesar = parseFloat(jumlahSatuanBesarInput.value);
                var hargaSatuanKulak = parseFloat(hargaSatuanKulakInput.value.replace(/\D/g, '')); // Menghilangkan format Rupiah

                var totalHargaKulak = jumlahSatuanBesar * hargaSatuanKulak;
                totalHargaKulakInput.value = totalHargaKulak;
            }


            // Panggil fungsi perhitungan saat terjadi perubahan pada field "Jumlah Satuan Besar" atau "Isi Satuan Besar"
            jumlahSatuanBesarInput.addEventListener("change", calculateTotalIsiSatuanKecil);
            jumlahIsiSatuanBesarInput.addEventListener("change", calculateTotalIsiSatuanKecil);

            // Panggil fungsi perhitungan saat terjadi perubahan pada field "Jumlah Satuan Besar" atau "Harga Satuan Kulak"
            jumlahSatuanBesarInput.addEventListener("change", calculateTotalHargaKulak);
            hargaSatuanKulakInput.addEventListener("change", calculateTotalHargaKulak);
        });
    </script>


</body>

</html>