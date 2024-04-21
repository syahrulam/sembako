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
                                                                    <div class="col-7">
                                                                        <input class="form-control nama" type="text" name="nama" placeholder="Nama Pelanggan" required />
                                                                        <div class="result_pelanggan"></div>
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
                                            </div>

                                            <div class="hidden-form" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <button type="button" class="btn btn-success" id="btnTambahItem" onclick="addNewItem()">Transkasi Baru</button>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 offset-md-8">
                                                        <div class="form-group">
                                                            <label for="total_harus_dibayar" class="text-dark" style="font-weight: bold;">Harus Dibayar
                                                                (Rp.)<span class='red'> *</span></label>
                                                            <input type="text" id="total_harus_dibayar" class="form-control"  style="font-weight: bold;" name="total_harga" value="Rp. 0" readonly />
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
                                                            <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary px-4" title="Save">
                                                                <i class="fa fa-check"></i> Bayar
                                                            </button>
                                                        </div>
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


        </div>
    </div>
    <?php include('layout/js.php'); ?>
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // Ketika dokumen selesai dimuat
        $(document).ready(function() {
            // Memeriksa apakah input nama pelanggan sudah diisi saat halaman dimuat
            checkNamaPelanggan();

            // Fungsi untuk memeriksa apakah input nama pelanggan sudah diisi
            function checkNamaPelanggan() {
                var namaPelanggan = $('input[name="nama"]').val(); // Mendapatkan nilai input nama pelanggan
                if (namaPelanggan === '') { // Jika input nama pelanggan kosong
                    alert('Isi nama pelanggan terlebih dahulu!');
                }
            }
        });
    </script>

    <!-- JavaScript -->
    <script>
        var itemCounter = 1;

        function addNewItem() {
            itemCounter++;

            var newItemHtml = `
            <div class="row item-row item-container" id="item-${itemCounter}">
                <div class="col-md-3">
                    <label for="nama_item_${itemCounter}" class="text-dark">Nama Item<span class='red'> *</span></label>
                    <input class="form-control nama_item" type="text" name="nama_item_${itemCounter}" required />
                    <input class="form-control id_item" type="text" name="id_item_${itemCounter}" required style="display:none;"/>
                    <!-- Result container for item search -->
                    <div class="result"></div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="jenis_satuan_${itemCounter}" class="text-dark">Jenis Satuan</label>
                        <select class="form-control jenis_satuan" name="jenis_satuan_${itemCounter}" id="jenis_satuan_${itemCounter}">
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="harga_satuan_${itemCounter}" class="text-dark">Harga Satuan (Rp.)</label>
                        <select class="form-control harga_satuan" name="harga_satuan_${itemCounter}" id="harga_satuan_${itemCounter}"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="jumlah_${itemCounter}" class="text-dark">Jumlah</label>
                        <input type="number" class="form-control jumlah" name="jumlah_${itemCounter}" id="jumlah_${itemCounter}" min="1" onchange="updateTotal(${itemCounter})" required />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="total_${itemCounter}" class="text-dark">Total Harga (Rp.)</label>
                        <input type="number" class="form-control" name="total_${itemCounter}" id="total_${itemCounter}" min="0" readonly />
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group" style="margin-top:30px;">
                        <button class="btn btn-danger remove-item" onclick="removeItem(${itemCounter})"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
            </div>
        `;

            $('#items-container').append(newItemHtml);

            // Memanggil fungsi updateItemDetails untuk menginisialisasi nilai jenis satuan dan harga satuan
            updateItemDetails(itemCounter);
        }

        function removeItem(itemId) {
            var removedItemTotal = parseFloat($('#total_' + itemId).val());
            $('#item-' + itemId).remove();

            var currentTotal = parseFloat($('#total_harus_dibayar').val());
            var newTotal = currentTotal - removedItemTotal;
            $('#total_harus_dibayar').val(newTotal);
            var uangDiterima = parseFloat($('input[name="uang_diterima"]').val());
            var kekurangan = newTotal - uangDiterima;
            $('#kurangan').val(kekurangan.toFixed(0));

            updatePembayaran();
        }

        $(document).ready(function() {
            // Event listener untuk setiap perubahan pada input item, jenis satuan, harga satuan, dan jumlah
            $('body').on('change', 'select[name^="item_"], select[name^="jenis_satuan_"], input[name^="harga_satuan_"], input[name^="jumlah_"]', function() {
                updateTotalHarga();
            });

            $(document).on("change", ".harga_satuan", function() {
                updateTotalHarga();
            });

            $('select[name="tipe_pembayaran"]').change(function() {
                toggleFields();
                updatePembayaran();
            });

            $('input[name="uang_diterima"]').keyup(function() {
                updatePembayaran();
            });


            function updateTotalHarga() {
                var totalHarga = 0;

                $('div[id^="item-"]').each(function() {
                    var itemIndex = $(this).attr('id').split('-')[1];
                    var hargaSatuan = parseFloat($('#harga_satuan_' + itemIndex).val());
                    var jumlah = parseInt($('#jumlah_' + itemIndex).val());
                    var totalItemHarga = hargaSatuan * jumlah;
                    totalHarga += totalItemHarga;
                    $('#total_' + itemIndex).val(totalItemHarga);
                });

                $('#total_harus_dibayar').val(totalHarga);
                updatePembayaran();
            }


            function updatePembayaran() {
                var tipePembayaran = $('select[name="tipe_pembayaran"]').val();
                var bayar = parseFloat($('input[name="uang_diterima"]').val());
                var harusDibayar = parseFloat($('#total_harus_dibayar').val());

                // Jika jenis pembayaran adalah "Cash"
                if (tipePembayaran === 'Cash') {
                    if (!isNaN(bayar) && bayar >= harusDibayar) {
                        var kembalian = bayar - harusDibayar;
                        $('input[name="kembalian"]').val(parseInt(kembalian)); // Menghapus angka di belakang koma
                        $('input[name="kurangan"]').val(0); // Reset nilai kurangan menjadi 0
                    } else {
                        $('input[name="kembalian"]').val("Uang Kurang");
                        var kekurangan = harusDibayar - bayar;
                        $('input[name="kurangan"]').val(parseInt(kekurangan)); // Menghapus angka di belakang koma dan tampilkan kekurangan
                    }
                }
                // Jika jenis pembayaran adalah "Debit"
                else if (tipePembayaran === 'Debit') {
                    if (!isNaN(bayar) && bayar < harusDibayar) {
                        var kurangan = harusDibayar - bayar;
                        $('input[name="kurangan"]').val(parseInt(kurangan)); // Menghapus angka di belakang koma dan tampilkan kekurangan
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0
                    } else {
                        $('input[name="kurangan"]').val("Uang Lebih"); // Tampilkan Uang Lebih
                        $('input[name="kembalian"]').val(0); // Reset nilai kembalian menjadi 0
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
                var inputNamaPelanggan = $(this).val();

                if (inputNamaPelanggan === '') {
                    // Jika input nama pelanggan kosong, sembunyikan list pelanggan dan komponen form tersembunyi
                    $('.result_pelanggan').empty();
                    $('.hidden-form').hide();
                } else {
                    handleItemSearch($(this)); // Lakukan pencarian
                }
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
                        pelangganContainer.find(".id_pelanggan").val(data.id);

                        // Menampilkan komponen form tersembunyi jika data pelanggan ditemukan
                        $('.hidden-form').show();
                    },
                    error: function() {
                        // Menyembunyikan komponen form tersembunyi jika data pelanggan tidak ditemukan
                        $('.hidden-form').hide();
                    }
                });

                pelangganContainer.find(".result_pelanggan").empty(); // Mengganti itemContainer menjadi pelangganContainer
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                var tipePembayaran = $('select[name="tipe_pembayaran"]').val();
                var totalHarga = parseFloat($('#total_harus_dibayar').val());
                var uangDiterima = parseFloat($('#uang_diterima').val());
                if (tipePembayaran === 'Cash' && uangDiterima < totalHarga) {
                    event.preventDefault();
                    alert('Jumlah yang dibayarkan kurang dari jumlah yang harus dibayar!');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on("input", ".nama_item", function() {
                var searchTerm = $(this).val();
                var resultContainer = $(this).parent().find(".result");

                if (searchTerm !== "") {
                    $.ajax({
                        type: "POST",
                        url: "search_item.php",
                        data: {
                            searchTerm: searchTerm
                        },
                        success: function(data) {
                            resultContainer.html(data);
                        }
                    });
                } else {
                    resultContainer.empty();
                }
            });

            $(document).on("click", ".result li", function() {
                var selectedItem = $(this).text();
                var itemContainer = $(this).closest(".item-container");

                itemContainer.find(".nama_item").val(selectedItem);

                $.ajax({
                    type: "POST",
                    url: "get_quantity.php",
                    data: {
                        selectedItem: selectedItem
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        itemContainer.find(".id_item").val(data.id_item);
                        var jenisSatuanSelect = itemContainer.find(".jenis_satuan");
                        jenisSatuanSelect.empty();
                        jenisSatuanSelect.append(`<option value="Besar">${data.jenis_satuan_besar}</option>`);
                        jenisSatuanSelect.append(`<option value="Kecil">${data.jenis_satuan_kecil}</option>`);

                        var hargaJualSatuanInput = itemContainer.find(".harga_satuan");
                        var jumlahItemInput = itemContainer.find(".jumlah");
                        var maxStok = data.jumlah_satuan_besar;

                        jenisSatuanSelect.on("change", function() {
                            var selectedJenisSatuan = $(this).val();
                            if (selectedJenisSatuan === "Besar") {
                                hargaJualSatuanInput.val(data.harga_jual_satuan_besar1);
                                maxStok = data.jumlah_satuan_besar;
                                // Update options for "Besar"
                                updateOptions(data.harga_jual_satuan_besar1, data.harga_jual_satuan_besar2, data.harga_jual_satuan_besar3);
                            } else if (selectedJenisSatuan === "Kecil") {
                                hargaJualSatuanInput.val(data.harga_jual_satuan_kecil1);
                                maxStok = data.total_isi_satuan_kecil;
                                // Update options for "Kecil"
                                updateOptions(data.harga_jual_satuan_kecil1, data.harga_jual_satuan_kecil2, data.harga_jual_satuan_kecil3);
                            }

                            jumlahItemInput.attr("max", maxStok);
                        }).change();

                        function updateOptions(option1, option2, option3) {
                            hargaJualSatuanInput.empty();
                            hargaJualSatuanInput.append(`<option value="${option1}">${option1}</option>`);
                            if (option2) {
                                hargaJualSatuanInput.append(`<option value="${option2}">${option2}</option>`);
                            }
                            if (option3) {
                                hargaJualSatuanInput.append(`<option value="${option3}">${option3}</option>`);
                            }
                        }


                    }
                });
                itemContainer.find(".result").empty();
            });

            $(document).on("click", ".remove-item", function() {
                var itemContainer = $(this).closest(".item-container");
                itemContainer.find(".jenis_satuan").empty();
                itemContainer.find(".harga_satuan").val("");
                itemContainer.find(".nama_item").val("");
                itemContainer.find(".id_item").val("");
                itemContainer.remove();
            });

            $(document).on("input", ".nama_item", function() {
                var itemContainer = $(this).closest(".item-container");
                var inputLength = $(this).val().length;
                if (inputLength === 0) {
                    itemContainer.find(".jenis_satuan").empty();
                    itemContainer.find(".harga_satuan").val("");
                    itemContainer.find(".id_item").val("");
                }
            });

            $(document).on("input", ".jumlah", function() {
                var jumlahItem = parseInt($(this).val());
                var maxStok = parseInt($(this).attr("max"));

                if (jumlahItem > maxStok) {
                    alert("Jumlah melebihi stok yang tersedia!");
                    $(this).val(maxStok);
                }

                if (jumlahItem <= 0) {
                    $(this).val(1);
                }
            });
        });
    </script>




</body>

</html>