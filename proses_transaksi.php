<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = date('Y-m-d H:i:s');
    $no_transaksi = $_POST['no_transaksi'];
    $nama = $_POST['nama'];
    $total_harga = intval(str_replace(["Rp. ", "."], "", $_POST['total_harga']));
    $total_bayar = intval(str_replace(["Rp. ", "."], "", $_POST['uang_diterima']));
    $kembalian = floatval(str_replace(["Rp. ", "."], "", $_POST['kembalian']));
    $tipe_pembayaran = $_POST['tipe_pembayaran'];

    // Perbaikan 1: Tambahkan titik koma di sini
    if ($tipe_pembayaran == 'Cash'){
        $kekurangan = $_POST['kekurangan'] = 0;
    } else {
        $kekurangan = $_POST['kekurangan'];
    }
    
    $sales = $_POST['nama_sales'];

    $query = "INSERT INTO transaksi (no_transaksi, tanggal, nama_pelanggan, total_harga, total_bayar, kembalian, tipe_pembayaran, kekurangan, sales) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("sssiiddss", $no_transaksi, $tanggal, $nama, $total_harga, $total_bayar, $kembalian, $tipe_pembayaran, $kekurangan, $sales);

    // Execute the main transaction insertion
    $stmt->execute();

    // Fetch the id_transaksi after insertion
    $id_transaksi = $koneksi->insert_id;

    // Loop through items and insert into 'detail_transaksi' table
    for ($i = 1; isset($_POST['nama_item_' . $i]); $i++) {
        $id_item = $_POST['id_item_' . $i];
        $jumlah_satuan = $_POST['jumlah_' . $i];
        $total_per_satuan = $_POST['total_' . $i];

        // Insert data into the 'detail_transaksi' table using prepared statement
        // Perbaikan 2: Sesuaikan pengikatan parameter dengan jumlah parameter yang benar
        $detailQuery = "INSERT INTO detail_transaksi (id_transaksi, id_item, jumlah_satuan, total) 
                        VALUES (?, ?, ?, ?)";

        $detailStmt = $koneksi->prepare($detailQuery);
        $detailStmt->bind_param("ssssi", $id_transaksi, $id_item,$jumlah_satuan, $total_per_satuan);
        $detailStmt->execute();

        // Subtract the quantity from the 'jumlah_satuan' column in the 'item' table
        // Perbaikan 3: Kembalikan penggunaan update item setelah melakukan transaksi
        // $updateItemQuery = "UPDATE item SET jumlah_satuan = jumlah_satuan - ? WHERE id_item = ?";
        // $updateItemStmt = $koneksi->prepare($updateItemQuery);
        // $updateItemStmt->bind_param("ss", $jumlah_satuan, $id_item);
        // $updateItemStmt->execute();
    }

    header("Location: print_invoice_riwayat.php?id_transaksi=" . $id_transaksi);
}
?>
