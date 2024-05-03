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

// Query untuk mengambil beberapa transaksi per masing-masing nama pelanggan yang tipe pembayarannya adalah Debit
$query = "SELECT
             t.nama_pelanggan,
             COUNT(t.id_transaksi) AS jumlah_transaksi,
             SUM(t.total_harga) AS total_harga,
             SUM(t.total_bayar) AS total_bayar,
             SUM(t.kekurangan) AS total_hutang,
             COALESCE(SUM(p.kurangan_hutang), 0) AS hutang_sekarang
         FROM
             transaksi t
             LEFT JOIN piutang p ON t.id_transaksi = p.id_transaksi
         WHERE
             t.tipe_pembayaran = 'Debit' AND t.kekurangan <> 0 
         GROUP BY
             t.nama_pelanggan";


$result = mysqli_query($koneksi, $query);

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
                                        <table id="empTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Jumlah Transaksi</th>
                                                    <th>Total Hutang</th>
                                                    <th>Sisa Hutang</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo ucwords($row['nama_pelanggan']); ?></td>
                                                        <td><?php echo $row['jumlah_transaksi']; ?></td>
                                                        <td><?php echo 'Rp. ' . number_format($row['total_hutang'], 0, ',', '.'); ?></td>
                                                        <td><?php echo 'Rp. ' . number_format($row['hutang_sekarang'], 0, ',', '.'); ?></td> <!-- Baris ini untuk data Hutang Sekarang -->
                                                        <td>
                                                            <a href="detail_piutang.php?id=<?php echo $row['nama_pelanggan']; ?>" class="btn btn-warning">Detail</a>
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
                            <form action="proses_pembayaran_hutang.php" method="post">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Pembayaran:</label>
                                    <input style="display: none;" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" readonly>
                                    <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Cicilan (Rp)" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Bayar Cicilan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Bayar Cicilan -->

        </div>

        <?php include('layout/js.php'); ?>

        <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <link href='https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css' rel='stylesheet' type='text/css'>

        <!-- jQuery Library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Datatable JS -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>


        <script>
            $(document).ready(function() {
                // Tangkap nilai data saat tombol "Bayar Cicilan" diklik
                $('.bayarCicilanBtn').click(function() {
                    var namaPelanggan = $(this).data('pelanggan-nama');
                    $('#nama_pelanggan').val(namaPelanggan); // Tampilkan nama pelanggan di dalam input
                });
            });
        </script>
        <!-- Script -->
        <script>
            $(document).ready(function() {
                var empDataTable = $('#empTable').DataTable({
                    dom: 'Blfrtip',
                    buttons: [{
                            extend: 'copy',
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3] // Column index which needs to export
                            }
                        },
                        {
                            extend: 'csv',
                        },
                        {
                            extend: 'excel',
                        }
                    ]
                });
            });
        </script>
    </div>
</body>

</html>