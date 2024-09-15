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

include('koneksi/config.php');

// Ambil data penjualan berdasarkan pelanggan
$query = "SELECT 
            trans.nama_pelanggan,
            COUNT(DISTINCT det.id_item) AS total_jenis_item
          FROM 
            detail_transaksi AS det
          INNER JOIN 
            transaksi AS trans ON det.id_transaksi = trans.id_transaksi
          WHERE 
            trans.nama_pelanggan IS NOT NULL
          GROUP BY 
            trans.nama_pelanggan";

$result = mysqli_query($koneksi, $query);
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
                                    <h4>Detail Penjualan Item</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="penjualanTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Total Jenis Item Terjual</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    $no = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>";
                                                        echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                                        echo "<td>" . $row['total_jenis_item'] . "</td>";
                                                        echo "<td><a href='detail_item_terjual.php?nama_pelanggan=" . $row['nama_pelanggan'] . "' class='btn btn-warning btn-sm'>Detail</a></td>";
                                                        echo "</tr>";
                                                        $no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>Tidak ada detail item terjual.</td></tr>";
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
                $('#penjualanTable').DataTable({
                    dom: 'Blfrtip',
                    buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2] // Hanya export kolom ini
                        }
                    }]
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
