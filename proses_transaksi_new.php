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
                // Update stok item di sini (perlu disesuaikan dengan logika bisnis yang sesuai)
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
