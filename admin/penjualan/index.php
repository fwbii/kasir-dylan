<?php 
session_start();
include '../../main/connect.php';

// Proteksi Halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            font-size: 1.75rem;
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
            white-space: nowrap;
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

        /* Badge Modern */
        .badge-modern {
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-nota {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 700;
        }

        /* Alert Modern */
        .alert-modern {
            border-radius: 15px;
            border: none;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left: 4px solid #667eea;
            padding: 1rem 1.5rem;
            animation: slideInRight 0.5s ease-out;
        }

        /* Action Buttons */
        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-action:hover {
            transform: translateY(-3px) rotate(5deg);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-action-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-action-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

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
        }

        /* Label */
        .form-label-modern {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.3rem;
            }
            
            .modern-table {
                font-size: 0.85rem;
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
            <div class="glass-card">
                <div class="glass-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="page-title m-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Riwayat Transaksi
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Kelola dan pantau semua transaksi penjualan</p>
                    </div>
                    <?php if(isset($_GET['tgl_mulai'])): ?>
                        <button onclick="window.print()" class="btn btn-modern btn-gradient-print no-print">
                            <i class="fas fa-print me-2"></i> Cetak Laporan
                        </button>
                    <?php endif; ?>
                </div>
                
                <div class="card-body p-4">
                    <!-- Filter Section -->
                    <div class="filter-card mb-4 no-print">
                        <form method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-alt me-1"></i> Dari Tanggal
                                    </label>
                                    <input type="date" name="tgl_mulai" class="form-control modern-input" value="<?= $_GET['tgl_mulai'] ?? ''; ?>" required>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-check me-1"></i> Sampai Tanggal
                                    </label>
                                    <input type="date" name="tgl_selesai" class="form-control modern-input" value="<?= $_GET['tgl_selesai'] ?? ''; ?>" required>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-modern btn-gradient-primary flex-grow-1">
                                            <i class="fas fa-filter me-2"></i> Filter Data
                                        </button>
                                        <a href="index.php" class="btn btn-modern btn-gradient-secondary">
                                            <i class="fas fa-sync"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php 
                    $tgl_mulai = $_GET['tgl_mulai'] ?? '';
                    $tgl_selesai = $_GET['tgl_selesai'] ?? '';

                    if ($tgl_mulai != '' && $tgl_selesai != '') {
                        // Jika filter digunakan
                        $query_str = "SELECT * FROM penjualan 
                                      JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                      WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
                                      ORDER BY PenjualanID DESC";
                        echo "<div class='alert alert-modern no-print'>
                                <i class='fas fa-info-circle me-2'></i>
                                Menampilkan data dari <strong>" . date('d/m/Y', strtotime($tgl_mulai)) . "</strong> sampai <strong>" . date('d/m/Y', strtotime($tgl_selesai)) . "</strong>
                              </div>";
                    } else {
                        // Query default (Tampilkan semua)
                        $query_str = "SELECT * FROM penjualan 
                                      JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                      ORDER BY PenjualanID DESC";
                    }
                    $sql = mysqli_query($conn, $query_str);
                    ?>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>No. Nota</th>
                                    <th><i class="fas fa-clock me-2"></i>Tanggal & Waktu</th>
                                    <th><i class="fas fa-user me-2"></i>Nama Pelanggan</th>
                                    <th class="text-end"><i class="fas fa-money-bill-wave me-2"></i>Total Bayar</th>
                                    <th class="text-center no-print"><i class="fas fa-cog me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($sql) == 0){
                                    echo "<tr>
                                            <td colspan='5' class='p-0'>
                                                <div class='empty-state'>
                                                    <div class='empty-state-icon'>
                                                        <i class='fas fa-inbox'></i>
                                                    </div>
                                                    <h5 class='text-muted'>Tidak Ada Data</h5>
                                                    <p class='text-muted small'>Tidak ada transaksi ditemukan pada periode ini</p>
                                                </div>
                                            </td>
                                          </tr>";
                                }

                                while($d = mysqli_fetch_array($sql)){
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge-nota">#<?= str_pad($d['PenjualanID'], 5, '0', STR_PAD_LEFT); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold"><?= date('d/m/Y', strtotime($d['TanggalPenjualan'])); ?></span>
                                            <small class="text-muted"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-uppercase" style="color: #667eea; font-size: 0.95rem;">
                                            <?= $d['NamaPelanggan']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold" style="color: #11998e; font-size: 1.1rem;">
                                            Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center no-print">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="detail.php?id=<?= $d['PenjualanID']; ?>" 
                                               class="btn btn-action btn-action-info" 
                                               title="Lihat Detail"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="hapus.php?id=<?= $d['PenjualanID']; ?>" 
                                               class="btn btn-action btn-action-danger" 
                                               onclick="return confirm('Menghapus transaksi akan mengembalikan stok produk. Yakin?')" 
                                               title="Hapus Transaksi"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
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