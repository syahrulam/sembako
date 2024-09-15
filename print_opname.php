<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Opname</title>
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            table {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <h2>Data Opname</h2>
                <button class="btn btn-primary mb-3 no-print" id="printButton">Cetak</button>
                <table id="opnameTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Item</th>
                            <th>Jumlah Fisik</th>
                            <th>Balance Satuan Besar</th>
                            <th>Balance Satuan Kecil</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('koneksi/config.php');
                        $query_opname = "SELECT opname.id_opname, opname.tanggal, item.nama_item, item.total_isi_satuan_kecil, opname.stok_opname, opname.balance, opname.balance_small, opname.keterangan, opname.jenis_pengecekan, item.jenis_satuan_kecil, item.jenis_satuan_besar
                         FROM opname
                         INNER JOIN item ON opname.id_item = item.id_item";
                        $result_opname = $koneksi->query($query_opname);
                        if ($result_opname->num_rows > 0) {
                            $no = 1;
                            while ($row_opname = $result_opname->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . date('d F Y', strtotime($row_opname['tanggal'])) . "</td>";
                                echo "<td>" . $row_opname['nama_item'] . "</td>";

                                if ($row_opname['jenis_pengecekan'] == 'besar') {
                                    echo "<td>" . $row_opname['stok_opname'] . " " . $row_opname['jenis_satuan_besar'] . "</td>";
                                    echo "<td>" . $row_opname['balance'] . " " . $row_opname['jenis_satuan_besar'] . "</td>";
                                    echo "<td>" . $row_opname['balance_small'] . " " . $row_opname['jenis_satuan_kecil'] . "</td>";
                                } else {
                                    echo "<td>" . $row_opname['stok_opname'] . " " . $row_opname['jenis_satuan_kecil'] . "</td>";
                                    echo "<td>" . $row_opname['balance'] . " " . $row_opname['jenis_satuan_besar'] . "</td>";
                                    echo "<td>" . $row_opname['balance_small'] . " " . $row_opname['jenis_satuan_kecil'] . "</td>";
                                }

                                echo "<td>" . $row_opname['keterangan'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada data stok opname</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Fungsi untuk mencetak tabel data dengan sedikit penundaan
        $(document).ready(function () {
            $('#printButton').click(function () {
                console.log("Print button clicked");  // Debugging: memastikan tombol diklik
                setTimeout(function () {
                    window.print();
                }, 500);  // Tambahkan penundaan 500ms
            });
        });
    </script>
</body>

</html>
