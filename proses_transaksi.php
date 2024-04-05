<?php
include('koneksi/config.php');

// Proses data transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data transaksi dari form
    $tgl_transaksi = date('Y-m-d H:i:s');
    $no_transaksi = $_POST['no_transaksi'];
    $total_harga = $_POST['total_harga'];
    $nama_pelanggan = $_POST['nama'];
    $tipe_pembayaran = $_POST['tipe_pembayaran'];
    $uang_diterima = $_POST['uang_diterima'];
    $kembalian = $_POST['kembalian'];
    $kurangan = $_POST['kurangan'];
    $nama_sales = $_POST['nama_sales'];

    // Query untuk menyimpan data transaksi ke dalam tabel transaksi
    $query_transaksi = "INSERT INTO transaksi (tanggal, no_transaksi, total_harga, nama_pelanggan, tipe_pembayaran, total_bayar, kembalian, kekurangan, sales)
                        VALUES ('$tgl_transaksi', '$no_transaksi', '$total_harga', '$nama_pelanggan', '$tipe_pembayaran', '$uang_diterima', '$kembalian', '$kurangan', '$nama_sales')";
                        
    // Eksekusi query transaksi
    if (mysqli_query($koneksi, $query_transaksi)) {
        echo "Data transaksi berhasil disimpan.";
    } else {
        echo "Error: " . $query_transaksi . "<br>" . mysqli_error($koneksi);
    }

    $id_transaksi = mysqli_insert_id($koneksi); // Menggunakan mysqli_insert_id() untuk mendapatkan ID transaksi yang baru saja di-insert

    // Proses data detail transaksi
    // Loop melalui setiap item dalam transaksi
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'nama_item_') === 0) {
            $itemIndex = substr($key, 10);

            // Ambil data detail transaksi dari form
            $nama_item = $_POST['nama_item_' . $itemIndex];
            $id_item = $_POST['id_item_' . $itemIndex];
            $jenis_satuan = $_POST['jenis_satuan_' . $itemIndex];
            $harga_satuan = $_POST['harga_satuan_' . $itemIndex];
            $jumlah = $_POST['jumlah_' . $itemIndex];
            $total_harga_item = $_POST['total_' . $itemIndex];

            // Query untuk menyimpan data detail transaksi ke dalam tabel detail_transaksi
            $query_detail_transaksi = "INSERT INTO detail_transaksi (id_transaksi, id_item, jenis_satuan, harga_satuan, jumlah_satuan, total)
                                       VALUES ('$id_transaksi', '$id_item', '$jenis_satuan', '$harga_satuan', '$jumlah', '$total_harga_item')";

            // Eksekusi query detail transaksi
            if (mysqli_query($koneksi, $query_detail_transaksi)) {
                echo "Data detail transaksi berhasil disimpan.";

                if ($jenis_satuan == 'Besar') {
                    // Ambil stok satuan besar dan jumlah isi satuan kecil dari database
                    $query_stok = "SELECT jumlah_satuan_besar, jumlah_isi_satuan_besar FROM item WHERE id_item = '$id_item'";
                    $result_stok = mysqli_query($koneksi, $query_stok);
                    $row_stok = mysqli_fetch_assoc($result_stok);
                    $stok_satuan_besar = $row_stok['jumlah_satuan_besar'];
                    $stok_satuan_kecil = $row_stok['jumlah_isi_satuan_besar'];

                    // Kurangi jumlah terjual dari stok satuan besar
                    $stok_baru_satuan_besar = $stok_satuan_besar - $jumlah;

                    $total_sisa_perpcs = ($stok_baru_satuan_besar * $stok_satuan_kecil);

                    $query_update_stok = "UPDATE item SET jumlah_satuan_besar = '$stok_baru_satuan_besar', total_isi_satuan_kecil = '$total_sisa_perpcs' WHERE id_item = '$id_item'";
                    mysqli_query($koneksi, $query_update_stok);
                } else if ($jenis_satuan == 'Kecil') {
                    // Ambil total isi satuan kecil dan jumlah isi satuan besar dari database
                    $query_stok = "SELECT total_isi_satuan_kecil, jumlah_isi_satuan_besar FROM item WHERE id_item = '$id_item'";
                    $result_stok = mysqli_query($koneksi, $query_stok);
                    $row_stok = mysqli_fetch_assoc($result_stok);
                    $stok_total_kecil = $row_stok['total_isi_satuan_kecil'];
                    $stok_satuan_besar = $row_stok['jumlah_isi_satuan_besar'];

                    // Kurangi jumlah terjual dari total isi satuan kecil
                    $stok_baru_kecil = $stok_total_kecil - $jumlah;

                    // Hitung total sisa perdus berdasarkan total isi satuan kecil dan jumlah isi satuan besar
                    $jumlah_satuan_besar = $stok_baru_kecil / $stok_satuan_besar;

                    // Update total isi satuan kecil dan total sisa perdus di database
                    $query_update_stok_kecil = "UPDATE item SET total_isi_satuan_kecil = '$stok_baru_kecil', jumlah_satuan_besar = '$jumlah_satuan_besar' WHERE id_item = '$id_item'";
                    mysqli_query($koneksi, $query_update_stok_kecil);
                }
            } else {
                echo "Error: " . $query_detail_transaksi . "<br>" . mysqli_error($koneksi);
            }
        }
    }

    // Tutup koneksi database
    mysqli_close($koneksi);
    header("Location: print_invoice.php?id_transaksi=" . $id_transaksi);
}
?>
