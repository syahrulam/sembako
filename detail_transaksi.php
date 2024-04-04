<?php include('layout/head.php'); ?>

<?php include('layout/head.php'); ?>

<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];
?>

<link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<link href='https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css' rel='stylesheet' type='text/css'>


<?php
include('koneksi/config.php');
$id_transaksi = $_GET['id_transaksi'];
$sql = "SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'";
$result = $koneksi->query($sql);
$row = $result->fetch_assoc();
$koneksi->close();
?>


<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <?php include('layout/navbar.php'); ?>
            </nav>
            <div class="main-sidebar sidebar-style-2" style="overflow-y: auto;">
                <?php include('layout/sidebar.php'); ?>
            </div>

            <div id="app">
                <div class="main-content">
                    <section class="section">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4>Detail Transaksi</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2">
                                                No Transaksi
                                            </div>
                                            <div class="col-3">
                                                <p class="font-weight-bold">: <?php echo $row['no_transaksi']; ?></p>
                                            </div>
                                            <div class="col-2">
                                                Nama Pelanggan
                                            </div>
                                            <div class="col-5">
                                                <p style="text-transform: capitalize;">: <?php echo $row['nama_pelanggan']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                Tanggal
                                            </div>
                                            <div class="col-3">
                                                <p>: <?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                                            </div>
                                            <div class="col-2">
                                                Sales
                                            </div>
                                            <div class="col-5">
                                                <p>: <?php echo $row['sales']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <table class="table">
                                                <thead class="table-warning">
                                                    <tr>
                                                        <th scope="col">Nama Item</th>
                                                        <th scope="col">Jenis Satuan</th>
                                                        <th scope="col">Jumlah Satuan</th>
                                                        <th scope="col">Harga Satuan</th>
                                                        <th scope="col">Total Per/satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    include('koneksi/config.php');
                                                    $id_transaksi = $_GET['id_transaksi'];
                                                    $query = "SELECT detail_transaksi.*, item.*, detail_transaksi.jumlah_satuan as jumlah
                                                    FROM detail_transaksi
                                                    INNER JOIN item ON detail_transaksi.id_item = item.id_item
                                                    WHERE detail_transaksi.id_transaksi = $id_transaksi";
                                                    $result = $koneksi->query($query);

                                                    // Memeriksa apakah query berhasil dieksekusi
                                                    if ($result) {
                                                        if ($result->num_rows > 0) {
                                                            $no = 1;
                                                            // Output data dari setiap baris
                                                            while ($data = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td>" . $data['nama_item'] . "</td>";
                                                                echo "<td>" . $data['jenis_satuan'] . "</td>";
                                                                echo "<td>" . $data['jumlah'] . "</td>";
                                                                echo "<td> Rp." . number_format($data['harga_satuan'], 0, ',', '.') . "</td>";
                                                                echo "<td> Rp." .  number_format($data['total'], 0, ',', '.') . "</td>";


                                                                echo "</tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='6'>Tidak ada data member.</td></tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>Error: " . $koneksi->error . "</td></tr>";
                                                    }

                                                    // Menutup koneksi database
                                                    $koneksi->close();
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div class="mb-4 px-4">
                                                <div class="row">
                                                    <div class="co-6">
                                                        <p><strong>Harga Total</strong></p>
                                                    </div>
                                                    <div class="col">
                                                        <p class="font-weight-bold"> : Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?> </p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="co-6">
                                                        <p><strong>Tipe Pembayaran</strong></p>
                                                    </div>
                                                    <div class="col">
                                                        <p class="font-weight-bold"> : <?php echo ($row['tipe_pembayaran']); ?> </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="co-6">
                                                        <p><strong>Uang diterima</strong></p>
                                                    </div>
                                                    <div class="col">
                                                        <p class="font-weight-bold"> : Rp. <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?> </p>
                                                    </div>
                                                </div>


                                                <div class="row">


                                                    <?php
                                                    if ($row['tipe_pembayaran'] == 'Cash') {
                                                        echo "Kembalian";
                                                    } elseif ($row['tipe_pembayaran'] == 'Debit') {
                                                        echo "Kekurangan";
                                                    } else {
                                                        echo "Metode pembayaran tidak valid";
                                                    }
                                                    ?>
                                                    </strong></p>

                                                    <div class="col">
                                                        <p class="font-weight-bold">: Rp. <?php echo number_format($row['tipe_pembayaran'] == 'Cash' ? $row['kembalian'] : $row['kekurangan'], 0, ',', '.'); ?></p>
                                                    </div>
                                                </div>





                                            </div>

                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <?php include('layout/js.php'); ?>

</body>

</html>