<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kasir DYLAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-report { border-radius: 15px; border: none; transition: 0.3s; }
        .table thead { background-color: #f8f9fa; }
        .filter-box { border-radius: 15px; background: #fff; border: 1px solid #eee; }
        @media print {
            .no-print, .sidebar, .btn, .filter-box { display: none !important; }
            .container-fluid { width: 100%; margin: 0; padding: 0; }
            .card-report { box-shadow: none !important; border: 1px solid #eee !important; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <h3 class="fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-success"></i>Laporan Penjualan</h3>
                <button class="btn btn-dark fw-bold shadow-sm" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </button>
            </div>

            <div class="card filter-box shadow-sm mb-4 no-print">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">DARI TANGGAL</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">SAMPAI TANGGAL</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                        </div>
                        <div class="col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary px-4 fw-bold">
                                <i class="fas fa-sync-alt me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <?php 
                // Query Ringkasan berdasarkan Filter
                $where = "";
                if($tgl_mulai != '' && $tgl_selesai != '') {
                    $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
                }
                
                $summary_omset = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
                $ds = mysqli_fetch_assoc($summary_omset);
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-report shadow-sm p-4 bg-white border-start border-success border-5">
                        <small class="text-muted fw-bold">TOTAL OMSET (PENDAPATAN)</small>
                        <h2 class="fw-bold text-success m-0">Rp <?= number_format($ds['total'] ?? 0); ?></h2>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card card-report shadow-sm p-4 bg-white border-start border-primary border-5">
                        <small class="text-muted fw-bold">JUMLAH TRANSAKSI</small>
                        <h2 class="fw-bold text-primary m-0"><?= $ds['jml']; ?> Transaksi</h2>
                    </div>
                </div>
            </div>

            <div class="card card-report shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4 d-none d-print-block">
                        <h2 class="fw-bold">LAPORAN PENJUALAN KASIR DYLAN</h2>
                        <?php if($tgl_mulai != ''): ?>
                            <p class="mb-0">Periode: <?= date('d/m/Y', strtotime($tgl_mulai)) ?> s/d <?= date('d/m/Y', strtotime($tgl_selesai)) ?></p>
                        <?php endif; ?>
                        <p class="small text-muted">Dicetak pada: <?= date('d-m-Y H:i'); ?></p>
                        <hr>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>NO</th>
                                    <th>TANGGAL</th>
                                    <th>PELANGGAN</th>
                                    <th class="text-end">TOTAL BAYAR</th>
                                    <th class="no-print text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM penjualan 
                                         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                         $where ORDER BY TanggalPenjualan DESC");
                                
                                if(mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Tidak ada data transaksi pada periode ini.</td></tr>";
                                }

                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><span class="text-muted small"><i class="far fa-calendar-alt me-1"></i></span> <?= date('d/m/Y', strtotime($d['TanggalPenjualan'])); ?></td>
                                    <td class="fw-bold"><?= $d['NamaPelanggan']; ?></td>
                                    <td class="fw-bold text-end text-primary">Rp <?= number_format($d['TotalHarga']); ?></td>
                                    <td class="no-print text-center">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-sm btn-light rounded-pill border shadow-sm px-3">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>