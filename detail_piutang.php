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
// Menggunakan ORDER BY untuk mendapatkan data terbaru
$query = "SELECT
            t.nama_pelanggan,
            t.id_transaksi,
            t.no_transaksi,
            t.tanggal,
            COALESCE(SUM(p.kurangan_hutang), 0) AS hutang_sekarang
          FROM
            transaksi t
            JOIN piutang p ON t.id_transaksi = p.id_transaksi
          WHERE
            t.nama_pelanggan = ?
          ORDER BY
            p.tanggal DESC
          LIMIT 1"; // Hanya mengambil data piutang terbaru


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
                            <div class="card mt-4"><br>
                                <div class="col-12">
                                    <a href="javascript:history.go(-1)" class="btn btn-primary">Kembali</a>
                                </div>
                                <div class="card-header">
                                    <h4>Piutang</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="transaksiTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                   
                                                    <th>Hutang</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        
                                                        <td><?php echo 'Rp. ' . number_format($row['hutang_sekarang'], 0, ',', '.'); ?></td> <!-- Ambil data dari kurangan_hutang -->
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-bayar-cicilan" data-toggle="modal" data-target="#bayarCicilanModal" data-id="<?php echo $row['nama_pelanggan']; ?>">Bayar Cicilan</button>
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
                                    <input class="form-control" id="nama_pelanggan" name="nama_pelanggan">
                                    <input type="text" class="form-control" id="cicilan" name="cicilan" placeholder="Jumlah Cicilan (Rp)" required>
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
                    var nama_pelanggan = $(this).data('id');
                    $('#nama_pelanggan').val(nama_pelanggan);
                });
            });
        </script>


    </div>
</body>

</html>