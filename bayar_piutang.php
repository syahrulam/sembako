<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $total_hutang_baru = $_POST['total_hutang'];

    // Periksa apakah nama pelanggan sudah ada dalam database
    $sql_cek_pelanggan = "SELECT * FROM pelanggan WHERE nama='$nama_pelanggan'";
    $result_cek_pelanggan = mysqli_query($koneksi, $sql_cek_pelanggan);

    if (mysqli_num_rows($result_cek_pelanggan) > 0) {
        $row = mysqli_fetch_assoc($result_cek_pelanggan);
        $id_pelanggan = $row['id'];

        // Cek apakah pelanggan sudah memiliki entri piutang
        $sql_cek_piutang = "SELECT * FROM piutang WHERE id_pelanggan='$id_pelanggan'";
        $result_cek_piutang = mysqli_query($koneksi, $sql_cek_piutang);

        if (mysqli_num_rows($result_cek_piutang) > 0) {
            // Jika sudah ada entri piutang, tambahkan total hutang baru
            $sql_update_total_hutang = "UPDATE piutang SET total_hutang=total_hutang+'$total_hutang_baru' WHERE id_pelanggan='$id_pelanggan'";
        } else {
            // Jika belum ada entri piutang, buat entri baru
            $sql_update_total_hutang = "INSERT INTO piutang (id_pelanggan, total_hutang) VALUES ('$id_pelanggan', '$total_hutang_baru')";
        }

        if (mysqli_query($koneksi, $sql_update_total_hutang)) {
            $message = "Total hutang berhasil diperbarui untuk pelanggan $nama_pelanggan.";
        } else {
            $message = "Terjadi kesalahan dalam memperbarui total hutang untuk pelanggan $nama_pelanggan.";
        }
    } else {
        $message = "Pelanggan dengan nama $nama_pelanggan tidak ditemukan dalam database.";
    }
}

// Tutup koneksi
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Total Hutang</title>
</head>

<body>
    <h2>Tambah Total Hutang</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nama_pelanggan">Nama Pelanggan:</label><br>
        <input type="text" id="nama_pelanggan" name="nama_pelanggan" required><br><br>
        <label for="total_hutang">Total Hutang Baru:</label><br>
        <input type="number" id="total_hutang" name="total_hutang" required><br><br>
        <input type="submit" value="Tambah Total Hutang">
    </form>
    <p><?php echo isset($message) ? $message : ''; ?></p>
</body>

</html>