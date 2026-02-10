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
    <title>Struk #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-nota { max-width: 450px; margin: 20px auto; border: 1px solid #eee; }
        .dashed-line { border-top: 1.5px dashed #333; margin: 15px 0; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card-nota { border: none; box-shadow: none; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body class="bg-light">

    <div class="container">
        <div class="card card-nota shadow-sm">
            <div class="card-body p-4">
                <div class="text-center">
                    <h5 class="fw-bold mb-0">KASIR DYLAN</h5>
                    <small>Pekanbaru, Riau</small><br>
                    <small>0896-2347-9137</small>
                    <div class="dashed-line"></div>
                </div>

                <div class="row small mb-2">
                    <div class="col-6">Nota: #<?= $id; ?></div>
                    <div class="col-6 text-end"><?= date('d/m/Y H:i', strtotime($data['TanggalPenjualan'])); ?></div>
                    <div class="col-12 text-uppercase fw-bold">Plg: <?= $data['NamaPelanggan']; ?></div>
                </div>

                <table class="table table-sm table-borderless small">
                    <tbody>
                        <?php 
                        $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                  JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                  WHERE PenjualanID = '$id'");
                        while($d = mysqli_fetch_array($detail)){
                        ?>
                        <tr>
                            <td><?= $d['NamaProduk']; ?> x <?= $d['JumlahProduk']; ?></td>
                            <td class="text-end">Rp <?= number_format($d['Subtotal']); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div class="dashed-line"></div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>TOTAL</span>
                    <span>Rp <?= number_format($data['TotalHarga']); ?></span>
                </div>
                <div class="text-center mt-4">
                    <p class="small">-- Terima Kasih --</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=INV-<?= $id; ?>" class="mb-2">
                </div>

                <div class="no-print mt-4 d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-primary btn-lg">CETAK STRUK</button>
                    <a href="index.php" class="btn btn-outline-secondary">TRANSAKSI BARU</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>