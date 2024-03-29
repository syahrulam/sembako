<?php
include('koneksi/config.php');

// Initialize $row
$row = null;

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nomor = $_POST['nomor'];

    $query = "UPDATE sales SET nama='$nama', alamat='$alamat', nomor='$nomor' WHERE id='$id'";
    if ($koneksi->query($query) === TRUE) {
        header("Location: sales.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
}

// Jika parameter id ada dalam URL, artinya kita ingin mengedit sales
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM sales WHERE id='$id'";
    $result = $koneksi->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Sales tidak ditemukan.";
        exit();
    }
} else {
    // Jika parameter id tidak ada, kembali ke halaman sales.php
    header("Location: sales.php");
    exit();
}

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

                                <!-- Form Edit sales -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Edit Sales</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="edit_sales.php">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <div class="form-group">
                                                <label for="nama">Nama:</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat">Alamat:</label>
                                                <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $row['alamat']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nomor">Nomor:</label>
                                                <input type="text" class="form-control" id="nomor" name="nomor" value="<?php echo $row['nomor']; ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="sales.php" class="btn btn-secondary">Batal</a>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Form Edit sales -->

                            </div>
                        </div>
                    </section>
                </div>
                <!-- End Bagian Utama -->

            </div>

            <?php include('layout/js.php'); ?>

</body>

</html>