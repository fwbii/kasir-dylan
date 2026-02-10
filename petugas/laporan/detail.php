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
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="card shadow border-0 col-md-8 mx-auto" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0"><i class="fas fa-info-circle me-2 text-primary"></i>Detail Transaksi #<?= $id; ?></h5>
                    <a href="index.php" class="btn btn-dark btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4 p-3 bg-light rounded-3">
                        <div class="col-6">
                            <small class="text-muted d-block fw-bold">PELANGGAN</small>
                            <p class="fw-bold text-uppercase m-0"><?= $data['NamaPelanggan']; ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block fw-bold">WAKTU TRANSAKSI</small>
                            <p class="fw-bold m-0"><?= date('d M Y, H:i', strtotime($data['TanggalPenjualan'])); ?></p>
                        </div>
                    </div>

                    <table class="table table-borderless align-middle">
                        <thead class="border-bottom">
                            <tr class="text-muted small">
                                <th>PRODUK</th>
                                <th class="text-center">HARGA</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">SUBTOTAL</th>
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
                                <td class="fw-bold"><?= $d['NamaProduk']; ?></td>
                                <td class="text-center">Rp <?= number_format($d['Harga']); ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $d['JumlahProduk']; ?></span></td>
                                <td class="text-end fw-bold text-primary">Rp <?= number_format($d['Subtotal']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <th colspan="3" class="text-end pt-3">TOTAL PEMBAYARAN</th>
                                <th class="text-end pt-3 h5 fw-bold text-success">Rp <?= number_format($data['TotalHarga']); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>