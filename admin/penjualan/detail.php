<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya Admin yang boleh masuk
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

$id = $_GET['id'];

// Ambil data penjualan & pelanggan
$query = mysqli_query($conn, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan, kembalikan ke index
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi Admin #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .sidebar-placeholder { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>

        <div class="container-fluid p-4">
            <div class="card shadow border-0 col-md-8 mx-auto">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center no-print">
                    <h5 class="fw-bold m-0"><i class="fas fa-info-circle me-2"></i>Rincian Nota #<?= $id; ?></h5>
                    <a href="index.php" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-0">KASIR DYLAN</h4>
                        <small class="text-muted text-uppercase">Laporan Detail Transaksi</small>
                        <hr>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted d-block">PELANGGAN:</small>
                            <p class="fw-bold"><?= $data['NamaPelanggan']; ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block">TANGGAL TRANSAKSI:</small>
                            <p class="fw-bold"><?= date('d F Y H:i', strtotime($data['TanggalPenjualan'])); ?></p>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-light">
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
                                <td><?= $d['NamaProduk']; ?></td>
                                <td class="text-center">Rp <?= number_format($d['Harga']); ?></td>
                                <td class="text-center"><?= $d['JumlahProduk']; ?></td>
                                <td class="text-end">Rp <?= number_format($d['Subtotal']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end py-3">TOTAL KESELURUHAN</th>
                                <th class="text-end py-3 text-primary h5 fw-bold">Rp <?= number_format($data['TotalHarga']); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="text-center mt-4 no-print">
                        <hr>
                        <button onclick="window.print()" class="btn btn-dark px-4">
                            <i class="fas fa-print me-2"></i> Cetak Salinan Nota
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>