<?php 
session_start();
include '../../main/connect.php';
// Cek login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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

        /* Modern Button */
        .btn-modern {
            border-radius: 20px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
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
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            border-radius: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.8rem 1.5rem 0.8rem 3rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
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

        /* Product Name */
        .product-name {
            font-weight: 700;
            font-size: 1rem;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .product-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 1.2rem;
        }

        /* Price Tag */
        .price-tag {
            font-weight: 700;
            font-size: 1.1rem;
            color: #11998e;
        }

        /* Stock Badge */
        .stock-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            min-width: 70px;
            display: inline-block;
            text-align: center;
        }

        .stock-low {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            animation: pulse 2s infinite;
        }

        .stock-ok {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Action Buttons */
        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-action:hover {
            transform: translateY(-3px) rotate(5deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-action-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-action-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        /* Total Assets Card */
        .assets-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 1.5rem;
            color: white;
            margin-top: 1rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: slideInUp 0.6s ease-out;
        }

        .assets-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .assets-value {
            font-size: 2rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Summary */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 1.2rem;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #667eea;
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 0.3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.3rem;
            }
            
            .modern-table {
                font-size: 0.85rem;
            }

            .product-icon {
                display: none;
            }

            .assets-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>

        <div class="container-fluid p-4">
            <div class="glass-card">
                <div class="glass-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="page-title m-0">
                            <i class="fas fa-box me-2"></i>Manajemen Stok Produk
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Kelola inventaris dan stok barang dagangan</p>
                    </div>
                    <a href="tambah.php" class="btn btn-modern btn-gradient-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Produk
                    </a>
                </div>

                <div class="card-body p-4">
                    <?php 
                    $total_produk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
                    $stok_rendah = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk WHERE Stok < 10"));
                    $total_aset = 0;
                    $query_aset = mysqli_query($conn, "SELECT SUM(Harga * Stok) as total FROM produk");
                    $data_aset = mysqli_fetch_assoc($query_aset);
                    $total_aset = $data_aset['total'] ?? 0;
                    ?>

                    <!-- Stats Summary -->
                    <div class="stats-summary">
                        <div class="stat-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-value"><?= $total_produk; ?></div>
                                    <div class="stat-label">Total Produk</div>
                                </div>
                                <i class="fas fa-boxes fa-2x" style="color: rgba(102, 126, 234, 0.2);"></i>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-value text-danger"><?= $stok_rendah; ?></div>
                                    <div class="stat-label">Stok Rendah</div>
                                </div>
                                <i class="fas fa-exclamation-triangle fa-2x" style="color: rgba(250, 112, 154, 0.2);"></i>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-value">Rp <?= number_format($total_aset, 0, ',', '.'); ?></div>
                                    <div class="stat-label">Nilai Inventaris</div>
                                </div>
                                <i class="fas fa-wallet fa-2x" style="color: rgba(17, 153, 142, 0.2);"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari produk berdasarkan nama...">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table modern-table" id="productTable">
                            <thead>
                                <tr>
                                    <th width="5%"><i class="fas fa-hashtag me-1"></i>No</th>
                                    <th><i class="fas fa-tag me-2"></i>Nama Produk</th>
                                    <th><i class="fas fa-money-bill-wave me-2"></i>Harga Jual</th>
                                    <th class="text-center"><i class="fas fa-layer-group me-2"></i>Stok</th>
                                    <th class="text-center"><i class="fas fa-cog me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                                
                                if(mysqli_num_rows($query) == 0){
                                    echo "<tr>
                                            <td colspan='5' class='p-0'>
                                                <div class='empty-state'>
                                                    <div class='empty-state-icon'>
                                                        <i class='fas fa-box-open'></i>
                                                    </div>
                                                    <h5 class='text-muted'>Belum Ada Produk</h5>
                                                    <p class='text-muted small'>Klik tombol 'Tambah Produk' untuk menambahkan produk baru</p>
                                                </div>
                                            </td>
                                          </tr>";
                                }

                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td class="fw-bold text-muted"><?= $no++; ?></td>
                                    <td>
                                        <div class="product-name">
                                            <div class="product-icon">
                                                <?php if(!empty($d['Foto'])): ?>
                                                    <img src="../../assets/img/produk/<?= htmlspecialchars($d['Foto']); ?>" alt="<?= htmlspecialchars($d['NamaProduk']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" onerror="this.src='../../assets/img/bahlil.jpg';">
                                                <?php else: ?>
                                                    <img src="../../assets/img/bahlil.jpg" alt="default" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                <?php endif; ?>
                                            </div>
                                            <span><?= htmlspecialchars($d['NamaProduk']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="price-tag">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="stock-badge <?= $d['Stok'] < 10 ? 'stock-low' : 'stock-ok'; ?>">
                                            <?= $d['Stok']; ?> Unit
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="edit.php?id=<?= $d['ProdukID']; ?>" 
                                               class="btn btn-action btn-action-warning" 
                                               title="Edit Produk"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="hapus.php?id=<?= $d['ProdukID']; ?>" 
                                               class="btn btn-action btn-action-danger" 
                                               title="Hapus Produk"
                                               data-bs-toggle="tooltip"
                                               onclick="return confirm('Menghapus produk akan berpengaruh pada data transaksi terkait. Yakin?')">
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

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#productTable tbody tr');
            
            rows.forEach(row => {
                let productName = row.querySelector('.product-name');
                if(productName) {
                    let text = productName.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>