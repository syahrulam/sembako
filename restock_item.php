<?php
// Sertakan file koneksi
include('koneksi/config.php');

// Lakukan validasi input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan data yang dibutuhkan ada
    if (isset($_POST['id_item']) && isset($_POST['restock_quantity'])) {
        // Tangkap nilai dari form
        $id_item = $_POST['id_item'];
        $restock_quantity = intval($_POST['restock_quantity']); // Konversi ke integer

        // Pastikan jumlah restok tidak negatif
        if ($restock_quantity > 0) {
            // Lakukan query untuk memperbarui jumlah satuan
            $sql = "UPDATE item SET jumlah_satuan_besar = jumlah_satuan_besar + $restock_quantity, 
                    total_kulak = total_kulak + $restock_quantity,
                    total_isi_satuan_kecil = jumlah_satuan_besar * jumlah_isi_satuan_besar
                    WHERE id_item = $id_item";

            if ($koneksi->query($sql) === TRUE) {
                echo "Restok berhasil dilakukan.";
            } else {
                echo "Error: " . $sql . "<br>" . $koneksi->error;
            }
        } else {
            echo "Jumlah restok harus lebih besar dari 0.";
        }
    } else {
        echo "ID item dan jumlah restok harus disediakan.";
    }
}
