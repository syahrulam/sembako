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
                                                    <div class="col-md-12 mb-4">
                                                        <button type="button" class="btn btn-success" id="btnTambahItem" onclick="addNewItem()">Transkasi Baru</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <button type="submit">Simpan</button>

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
            var idItem = selectedOption.val(); // Ambil id_item dari opsi yang dipilih

            // Set nilai id_item ke input id_item_${itemIndex}
            $('#id_item_' + itemIndex).val(idItem);

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
    <div class="row item-row items-container" id="item-${itemCounter}">
        <div class="col-md-4">
            <div class="form-group">
                <label for="item_${itemCounter}" class="text-dark">Item</label>
                <select class="form-control" name="nama_item_${itemCounter}" id="item_${itemCounter}" onchange="updateItemDetails(${itemCounter})" required>
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

        <input type="number" class="form-control id_item" name="id_item_${itemCounter}" id="id_item_${itemCounter}" style="display:none" />
        <div class="col-md-2">
            <div class="form-group">
                <label for="jenis_satuan_${itemCounter}" class="text-dark">Jenis Satuan</label>
                <div id="jenis_satuan_${itemCounter}"></div>
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



</body>

</html>