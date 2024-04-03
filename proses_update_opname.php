<?php
// Sisipkan file koneksi jika diperlukan
include('koneksi/config.php');

// Periksa apakah ada data yang dikirim dari AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_opname']) && isset($_POST['keterangan'])) {
    include('koneksi/config.php');

    // Escape input untuk menghindari SQL injection
    $id_opname = mysqli_real_escape_string($koneksi, $_POST['id_opname']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Query SQL untuk update keterangan berdasarkan id opname
    $query_update = "UPDATE opname SET keterangan='$keterangan' WHERE id_opname='$id_opname'";

    // Eksekusi query
    if (mysqli_query($koneksi, $query_update)) {
        echo "success"; // Mengirimkan pesan berhasil ke JavaScript
    } else {
        echo "error"; // Mengirimkan pesan gagal ke JavaScript
    }

    // Tutup koneksi database
    mysqli_close($koneksi);
} else {
    // Jika tidak ada data yang dikirim, berikan pesan error
    echo "Data tidak lengkap";
}
?>