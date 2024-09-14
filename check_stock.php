<?php
include('koneksi/config.php');

if (isset($_POST['id_item']) && isset($_POST['jenis_satuan']) && isset($_POST['jumlah'])) {
    $id_item = $_POST['id_item'];
    $jenis_satuan = $_POST['jenis_satuan'];
    $jumlah = (int)$_POST['jumlah'];

    // Query untuk mendapatkan stok berdasarkan jenis satuan
    $query = "SELECT jumlah_satuan_besar, jumlah_isi_satuan_besar, total_isi_satuan_kecil FROM item WHERE id_item = '$id_item'";
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Ambil stok dan informasi satuan dari database
        $stokSatuanBesar = (float)$row['jumlah_satuan_besar'];
        $jumlahIsiSatuanBesar = (int)$row['jumlah_isi_satuan_besar'];
        $stokSatuanKecil = (int)$row['total_isi_satuan_kecil'];

        if ($jenis_satuan == "Besar") {
            // Cek jika stok besar mencukupi
            if ($stokSatuanBesar >= $jumlah) {
                echo json_encode(["status" => "success"]);
            } 
            // Jika stok satuan besar tidak cukup, cek apakah stok kecil bisa mencukupi
            else if ($stokSatuanKecil < $jumlahIsiSatuanBesar * $jumlah) {
                echo json_encode(["status" => "error", "message" => "Tidak ada stok besar, hanya ada satuan kecil yang tersisa!"]);
            } 
            // Stok besar tidak mencukupi, tapi stok kecil cukup
            else {
                echo json_encode(["status" => "error", "message" => "Stok satuan besar tidak mencukupi, tetapi Anda bisa mengambil dalam satuan kecil."]);
            }
        } 
        // Jika jenis satuan kecil yang dipilih
        else if ($jenis_satuan == "Kecil") {
            if ($stokSatuanKecil >= $jumlah) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Stok satuan kecil tidak mencukupi! Stok tersedia: $stokSatuanKecil"]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Item tidak ditemukan."]);
    }
}
?>
