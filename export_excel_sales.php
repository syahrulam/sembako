<?php
include('koneksi/config.php');

if (isset($_POST['export'])) {
    if (isset($_POST['bulanTahunMulai']) && isset($_POST['bulanTahunAkhir'])) {
        $bulanTahunMulai = $_POST['bulanTahunMulai'];
        $bulanTahunAkhir = $_POST['bulanTahunAkhir'];
        $namaPelanggan = $_POST['nama']; // Ambil nilai nama pelanggan

        // Parsing bulan dan tahun mulai
        list($tahunMulai, $bulanMulai) = explode('-', $bulanTahunMulai);

        // Parsing bulan dan tahun akhir
        list($tahunAkhir, $bulanAkhir) = explode('-', $bulanTahunAkhir);

        // Query untuk mengambil data transaksi berdasarkan rentang bulan dan tahun
        $query = "SELECT transaksi.*, detail_transaksi.*, item.*, detail_transaksi.jumlah_satuan as jumlah
                    FROM transaksi
                    INNER JOIN detail_transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi
                    INNER JOIN item ON detail_transaksi.id_item = item.id_item
                    WHERE (YEAR(transaksi.tanggal)*100 + MONTH(transaksi.tanggal)) BETWEEN ($tahunMulai*100 + $bulanMulai) AND ($tahunAkhir*100 + $bulanAkhir)";

        // Jika nama sales tidak kosong, tambahkan kondisi WHERE untuk filter nama sales
        if (!empty($namaPelanggan)) {
            $query .= " AND transaksi.nama_pelanggan LIKE '%$namaPelanggan%'";
        }

        $result = $koneksi->query($query);

        // Mengatur header untuk membuat file Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Data_Transaksi_Rentang_".$bulanTahunMulai."_sampai_".$bulanTahunAkhir.".xls");

        // Membuat tabel Excel dengan judul kolom
        echo "No Transaksi\tTanggal\tNama Pelanggan\tNama Item\tJenis Satuan\tJumlah\tHarga Jual\tTotal Per Satuan\n";

        // Isi data transaksi ke dalam file Excel
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ubah format tanggal menjadi yyyy-mm-dd
                $tanggal_excel = date('Y-m-d', strtotime($row['tanggal']));
                echo $row['no_transaksi']."\t".$tanggal_excel."\t".$row['nama_pelanggan']."\t".$row['nama_item']."\t".$row['jenis_satuan']."\t".$row['jumlah']."\t"."Rp. ".number_format($row['harga_jual'], 0, ',', '.')."\t"."Rp. ".number_format($row['total_per_satuan'], 0, ',', '.')."\n";
            }
        } else {
            echo "Tidak ada data yang ditemukan.";
        }
    }
}
?>
