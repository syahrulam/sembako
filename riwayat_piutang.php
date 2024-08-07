<?php
include('layout/head.php');
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

// Ambil data piutang dari tabel Piutang dan informasi transaksi dari tabel Transaksi
$query_data = "SELECT Transaksi.nama_pelanggan, MAX(Piutang.tanggal) AS tanggal, MAX(Piutang.status) AS status FROM Piutang INNER JOIN Transaksi ON Piutang.id_transaksi = Transaksi.id_transaksi GROUP BY Transaksi.nama_pelanggan";
$result_data = mysqli_query($koneksi, $query_data);
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
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Riwayat Pembayaran Hutang Pertransaksi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="empTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Status</th>
                                                    <th>Detail Cicilan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result_data) > 0) {
                                                    $no = 1;
                                                    while ($row = mysqli_fetch_assoc($result_data)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>";
                                                        echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                                        echo "<td>" . $row['status'] . "</td>";
                                                        echo "<td><a href='detail_cicilan.php?nama_pelanggan=" . $row['nama_pelanggan'] . "' class='btn btn-info btn-sm'>Detail Cicilan</a></td>";
                                                        echo "</tr>";
                                                        $no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>Tidak ada data piutang</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Bagian Utama -->
        </div>

        <?php include('layout/js.php'); ?>

        <script>
            $(document).ready(function() {
                var empDataTable = $('#empTable').DataTable({
                    dom: 'Blfrtip',
                    buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4] // Column index which needs to export
                        }
                    }, ]

                });

            });
        </script>

        <!-- jQuery Library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Datatable JS -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    </div>
</body>

</html>