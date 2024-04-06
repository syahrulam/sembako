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

// Menggunakan tanda == untuk perbandingan dalam query SQL
include('koneksi/config.php');

// Ambil ID pelanggan dari URL
$id_pelanggan = $_GET['id'];

// Query untuk mengambil transaksi berdasarkan ID pelanggan
$query = "SELECT id_transaksi,
                 no_transaksi,
                 tanggal,
                 kekurangan
          FROM transaksi 
          WHERE nama_pelanggan = ? AND tipe_pembayaran = 'Debit' AND kekurangan <> 0";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $id_pelanggan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Variabel untuk menyimpan nomor urut
$no = 1;
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
                            <!-- Tabel Piutang -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Piutang</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="transaksiTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>No. Transaksi</th>
                                                    <th>Tanggal</th>
                                                    <th>Hutang</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $row['no_transaksi']; ?></td>
                                                        <td><?php echo  date('d F Y', strtotime($row['tanggal'])) ?></td>
                                                        <td><?php echo 'Rp. ' . number_format($row['kekurangan'], 0, ',', '.'); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-bayar-cicilan" data-toggle="modal" data-target="#bayarCicilanModal" data-id="<?php echo $row['id_transaksi']; ?>">Bayar Cicilan</button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- End Tabel Piutang -->

                        </div>
                    </div>
                </section>
            </div>
            <!-- End Bagian Utama -->

            <!-- Modal Bayar Cicilan -->
            <div class="modal fade" id="bayarCicilanModal" tabindex="-1" role="dialog" aria-labelledby="bayarCicilanModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bayarCicilanModalLabel">Bayar Cicilan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formBayarCicilan" action="proses_pembayaran_hutang.php" method="post">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Pembayaran:</label>
                                    <!-- Hidden input untuk ID transaksi -->
                                    <input style="display: none;" class="form-control" id="id_transaksi" name="id_transaksi">
                                    <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Cicilan (Rp)" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Bayar Cicilan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php include('layout/js.php'); ?>

        <script>
            $(document).ready(function() {
                $('.btn-bayar-cicilan').click(function() {
                    var id_transaksi = $(this).data('id');
                    $('#id_transaksi').val(id_transaksi);
                });
            });
        </script>


    </div>
</body>

</html>