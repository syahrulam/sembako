<?php
include('koneksi/config.php');
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data transaksi utama dari form
    $tgl_transaksi = date('Y-m-d H:i:s');
    $no_transaksi = $_POST['no_transaksi'];
    $total_harga = $_POST['total_harga'];
    $nama_pelanggan = $_POST['nama'];
    $tipe_pembayaran = $_POST['tipe_pembayaran'];
    $uang_diterima = $_POST['uang_diterima'];
    $kembalian = $_POST['kembalian'];
    $kurangan = $_POST['kurangan'];
    $nama_sales = $_POST['nama_sales'];

    // Simpan data transaksi utama ke tabel transaksi
    $query_transaksi = "INSERT INTO transaksi (tanggal, no_transaksi, total_harga, nama_pelanggan, tipe_pembayaran, total_bayar, kembalian, kekurangan, sales)
                        VALUES ('$tgl_transaksi', '$no_transaksi', '$total_harga', '$nama_pelanggan', '$tipe_pembayaran', '$uang_diterima', '$kembalian', '$kurangan', '$nama_sales')";

    if (mysqli_query($koneksi, $query_transaksi)) {
        // Dapatkan ID transaksi yang baru saja disimpan
        $id_transaksi = mysqli_insert_id($koneksi);

        // Proses item yang dikirim dari form
        foreach ($_POST['items'] as $item) {
            $id_item = $item['id_item'];
            $jenis_satuan = $item['jenis_satuan'];
            $harga_satuan = $item['harga_satuan'];
            $jumlah = $item['jumlah'];
            $total_harga_item = $item['total_harga'];

            // Query untuk menyimpan data ke tabel detail_transaksi
            $query_detail_transaksi = "INSERT INTO detail_transaksi (id_transaksi, id_item, jenis_satuan, harga_satuan, jumlah, total_harga)
                                       VALUES ('$id_transaksi', '$id_item', '$jenis_satuan', '$harga_satuan', '$jumlah', '$total_harga_item')";

            if (!mysqli_query($koneksi, $query_detail_transaksi)) {
                echo "Error: " . $query_detail_transaksi . "<br>" . mysqli_error($koneksi);
                exit();
            }
        }

        // Cek apakah ada kekurangan pembayaran (piutang)
        if ($kurangan > 0) {
            // Simpan data piutang ke tabel piutang
            $query_piutang = "INSERT INTO piutang (id_transaksi, bayar, kurangan_hutang, tanggal, status)
                              VALUES ('$id_transaksi', '$uang_diterima', '$kurangan', '$tgl_transaksi', 'Belum Lunas')";

            if (!mysqli_query($koneksi, $query_piutang)) {
                echo "Error: " . $query_piutang . "<br>" . mysqli_error($koneksi);
                exit();
            }
        }

        // Redirect setelah transaksi berhasil
        if ($tipe_pembayaran === 'Cash') {
            header("Location: print_invoice.php?id_transaksi=$id_transaksi");
            exit(); // Pastikan untuk menghentikan eksekusi setelah header Location
        } else if ($tipe_pembayaran === 'Kredit') {
            header("Location: print_invoice_besar.php?id_transaksi=$id_transaksi");
            exit(); // Pastikan untuk menghentikan eksekusi setelah header Location
        }
        
    } else {
        echo "Error: " . $query_transaksi . "<br>" . mysqli_error($koneksi);
    }

    // Tutup koneksi
    mysqli_close($koneksi);
}
?>
