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
                detail_transaksi.jumlah AS jumlah,
                detail_transaksi.harga_satuan,
                detail_transaksi.total_harga
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

        class PDF extends FPDF
        {
            function Header()
            {
                $imagePath = 'layout/logo-toko.png';
                $this->Image($imagePath, 4, 4, 40); // Sesuaikan ukuran gambar agar sesuai dengan lebar kertas
                $this->SetFont('Arial', 'B', 7);
                $this->Ln(5); // Sesuaikan posisi vertikal setelah logo
            }
        }

        $pdf = new PDF();
        $pdf->AddPage('P', array(48, 210));
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="Invoice_' . date('Ymd', strtotime($row['tanggal'])) . '_' . str_replace(' ', '_', $row['nama_pelanggan']) . '.pdf"');

        $pdf->SetLeftMargin(2); 
        $pdf->SetRightMargin(2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, '', 0, 1);
        $pdf->Cell(0, 5, 'Faktur Pembelian', 0, 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, 'No_transaksi: ' . $row['no_transaksi'], 0, 1);
        $pdf->Cell(0, 5, 'Tanggal: ' . date('d F Y', strtotime($row['tanggal'])), 0, 1);
        $pdf->Cell(0, 5, 'Nama Pelanggan: ' . ucwords($row['nama_pelanggan']), 0, 1);
        $pdf->Cell(0, 5, 'Sales: ' . $row['sales'], 0, 1);
        $pdf->Cell(0, 5, 'Tipe Pembayaran: ' . $row['tipe_pembayaran'], 0, 1);
        $pdf->Ln(2);

        if ($detailResult->num_rows > 0) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(0, 5, 'Detail Item:', 0, 1);
            $pdf->SetFont('Arial', '', 9);
            while ($detailRow = $detailResult->fetch_assoc()) {
                // Menentukan jenis satuan berdasarkan field jenis_satuan
                if ($detailRow['jenis_satuan'] === 'Besar') {
                    $jenis_satuan = $detailRow['jenis_satuan_besar'];
                } elseif ($detailRow['jenis_satuan'] === 'Kecil') {
                    $jenis_satuan = $detailRow['jenis_satuan_kecil'];
                } else {
                    $jenis_satuan = 'Unknown';
                }

                $itemLine = $detailRow['nama_item'] . 
                            ' x ' . $detailRow['jumlah'] . ' ' . $jenis_satuan . // Menambahkan jenis_satuan
                            ' @ Rp.' . number_format($detailRow['harga_satuan'], 0, ',', '.') .
                            ' = Rp.' . number_format($detailRow['total_harga'], 0, ',', '.');
                $pdf->MultiCell(0, 5, $itemLine, 0, 'L');
            }
        }

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, 'Total Harga: Rp.' . number_format($row['total_harga'], 0, ',', '.'), 0, 1);
        $pdf->Cell(0, 5, 'Bayar: Rp.' . number_format($row['total_bayar'], 0, ',', '.'), 0, 1);

        if ($row['tipe_pembayaran'] === 'Cash') {
            $pdf->Cell(0, 5, 'Kembalian: Rp.' . number_format($row['kembalian'], 0, ',', '.'), 0, 1);
        } else {
            $pdf->Cell(0, 5, 'Kekurangan: Rp.' . number_format($row['kekurangan'], 0, ',', '.'), 0, 1);
        }

        $pdf->Output('Invoice_' . date('Ymd', strtotime($row['tanggal'])) . '_' . str_replace(' ', '_', $row['nama_pelanggan']) . '.pdf', 'I');
    } else {
        echo "Tidak ada data transaksi dengan ID tersebut.";
    }
} else {
    echo "Parameter id_transaksi tidak tersedia.";
}

$koneksi->close();
?>
