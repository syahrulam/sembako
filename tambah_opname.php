<?php
include('koneksi/config.php');
session_start();

// Jika request melalui AJAX untuk pencarian item
if (isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];

    // Query untuk mencari item berdasarkan nama
    $query = "SELECT id_item, nama_item FROM item WHERE nama_item LIKE '%$searchTerm%' LIMIT 5";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Tampilkan hasil pencarian
            echo '<li data-id="'.$row['id_item'].'">'.$row['nama_item'].'</li>';
        }
    } else {
        echo "<li>Item tidak ditemukan</li>";
    }
    exit();
}

// Jika request dari form untuk penyimpanan data opname
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['searchTerm'])) {
    $id_item = $_POST['id_item'];
    $stok_opname = $_POST['stok_opname'];
    $jenis_satuan = $_POST['jenis_satuan'];  // Pilihan satuan besar atau kecil

    // Pastikan id_item ada
    if (!$id_item) {
        echo "Error: ID item tidak valid.";
        exit();
    }

    // Ambil data jumlah_isi_satuan_besar, jumlah_satuan_besar, dan total_isi_satuan_kecil dari tabel item
    $query_get_item = "SELECT jumlah_isi_satuan_besar, jumlah_satuan_besar, total_isi_satuan_kecil FROM item WHERE id_item = '$id_item'";
    $result = $koneksi->query($query_get_item);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $jumlah_isi_satuan_besar = $row['jumlah_isi_satuan_besar'];  // Jumlah isi satuan besar
        $jumlah_satuan_besar = $row['jumlah_satuan_besar'];          // Jumlah stok satuan besar
        $total_isi_satuan_kecil_db = $row['total_isi_satuan_kecil'];  // Jumlah stok total dalam satuan kecil dari database

        // Cek apakah menggunakan satuan besar atau kecil
        if ($jenis_satuan == "besar") {
            // Perhitungan berdasarkan satuan besar
            if ($stok_opname == $jumlah_satuan_besar) {
                $balance = "Benar";
            } elseif ($stok_opname < $jumlah_satuan_besar) {
                $kurang = $jumlah_satuan_besar - $stok_opname;
                // Pembulatan balance satuan besar
                $balance = "Kurang " . ceil($kurang);
                // Perhitungan balance satuan kecil sebelum pembulatan dikali jumlah_isi_satuan_besar
                $balance_small = "Kurang " . ($kurang * $jumlah_isi_satuan_besar);
            } else {
                $lebih = $stok_opname - $jumlah_satuan_besar;
                // Pembulatan balance satuan besar
                $balance = "Lebih " . ceil($lebih);
                // Perhitungan balance satuan kecil sebelum pembulatan dikali jumlah_isi_satuan_besar
                $balance_small = "Lebih " . ($lebih * $jumlah_isi_satuan_besar);
            }

        } else if ($jenis_satuan == "kecil") {
            // Perhitungan berdasarkan satuan kecil
            if ($stok_opname == $total_isi_satuan_kecil_db) {
                $balance_small = "Sesuai";
            } elseif ($stok_opname < $total_isi_satuan_kecil_db) {
                $kurang_small = $total_isi_satuan_kecil_db - $stok_opname;
                $balance_small = "Kurang $kurang_small";
                // Perhitungan balance satuan besar dibagi jumlah_isi_satuan_besar
                $balance = "Kurang " . ceil($kurang_small / $jumlah_isi_satuan_besar);
            } else {
                $lebih_small = $stok_opname - $total_isi_satuan_kecil_db;
                $balance_small = "Lebih $lebih_small";
                // Perhitungan balance satuan besar dibagi jumlah_isi_satuan_besar
                $balance = "Lebih " . ceil($lebih_small / $jumlah_isi_satuan_besar);
            }
        }

        // Simpan data opname ke tabel opname
        $query_simpan = "INSERT INTO opname (id_item, stok_opname, balance, balance_small, keterangan, jenis_pengecekan, tanggal) 
                         VALUES ('$id_item', '$stok_opname', '$balance', '$balance_small', '$keterangan', '$jenis_satuan', NOW())";

        if ($koneksi->query($query_simpan) === TRUE) {
            echo "Data berhasil disimpan.";
            header("Location: opname.php");
            exit();
        } else {
            echo "Error: " . $query_simpan . "<br>" . $koneksi->error;
        }
    } else {
        echo "Error: Data item tidak ditemukan.";
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('layout/head.php'); ?>
</head>
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
                                                    <input class="form-control id_item" type="hidden" name="id_item" />
                                                    <input class="form-control nama_item" type="text" name="nama_item" placeholder="Nama Item" required />
                                                    <ul class="result"></ul> <!-- Tempat untuk menampilkan rekomendasi item -->
                                                </div>

                                                <!-- Tambahan: Pilihan pengecekan berdasarkan satuan besar atau kecil -->
                                                <div class="form-group">
                                                    <label for="jenis_satuan">Jenis Satuan Pengecekan:</label>
                                                    <select class="form-control" name="jenis_satuan" required>
                                                        <option value="kecil">Satuan Kecil</option>
                                                        <option value="besar">Satuan Besar</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="stok_opname">Stok Opname:</label>
                                                    <input class="form-control" id="stok_opname" value="0" min="0" type="number" name="stok_opname" required />
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
                    // Fungsi untuk menangani pencarian item saat mengetik
                    function handleItemSearch(inputElement) {
                        var searchTerm = inputElement.val();
                        var resultContainer = inputElement.parent().find(".result");

                        if (searchTerm !== "") {
                            $.ajax({
                                type: "POST",
                                url: "tambah_opname.php", // Mengirim request ke file yang sama
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

                    // Event listener untuk input pada kolom nama item
                    $(document).on("input", ".nama_item", function() {
                        handleItemSearch($(this));
                    });

                    // Saat item dari hasil pencarian diklik, nilai item diinputkan ke form
                    $(document).on("click", ".result li", function() {
                        var selectedItem = $(this).text();
                        var idItem = $(this).data('id');
                        var itemContainer = $(this).closest(".item-container");

                        // Set nilai input untuk nama item dan id_item
                        itemContainer.find(".nama_item").val(selectedItem);
                        itemContainer.find(".id_item").val(idItem);

                        // Kosongkan hasil pencarian setelah memilih item
                        itemContainer.find(".result").empty();
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
