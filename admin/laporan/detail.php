<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-detail { border-radius: 15px; border: none; }
        .table { border-radius: 10px; overflow: hidden; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="col-md-9 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="index.php" class="btn btn-white shadow-sm border rounded-pill px-3 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <h4 class="fw-bold m-0">Detail Transaksi #<?= $id; ?></h4>
                </div>

                <div class="card card-detail shadow-sm">
                    <div class="card-body p-4">
                        <div class="row mb-4 bg-light p-3 rounded-3 g-0">
                            <div class="col-md-6 border-end p-2">
                                <small class="text-muted d-block fw-bold">INFO PELANGGAN</small>
                                <h5 class="fw-bold text-primary m-0"><?= $data['NamaPelanggan']; ?></h5>
                            </div>
                            <div class="col-md-6 p-2 text-md-end">
                                <small class="text-muted d-block fw-bold">TANGGAL TRANSAKSI</small>
                                <h5 class="fw-bold m-0"><?= date('d F Y', strtotime($data['TanggalPenjualan'])); ?></h5>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                              JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                              WHERE PenjualanID = '$id'");
                                    while($d = mysqli_fetch_array($detail)){
                                    ?>
                                    <tr>
                                        <td><span class="fw-bold"><?= $d['NamaProduk']; ?></span></td>
                                        <td class="text-center">Rp <?= number_format($d['Harga']); ?></td>
                                        <td class="text-center"><span class="badge bg-secondary"><?= $d['JumlahProduk']; ?></span></td>
                                        <td class="text-end fw-bold">Rp <?= number_format($d['Subtotal']); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end py-3">TOTAL PEMBAYARAN</th>
                                        <th class="text-end py-3 text-primary h5 fw-bold">Rp <?= number_format($data['TotalHarga']); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>