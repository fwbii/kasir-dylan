<?php 
session_start();
include '../../main/connect.php';

// Debug: aktifkan tampilkan error sementara untuk diagnosis
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$debug_start = microtime(true);

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - DANDYLAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        body {
            background: linear-gradient(135deg, #321f85 0%, #614eca 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Glass Morphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .glass-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            padding: 1.5rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Filter Card */
        .filter-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .filter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        }

        /* Modern Form Controls */
        .modern-input {
            border-radius: 12px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-label-modern {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 15px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-gradient-secondary {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%);
            color: white;
        }

        .btn-gradient-print {
            background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
            color: white;
        }

        /* Summary Cards */
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            transition: width 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .summary-card:hover::before {
            width: 100%;
            opacity: 0.1;
        }

        .summary-card.card-success::before {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .summary-card.card-info::before {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .summary-card.card-warning::before {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .summary-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8492a6;
            margin-bottom: 0.8rem;
        }

        .summary-value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .summary-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.15;
        }

        .icon-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .icon-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .icon-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        /* Modern Table */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.2rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .modern-table thead th:first-child {
            border-radius: 15px 0 0 15px;
        }

        .modern-table thead th:last-child {
            border-radius: 0 15px 15px 0;
        }

        .modern-table tbody tr {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .modern-table tbody td {
            padding: 1.3rem 1rem;
            border: none;
            vertical-align: middle;
        }

        .modern-table tbody tr td:first-child {
            border-radius: 15px 0 0 15px;
        }

        .modern-table tbody tr td:last-child {
            border-radius: 0 15px 15px 0;
        }

        /* Badge & Labels */
        .badge-modern {
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .customer-name {
            font-weight: 700;
            text-transform: uppercase;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .price-value {
            font-weight: 700;
            font-size: 1.1rem;
            color: #11998e;
        }

        /* Action Button */
        .btn-action-detail {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            color: white;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-action-detail:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
            color: white;
        }

        /* Print Header */
        .print-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 15px;
        }

        .print-header h2 {
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            opacity: 0.3;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-card:nth-child(1) { animation-delay: 0.1s; }
        .animate-card:nth-child(2) { animation-delay: 0.2s; }
        .animate-card:nth-child(3) { animation-delay: 0.3s; }

        /* Print Styles */
        @media print {
            .no-print { display: none !important; }
            body {
                background: white;
            }
            .glass-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            .modern-table tbody tr {
                box-shadow: none;
                border-bottom: 1px solid #eee;
            }
            .summary-card {
                break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .summary-value {
                font-size: 1.6rem;
            }

            .summary-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <div>
                    <h3 class="page-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Laporan Penjualan
                    </h3>
                    <p class="text-white small opacity-75 mb-0 mt-1">Analisis dan ringkasan transaksi penjualan</p>
                </div>
                <button class="btn btn-modern btn-gradient-print" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </button>
            </div>

            <!-- Filter Card -->
            <div class="glass-card mb-4 no-print">
                <div class="card-body p-4">
                    <form method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label-modern">
                                    <i class="fas fa-calendar-alt me-1"></i> Dari Tanggal
                                </label>
                                <input type="date" name="tgl_mulai" class="form-control modern-input" value="<?= $tgl_mulai ?>">
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label-modern">
                                    <i class="fas fa-calendar-check me-1"></i> Sampai Tanggal
                                </label>
                                <input type="date" name="tgl_selesai" class="form-control modern-input" value="<?= $tgl_selesai ?>">
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-modern btn-gradient-primary flex-grow-1">
                                        <i class="fas fa-filter me-2"></i>Filter Data
                                    </button>
                                    <a href="index.php" class="btn btn-modern btn-gradient-secondary">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php 
            $where = "";
            if($tgl_mulai != '' && $tgl_selesai != '') {
                $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
            }
            $summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml, AVG(TotalHarga) as rata FROM penjualan $where");
            $ds = mysqli_fetch_assoc($summary);
            ?>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6 animate-card">
                    <div class="summary-card card-success">
                        <div class="summary-label">
                            <i class="fas fa-money-bill-wave me-1"></i> Total Omset
                        </div>
                        <div class="summary-value text-success">
                            Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?>
                        </div>
                        <div class="summary-icon icon-success">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 animate-card">
                    <div class="summary-card card-info">
                        <div class="summary-label">
                            <i class="fas fa-shopping-cart me-1"></i> Total Transaksi
                        </div>
                        <div class="summary-value text-info">
                            <?= $ds['jml']; ?> Transaksi
                        </div>
                        <div class="summary-icon icon-info">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 animate-card">
                    <div class="summary-card card-warning">
                        <div class="summary-label">
                            <i class="fas fa-chart-bar me-1"></i> Rata-rata per Transaksi
                        </div>
                        <div class="summary-value text-warning">
                            Rp <?= number_format($ds['rata'] ?? 0, 0, ',', '.'); ?>
                        </div>
                        <div class="summary-icon icon-warning">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="glass-card">
                <!-- Print Header -->
                <div class="print-header d-none d-print-block">
                    <h2>LAPORAN PENJUALAN</h2>
                    <p class="text-muted mb-0">KASIR DYLAN</p>
                    <p class="text-muted">Tanggal Cetak: <?= date('d F Y, H:i'); ?> WIB</p>
                    <?php if($tgl_mulai && $tgl_selesai): ?>
                    <p class="text-muted">Periode: <?= date('d/m/Y', strtotime($tgl_mulai)); ?> - <?= date('d/m/Y', strtotime($tgl_selesai)); ?></p>
                    <?php endif; ?>
                    <hr>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th width="5%"><i class="fas fa-hashtag me-1"></i>No</th>
                                    <th><i class="fas fa-clock me-2"></i>Tanggal & Waktu</th>
                                    <th><i class="fas fa-user me-2"></i>Pelanggan</th>
                                    <th class="text-end"><i class="fas fa-money-bill-wave me-2"></i>Total Bayar</th>
                                    <th class="no-print text-center"><i class="fas fa-cog me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID $where ORDER BY TanggalPenjualan DESC");
                                
                                if(mysqli_num_rows($query) == 0) {
                                    echo "<tr>
                                            <td colspan='5' class='p-0'>
                                                <div class='empty-state'>
                                                    <div class='empty-state-icon'>
                                                        <i class='fas fa-inbox'></i>
                                                    </div>
                                                    <h5 class='text-muted'>Tidak Ada Data</h5>
                                                    <p class='text-muted small'>Tidak ada transaksi pada periode yang dipilih</p>
                                                </div>
                                            </td>
                                          </tr>";
                                }

                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td class="fw-bold text-muted"><?= $no++; ?></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold"><?= date('d/m/Y', strtotime($d['TanggalPenjualan'])); ?></span>
                                            <small class="text-muted"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="customer-name"><?= $d['NamaPelanggan']; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-value">Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="no-print text-center">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" 
                                           class="btn btn-action-detail"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail Transaksi">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>