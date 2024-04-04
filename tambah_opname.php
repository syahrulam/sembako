<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_item = $_POST['id_item'];
    $stok_opname = $_POST['stok_opname'];
    $total_isi_satuan_kecil = $_POST['total_isi_satuan_kecil'];

    // Ambil data jumlah_isi_satuan_besar dari database
    $query_get_jumlah_isi_satuan_besar = "SELECT jumlah_isi_satuan_besar FROM item WHERE id_item = '$id_item'";
    $result = $koneksi->query($query_get_jumlah_isi_satuan_besar);
    $row = $result->fetch_assoc();
    $jumlah_isi_satuan_besar = $row['jumlah_isi_satuan_besar'];

    // Hitung nilai stok opname yang setara dengan jumlah_satuan_besar dalam database
    $stok_opname_setara = $stok_opname / $jumlah_isi_satuan_besar;
    // Konversi ke satuan kecil
    $stok_opname_satuan_kecil = $stok_opname_setara * $jumlah_isi_satuan_besar;
    // Hitung nilai balance small
    $balance_small = $stok_opname_satuan_kecil - $total_isi_satuan_kecil;
    // Ambil nilai jumlah_satuan_besar dari database
    $query_get_jumlah_satuan_besar = "SELECT jumlah_satuan_besar FROM item WHERE id_item = '$id_item'";
    $result = $koneksi->query($query_get_jumlah_satuan_besar);
    $row = $result->fetch_assoc();
    $jumlah_satuan_besar = $row['jumlah_satuan_besar'];

    // Validasi
    if ($stok_opname_setara == $jumlah_satuan_besar) {
        $balance = "Benar";
    } elseif ($stok_opname_setara < $jumlah_satuan_besar) {
        $kurang = $jumlah_satuan_besar - $stok_opname_setara;
        $balance = "Kurang $kurang";
    } else {
        $lebih = $stok_opname_setara - $jumlah_satuan_besar;
        $balance = "Lebih $lebih";
    }
    // Validasi untuk balance_small
    if ($stok_opname_satuan_kecil == $total_isi_satuan_kecil) {
        $balance_small = "Sesuai";
    } elseif ($stok_opname_satuan_kecil < $total_isi_satuan_kecil) {
        $kurang_small = $total_isi_satuan_kecil - $stok_opname_satuan_kecil;
        $balance_small = "Kurang $kurang_small";
    } else {
        $lebih_small = $stok_opname_satuan_kecil - $total_isi_satuan_kecil;
        $balance_small = "Lebih $lebih_small";
    }


    // Simpan data opname ke database
    $query_simpan = "INSERT INTO opname (id_item, stok_opname, balance, balance_small, keterangan) VALUES ('$id_item', '$stok_opname', '$balance', '$balance_small', 'Tulis Keterangan')";
    if ($koneksi->query($query_simpan) === TRUE) {
        echo "Data berhasil disimpan.";
        header("Location: opname.php");
        exit();
    } else {
        echo "Error: " . $query_simpan . "<br>" . $koneksi->error;
    }
} else {
}

$koneksi->close();
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
                                <!-- Form Cek Stock Opname -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Cek Stock Opname</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="tambah_opname.php">
                                            <div class="item-container">
                                                <div class="form-group">
                                                    <label for="nama_item">Nama Item:</label>
                                                    <input class="form-control id_item" type="text" name="id_item" style="display: none;" />
                                                    <input class="form-control total_isi_satuan_kecil" type="text" name="total_isi_satuan_kecil" style="display: none;" />
                                                    <input class="form-control nama_item" type="text" name="nama_item" placeholder="Nama Item" required />
                                                    <div class="result"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="total_isi_satuan_kecil">Stok Opname:</label>
                                                    <input class="form-control" id="nilai" value="0" min="0" type="number" name="stok_opname" required />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Cek Stok Opname</button>
                                        </form>
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
                    function handleItemSearch(inputElement) {
                        var searchTerm = inputElement.val();
                        var resultContainer = inputElement.parent().find(".result");

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
                    }

                    $(document).on("input", ".nama_item", function() {
                        handleItemSearch($(this));
                    });

                    $(document).on("click", ".result li", function() {
                        var selectedItem = $(this).text();
                        var itemContainer = $(this).closest(".item-container");
                        itemContainer.find(".nama_item").val(selectedItem);

                        $.ajax({
                            type: "POST",
                            url: "get_opname.php",
                            data: {
                                selectedItem: selectedItem
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                itemContainer.find(".id_item").val(data.id_item);
                                itemContainer.find(".total_isi_satuan_kecil").val(data.total_isi_satuan_kecil);
                            }
                        });

                        itemContainer.find(".result").empty();
                    });
                });
            </script>

        </div>
    </div>
</body>

</html>