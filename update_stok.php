<?php
include('koneksi/config.php');  // Koneksi ke database

if (isset($_POST['id_item']) && isset($_POST['jumlah']) && isset($_POST['jenis_satuan']) && isset($_POST['operasi'])) {
    $id_item = $_POST['id_item'];
    $jumlah = (int)$_POST['jumlah'];  // Jumlah unit yang diambil
    $jenis_satuan = $_POST['jenis_satuan'];  // Jenis satuan (besar/kecil)
    $operasi = $_POST['operasi'];  // Operasi (kurangi/tambah)

    // Ambil data stok saat ini dari database
    $query = "SELECT jumlah_satuan_besar, jumlah_isi_satuan_besar, total_isi_satuan_kecil FROM item WHERE id_item = '$id_item'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stokSatuanBesar = (float)$row['jumlah_satuan_besar'];  // stok unit besar
        $isiPerSatuanBesar = (int)$row['jumlah_isi_satuan_besar'];  // jumlah unit kecil dalam 1 unit besar
        $stokSatuanKecil = (int)$row['total_isi_satuan_kecil'];  // total stok unit kecil

        if ($jenis_satuan === 'Besar') {
            if ($operasi === 'kurangi') {
                $stokSatuanBesar -= $jumlah;
                $stokSatuanKecil -= $jumlah * $isiPerSatuanBesar;
            } elseif ($operasi === 'tambah') {
                $stokSatuanBesar += $jumlah;
                $stokSatuanKecil += $jumlah * $isiPerSatuanBesar;
            }
        } elseif ($jenis_satuan === 'Kecil') {
            if ($operasi === 'kurangi') {
                $stokSatuanKecil -= $jumlah;
                $penguranganBesar = $jumlah / $isiPerSatuanBesar;
                $stokSatuanBesar -= $penguranganBesar;
            } elseif ($operasi === 'tambah') {
                $stokSatuanKecil += $jumlah;
                $penambahanBesar = $jumlah / $isiPerSatuanBesar;
                $stokSatuanBesar += $penambahanBesar;
            }
        }

        // Update stok di database
        $updateQuery = "UPDATE item SET jumlah_satuan_besar = '$stokSatuanBesar', total_isi_satuan_kecil = '$stokSatuanKecil' WHERE id_item = '$id_item'";
        if (mysqli_query($koneksi, $updateQuery)) {
            echo "Stok berhasil diperbarui.";
        } else {
            echo "Error memperbarui stok.";
        }
    } else {
        echo "Item tidak ditemukan.";
    }
} else {
    echo "Data tidak lengkap.";
}
?>
