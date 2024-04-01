<?php
include('koneksi/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jumlah_pembayaran = $_POST['jumlah_pembayaran'];

    // Ambil id_pelanggan dari tabel pelanggan berdasarkan nama_pelanggan
    $query_id_pelanggan = "SELECT id FROM pelanggan WHERE nama = '$nama_pelanggan'";
    $result_id_pelanggan = mysqli_query($koneksi, $query_id_pelanggan);
    $row_id_pelanggan = mysqli_fetch_assoc($result_id_pelanggan);
    $id_pelanggan = $row_id_pelanggan['id'];

    // Ambil total_hutang dari tabel piutang berdasarkan id_pelanggan
    $query_total_hutang = "SELECT total_hutang FROM piutang WHERE id_pelanggan = $id_pelanggan";
    $result_total_hutang = mysqli_query($koneksi, $query_total_hutang);
    $row_total_hutang = mysqli_fetch_assoc($result_total_hutang);
    $total_hutang = $row_total_hutang['total_hutang'];

    // Kurangi total_hutang dengan jumlah_pembayaran
    $total_hutang_setelah_cicilan = $total_hutang - $jumlah_pembayaran;

    // Simpan riwayat pembayaran
    $query_simpan_riwayat = "INSERT INTO pembayaran_hutang (id_pelanggan, jumlah_pembayaran) VALUES ($id_pelanggan, $jumlah_pembayaran)";
    $result_simpan_riwayat = mysqli_query($koneksi, $query_simpan_riwayat);

    if ($result_simpan_riwayat) {
        // Perbarui total_hutang di tabel piutang dengan total_hutang_setelah_cicilan
        $query_perbarui_total_hutang = "UPDATE piutang SET total_hutang = $total_hutang_setelah_cicilan WHERE id_pelanggan = $id_pelanggan";
        $result_perbarui_total_hutang = mysqli_query($koneksi, $query_perbarui_total_hutang);

        if ($result_perbarui_total_hutang) {
            echo "Pembayaran cicilan berhasil disimpan.";
            // Redirect atau tampilkan pesan sukses
        } else {
            echo "Error: " . mysqli_error($koneksi);
            // Tampilkan pesan error atau redirect ke halaman error
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
        // Tampilkan pesan error atau redirect ke halaman error
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Cicilan</title>
</head>

<body>
    <h2>Form Pembayaran Cicilan</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nama_pelanggan">Nama Pelanggan:</label>
        <select name="nama_pelanggan" id="nama_pelanggan">
            <?php
            // Ambil nama pelanggan dari tabel pelanggan
            $query_nama_pelanggan = "SELECT nama FROM pelanggan";
            $result_nama_pelanggan = mysqli_query($koneksi, $query_nama_pelanggan);
            while ($row_nama_pelanggan = mysqli_fetch_assoc($result_nama_pelanggan)) {
                echo "<option value='" . $row_nama_pelanggan['nama'] . "'>" . $row_nama_pelanggan['nama'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="jumlah_pembayaran">Jumlah Cicilan:</label>
        <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" min="0">
        <br><br>
        <input type="submit" value="Bayar Cicilan">
    </form>

    <?php
    // Tampilkan pesan kesalahan atau sukses
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    ?>
</body>

</html>