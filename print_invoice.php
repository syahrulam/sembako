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

    // Memeriksa apakah query berhasil dieksekusi
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Query to get details associated with the transaction
            $detailQuery = "SELECT detail_transaksi.*, item.*, detail_transaksi.jumlah_satuan as jumlah
            FROM detail_transaksi
            INNER JOIN item ON detail_transaksi.id_item = item.id_item
            WHERE detail_transaksi.id_transaksi = ?";
            $detailStmt = $koneksi->prepare($detailQuery);
            $detailStmt->bind_param("s", $id_transaksi);
            $detailStmt->execute();
            $detailResult = $detailStmt->get_result();

            // Use fpdf to create a PDF
            class PDF extends FPDF
            {
                function Header()
                {
                    $this->SetFont('Arial', 'B', 20);
                    $this->Cell(0, 5, 'INVOICE', 0, 1, 'C');
                    $this->SetFont('Arial', 'B', 15);
                    $this->Cell(0, 10, 'Toko Sumber Jaya', 0, 1, 'C');
                    $this->Ln(10); // Add some space after title
                }

                function Footer()
                {
                    // Footer implementation (if needed)
                }
            }

            $pdf = new PDF();
            $pdf->AddPage('P', array(200, 200));

            // Output data from transaksi to PDF
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'No. Transaksi             : ' . $row['no_transaksi'], 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, 'Tanggal Transaksi     : ' . date('d F Y', strtotime($row['tanggal'])), 0, 1);
            $pdf->Cell(0, 10, 'Nama Pelanggan       : ' . ucwords($row['nama_pelanggan']), 0, 1);
            $pdf->Cell(0, 10, 'Sales                          : ' . $row['sales'], 0, 1);
            $pdf->Cell(0, 5, 'Tipe Pembayaran      : ' . $row['tipe_pembayaran'], 0, 1);

            // Output details from detail_transaksi to PDF
            if ($detailResult->num_rows > 0) {
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(30, 10, 'Item', 1, 0, 'C');
                $pdf->Cell(30, 10, 'Jenis Satuan', 1, 0, 'C');
                $pdf->Cell(40, 10, 'Jumlah Satuan', 1, 0, 'C');
                $pdf->Cell(40, 10, 'Harga', 1, 0, 'C');
                $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');


                $pdf->SetFont('Arial', '', 10);
                // Menampilkan detail transaksi 
                while ($detailRow = $detailResult->fetch_assoc()) {
                    $pdf->Cell(30, 10, $detailRow['nama_item'], 1, 0, 'C'); // Item
                    $pdf->Cell(30, 10, $detailRow['jenis_satuan'], 1, 0, 'C'); // Jenis Satuan
                    $pdf->Cell(40, 10, $detailRow['jumlah'], 1, 0, 'C'); // Jumlah Satuan
                    $pdf->Cell(40, 10, 'Rp.' . number_format($detailRow['harga_satuan'], 0, ',', '.'), 1, 0, 'C'); // Harga
                    $pdf->Cell(40, 10, 'Rp.' . number_format($detailRow['total'], 0, ',', '.'), 1, 1, 'C'); // Subtotal, 1 artinya pindah ke baris baru
                }
            }
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 10, 'Total Harga   : Rp.' . number_format($row['total_harga'], 0, ',', '.'), 0, 1);
            $pdf->Cell(0, 10, 'Bayar            : Rp.' . number_format($row['total_bayar'], 0, ',', '.'), 0, 1);
            if ($row['tipe_pembayaran'] == 'Cash') {
                $pdf->Cell(0, 10, 'Kembalian    : Rp.' . number_format($row['kembalian'], 0, ',', '.'), 0, 1);
            } else {
                $pdf->Cell(0, 10, "Kekurangan : Rp " . number_format($row['kekurangan'], 0, ',', '.'), 0, 1);
            }

            // Output the PDF to the browser
            $pdf->Output();
        } else {
            echo "Tidak ada data transaksi dengan ID tersebut.";
        }
    } else {
        echo "Error: " . $koneksi->error;
    }
} else {
    echo "Parameter id_transaksi tidak tersedia.";
}

// Menutup koneksi database
$koneksi->close();
