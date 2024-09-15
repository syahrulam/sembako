<?php
include('koneksi/config.php');

if (isset($_POST['export'])) {
    if (isset($_POST['bulanTahunMulai']) && isset($_POST['bulanTahunAkhir'])) {
        $bulanTahunMulai = $_POST['bulanTahunMulai'];
        $bulanTahunAkhir = $_POST['bulanTahunAkhir'];
        $namaSales = $_POST['namaSales']; 
        list($tahunMulai, $bulanMulai) = explode('-', $bulanTahunMulai);
        list($tahunAkhir, $bulanAkhir) = explode('-', $bulanTahunAkhir);

        $query = "SELECT transaksi.*, detail_transaksi.*, item.*, detail_transaksi.jumlah as jumlah
                    FROM transaksi
                    INNER JOIN detail_transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi
                    INNER JOIN item ON detail_transaksi.id_item = item.id_item
                    WHERE (YEAR(transaksi.tanggal)*100 + MONTH(transaksi.tanggal)) BETWEEN ($tahunMulai*100 + $bulanMulai) AND ($tahunAkhir*100 + $bulanAkhir)";

        if (!empty($namaSales)) {
            $query .= " AND transaksi.sales LIKE '%$namaSales%'";
        }

        $result = $koneksi->query($query);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Data_Transaksi_Rentang_".$bulanTahunMulai."_sampai_".$bulanTahunAkhir.".xls");

        echo "No Transaksi\tTanggal\tSales\tNama Pelanggan\tNama Item\tJenis Satuan\tJumlah\tHarga Jual\tTotal Per Satuan\n";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tanggal_excel = date('Y-m-d', strtotime($row['tanggal']));
                echo $row['no_transaksi']."\t".$tanggal_excel."\t".$row['sales']."\t".$row['nama_pelanggan']."\t".$row['nama_item']."\t".$row['jenis_satuan']."\t".$row['jumlah']."\t"."Rp. ".number_format($row['harga_satuan'], 0, ',', '.')."\t"."Rp. ".number_format($row['total_harga'], 0, ',', '.')."\n";
            }
        } else {
            echo "Tidak ada data yang ditemukan.";
        }
    }
}
?>
