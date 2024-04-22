<?php
include('koneksi/config.php');

// Memulai sesi
session_start();

// Memastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

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

    // Simpan data transaksi ke dalam tabel transaksi
    $query_transaksi = "INSERT INTO transaksi (tanggal, no_transaksi, total_harga, nama_pelanggan, tipe_pembayaran, total_bayar, kembalian, kekurangan, sales)
                        VALUES ('$tgl_transaksi', '$no_transaksi', '$total_harga', '$nama_pelanggan', '$tipe_pembayaran', '$uang_diterima', '$kembalian', '$kurangan', '$nama_sales')";

    if (mysqli_query($koneksi, $query_transaksi)) {
        // Mendapatkan ID transaksi yang baru saja disimpan
        $id_transaksi = mysqli_insert_id($koneksi);

        // Simpan ke dalam tabel piutang jika ada kekurangan
        if ($kurangan > 0) {
            $query_piutang = "INSERT INTO piutang (id_transaksi, nominal, kurangan_hutang, tanggal, status)
                  VALUES ('$id_transaksi', '$nominal', '$kurangan', '$tgl_transaksi', 'Belum Lunas')";
            if (!mysqli_query($koneksi, $query_piutang)) {
                echo "Error: " . $query_piutang . "<br>" . mysqli_error($koneksi);
            }
        }

        // Proses data detail transaksi
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'nama_item_') === 0) {
                // Identifikasi indeks item
                $itemIndex = substr($key, 10);

                // Ambil data detail transaksi dari form
                $id_item = $_POST['id_item_' . $itemIndex];
                $nama_item = $_POST['nama_item_' . $itemIndex];
                $jenis_satuan = $_POST['jenis_satuan_' . $itemIndex];
                $harga_satuan = $_POST['harga_satuan_' . $itemIndex];
                $jumlah = $_POST['jumlah_' . $itemIndex];
                $total_harga_item = $_POST['total_' . $itemIndex];

                // Simpan ke dalam tabel detail_transaksi
                $query_detail_transaksi = "INSERT INTO detail_transaksi (id_transaksi, id_item, jenis_satuan, harga_satuan, jumlah_satuan, total)
                                          VALUES ('$id_transaksi', '$id_item', '$jenis_satuan', '$harga_satuan', '$jumlah', '$total_harga_item')";
                
                if (mysqli_query($koneksi, $query_detail_transaksi)) {
                    // Update stok berdasarkan jenis satuan
                    if ($jenis_satuan === 'Besar') {
                        // Kurangi stok satuan besar
                        $query_stok = "SELECT jumlah_satuan_besar, jumlah_isi_satuan_besar FROM item WHERE id_item = '$id_item'";
                        $result_stok = mysqli_query($koneksi, $query_stok);
                        $row_stok = mysqli_fetch_assoc($result_stok);
                        $stok_baru_satuan_besar = $row_stok['jumlah_satuan_besar'] - $jumlah;

                        // Update stok satuan besar dan total isi satuan kecil
                        $total_isi_kecil = $stok_baru_satuan_besar * $row_stok['jumlah_isi_satuan_besar'];
                        $query_update_stok = "UPDATE item SET jumlah_satuan_besar = '$stok_baru_satuan_besar', total_isi_satuan_kecil = '$total_isi_kecil' WHERE id_item = '$id_item'";
                        mysqli_query($koneksi, $query_update_stok);
                    } else if ($jenis_satuan === 'Kecil') {
                        // Kurangi stok total isi satuan kecil
                        $query_stok = "SELECT total_isi_satuan_kecil, jumlah_isi_satuan_besar FROM item WHERE id_item = '$id_item'";
                        $result_stok = mysqli_query($koneksi, $query_stok);
                        $row_stok = mysqli_fetch_assoc($result_stok);
                        $stok_baru_kecil = $row_stok['total_isi_satuan_kecil'] - $jumlah;

                        // Hitung total jumlah satuan besar
                        $jumlah_satuan_besar = $stok_baru_kecil / $row_stok['jumlah_isi_satuan_besar'];
                        $query_update_stok_kecil = "UPDATE item SET total_isi_satuan_kecil = '$stok_baru_kecil', jumlah_satuan_besar = '$jumlah_satuan_besar' WHERE id_item = '$id_item'";
                        mysqli_query($koneksi, $query_update_stok_kecil);
                    }
                } else {
                    echo "Error: " . $query_detail_transaksi . "<br>" . mysqli_error($koneksi);
                }
            }
        }
    } else {
        echo "Error: " . $query_transaksi . "<br>" . mysqli_error($koneksi);
    }

    // Tutup koneksi dan redirect
    mysqli_close($koneksi);
    header("Location: print_invoice.php?id_transaksi=" . $id_transaksi);
}
?>
