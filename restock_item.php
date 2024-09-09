<?php
function submitRestock($itemId, $restockQuantity) {
    include('koneksi/config.php');

    // Validasi input
    $itemId = intval($itemId);
    $restockQuantity = floatval($restockQuantity);

    // Ambil data item berdasarkan ID
    $sql = "SELECT item.*, kategori.kategori FROM item 
            INNER JOIN kategori ON item.kategori_id = kategori.id 
            WHERE item.id_item = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Menghitung stok baru untuk satuan besar
        $stokBaru = $row['jumlah_satuan_besar'] + $restockQuantity;
        
        // Menghitung total isi satuan kecil yang baru
        $totalIsiSatuanKecilBaru = $stokBaru * $row['jumlah_isi_satuan_besar'];

        // Update stok di tabel item
        $sqlUpdate = "UPDATE item 
                      SET jumlah_satuan_besar = ?, 
                          total_isi_satuan_kecil = ? 
                      WHERE id_item = ?";
        $stmtUpdate = $koneksi->prepare($sqlUpdate);
        $stmtUpdate->bind_param("dii", $stokBaru, $totalIsiSatuanKecilBaru, $itemId);
        $stmtUpdate->execute();

        // Simpan riwayat restock di tabel restock
        $sqlInsert = "INSERT INTO restock (id_item, kategori, tanggal, nama_item, stok_satuan_besar, isi_satuan_besar, totalnya, jumlah_restock) 
                      VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)";
        $totalnya = $totalIsiSatuanKecilBaru; // Totalnya di sini diambil dari perhitungan sebelumnya
        $stmtInsert = $koneksi->prepare($sqlInsert);
        $stmtInsert->bind_param("issddii", $itemId, $row['kategori'], $row['nama_item'], $stokBaru, $row['jumlah_isi_satuan_besar'], $totalnya, $restockQuantity);
        $stmtInsert->execute();
    }

    $stmt->close();
    $stmtUpdate->close();
    $stmtInsert->close();
    $koneksi->close();
}

// Proses data yang dikirim dari JavaScript
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['id_item'];
    $restockQuantity = $_POST['restock_quantity'];
    submitRestock($itemId, $restockQuantity);
    echo "Restock berhasil!";
}
?>
