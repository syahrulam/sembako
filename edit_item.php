<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_item = $_POST['id_item'];
    $kategori_id = $_POST['kategori_id'];
    $nama_item = $_POST['nama_item'];
    $jenis_satuan_besar = $_POST['jenis_satuan_besar'];
    $jenis_satuan_kecil = $_POST['jenis_satuan_kecil'];
    $jumlah_satuan_besar = $_POST['jumlah_satuan_besar'];
    $jumlah_isi_satuan_besar = $_POST['jumlah_isi_satuan_besar'];
    $harga_satuan_kulak = $_POST['harga_satuan_kulak'];
    $harga_jual_satuan_besar = $_POST['harga_jual_satuan_besar'];
    $harga_jual_satuan_kecil = $_POST['harga_jual_satuan_kecil'];
    $tanggal = $_POST['tanggal'];

    // Hitung total harga kulak
    $total_harga_kulak = $jumlah_satuan_besar * $harga_satuan_kulak;

    // Periksa apakah kategori_id yang dikirimkan ada dalam tabel kategori
    $check_kategori = "SELECT * FROM kategori WHERE id='$kategori_id'";
    $result_check_kategori = $koneksi->query($check_kategori);
    if ($result_check_kategori->num_rows > 0) {
        // Jika kategori_id valid, jalankan pernyataan SQL untuk memperbarui data
        $query = "UPDATE item SET kategori_id='$kategori_id', nama_item='$nama_item', jenis_satuan_besar='$jenis_satuan_besar', jenis_satuan_kecil='$jenis_satuan_kecil', jumlah_satuan_besar='$jumlah_satuan_besar', jumlah_isi_satuan_besar='$jumlah_isi_satuan_besar', harga_satuan_kulak='$harga_satuan_kulak', harga_jual_satuan_besar='$harga_jual_satuan_besar', harga_jual_satuan_kecil='$harga_jual_satuan_kecil', total_kulak='$total_kulak', total_harga_kulak='$total_harga_kulak', tanggal='$tanggal' WHERE id_item='$id_item'";
        if ($koneksi->query($query) === TRUE) {
            header("Location: item.php");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $koneksi->error;
        }
    } else {
        // Jika kategori_id tidak valid, tampilkan pesan kesalahan
        echo "Error: Kategori tidak valid.";
    }
}

$id_item = $_GET['id_item'];
$sql = "SELECT * FROM item WHERE id_item='$id_item'";
$result = $koneksi->query($sql);
$row = $result->fetch_assoc();
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
            <div class="main-sidebar sidebar-style-2" style="overflow-y: auto;">
                <?php include('layout/sidebar.php'); ?>
            </div>


            <div id="app">
                <!-- Bagian Utama -->
                <div class="main-content">
                    <section class="section">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <!-- Form Edit Item -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Edit Item</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="">
                                            <input type="hidden" name="id_item" value="<?php echo $row['id_item']; ?>">
                                            <div class="form-row">
                                                <div class="form-group col-3">
                                                    <label for="kategori_id">Kategori:</label>
                                                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                                                        <?php
                                                        include('koneksi/config.php');
                                                        $sql_kategori = "SELECT * FROM kategori";
                                                        $result_kategori = $koneksi->query($sql_kategori);
                                                        if ($result_kategori->num_rows > 0) {
                                                            while ($row_kategori = $result_kategori->fetch_assoc()) {
                                                                if ($row['kategori_id'] == $row_kategori['id']) {
                                                                    echo "<option value='" . $row_kategori['id'] . "' selected>" . $row_kategori['kategori'] . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $row_kategori['id'] . "'>" . $row_kategori['kategori'] . "</option>";
                                                                }
                                                            }
                                                        }
                                                        $koneksi->close();
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="nama_item">Nama Item:</label>
                                                    <input type="text" class="form-control" id="nama_item" name="nama_item" value="<?php echo $row['nama_item']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="jenis_satuan_besar">Jenis Satuan Besar:</label>
                                                    <input type="text" class="form-control" id="jenis_satuan_besar" name="jenis_satuan_besar" value="<?php echo $row['jenis_satuan_besar']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="jenis_satuan_kecil">Jenis Satuan Kecil:</label>
                                                    <input type="text" class="form-control" id="jenis_satuan_kecil" name="jenis_satuan_kecil" value="<?php echo $row['jenis_satuan_kecil']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-3">
                                                    <label for="jumlah_satuan_besar">Jumlah Satuan Besar:</label>
                                                    <input type="text" class="form-control" id="jumlah_satuan_besar" name="jumlah_satuan_besar" value="<?php echo $row['jumlah_satuan_besar']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="jumlah_isi_satuan_besar">Jumlah Isi Satuan Besar:</label>
                                                    <input type="text" class="form-control" id="jumlah_isi_satuan_besar" name="jumlah_isi_satuan_besar" value="<?php echo $row['jumlah_isi_satuan_besar']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="harga_satuan_kulak">Harga Kulak:</label>
                                                    <input type="text" class="form-control" id="harga_satuan_kulak" name="harga_satuan_kulak" value="<?php echo $row['harga_satuan_kulak']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="harga_jual_satuan_besar">Harga Jual Satuan Besar:</label>
                                                    <input type="text" class="form-control" id="harga_jual_satuan_besar" name="harga_jual_satuan_besar" value="<?php echo $row['harga_jual_satuan_besar']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-3">
                                                    <label for="harga_jual_satuan_kecil">Harga Jual Satuan Kecil:</label>
                                                    <input type="text" class="form-control" id="harga_jual_satuan_kecil" name="harga_jual_satuan_kecil" value="<?php echo $row['harga_jual_satuan_kecil']; ?>" required>
                                                </div>
                                                <div class="form-group col-3">
                                                    <label for="tanggal">Tanggal:</label>
                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="item.php" class="btn btn-secondary">Batal</a>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Form Edit Item -->

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