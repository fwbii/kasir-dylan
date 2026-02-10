<?php 
session_start();
// Proteksi halaman: Jika belum login, lempar ke halaman login
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
include '../../main/connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Modern Stats Card */
        .stats-card {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
        }

        .stats-card:hover::before {
            transform: translateY(0);
        }

        .stats-card .card-body {
            position: relative;
            z-index: 1;
            padding: 2rem;
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: rotate(10deg) scale(1.1);
            background: rgba(255, 255, 255, 0.3);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .stats-label {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Glass Morphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .glass-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            padding: 1.5rem;
        }

        /* Modern Table */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .modern-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .modern-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .modern-table tbody tr {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .modern-table tbody td {
            padding: 1.2rem 1rem;
            border: none;
            vertical-align: middle;
        }

        .modern-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .modern-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Badge Modern */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Button Modern */
        .btn-modern {
            border-radius: 15px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Header Dashboard */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
        .animate-card:nth-child(4) { animation-delay: 0.4s; }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-number {
                font-size: 2rem;
            }
            
            .stats-icon {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="container-fluid p-4" style="margin-left: 0;">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <div class="welcome-text">Dashboard Admin</div>
                    <p class="text-muted mb-0 mt-2">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?php echo date('l, d F Y'); ?>
                    </p>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Selamat Datang,</div>
                    <h4 class="mb-0 fw-bold" style="color: #667eea;">
                        <i class="fas fa-user-circle me-2"></i><?php echo $_SESSION['username']; ?>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6 animate-card">
                <div class="card stats-card text-white shadow-lg" style="background: var(--primary-gradient);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Total Produk</div>
                            <?php 
                                $ambil_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                                $data_produk  = mysqli_fetch_assoc($ambil_produk);
                                echo "<h2 class='stats-number'>" . $data_produk['total'] . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-card">
                <div class="card stats-card text-white shadow-lg" style="background: var(--success-gradient);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Penjualan Hari Ini</div>
                            <?php 
                                date_default_timezone_set('Asia/Jakarta'); 
                                $tgl_hari_ini = date('Y-m-d');
                                $query_hari_ini = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                                $data_hari_ini = mysqli_fetch_assoc($query_hari_ini);
                                echo "<h2 class='stats-number'>" . ($data_hari_ini['total'] ?? 0) . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-card">
                <div class="card stats-card text-white shadow-lg" style="background: var(--warning-gradient);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Total Pelanggan</div>
                            <?php 
                                $query_plg = mysqli_query($conn, "SELECT DISTINCT PelangganID FROM penjualan");
                                $jml_plg = mysqli_num_rows($query_plg);
                                echo "<h2 class='stats-number'>$jml_plg</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-card">
                <div class="card stats-card text-white shadow-lg" style="background: var(--danger-gradient);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Total Petugas</div>
                            <?php 
                                $hitung_user = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user");
                                $hasil_user = mysqli_fetch_assoc($hitung_user);
                                echo "<h2 class='stats-number'>" . $hasil_user['total'] . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-user-shield fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Table -->
        <div class="glass-card">
            <div class="glass-header">
                <h5 class="m-0 fw-bold" style="color: #667eea;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Stok Barang Hampir Habis
                </h5>
                <p class="text-muted small mb-0 mt-1">Produk dengan stok kurang dari 10 unit</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID Produk</th>
                                <th><i class="fas fa-box me-2"></i>Nama Produk</th>
                                <th><i class="fas fa-money-bill-wave me-2"></i>Harga</th>
                                <th><i class="fas fa-layer-group me-2"></i>Stok</th>
                                <th class="text-center"><i class="fas fa-cog me-2"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stok_low = mysqli_query($conn, "SELECT * FROM produk WHERE Stok < 10 ORDER BY Stok ASC");
                            if(mysqli_num_rows($stok_low) > 0) {
                                while($d = mysqli_fetch_assoc($stok_low)){
                            ?>
                            <tr>
                                <td class="fw-bold text-muted"><?php echo $d['ProdukID']; ?></td>
                                <td class="fw-semibold"><?php echo $d['NamaProduk']; ?></td>
                                <td class="text-success fw-bold">Rp <?php echo number_format($d['Harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge badge-modern bg-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i><?php echo $d['Stok']; ?> Unit
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="../produk/edit.php?id=<?= $d['ProdukID']; ?>" class="btn btn-modern btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i> Update
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-4'>
                                        <i class='fas fa-check-circle fa-3x text-success mb-3'></i>
                                        <p class='text-muted'>Semua stok produk aman!</p>
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../template/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>