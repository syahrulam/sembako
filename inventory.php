<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('layout/head.php'); ?>
</head>

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

                                <!-- Tabel Items -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Inventory</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- <a href="#" class="btn btn-primary mb-3" onclick="printInventory()">Print Inventory</a> -->
                                        <div class="table-responsive">
                                            <table id="empTable" class="table mt-3" id="inventoryTable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Item</th>
                                                        <th>Jumlah</th>
                                                        <th>Sisa Satuan Besar</th>
                                                        <th>Sisa Satuan Kecil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    include('koneksi/config.php');

                                                    $sql = "SELECT * FROM item";
                                                    $result = $koneksi->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        $no = 1;
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $row['nama_item'] . "</td>";
                                                            echo "<td>" . $row['total_kulak'] . " / " . $row['jenis_satuan_besar'] . "</td>";
                                                            echo "<td>" . floatval($row['jumlah_satuan_besar']) . " / " . $row['jenis_satuan_besar'] . "</td>";
                                                            echo "<td>" . $row['total_isi_satuan_kecil'] . " / " . $row['jenis_satuan_kecil'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                                                    }
                                                    $koneksi->close();
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tabel Items -->

                            </div>
                        </div>
                    </section>
                </div>
                <!-- End Bagian Utama -->
            </div>

            <?php include('layout/js.php'); ?>
            <script>
                function printInventory() {
                    // Ambil konten tabel
                    var printContents = document.getElementById('inventoryTable').outerHTML;
                    // Buat jendela baru untuk mencetak
                    var originalContents = document.body.innerHTML;
                    var newWindow = window.open();
                    newWindow.document.write('<html><head><title>Print Inventory</title></head><body>');
                    newWindow.document.write('<h1 style="text-align:center;">Inventory</h1>');
                    newWindow.document.write(printContents);
                    newWindow.document.write('</body></html>');
                    // Cetak
                    newWindow.print();
                    newWindow.close();
                }
            </script>

            <script>
                $(document).ready(function() {
                    var empDataTable = $('#empTable').DataTable({
                        dom: 'Blfrtip',
                        buttons: [{
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5] // Column index which needs to export
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
    </div>
</body>

</html>