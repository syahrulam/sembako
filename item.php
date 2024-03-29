<?php include('layout/head.php'); ?>

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
            <div class="main-sidebar sidebar-style-2">
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
                                        <h4>Items</h4>
                                    </div>
                                    <div class="card-body">
                                        <a href="tambah_item.php" class="btn btn-primary mb-3">Tambah Item</a>
                                        <div class="table-responsive">
                                            <table id="empTable" class="table mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kategori</th>
                                                        <th>Nama Item</th>
                                                        <th>Jenis Satuan Besar</th>
                                                        <th>Jenis Satuan Kecil</th>
                                                        <th>Jumlah Satuan Besar</th>
                                                        <th>Jumlah Isi Satuan Besar</th>
                                                        <th>Harga Kulak</th>
                                                        <th>Harga Jual Satuan Besar</th>
                                                        <th>Harga Jual Satuan Kecil</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    include('koneksi/config.php');

                                                    $sql = "SELECT item.*, kategori.kategori 
                                                            FROM item 
                                                            INNER JOIN kategori ON item.kategori_id = kategori.id";
                                                    $result = $koneksi->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        $no = 1;
                                                        while ($row = $result->fetch_assoc()) {

                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $row['kategori'] . "</td>"; // Menggunakan kolom 'kategori' dari tabel kategori
                                                            echo "<td>" . $row['nama_item'] . "</td>";
                                                            echo "<td>" . $row['jenis_satuan_besar'] . "</td>";
                                                            echo "<td>" . $row['jenis_satuan_kecil'] . "</td>";
                                                            echo "<td>" . $row['jumlah_satuan_besar'] . "</td>";
                                                            echo "<td>" . $row['jumlah_isi_satuan_besar'] . "</td>";
                                                            echo "<td class='harga_kulak'>" . $row['harga_kulak'] . "</td>";
                                                            echo "<td class='harga_jual_satuan_besar'>" . $row['harga_jual_satuan_besar'] . "</td>";
                                                            echo "<td class='harga_jual_satuan_kecil'>" . $row['harga_jual_satuan_kecil'] . "</td>";
                                                            echo "<td>";
                                                            echo "<a href='edit_item.php?id_item=" . $row['id_item'] . "' class='btn btn-warning btn-sm'>Perbarui Data</a>";
                                                            echo "<a href='hapus_item.php?id_item=" . $row['id_item'] . "' class='btn btn-danger btn-sm'>Hapus</a>";
                                                            echo "<a href='print_item.php?id_item=" . $row['id_item'] . "' class='btn btn-success btn-sm'>Print</a>";
                                                            echo "<button onclick='showRestockField(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Restok</button>"; // Tombol Restok

                                                            // Field restok dimasukkan ke dalam tabel di bawah tombol Restok
                                                            echo "<div id='restockField_" . $row['id_item'] . "' style='display: none;'>";
                                                            echo "<input type='number' id='restockQuantity_" . $row['id_item'] . "' placeholder='Jumlah Restok'>";
                                                            echo "<button onclick='submitRestock(" . $row['id_item'] . ")' class='btn btn-primary btn-sm'>Submit</button>"; // Tombol Submit Restok
                                                            echo "</div>";

                                                            echo "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='11'>Tidak ada data</td></tr>";
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
                $(document).ready(function() {
                    // Function to format number to Indonesian currency format
                    function formatRupiah(angka) {
                        var reverse = angka.toString().split('').reverse().join(''),
                            ribuan = reverse.match(/\d{1,3}/g);
                        ribuan = ribuan.join('.').split('').reverse().join('');
                        return 'Rp. ' + ribuan;
                    }

                    // Format harga beli dan harga jual saat dokumen siap
                    $(".harga_beli").each(function() {
                        var hargaBeli = parseFloat($(this).text());
                        $(this).text(formatRupiah(hargaBeli));
                    });

                    $(".harga_jual").each(function() {
                        var hargaJual = parseFloat($(this).text());
                        $(this).text(formatRupiah(hargaJual));
                    });
                });

                function printItem(id) {
                    // Buka halaman cetak item dalam jendela baru
                    var newWindow = window.open('print_item.php?id=' + id, '_blank');

                    // Cetak halaman
                    newWindow.print();
                    newWindow.close();
                }

                function showRestockField(itemId) {
                    var restockField = document.getElementById('restockField_' + itemId);
                    restockField.style.display = 'block';
                }

                function submitRestock(itemId) {
                    var restockQuantity = document.getElementById('restockQuantity_' + itemId).value;

                    // Validasi input (misalnya, pastikan bahwa restockQuantity adalah angka positif)
                    if (isNaN(restockQuantity) || restockQuantity <= 0) {
                        alert('Jumlah restok harus angka positif.');
                        return;
                    }

                    // Kirim data restok ke server (Anda dapat menggunakan AJAX atau bentuk pengiriman data yang sesuai)
                    // Misalnya, Anda dapat menggunakan fetch() atau jQuery.ajax() untuk mengirim data ke server.
                    // Pastikan untuk mengatur endpoint yang sesuai di sisi server (contoh: restock_item.php).
                    fetch('restock_item.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id_item=' + itemId + '&restock_quantity=' + restockQuantity,
                        })
                        .then(response => response.text())
                        .then(data => {
                            // Tampilkan pesan feedback dari server (misalnya, "Restok berhasil dilakukan.")
                            alert(data);
                            // Sembunyikan field restok setelah restok berhasil
                            var restockField = document.getElementById('restockField_' + itemId);
                            restockField.style.display = 'none';
                            // Refresh halaman item
                            window.location.reload();
                        })
                        .catch(error => {
                            // Tangani kesalahan jika terjadi
                            console.error('Error:', error);
                        });
                }
            </script>

            <script>
                $(document).ready(function() {
                    var empDataTable = $('#empTable').DataTable({
                        dom: 'Blfrtip',
                        buttons: [{
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] // Column index which needs to export
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

</body>

</html>