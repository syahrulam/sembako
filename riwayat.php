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
$role = $_SESSION['role'];
?>

<?php
include('koneksi/config.php');

$bulan_sekarang = date('m');
$tahun_sekarang = date('Y');

$query_total_harga = "SELECT SUM(total_harga) AS total_harga 
                      FROM transaksi 
                      WHERE MONTH(tanggal) = $bulan_sekarang 
                      AND YEAR(tanggal) = $tahun_sekarang";

$hasil_total_harga = $koneksi->query($query_total_harga);
$row_total_harga = $hasil_total_harga->fetch_assoc();
$total_harga_bulan_ini = $row_total_harga['total_harga'];
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

                                <!-- Tabel Riwayat Transaksi -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Riwayat Pembayaran</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="alert alert-success" role="alert">
                                                    <p style="font-size: 25px;" class="font-weight-bold">Pendapatan Perbulan (<?php echo date('F Y'); ?>)</p>
                                                    <p style="font-size: 20px;" class="mt-2"><?php echo 'Rp.' . number_format($total_harga_bulan_ini, 0, ',', '.'); ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <p style="font-size: 20px;" class="font-weight-bold">Cetak Berdasarkan Sales </p>
                                        <form action="export_excel_sales.php" method="POST">
                                            <div class="row mb-3">
                                                <div class="col-3">
                                                    <div class="sales-container">
                                                        <input type="text" class="form-control namaSales" id="namaSales" name="namaSales" placeholder="Nama Sales">
                                                        <div class="result_sales"></div>
                                                    </div>

                                                </div>
                                                <div class="col-3">
                                                    <input type="month" class="form-control" id="bulanTahunMulai" name="bulanTahunMulai" placeholder="Mulai Bulan Tahun" required>
                                                </div>
                                                <div class="col-3">
                                                    <input type="month" class="form-control" id="bulanTahunAkhir" name="bulanTahunAkhir" placeholder="Akhir Bulan Tahun" required>
                                                </div>
                                                <div class="col">
                                                    <button type="submit" class="btn btn-success" name="export">Export Excel</button>
                                                </div>
                                            </div>
                                        </form>

                                        <p style="font-size: 20px;" class="font-weight-bold">Cetak Berdasarkan Nama Pelanggan </p>
                                        <form action="export_excel.php" method="POST">
                                            <div class="row mb-3">
                                                <div class="col-3">
                                                    <div class="pelanggan-container">
                                                        <input type="text" class="form-control namaPelanggan" id="namaPelanggan" name="namaPelanggan" placeholder="Nama Pelanggan">
                                                        <div class="result_pelanggan"></div>
                                                    </div>

                                                </div>
                                                <div class="col-3">
                                                    <input type="month" class="form-control" id="bulanTahunMulai" name="bulanTahunMulai" placeholder="Mulai Bulan Tahun" required>
                                                </div>
                                                <div class="col-3">
                                                    <input type="month" class="form-control" id="bulanTahunAkhir" name="bulanTahunAkhir" placeholder="Akhir Bulan Tahun" required>
                                                </div>
                                                <div class="col">
                                                    <button type="submit" class="btn btn-success" name="export">Export Excel</button>
                                                </div>
                                            </div>
                                        </form>



                                        <div style="margin-bottom: 40px;">
                                            <p style="font-size: 20px;" class="font-weight-bold">Tampilkan Berdasarkan </p>
                                            <form id="searchForm">
                                                <div class="row ml-0">
                                                    <div class="pelanggan-container">
                                                        <input type="text" class="form-control namaPelanggan" id="namaPelanggan" name="namaPelanggan" placeholder="Nama Pelanggan">
                                                        <div class="result_pelanggan"></div>
                                                    </div>

                                                    <div class="col">
                                                        <input type="month" class="form-control" id="bulanTahun" name="bulanTahun">
                                                    </div>
                                                    <div class="col">
                                                        <select id="sorting" class="form-control" name="sorting">
                                                            <option value="terlama">Terlama</option>
                                                            <option value="terbaru">Terbaru</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-primary" onclick="searchData()">Filter Data</button>
                                                    </div>
                                                </div>
                                            </form>

                                            <hr>
                                        </div>
                                        <div class="table-responsive">
                                            <table id='empTable' class='display dataTable'>
                                                <thead>
                                                    <tr>
                                                        <th>No Transaksi</th>
                                                        <th>Tanggal</th>
                                                        <th>Pembayaran</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Keterangan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Menghubungkan ke file config.php
                                                    include('koneksi/config.php');

                                                    // Query untuk mengambil data member
                                                    $query = "SELECT * FROM transaksi";
                                                    $result = $koneksi->query($query);

                                                    // Memeriksa apakah query berhasil dieksekusi
                                                    if ($result) {
                                                        if ($result->num_rows > 0) {
                                                            $no = 1;
                                                            // Output data dari setiap baris
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td>" . $row['no_transaksi'] . "</td>";
                                                                echo "<td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>";
                                                                echo "<td>" . $row['tipe_pembayaran'] . "</td>";
                                                                echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                                                echo "<td>";

                                                                if ($row['tipe_pembayaran'] == 'Cash') {
                                                                    echo "Lunas";
                                                                } else {
                                                                    echo "Kekurangan : Rp " . number_format($row['kekurangan'], 0, ',', '.');
                                                                }

                                                                echo "<td>
                                                                <!-- Tombol aksi -->
                                                                <a class='btn btn-warning btn-sm px-4 mt-2' href='print_invoice.php?id_transaksi=" . $row['id_transaksi'] . "'>Print</a>";

                                                                if ($_SESSION['role'] === 'Admin') {
                                                                    echo "<a class='btn btn-danger btn-sm px-4 mt-2' href='hapus_transaksi.php?id_transaksi=" . $row['id_transaksi'] . "' onclick='return confirmDelete()'>Hapus</a>";
                                                                }

                                                                echo "<a class='btn btn-primary btn-sm px-4 mt-2' href='detail_transaksi.php?id_transaksi=" . $row['id_transaksi'] . "'>Detail</a>";
                                                                echo "</td>";
                                                                echo "</tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='6'>Tidak ada data member.</td></tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>Error: " . $koneksi->error . "</td></tr>";
                                                    }

                                                    // Menutup koneksi database
                                                    $koneksi->close();
                                                    ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tabel Riwayat Transaksi -->

                            </div>
                        </div>

                    </section>
                </div>
                <!-- End Bagian Utama -->

                <!-- ... (your existing code) ... -->

            </div>

            <?php include('layout/js.php'); ?>

            <script>
                function confirmDelete() {
                    return confirm("Apakah Anda yakin ingin menghapus transaksi ini?");
                }
            </script>
            <!-- ... (your existing code) ... -->
            <script>
                // Fungsi untuk mencetak invoice
                function printInvoice(transactionId) {
                    // Di sini Anda dapat menambahkan logika untuk mencetak invoice
                    // Contoh: Buka halaman cetak invoice dalam jendela baru atau lakukan cetak langsung
                    window.open('print_invoice_riwayat.php', '_blank');
                }

                // Fungsi untuk menghapus transaksi
                function deleteTransaction(transactionId) {
                    // Di sini Anda dapat menambahkan logika untuk menghapus transaksi
                    // Contoh: Kirim permintaan AJAX ke server untuk menghapus transaksi dengan ID tertentu
                    console.log('Menghapus transaksi dengan ID: ' + transactionId);
                }
            </script>


            <script>
                // JavaScript function to trigger the print action
                function printPage() {
                    window.print();
                }
            </script>


            <script>
                $(document).ready(function() {
                    var empDataTable = $('#empTable').DataTable({
                        dom: 'Blfrtip',
                        searching: false,
                        buttons: []

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

            <script>
                function searchData() {
                    var namaPelanggan = $('#namaPelanggan').val();
                    var bulanTahun = $('#bulanTahun').val();
                    var sorting = $('#sorting').val();

                    $.ajax({
                        type: 'POST',
                        url: 'search_transaksi.php', // Ganti dengan nama file PHP yang akan memproses pencarian
                        data: {
                            namaPelanggan: namaPelanggan,
                            bulanTahun,
                            bulanTahun,
                            sorting: sorting
                        },
                        success: function(data) {
                            $('#empTable tbody').html(data);
                        }
                    });
                }
            </script>

            <script>
                $(document).ready(function() {
                    function handleItemSearch(inputElement) {
                        var searchPelanggan = inputElement.val();
                        var resultPelanggan = inputElement.parent().find(".result_pelanggan");

                        if (searchPelanggan !== "") {
                            $.ajax({
                                type: "POST",
                                url: "search_pelanggan.php",
                                data: {
                                    searchPelanggan: searchPelanggan
                                },
                                success: function(data) {
                                    resultPelanggan.html(data);
                                }
                            });
                        } else {
                            resultPelanggan.empty();
                        }
                    }

                    $(document).on("input", ".namaPelanggan", function() {
                        handleItemSearch($(this));
                    });

                    $(document).on("click", ".result_pelanggan li", function() {
                        var selectedPelanggan = $(this).text();
                        var pelangganContainer = $(this).closest(".pelanggan-container");
                        pelangganContainer.find(".nama").val(selectedPelanggan);

                        $.ajax({
                            type: "POST",
                            url: "get_pelanggan.php",
                            data: {
                                selectedPelanggan: selectedPelanggan
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                pelangganContainer.find(".namaPelanggan").val(data.nama);
                            }
                        });

                        pelangganContainer.find(".result_pelanggan").empty(); // Mengganti itemContainer menjadi pelangganContainer
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    function handleSalesSearch(inputElement) {
                        var searchSales = inputElement.val();
                        var resultSales = inputElement.parent().find(".result_sales");

                        if (searchSales !== "") {
                            $.ajax({
                                type: "POST",
                                url: "search_sales.php",
                                data: {
                                    searchSales: searchSales
                                },
                                success: function(data) {
                                    resultSales.html(data);
                                }
                            });
                        } else {
                            resultSales.empty();
                        }
                    }

                    $(document).on("input", ".namaSales", function() {
                        handleSalesSearch($(this)); // Perbaikan: Memanggil fungsi handleSalesSearch
                    });

                    $(document).on("click", ".result_sales li", function() {
                        var selectedSales = $(this).text();
                        var salesContainer = $(this).closest(".sales-container");
                        salesContainer.find(".namaSales").val(selectedSales);

                        $.ajax({
                            type: "POST",
                            url: "get_sales.php",
                            data: {
                                selectedSales: selectedSales
                            },
                            success: function(response) {
                                if (response) {
                                    var data = JSON.parse(response);
                                    salesContainer.find(".namaSales").val(data.nama);
                                }
                            }
                        });

                        salesContainer.find(".result_sales").empty(); // Mengganti itemContainer menjadi pelangganContainer
                    });
                });
            </script>


</body>

</html>