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

// Koneksi ke database
include('koneksi/config.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Input Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Form untuk input transaksi -->
                                    <form action="proses_transaksi.php" method="post">
                                        <div class="card-body">
                                            <div id="items-container">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <div class="form-group">
                                                            <div class="pelanggan-container">
                                                                <label for="nama" class="text-dark">Nama Pelanggan<span class='red'> *</span></label>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <input class="form-control nama" type="text" name="nama" placeholder="Nama Pelanggan" required />
                                                                        <div class="result_pelanggan"></div>
                                                                    </div>

                                                                    <div class="total_hutang_container">
                                                                        <span class="total_hutang_display" style="display: none;"></span>
                                                                    </div>

                                                                    <div class="col-2 m-0 p-0">
                                                                        <button type="button" class="btn btn-warning p-2 btn-bayar-cicilan" data-toggle="modal" data-target="#PelangganModal">Tambah Pelanggan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tgl_transaksi" class="text-dark">Tanggal Transaksi<span class='red'> *</span></label>
                                                            <?php $tanggal_hari_ini = date("d F Y"); ?>
                                                            <input class="form-control" type="text" name="tgl_transaksi" id="tgl_transaksi" value="<?php echo $tanggal_hari_ini; ?>" readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="no_transaksi" class="text-dark">Nomor Transaksi<span class='red'> *</span></label>
                                                            <?php $nomor_transaksi = "TR" . rand(); ?>
                                                            <input class="form-control" type="text" name="no_transaksi" id="no_transaksi" value="<?php echo $nomor_transaksi; ?>" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hidden-form">
                                            <!-- Row item inputan -->
                                            <div class="row item-row item-container" id="item-1">
                                                <div class="col-md-3">
                                                    <label for="nama_item_1" class="text-dark">Nama Item<span class='red'> *</span></label>
                                                    <input class="form-control nama_item" type="text" name="nama_item_1"/>
                                                    <input class="form-control id_item" type="text" name="id_item_1" style="display:none;" />
                                                    <div class="result"></div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="jenis_satuan_1" class="text-dark">Jenis Satuan</label>
                                                        <select class="form-control jenis_satuan" name="jenis_satuan_1" id="jenis_satuan_1">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="harga_satuan_1" class="text-dark">Harga Satuan (Rp.)</label>
                                                        <select class="form-control harga_satuan" name="harga_satuan_1" id="harga_satuan_1">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="jumlah_1" class="text-dark">Jumlah</label>
                                                        <input type="number" class="form-control jumlah" name="jumlah_1" id="jumlah_1" min="1" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="total_1" class="text-dark">Total Harga (Rp.)</label>
                                                        <input type="number" class="form-control total_harga" name="total_1" id="total_1" min="0" readonly />
                                                    </div>
                                                </div>


                                                <div class="col-md-1">
    <div class="form-group" style="margin-top:30px;">
        <button id="checkItemButton" class="btn btn-primary check-item">
            <i class="fa-solid fa-check-circle"></i>
        </button>
    </div>
</div>

                                            </div>
<br>
                                            <!-- Tabel untuk menyimpan item yang sudah diinputkan -->
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="itemTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nama Item</th>
                                                                    <th>Jenis Satuan</th>
                                                                    <th>Harga Satuan (Rp.)</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Total Harga (Rp.)</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Isi dari tabel ini akan diisi dengan item yang sudah diinputkan -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="total_harus_dibayar" class="text-dark" style="font-weight: bold;">Harus Dibayar (Rp.)<span class='red'> *</span></label>
                                                        <input type="text" id="total_harus_dibayar" class="form-control" style="font-weight: bold;" name="total_harga" value="Rp. 0" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="tipe_pembayaran" class="text-dark">Tipe Pembayaran<span class='red'> *</span></label>
                                                        <select class="form-control" name="tipe_pembayaran" id="tipe_pembayaran" required>
                                                            <option value="">Metode Pembayaran</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Kredit" id="Kredit-select">Kredit</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="uang_diterima" id="label_uang_diterima" class="text-dark">Bayar (Rp.)<span class='red'> *</span></label>
                                                        <input class="form-control" type="text" name="uang_diterima" id="uang_diterima" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="kembalian" id="label_kembalian" class="text-dark">Kembalian (Rp.)<span class='red'> *</span></label>
                                                        <input class="form-control" type="text" name="kembalian" id="kembalian" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="kurangan" id="label_kurangan" class="text-dark">Kurangan (Rp.)<span class='red'> *</span></label>
                                                        <input class="form-control" type="text" name="kurangan" id="kurangan" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="nama_sales" class="text-dark">Nama Sales<span class='red'> *</span></label>
                                                        <div class="sales-container">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <select class="form-control" id="namaSales" name="nama_sales" required>
                                                                        <option value="">Pilih Sales</option>
                                                                        <?php
                                                                        $sqlSales = "SELECT * FROM sales";
                                                                        $resultSales = mysqli_query($koneksi, $sqlSales);
                                                                        while ($rowSales = mysqli_fetch_assoc($resultSales)) {
                                                                            echo "<option value='" . $rowSales['nama'] . "'>" . $rowSales['nama'] . "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <div class="result_sales"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-actions float-right">
                                                        <button type="reset" name="Reset" class="btn btn-danger">
                                                            <i class="fa fa-times"></i> Batal
                                                        </button>
                                                        <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary px-4" title="Save">
                                                            <i class="fa fa-check"></i> Bayar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Main Content -->
            <footer class="main-footer">
                <?php include('layout/footer.php'); ?>
            </footer>

            <!-- Modal Tambah Pelanggan -->
            <div class="modal fade" id="PelangganModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bayarCicilanModalLabel">Tambah Pelanggan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="simpan_pelanggan.php">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pelanggan" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
                                </div>
                                <div class="form-group">
                                    <label for="nomor">Nomor</label>
                                    <input type="text" class="form-control" id="nomor" name="nomor" placeholder="No Telepon/WA" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Bootstrap untuk Notifikasi -->
            <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Pemberitahuan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="notificationMessage"></p>  <!-- Pesan notifikasi ditampilkan di sini -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('layout/js.php'); ?>
    <script>
    $(document).ready(function () {
    // ---------------------------fungsi cari nama pelanggan ----------------------------------------------
    $(document).on("input", ".nama", function () {
        var searchTerm = $(this).val();
        var resultContainer = $(this).parent().find(".result_pelanggan");
        if (searchTerm !== "") {
            $.ajax({
                type: "POST",
                url: "search_pelanggan.php",
                data: { searchPelanggan: searchTerm },
                success: function (data) {
                    resultContainer.html(data);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error: " + status + " - " + error);
                }
            });
        } else {
            resultContainer.empty();
        }
    });

    $(document).on("click", ".result_pelanggan li", function () {
        var selectedPelanggan = $(this).text();
        var pelangganContainer = $(this).closest(".pelanggan-container");
        pelangganContainer.find(".nama").val(selectedPelanggan);
        pelangganContainer.find(".result_pelanggan").empty();
    });
    // --------------------------------------fungsi cari nama item---------------------------------------------
    var itemSelected = false; // Tambahkan flag untuk memeriksa apakah item sudah dipilih

    $(document).on("input", ".nama_item", function () {
        var searchTerm = $(this).val();
        var resultContainer = $(this).parent().find(".result");

        if (searchTerm !== "" && !itemSelected) {  // Hanya jalankan jika item belum dipilih
            $.ajax({
                type: "POST",
                url: "search_item.php",
                data: { searchItem: searchTerm },
                success: function (data) {
                    resultContainer.html(data);
                }
            });
        } else {
            resultContainer.empty(); // Kosongkan hasil pencarian jika input kosong
        }
    });

    $(document).on("click", ".result li", function () {
    var selectedItem = $(this).text();
    var itemId = $(this).data('id');
    var jenisSatuanBesar = $(this).data('besar');  // Mengambil jenis satuan besar dari data
    var jenisSatuanKecil = $(this).data('kecil');  // Mengambil jenis satuan kecil dari data
    var hargaBesar1 = $(this).data('harga-besar1');
    var hargaBesar2 = $(this).data('harga-besar2');
    var hargaBesar3 = $(this).data('harga-besar3');
    var hargaKecil1 = $(this).data('harga-kecil1');
    var hargaKecil2 = $(this).data('harga-kecil2');
    var hargaKecil3 = $(this).data('harga-kecil3');
    var itemContainer = $(this).closest(".item-container");

    // Masukkan nilai item yang dipilih ke dalam input nama_item
    itemContainer.find(".nama_item").val(selectedItem);
    itemContainer.find(".id_item").val(itemId);

    // Tampilkan jenis satuan berdasarkan data dari database
    var jenisSatuanSelect = itemContainer.find(".jenis_satuan");
    jenisSatuanSelect.empty();  // Kosongkan opsi sebelumnya
    if (jenisSatuanBesar) {
        jenisSatuanSelect.append(`<option value="Besar">${jenisSatuanBesar}</option>`);
    }
    if (jenisSatuanKecil) {
        jenisSatuanSelect.append(`<option value="Kecil">${jenisSatuanKecil}</option>`);
    }

    // Set harga berdasarkan jenis satuan yang dipilih
    jenisSatuanSelect.on("change", function () {
        var hargaSelect = itemContainer.find(".harga_satuan");
        var selectedJenisSatuan = $(this).val();

        hargaSelect.empty();  // Kosongkan opsi harga sebelumnya

        if (selectedJenisSatuan === "Besar") {
            hargaSelect.append(`<option value="${hargaBesar1}">${hargaBesar1}</option>`);
            hargaSelect.append(`<option value="${hargaBesar2}">${hargaBesar2}</option>`);
            hargaSelect.append(`<option value="${hargaBesar3}">${hargaBesar3}</option>`);
        } else if (selectedJenisSatuan === "Kecil") {
            hargaSelect.append(`<option value="${hargaKecil1}">${hargaKecil1}</option>`);
            hargaSelect.append(`<option value="${hargaKecil2}">${hargaKecil2}</option>`);
            hargaSelect.append(`<option value="${hargaKecil3}">${hargaKecil3}</option>`);
        }

        // Hitung ulang total harga
        calculateTotalPrice(itemContainer);
    }).trigger("change");  // Jalankan untuk menetapkan harga awal

    // Kosongkan hasil pencarian setelah item dipilih
    itemContainer.find(".result").empty();
});

// Fungsi untuk menghitung total harga
function calculateTotalPrice(itemContainer) {
    var hargaSatuan = parseFloat(itemContainer.find(".harga_satuan").val());
    var jumlah = parseInt(itemContainer.find(".jumlah").val());

    if (!isNaN(hargaSatuan) && !isNaN(jumlah)) {
        var totalHarga = hargaSatuan * jumlah;
        itemContainer.find(".total_harga").val(totalHarga);
    }
}


    $(document).on("change", ".harga_satuan, .jumlah, .jenis_satuan", function () {
        calculateTotalPrice($(this).closest(".item-container"));
        updateTotalHarga();
    });

    // ---------------------------Event untuk menambah item ke dalam tabel-------------------------------
    let itemCounter = 1;

    $('#checkItemButton').on("click", function (e) {
        e.preventDefault();

        var itemContainer = $(this).closest(".item-container");
        var idItem = itemContainer.find(".id_item").val();
        var jenisSatuan = itemContainer.find(".jenis_satuan").val();
        var jumlah = itemContainer.find(".jumlah").val();

        // Cek apakah input item sudah lengkap
        if (idItem && jenisSatuan && jumlah) {
            // Validasi stok
            $.ajax({
    type: "POST",
    url: "check_stock.php",
    data: { id_item: idItem, jenis_satuan: jenisSatuan, jumlah: jumlah },
    success: function (response) {
        try {
            var data = JSON.parse(response);

            if (data.status === "success") {
                updateStok(idItem, jumlah, jenisSatuan, 'kurangi');
                addItemToTable(itemContainer);
            } else if (data.status === "error") {
                // Ganti pesan di modal dan tampilkan modal
                $('#notificationMessage').text(data.message);  // Setel pesan di modal
                $('#notificationModal').modal('show');  // Tampilkan modal Bootstrap
            }
        } catch (e) {
            console.error("Error parsing JSON response:", e);
        }
    },
    error: function (xhr, status, error) {
        console.error("Error in AJAX request:", status, error);
    }
});

        } else {
            console.log("Data item tidak lengkap.");
            return;
        }
    });

// Fungsi untuk menambahkan item ke tabel
function addItemToTable(itemContainer, index) {
    let idItem = itemContainer.find('.id_item').val();          // ID item
    let namaItem = itemContainer.find('.nama_item').val();      // Nama item
    let jenisSatuan = itemContainer.find('.jenis_satuan').val();  // Jenis satuan
    let hargaSatuan = itemContainer.find('.harga_satuan').val();  // Harga satuan
    let jumlah = itemContainer.find('.jumlah').val();           // Jumlah
    let totalHarga = itemContainer.find('.total_harga').val();  // Total harga

    // Buat baris baru dengan input array untuk mengirimkan ke PHP
    let newRow = `<tr data-id-item="${idItem}" data-jumlah="${jumlah}" data-jenis-satuan="${jenisSatuan}">
        <td><input type="hidden" name="items[${index}][id_item]" value="${idItem}" />${namaItem}</td>
        <td><input type="hidden" name="items[${index}][jenis_satuan]" value="${jenisSatuan}" />${jenisSatuan}</td>
        <td><input type="hidden" name="items[${index}][harga_satuan]" value="${hargaSatuan}" />${hargaSatuan}</td>
        <td><input type="hidden" name="items[${index}][jumlah]" value="${jumlah}" />${jumlah}</td>
        <td><input type="hidden" name="items[${index}][total_harga]" value="${totalHarga}" />${totalHarga}</td>
        <td><button class="btn btn-danger btn-sm remove-item-table">Hapus</button></td>
    </tr>`;

    // Tambahkan baris baru ke dalam tabel
    $('#itemTable tbody').append(newRow);

    // Kosongkan input setelah item ditambahkan ke tabel
    itemContainer.find('.nama_item').val('');
    itemContainer.find('.jenis_satuan').val('');
    itemContainer.find('.harga_satuan').val('');
    itemContainer.find('.jumlah').val('');
    itemContainer.find('.total_harga').val('');

    // Update total harga setelah item ditambahkan
    updateTotalHarga();
}


    // Event listener untuk menghapus item dari tabel
    $(document).on('click', '.remove-item-table', function () {
        let row = $(this).closest('tr');
        let idItem = row.data('id-item');
        let jumlah = row.data('jumlah');
        let jenisSatuan = row.data('jenis-satuan');

        // Kembalikan stok di database saat item dihapus
        updateStok(idItem, jumlah, jenisSatuan, 'tambah');

        // Hapus baris dari tabel
        row.remove();

        // Update total harga setelah item dihapus
        updateTotalHarga();
    });

    // Fungsi untuk mengurangi atau menambah stok di database
    function updateStok(idItem, jumlah, jenisSatuan, operasi) {
        $.ajax({
            type: "POST",
            url: "update_stok.php",  // File PHP untuk meng-update stok
            data: { id_item: idItem, jumlah: jumlah, jenis_satuan: jenisSatuan, operasi: operasi },
            success: function (response) {
                console.log("Stok diperbarui: " + response);
            },
            error: function (xhr, status, error) {
                console.error("Error in AJAX request: ", status, error);
            }
        });
    }

    // Fungsi untuk menghitung total harga keseluruhan
    function updateTotalHarga() {
        let totalHarga = 0;

        $('#itemTable tbody tr').each(function () {
            let totalItem = parseFloat($(this).find('td:eq(4)').text());
            if (!isNaN(totalItem)) {
                totalHarga += totalItem;
            }
        });

        $('#total_harus_dibayar').val(totalHarga);
        updatePembayaran();
    }

    // Fungsi untuk update pembayaran
    function updatePembayaran() {
        let tipePembayaran = $('#tipe_pembayaran').val();
        let bayar = parseFloat($('#uang_diterima').val());
        let totalHarga = parseFloat($('#total_harus_dibayar').val());

        // Jika belum ada metode pembayaran, sembunyikan input Bayar, Kembalian, dan Kurangan
        if (tipePembayaran === "") {
            $('#uang_diterima').parent().hide();
            $('#kembalian').parent().hide();
            $('#kurangan').parent().hide();
            return;
        }

        // Tampilkan input Bayar setelah metode pembayaran dipilih
        $('#uang_diterima').parent().show();

        if (tipePembayaran === 'Cash') {
            $('#kembalian').parent().show();
            $('#kurangan').parent().hide(); // Sembunyikan kolom kurangan
            if (!isNaN(bayar) && bayar >= totalHarga) {
                let kembalian = bayar - totalHarga;
                $('#kembalian').val(parseInt(kembalian));
            } else {
                $('#kembalian').val('Uang Kurang');
            }
<<<<<<< HEAD
        } else if (tipePembayaran === 'Kredit') {
            $('#kurangan').parent().show();
            $('#kembalian').parent().hide(); // Sembunyikan kolom kembalian
            if (!isNaN(bayar) && bayar < totalHarga) {
                let kurangan = totalHarga - bayar;
                $('#kurangan').val(parseInt(kurangan));
            } else {
                $('#kurangan').val('Uang Lebih');
=======


            function updatePembayaran() {
                var tipePembayaran = $('select[name="tipe_pembayaran"]').val();
                var bayar = parseFloat($('input[name="uang_diterima"]').val());
                var harusDibayar = parseFloat($('#total_harus_dibayar').val());

                if (tipePembayaran === 'Cash') {
                    if (!isNaN(bayar) && bayar >= harusDibayar) {
                        var kembalian = bayar - harusDibayar;
                        $('input[name="kembalian"]').val(parseInt(kembalian)); // Menghapus angka di belakang koma
                        $('input[name="kurangan"]').val(0); // Reset nilai kurangan menjadi 0
                    } else {
                        $('input[name="kembalian"]').val("Uang Kurang, Ingin Ubah Tipe Pembayaran?");
                        var kekurangan = harusDibayar - bayar;
                        $('input[name="kurangan"]').val(parseInt(kekurangan)); // Menghapus angka di belakang koma dan tampilkan kekurangan
                    }
                } else if (tipePembayaran === 'Kredit') {
                    if (!isNaN(bayar) && bayar < harusDibayar) {
                        var kurangan = harusDibayar - bayar;
                        $('input[name="kurangan"]').val(parseInt(kurangan)); // Menghapus angka di belakang koma dan tampilkan kekurangan
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0
                    } else {
                        $('input[name="kurangan"]').val("Uang Lebih, Silahakan Ubah Tipe Pembayaran"); // Tampilkan Uang Lebih
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0
                    }
                }
>>>>>>> 741cbfd64e4617ecf859757b9a02d9ec31dd5ad6
            }
        }
    }

    // Event untuk memantau perubahan tipe pembayaran dan jumlah bayar
    $('#tipe_pembayaran').on('change', updatePembayaran);
    $('#uang_diterima').on('input', updatePembayaran);

    // Sembunyikan input Bayar, Kembalian, dan Kurangan saat halaman pertama kali dimuat
    $('#uang_diterima').parent().hide();
    $('#kembalian').parent().hide();
    $('#kurangan').parent().hide();
});
</script>



</body>

</html>
