<?php
// Include fpdf library
require('assets/fpdf/fpdf.php');

// Menghubungkan ke file config.php
include('koneksi/config.php');

// Memeriksa apakah parameter id_transaksi tersedia di URL
if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Query untuk mengambil data transaksi berdasarkan id_transaksi
    $query = "SELECT * FROM transaksi WHERE id_transaksi = ?";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $id_transaksi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Query untuk mendapatkan detail terkait transaksi
        $detailQuery = "
            SELECT 
                detail_transaksi.*, 
                item.nama_item,
                item.jenis_satuan_besar,
                item.jenis_satuan_kecil,
                detail_transaksi.jumlah_satuan AS jumlah,
                detail_transaksi.harga_satuan,
                detail_transaksi.total
            FROM 
                detail_transaksi
            INNER JOIN 
                item 
            ON 
                detail_transaksi.id_item = item.id_item
            WHERE 
                detail_transaksi.id_transaksi = ?
        ";

        $detailStmt = $koneksi->prepare($detailQuery);
        $detailStmt->bind_param("s", $id_transaksi);
        $detailStmt->execute();
        $detailResult = $detailStmt->get_result();

        // Membuat kelas PDF yang diperluas dari FPDF
        class PDF extends FPDF
        {
            function Header()
            {
                $imagePath = 'layout/logo-toko.png';
                $this->Image($imagePath, 10, 10, 30); // Sesuaikan ukuran gambar
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 10, 'Invoice', 0, 1, 'C');
                $this->Ln(5); // Kurangi jarak
            }
        }

        // Membuat instance PDF dan menambahkan halaman dengan ukuran A4
        $pdf = new PDF();
        $pdf->AddPage();

        // Mengatur header untuk PDF
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="Invoice_' . date('Ymd', strtotime($row['tanggal'])) . '_' . str_replace(' ', '_', $row['nama_pelanggan']) . '.pdf"');

        // Mengatur margin kiri dan kanan
        $pdf->SetLeftMargin(10); 
        $pdf->SetRightMargin(10);

        // Menampilkan informasi transaksi di PDF
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Faktur Pembelian', 0, 1); // Tinggi 8
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'No_transaksi: ' . $row['no_transaksi'], 0, 1); // Tinggi 6
        $pdf->Cell(0, 6, 'Tanggal: ' . date('d F Y', strtotime($row['tanggal'])), 0, 1); // Tinggi 6
        $pdf->Cell(0, 6, 'Nama Pelanggan: ' . ucwords($row['nama_pelanggan']), 0, 1); // Tinggi 6
        $pdf->Cell(0, 6, 'Sales: ' . $row['sales'], 0, 1); // Tinggi 6
        $pdf->Cell(0, 6, 'Tipe Pembayaran: ' . $row['tipe_pembayaran'], 0, 1); // Tinggi 6
        $pdf->Ln(2); // Kurangi jarak

        // Jika ada detail transaksi, tampilkan dalam bentuk tabel
        if ($detailResult->num_rows > 0) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 8, 'Nama Item', 1); // Tinggi 8
            $pdf->Cell(30, 8, 'Jumlah', 1); // Tinggi 8
            $pdf->Cell(30, 8, 'Satuan', 1); // Tinggi 8
            $pdf->Cell(40, 8, 'Harga Satuan', 1); // Tinggi 8
            $pdf->Cell(40, 8, 'Total', 1); // Tinggi 8
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);
            while ($detailRow = $detailResult->fetch_assoc()) {
                // Menentukan jenis satuan berdasarkan field jenis_satuan
                $satuan = $detailRow['jenis_satuan'] === 'Besar' 
                    ? $detailRow['jenis_satuan_besar'] 
                    : $detailRow['jenis_satuan_kecil'];

                $pdf->Cell(50, 6, $detailRow['nama_item'], 1); // Tinggi 6
                $pdf->Cell(30, 6, $detailRow['jumlah'], 1); // Tinggi 6
                $pdf->Cell(30, 6, $satuan, 1); // Tinggi 6
                $pdf->Cell(40, 6, 'Rp. ' . number_format($detailRow['harga_satuan'], 0, ',', '.'), 1); // Tinggi 6
                $pdf->Cell(40, 6, 'Rp. ' . number_format($detailRow['total'], 0, ',', '.'), 1); // Tinggi 6
                $pdf->Ln();
            }
        }

        $pdf->Ln(2); // Kurangi jarak
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Total Harga: Rp. ' . number_format($row['total_harga'], 0, ',', '.'), 0, 1); // Tinggi 8
        $pdf->Cell(0, 8, 'Bayar: Rp. ' . number_format($row['total_bayar'], 0, ',', '.'), 0, 1); // Tinggi 8

        if ($row['tipe_pembayaran'] === 'Cash') {
            $pdf->Cell(0, 8, 'Kembalian: Rp. ' . number_format($row['kembalian'], 0, ',', '.'), 0, 1); // Tinggi 8
        } else {
            $pdf->Cell(0, 8, 'Kekurangan: Rp. ' . number_format($row['kekurangan'], 0, ',', '.'), 0, 1); // Tinggi 8
        }

        $pdf->Output('Invoice_' . date('Ymd', strtotime($row['tanggal'])) . '_' . str_replace(' ', '_', $row['nama_pelanggan']) . '.pdf', 'I');
    } else {
        echo "Tidak ada data transaksi dengan ID tersebut.";
    }
} else {
    echo "Parameter id_transaksi tidak tersedia.";
}

// Menutup koneksi database
$koneksi->close();
?>
