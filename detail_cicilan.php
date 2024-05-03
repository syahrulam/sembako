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

// Periksa apakah nama pelanggan ada dalam URL
if (isset($_GET['nama_pelanggan'])) {
    // Ambil nama pelanggan dari URL
    $nama_pelanggan = $_GET['nama_pelanggan'];

    // Ambil data cicilan berdasarkan nama pelanggan dari tabel Cicilan_Piutang dan informasi transaksi dari tabel Piutang
    $query_data = "SELECT 
                        transaksi.no_transaksi, 
                        piutang.kurangan_hutang, 
                        cicilan_piutang.cicilan, 
                        cicilan_piutang.tanggal 
                    FROM 
                        cicilan_piutang 
                    INNER JOIN 
                        piutang ON cicilan_piutang.id_transaksi = piutang.id_transaksi 
                    INNER JOIN 
                        transaksi ON piutang.id_transaksi = transaksi.id_transaksi 
                    WHERE 
                        transaksi.nama_pelanggan = ?
                    ORDER BY 
                        transaksi.no_transaksi ASC, 
                        cicilan_piutang.id_cicilan ASC"; // Menambahkan ORDER BY

    $stmt = mysqli_prepare($koneksi, $query_data);
    mysqli_stmt_bind_param($stmt, "s", $nama_pelanggan);
    mysqli_stmt_execute($stmt);
    $result_data = mysqli_stmt_get_result($stmt);

    // Ambil informasi sisa hutang dari tabel Transaksi
    $query_sisa_hutang = "SELECT SUM(kurangan_hutang) AS sisa_hutang FROM piutang INNER JOIN transaksi ON piutang.id_transaksi = transaksi.id_transaksi WHERE transaksi.nama_pelanggan = ?";
    $stmt_sisa_hutang = mysqli_prepare($koneksi, $query_sisa_hutang);
    mysqli_stmt_bind_param($stmt_sisa_hutang, "s", $nama_pelanggan);
    mysqli_stmt_execute($stmt_sisa_hutang);
    $result_sisa_hutang = mysqli_stmt_get_result($stmt_sisa_hutang);
    $row_sisa_hutang = mysqli_fetch_assoc($result_sisa_hutang);
    $sisa_hutang = $row_sisa_hutang['sisa_hutang'];
} else {
    echo "Nama pelanggan tidak ditemukan dalam parameter URL.";
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
                                            <h4 class="mt-4">Riwayat Piutang</h4>
                                            <p>Nama : <?php echo $nama_pelanggan; ?> | Hutang Seluruh Transaksi: Rp. <?php echo number_format($sisa_hutang, 0, ',', '.'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="empTable" class="table mt-3">
                                            <thead>
                                                <tr>
                                                    <th>No.</th> <!-- Tetap urutan pertama -->
                                                    <th>Nomor Transaksi</th> <!-- Tetap urutan kedua -->
                                                    <th>Tanggal Pembayaran</th> <!-- Pindah menjadi urutan ketiga -->
                                                    <th>Jumlah Bayar</th> <!-- Pindah menjadi urutan keempat -->
                                                    <th>Sisa Hutang</th> <!-- Pindah menjadi urutan kelima -->
                                                    <th></th> <!-- Kolom tambahan yang tidak digunakan -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $previous_transaction = null; // Variabel untuk melacak nomor transaksi sebelumnya
                                                $previous_debt = null; // Variabel untuk melacak sisa hutang sebelumnya
                                                if (mysqli_num_rows($result_data) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result_data)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>"; // Nomor urut

                                                        // Jika nomor transaksi berbeda dari sebelumnya, tampilkan nomor transaksi, jika sama, biarkan kosong
                                                        if ($row['no_transaksi'] !== $previous_transaction) {
                                                            echo "<td>" . $row['no_transaksi'] . "</td>";
                                                            $previous_transaction = $row['no_transaksi'];
                                                        } else {
                                                            echo "<td></td>"; // Kosongkan jika nomor transaksi sama dengan sebelumnya
                                                        }

                                                        // Tampilkan tanggal pembayaran untuk setiap cicilan
                                                        echo "<td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>"; 

                                                        // Tampilkan cicilan yang dibayarkan
                                                        echo "<td>" . 'Rp.' . number_format($row['cicilan'], 0, ',', '.') . "</td>";

                                                        // Jika sisa hutang berbeda dari sebelumnya, tampilkan kurangan hutang, jika sama, biarkan kosong
                                                        if ($row['kurangan_hutang'] !== $previous_debt) {
                                                            echo "<td>" . 'Rp.' . number_format($row['kurangan_hutang'], 0, ',', '.') . "</td>";
                                                            $previous_debt = $row['kurangan_hutang'];
                                                        } else {
                                                            echo "<td></td>"; // Kosongkan jika sisa hutang sama dengan sebelumnya
                                                        }

                                                        echo "</tr>";
                                                        $no++; // Tambah nomor urut
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>Tidak ada data piutang untuk nama pelanggan ini</td></tr>";
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