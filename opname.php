<?php
include('koneksi/config.php');
?>

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

<?php include('layout/head.php'); ?>

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
                                <!-- Tabel Nama Item dan Jumlah Satuan -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Informasi Item</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="empTable1" class="table mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Item</th>
                                                        <th>Jumlah Item</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tabel Nama Item dan Jumlah Satuan -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Cek Stock Opname</h4>
                                    </div>
                                    <div class="card-body">
                                        <a href="tambah_opname.php" class="btn btn-primary mb-3">Tambah</a>
                                        <a href="#" class="btn btn-warning mb-3" id="printButton">Print</a>
                                        <div class="table-responsive">
                                            <!-- Tabel Stock Opname -->
                                            <table id="empTable" class="table mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Nama Item</th>
                                                        <th>Jumlah Fisik</th>
                                                        <th>Balance</th>
                                                        <th>Keterangan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
        </div>
    </div>
    <script>
        // Fungsi untuk menjalankan print_opname.php saat tombol cetak diklik
        document.getElementById('printButton').addEventListener('click', function() {
            window.location.href = 'print_opname.php';
        });
    </script>

    <script>
        // Tangkap klik tombol update
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('update-btn')) {
                // Ambil ID opname dari atribut data-id
                var idOpname = e.target.getAttribute('data-id');
                // Ambil nilai keterangan dari input field di baris yang sama
                var keterangan = document.querySelector('input[name="keterangan_' + idOpname + '"]').value;

                // Kirim data yang diubah ke file PHP menggunakan AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'proses_update_opname.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Tambahkan kode untuk menangani respon dari server (jika diperlukan)
                        console.log(xhr.responseText);
                        // Jika pembaruan berhasil, muat ulang halaman
                        location.reload(); // Ini akan memuat ulang halaman
                    }
                };
                xhr.send('id_opname=' + idOpname + '&keterangan=' + encodeURIComponent(keterangan)); // Perlu menggunakan encodeURIComponent untuk menghindari masalah karakter khusus
            }
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                var idOpname = e.target.getAttribute('data-id');

                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    // Kirim data yang dihapus ke file PHP menggunakan AJAX
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'proses_delete_opname.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            // Jika penghapusan berhasil, muat ulang halaman
                            location.reload();
                        }
                    };
                    xhr.send('id_opname=' + idOpname);
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var empDataTable = $('#empTable1').DataTable({
                dom: 'Blfrtip',
                buttons: []

            });

        });
    </script>
    <script>
        $(document).ready(function() {
            var empDataTable = $('#empTable').DataTable({
                dom: 'Blfrtip',
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

</body>

</html>