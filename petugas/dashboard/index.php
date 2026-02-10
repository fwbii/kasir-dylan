<?php 
session_start();
include '../../main/connect.php';

// Pastikan yang masuk adalah Petugas
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') {
    header("location:../../admin/dashboard/index.php");
}

$username = $_SESSION['username'];
$tgl_hari_ini = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Kasir Pro</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Welcome Hero Section */
        .welcome-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 25px;
            padding: 3rem;
            color: white;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
        }

        .welcome-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .welcome-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .welcome-time {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            margin-top: 1rem;
            backdrop-filter: blur(10px);
        }

        /* Modern Stats Card */
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .stats-card:hover::before {
            transform: translateY(0);
        }

        .stats-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .stats-icon-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .stats-icon-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            box-shadow: 0 10px 25px rgba(17, 153, 142, 0.3);
        }

        .stats-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stats-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%);
            border: 1px solid rgba(17, 153, 142, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #11998e;
        }

        /* Glass Card */
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

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        /* Time Badge */
        .time-badge {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Price Tag */
        .price-tag {
            font-size: 1.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Modern Button */
        .btn-modern {
            border-radius: 15px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
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
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: rgba(102, 126, 234, 0.2);
            margin-bottom: 1rem;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .quick-action-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 1rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .quick-action-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            color: white;
        }

        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 1.8rem;
            }
            
            .stats-value {
                font-size: 2rem;
            }

            .welcome-hero {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <!-- Welcome Hero -->
            <div class="welcome-hero mb-4">
                <div class="welcome-content">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h1 class="welcome-title">
                                Selamat Bekerja, <?= strtoupper($username); ?>! 👋
                            </h1>
                            <p class="welcome-subtitle mb-0">
                                Semangat melayani pelanggan hari ini. Berikan pelayanan terbaik dan jangan lupa cek stok barang secara berkala.
                            </p>
                            <div class="welcome-time">
                                <i class="fas fa-calendar-day"></i>
                                <span><?= strftime('%A, %d %B %Y', strtotime($tgl_hari_ini)); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="../penjualan/index.php" class="quick-action-btn">
                            <div class="quick-action-icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div class="fw-bold">Kasir</div>
                        </a>
                        <a href="../produk/index.php" class="quick-action-btn">
                            <div class="quick-action-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="fw-bold">Produk</div>
                        </a>
                        <a href="../laporan/index.php" class="quick-action-btn">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="fw-bold">Laporan</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6 animate-card">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-primary text-white">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <div class="stats-label">Transaksi Saya Hari Ini</div>
                        <?php 
                        $query_trx = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                        $data_trx = mysqli_fetch_assoc($query_trx);
                        ?>
                        <div class="stats-value"><?= $data_trx['total']; ?> <small style="font-size: 1rem; color: #a0aec0;">Transaksi</small></div>
                        <div class="mt-3">
                            <span class="stats-badge">
                                <i class="fas fa-receipt"></i>
                                <?= $data_trx['total']; ?> Nota Terjual
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 animate-card">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-success text-white">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stats-label">Total Penjualan</div>
                        <?php 
                        date_default_timezone_set('Asia/Jakarta');
                        $tgl_sekarang = date('Y-m-d');

                        $query_total = mysqli_query($conn, "SELECT SUM(TotalHarga) as total_all FROM penjualan");
                        $data_total = mysqli_fetch_assoc($query_total);
                        $total_all = $data_total['total_all'] ?? 0;

                        $query_harian = mysqli_query($conn, "SELECT SUM(TotalHarga) as total_hari FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_sekarang%'");
                        $data_harian = mysqli_fetch_assoc($query_harian);
                        $total_hari = $data_harian['total_hari'] ?? 0;
                        ?>
                        <div class="stats-value">Rp <?= number_format($total_all, 0, ',', '.'); ?></div>
                        <div class="mt-3">
                            <span class="stats-badge">
                                <i class="fas fa-chart-line"></i>
                                Hari ini: Rp <?= number_format($total_hari, 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="glass-card animate-card">
                <div class="glass-header">
                    <h5 class="section-title m-0">
                        <i class="fas fa-history"></i>
                        Transaksi Terakhir
                    </h5>
                    <p class="text-muted small mb-0 mt-1">5 transaksi paling baru</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-clock me-2"></i>Waktu</th>
                                    <th><i class="fas fa-user me-2"></i>Nama Pelanggan</th>
                                    <th><i class="fas fa-money-bill-wave me-2"></i>Total Bayar</th>
                                    <th class="text-center"><i class="fas fa-cog me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $log = mysqli_query($conn, "SELECT * FROM penjualan 
                                       JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                       ORDER BY PenjualanID DESC LIMIT 5");
                                
                                if(mysqli_num_rows($log) == 0) {
                                    echo "<tr>
                                            <td colspan='4' class='p-0'>
                                                <div class='empty-state'>
                                                    <div class='empty-state-icon'>
                                                        <i class='fas fa-receipt'></i>
                                                    </div>
                                                    <h6 class='text-muted'>Belum ada transaksi</h6>
                                                    <p class='text-muted small'>Mulai transaksi pertama Anda hari ini!</p>
                                                </div>
                                            </td>
                                          </tr>";
                                }

                                while($l = mysqli_fetch_array($log)){
                                ?>
                                <tr>
                                    <td>
                                        <span class="time-badge">
                                            <i class="fas fa-clock"></i>
                                            <?= date('H:i', strtotime($l['TanggalPenjualan'])); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-uppercase" style="color: #2d3748;"><?= $l['NamaPelanggan']; ?></td>
                                    <td>
                                        <span class="price-tag">Rp <?= number_format($l['TotalHarga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="../penjualan/detail.php?id=<?= $l['PenjualanID']; ?>" 
                                           class="btn btn-modern btn-gradient-primary btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
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

        // Set locale for Indonesian date
        <?php setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian_Indonesia.1252', 'id_ID', 'IND'); ?>
    </script>
</body>
</html>