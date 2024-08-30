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
                $this->Ln(10);
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
        $pdf->Cell(0, 10, 'Faktur Pembelian', 0, 1,);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, 'No_transaksi: ' . $row['no_transaksi'], 0, 1);
        $pdf->Cell(0, 10, 'Tanggal: ' . date('d F Y', strtotime($row['tanggal'])), 0, 1);
        $pdf->Cell(0, 10, 'Nama Pelanggan: ' . ucwords($row['nama_pelanggan']), 0, 1);
        $pdf->Cell(0, 10, 'Sales: ' . $row['sales'], 0, 1);
        $pdf->Cell(0, 10, 'Tipe Pembayaran: ' . $row['tipe_pembayaran'], 0, 1);
        $pdf->Ln(10);

        // Jika ada detail transaksi, tampilkan dalam bentuk tabel
        if ($detailResult->num_rows > 0) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 10, 'Nama Item', 1);
            $pdf->Cell(30, 10, 'Jumlah', 1);
            $pdf->Cell(30, 10, 'Satuan', 1);
            $pdf->Cell(40, 10, 'Harga Satuan', 1);
            $pdf->Cell(40, 10, 'Total', 1);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);
            while ($detailRow = $detailResult->fetch_assoc()) {
                // Menentukan jenis satuan berdasarkan field jenis_satuan
                $satuan = $detailRow['jenis_satuan'] === 'Besar' 
                    ? $detailRow['jenis_satuan_besar'] 
                    : $detailRow['jenis_satuan_kecil'];

                $pdf->Cell(50, 10, $detailRow['nama_item'], 1);
                $pdf->Cell(30, 10, $detailRow['jumlah'], 1);
                $pdf->Cell(30, 10, $satuan, 1);
                $pdf->Cell(40, 10, 'Rp. ' . number_format($detailRow['harga_satuan'], 0, ',', '.'), 1);
                $pdf->Cell(40, 10, 'Rp. ' . number_format($detailRow['total'], 0, ',', '.'), 1);
                $pdf->Ln();
            }
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total Harga: Rp. ' . number_format($row['total_harga'], 0, ',', '.'), 0, 1);
        $pdf->Cell(0, 10, 'Bayar: Rp. ' . number_format($row['total_bayar'], 0, ',', '.'), 0, 1);

        if ($row['tipe_pembayaran'] === 'Cash') {
            $pdf->Cell(0, 10, 'Kembalian: Rp. ' . number_format($row['kembalian'], 0, ',', '.'), 0, 1);
        } else {
            $pdf->Cell(0, 10, 'Kekurangan: Rp. ' . number_format($row['kekurangan'], 0, ',', '.'), 0, 1);
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
