<?php
include('layout/head.php');
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menggunakan tanda == untuk perbandingan dalam query SQL
include('koneksi/config.php');

// Periksa apakah nomor transaksi ada dalam URL
if (isset($_GET['id_transaksi'])) {
    // Ambil nomor transaksi dari URL
    $no_transaksi = $_GET['id_transaksi'];

    // Ambil data piutang berdasarkan nomor transaksi dari tabel Piutang dan informasi transaksi dari tabel Transaksi
    $query_data = "SELECT Piutang.id_piutang, Transaksi.no_transaksi, Transaksi.nama_pelanggan, Piutang.tanggal AS tanggal, Piutang.nominal, Piutang.status 
                   FROM Piutang 
                   INNER JOIN Transaksi ON Piutang.id_transaksi = Transaksi.id_transaksi 
                   WHERE Transaksi.no_transaksi = ?";

    $stmt = mysqli_prepare($koneksi, $query_data);
    mysqli_stmt_bind_param($stmt, "s", $no_transaksi);
    mysqli_stmt_execute($stmt);
    $result_data = mysqli_stmt_get_result($stmt);

    // Ambil informasi sisa hutang dari tabel Transaksi
    $query_kekurangan = "SELECT kekurangan FROM Transaksi WHERE no_transaksi = ?";
    $stmt_kekurangan = mysqli_prepare($koneksi, $query_kekurangan);
    mysqli_stmt_bind_param($stmt_kekurangan, "s", $no_transaksi);
    mysqli_stmt_execute($stmt_kekurangan);
    $result_kekurangan = mysqli_stmt_get_result($stmt_kekurangan);
    $row_kekurangan = mysqli_fetch_assoc($result_kekurangan);
    $sisa_hutang = $row_kekurangan['kekurangan'];

    // Ambil nama pelanggan untuk ditampilkan di luar tabel
    $query_nama_pelanggan = "SELECT nama_pelanggan FROM Transaksi WHERE no_transaksi = ?";
    $stmt_nama_pelanggan = mysqli_prepare($koneksi, $query_nama_pelanggan);
    mysqli_stmt_bind_param($stmt_nama_pelanggan, "s", $no_transaksi);
    mysqli_stmt_execute($stmt_nama_pelanggan);
    $result_nama_pelanggan = mysqli_stmt_get_result($stmt_nama_pelanggan);
    $row_nama_pelanggan = mysqli_fetch_assoc($result_nama_pelanggan);
    $nama_pelanggan = $row_nama_pelanggan['nama_pelanggan'];
} else {
    // Jika nomor transaksi tidak ada dalam URL, tampilkan pesan error
    echo "Nomor transaksi tidak ditemukan dalam parameter URL.";
    exit();
}
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
                                    <div class="row"><br>
                                        <div class="col-12">
                                            <a href="javascript:history.go(-1)" class="btn btn-primary">Kembali</a>
                                        </div>
                                        <div class="col">
                                            <h4 class="mt-4">Riwayat Piutang <?php echo $no_transaksi; ?> Atas nama <?php echo $nama_pelanggan; ?> Sisa Hutang: Rp. <?php echo number_format($sisa_hutang, 0, ',', '.'); ?></h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="empTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal Pelunasan</th>
                                                    <th>Bayar</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result_data) > 0) {
                                                    $no = 1;
                                                    while ($row = mysqli_fetch_assoc($result_data)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>";
                                                        echo "<td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>";
                                                        echo "<td>" . $row['nominal'] . "</td>";
                                                        echo "<td>" . $row['status'] . "</td>";

                                                        echo "</tr>";
                                                        $no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>Tidak ada data piutang untuk nomor transaksi ini</td></tr>";
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