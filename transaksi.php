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

// Koneksi ke database
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
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tgl_transaksi" class="text-dark">Tanggal
                                                                Transaksi<span class='red'> *</span></label>
                                                            <?php $tanggal_hari_ini = date("d F Y"); ?>
                                                            <input class="form-control" type="text" name="tgl_transaksi" id="tgl_transaksi" value="<?php echo $tanggal_hari_ini; ?>" readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="no_transaksi" class="text-dark">Nomor
                                                                Transaksi<span class='red'> *</span></label>
                                                            <?php $nomor_transaksi = "TR" . rand(); ?>
                                                            <input class="form-control" type="text" name="no_transaksi" id="no_transaksi" value="<?php echo $nomor_transaksi; ?>" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <button type="button" class="btn btn-success" id="btnTambahItem" onclick="addNewItem()">Transkasi Baru</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="total_harus_dibayar" class="text-dark">Harus Dibayar
                                                            (Rp.)<span class='red'> *</span></label>
                                                        <input type="text" id="total_harus_dibayar" class="form-control" name="total_harga" value="Rp. 0" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="nama" class="text-dark">Nama Pelanggan<span class='red'> *</span></label>
                                                        <div class="pelanggan-container">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <input class="form-control nama" type="text" name="nama" placeholder="Nama Pelanggan" required />
                                                                    <div class="result_pelanggan"></div>
                                                                </div>
                                                                <div class="col">
                                                                    <a href="tambah_pelanggan.php" class="btn btn-warning p-2">Tambah Pelanggan</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="tipe_pembayaran" class="text-dark">Tipe
                                                            Pembayaran<span class='red'> *</span></label>
                                                        <select class="form-control" name="tipe_pembayaran" id="tipe_pembayaran" required>
                                                            <option value="">Metode Pembayaran</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Debit">Debit</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="uang_diterima" id="label_uang_diterima" class="text-dark">Bayar (Rp.)<span class='red'>
                                                                *</span></label>
                                                        <input class="form-control" type="text" name="uang_diterima" id="uang_diterima" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="kembalian" id="label_kembalian" class="text-dark">Kembalian (Rp.)<span class='red'>
                                                                *</span></label>
                                                        <input class="form-control" type="text" name="kembalian" id="kembalian" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 offset-md-8">
                                                    <div class="form-group">
                                                        <label for="kurangan" id="label_kurangan" class="text-dark">Kurangan (Rp.)<span class='red'>
                                                                *</span></label>
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
                                                                        // Ambil daftar sales dari tabel sales
                                                                        $sqlSales = "SELECT * FROM sales";
                                                                        $resultSales = mysqli_query($koneksi, $sqlSales);

                                                                        // Tampilkan opsi untuk setiap sales
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
                                                        <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary" title="Save">
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
        </div>
    </div>
    <?php include('layout/js.php'); ?>
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript -->
    <script>
        ////////////////////////////////////////////// NAMA ITEM - JENIS - HARGA ///////////////////////////////////////
        var itemCounter = 1;

        function updateItemDetails(itemIndex) {
            var selectedOption = $('#item_' + itemIndex + ' option:selected');
            var jenisSatuanBesar = selectedOption.data('jenis-satuan-besar');
            var jenisSatuanKecil = selectedOption.data('jenis-satuan-kecil');
            var hargaSatuanBesar = selectedOption.data('harga-jual-satuan-besar');
            var hargaSatuanKecil = selectedOption.data('harga-jual-satuan-kecil');

            // Membuat dropdown jenis satuan
            var jenisSatuanDropdownHtml = `
            <select class="form-control jenis-satuan" name="jenis_satuan_${itemIndex}" id="jenis_satuan_${itemIndex}" onchange="updateHarga(${itemIndex})">
                <option value="Besar">${jenisSatuanBesar}</option>
                <option value="Kecil">${jenisSatuanKecil}</option>
            </select>
        `;
            $('#jenis_satuan_' + itemIndex).html(jenisSatuanDropdownHtml);

            // Menentukan harga satuan berdasarkan jenis satuan yang dipilih
            var hargaSatuan;
            if ($('#jenis_satuan_' + itemIndex + ' option:selected').val() === 'Besar') {
                hargaSatuan = hargaSatuanBesar;
            } else {
                hargaSatuan = hargaSatuanKecil;
            }
            $('#harga_satuan_' + itemIndex).val(hargaSatuan);
        }

        function updateHarga(itemIndex) {
            var selectedOption = $('#jenis_satuan_' + itemIndex + ' option:selected').val();
            var selectedOptionData;

            if (selectedOption === 'Besar') {
                selectedOptionData = $('#item_' + itemIndex + ' option:selected').data('harga-jual-satuan-besar');
            } else {
                selectedOptionData = $('#item_' + itemIndex + ' option:selected').data('harga-jual-satuan-kecil');
            }

            $('#harga_satuan_' + itemIndex).val(selectedOptionData);
        }

        function addNewItem() {
            itemCounter++;

            var newItemHtml = `
            <div class="row item-row item-container" id="item-${itemCounter}">
            
           
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="item_${itemCounter}" class="text-dark">Item</label>
                        <select class="form-control" name="item_${itemCounter}" id="item_${itemCounter}" onchange="updateItemDetails(${itemCounter})" required>
                            <?php
                            // Ambil daftar item dari database
                            $sql = "SELECT id_item, nama_item, jenis_satuan_besar, jenis_satuan_kecil, harga_jual_satuan_besar, harga_jual_satuan_kecil FROM item";
                            $result = mysqli_query($koneksi, $sql);

                            // Tampilkan opsi untuk setiap item
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id_item'] . "' data-jenis-satuan-besar='" . $row['jenis_satuan_besar'] . "' data-jenis-satuan-kecil='" . $row['jenis_satuan_kecil'] . "' data-harga-jual-satuan-besar='" . $row['harga_jual_satuan_besar'] . "' data-harga-jual-satuan-kecil='" . $row['harga_jual_satuan_kecil'] . "'>" . $row['nama_item'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
               
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="jenis_satuan_${itemCounter}" class="text-dark">Jenis Satuan</label>
                        <div id="jenis_satuan_${itemCounter}"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_item_s${itemCounter}" class="text-dark">id item</label>
                        <input type="number" class="form-control id_item" name="id_item_${itemCounter}" id="id_item_${itemCounter}"  readonly />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="harga_satuan_${itemCounter}" class="text-dark">Harga Satuan (Rp.)</label>
                        <input type="number" class="form-control harga-satuan" name="harga_satuan_${itemCounter}" id="harga_satuan_${itemCounter}" min="0" readonly />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="jumlah_${itemCounter}" class="text-dark">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah_${itemCounter}" id="jumlah_${itemCounter}" min="1" onchange="updateTotal(${itemCounter})" required />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="total_${itemCounter}" class="text-dark">Total Harga (Rp.)</label>
                        <input type="number" class="form-control" name="total_${itemCounter}" id="total_${itemCounter}" min="0" readonly />
                    </div>
                </div>
            </div>
        `;

            $('#items-container').append(newItemHtml);

            // Memanggil fungsi updateItemDetails untuk menginisialisasi nilai jenis satuan dan harga satuan
            updateItemDetails(itemCounter);

        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $(document).ready(function() {
            // Event listener untuk setiap perubahan pada input item, jenis satuan, harga satuan, dan jumlah
            $('body').on('change', 'select[name^="item_"], select[name^="jenis_satuan_"], input[name^="harga_satuan_"], input[name^="jumlah_"]', function() {
                updateTotalHarga();
            });

            // Event listener untuk setiap perubahan pada input tipe pembayaran
            $('select[name="tipe_pembayaran"]').change(function() {
                toggleFields();
                updatePembayaran();
            });

            // Event listener untuk setiap perubahan pada input bayar
            $('input[name="uang_diterima"]').keyup(function() {
                updatePembayaran();
            });

            // Fungsi untuk menghitung total harga
            function updateTotalHarga() {
                var totalHarga = 0;

                // Loop melalui setiap item
                $('div[id^="item-"]').each(function() {
                    var itemIndex = $(this).attr('id').split('-')[1];
                    var hargaSatuan = parseFloat($('#harga_satuan_' + itemIndex).val());
                    var jumlah = parseInt($('#jumlah_' + itemIndex).val());

                    // Hitung total harga untuk item saat ini
                    var totalItemHarga = hargaSatuan * jumlah;

                    // Tambahkan total harga item saat ini ke total harga keseluruhan
                    totalHarga += totalItemHarga;

                    // Perbarui total harga untuk item saat ini
                    $('#total_' + itemIndex).val(totalItemHarga);
                });

                // Perbarui nilai total harga yang harus dibayar
                $('#total_harus_dibayar').val(totalHarga);
            }

            // Fungsi untuk mengupdate pembayaran (kembalian atau kurangan)
            function updatePembayaran() {
                var tipePembayaran = $('select[name="tipe_pembayaran"]').val();
                var bayar = parseFloat($('input[name="uang_diterima"]').val());
                var harusDibayar = parseFloat($('#total_harus_dibayar').val());

                // Jika jenis pembayaran adalah "Cash"
                if (tipePembayaran === 'Cash') {
                    if (!isNaN(bayar) && bayar >= harusDibayar) {
                        var kembalian = bayar - harusDibayar;
                        $('input[name="kembalian"]').val(kembalian);
                        $('input[name="kurangan"]').val(0); // Reset nilai kurangan menjadi 0
                    } else {
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0 jika nilai bayar tidak valid
                    }
                }
                // Jika jenis pembayaran adalah "Debit"
                else if (tipePembayaran === 'Debit') {
                    if (!isNaN(bayar) && bayar < harusDibayar) {
                        var kurangan = harusDibayar - bayar;
                        $('input[name="kurangan"]').val(kurangan);
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0
                    } else {
                        $('input[name="kurangan"]').val(0); // Reset nilai kurangan menjadi 0 jika nilai bayar tidak valid
                    }
                }
            }

            // Fungsi untuk menyembunyikan atau menampilkan field kurangan atau kembalian berdasarkan tipe pembayaran
            function toggleFields() {
                var tipePembayaran = $('select[name="tipe_pembayaran"]').val();
                if (tipePembayaran === 'Cash') {
                    $('input[name="uang_diterima"]').closest('.form-group').show(); // Tampilkan field bayar jika memilih Cash
                    $('input[name="kembalian"]').closest('.form-group').show(); // Tampilkan field kembalian
                    $('input[name="kurangan"]').closest('.form-group').hide(); // Sembunyikan field kurangan
                } else if (tipePembayaran === 'Debit') {
                    $('input[name="uang_diterima"]').closest('.form-group').show(); // Tampilkan field bayar jika memilih Debit
                    $('input[name="kurangan"]').closest('.form-group').show(); // Tampilkan field kurangan
                    $('input[name="kembalian"]').closest('.form-group').hide(); // Sembunyikan field kembalian
                } else {
                    $('input[name="uang_diterima"], input[name="kembalian"], input[name="kurangan"]').val(0).closest('.form-group').hide(); // Sembunyikan field bayar, kembalian, dan kurangan jika belum memilih metode pembayaran
                }
            }


            // Panggil fungsi toggleFields untuk menyembunyikan atau menampilkan field sesuai dengan tipe pembayaran awal
            toggleFields();
        });
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

            $(document).on("input", ".nama", function() {
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
                        pelangganContainer.find(".nama").val(data.nama);
                    }
                });

                pelangganContainer.find(".result_pelanggan").empty(); // Mengganti itemContainer menjadi pelangganContainer
            });
        });
    </script>


</body>

</html>