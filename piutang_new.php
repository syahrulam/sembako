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

<?php
include('koneksi/config.php');
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

            <div id="app">
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
                                        <!-- Tombol Tambah Piutang -->
                                        <a href="bayar_piutang.php" class="btn btn-primary mb-3">Bayar Cicilan</a>

                                        <div class="table-responsive">
                                            <table id="empTable" class="table mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Total Hutang</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Query untuk mengambil data pelanggan dan total hutang
                                                    $query = "SELECT pelanggan.nama, piutang.total_hutang, SUM(transaksi.total_bayar) AS total_pembayaran FROM pelanggan LEFT JOIN piutang ON pelanggan.id = piutang.id_pelanggan LEFT JOIN transaksi ON pelanggan.nama = transaksi.nama_pelanggan GROUP BY pelanggan.nama";
                                                    $result = mysqli_query($koneksi, $query);

                                                    // Variabel untuk menyimpan nomor urut
                                                    $no = 1;

                                                    // Loop untuk menampilkan data pelanggan dan total hutang
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        // Hitung total kekurangan
                                                        $totalKekurangan = $row['total_hutang'] - $row['total_pembayaran'];
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no++; ?></td>
                                                            <td><?php echo $row['nama']; ?></td>
                                                            <td><?php echo $row['total_hutang']; ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
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

            </head>

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
                                    columns: [0, 1, 2, 3, 4] // Column index which needs to export
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


</body>

</html>