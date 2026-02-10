session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<?php 
/*
    File: petugas/penjualan/index.php
    Purpose: Frontend for petugas to create sales transactions.
    JS handles cart in-memory (array `items`) with functions:
        - tambahItem(id,nama,harga,stokMax): add/increment item
        - hapusItem(index): remove item
        - renderTabel(): render cart and totals
        - hitungKembalian(): enable checkout when payment sufficient
    On submit, form posts to proses_simpan.php after SweetAlert confirmation.
*/
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

        /* Product Grid Section */
        .products-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInLeft 0.6s ease-out;
        }

        .section-header {
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
            margin: 0;
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 20px;
            border: 2px solid rgba(102, 126, 234, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            overflow: hidden;
            position: relative;
            height: 100%;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }

        .product-card:hover::before {
            transform: translateY(0);
        }

        .product-card:active {
            transform: translateY(-5px) scale(0.98);
        }

        .product-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            position: relative;
            z-index: 1;
        }

        .product-icon i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .product-price {
            font-weight: 800;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .product-stock {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.2);
            position: relative;
            z-index: 1;
        }

        /* Cart Section */
        .cart-section {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: sticky;
            top: 20px;
            animation: fadeInRight 0.6s ease-out;
        }

        .cart-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            color: white;
        }

        .cart-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
        }

        /* Form Styling */
        .form-label-modern {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .modern-input {
            border-radius: 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.9rem 1.2rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .modern-input::placeholder {
            font-weight: 400;
            color: #cbd5e0;
        }

        /* Cart Table */
        .cart-table {
            margin: 1.5rem 0;
        }

        .cart-table thead th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8492a6;
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
            padding: 0.8rem 0.5rem;
        }

        .cart-item {
            border-bottom: 1px solid rgba(102, 126, 234, 0.05);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: rgba(102, 126, 234, 0.02);
        }

        .cart-item td {
            padding: 1rem 0.5rem;
            vertical-align: middle;
        }

        .item-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: #2d3748;
        }

        .item-qty {
            width: 60px;
            border-radius: 10px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            text-align: center;
            font-weight: 700;
            padding: 0.4rem;
        }

        .item-total {
            font-weight: 700;
            color: #11998e;
            font-size: 0.9rem;
        }

        .btn-remove {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 5px 15px rgba(250, 112, 154, 0.4);
        }

        /* Grand Total */
        .grand-total-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .grand-total-label {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
        }

        .grand-total-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Payment Section */
        .payment-section {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, rgba(56, 239, 125, 0.05) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(17, 153, 142, 0.1);
        }

        .payment-input {
            border-radius: 12px;
            border: 2px solid rgba(17, 153, 142, 0.3);
            padding: 1rem 1.2rem;
            font-weight: 700;
            font-size: 1.2rem;
            color: #11998e;
        }

        .payment-input:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.15);
        }

        .change-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 2px dashed rgba(17, 153, 142, 0.2);
        }

        .change-label {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #11998e;
        }

        .change-value {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Checkout Button */
        .btn-checkout {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 15px;
            padding: 1.2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout::before {
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

        .btn-checkout:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(17, 153, 142, 0.4);
        }

        .btn-checkout:disabled {
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
            cursor: not-allowed;
            transform: none;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-cart-icon {
            font-size: 4rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            border-radius: 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.8rem 1.2rem 0.8rem 3rem;
            transition: all 0.3s ease;
            background: white;
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
            font-size: 1rem;
        }

        /* Animations */
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .add-animation {
            animation: pulse 0.3s ease;
        }

        /* Scrollbar */
        .products-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .products-scroll::-webkit-scrollbar-track {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
        }

        .products-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-title {
                font-size: 1.2rem;
            }
            
            .cart-title {
                font-size: 1.1rem;
            }

            .grand-total-value {
                font-size: 1.5rem;
            }

            .product-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="row g-4">
                <!-- Products Section -->
                <div class="col-lg-7">
                    <div class="products-section">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-store me-2"></i>Pilih Produk
                            </h5>
                            <p class="text-muted small mb-0 mt-1">Klik produk untuk menambahkan ke keranjang</p>
                        </div>
                        <div class="p-4">
                            <!-- Search Box -->
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="searchProduct" class="form-control search-input" placeholder="Cari produk...">
                            </div>

                            <!-- Products Grid -->
                            <div class="products-scroll" style="max-height: 70vh; overflow-y: auto;">
                                <div class="row g-3" id="productGrid">
                                    <?php 
                                    $sql = mysqli_query($conn, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");
                                    while($p = mysqli_fetch_array($sql)){
                                    ?>
                                    <div class="col-md-4 col-sm-6 product-item">
                                        <div class="product-card" onclick="tambahItem('<?= $p['ProdukID'] ?>', '<?= $p['NamaProduk'] ?>', '<?= $p['Harga'] ?>', '<?= $p['Stok'] ?>')">
                                            <div class="card-body text-center p-3">
                                                <div class="product-icon">
                                                    <?php if(!empty($p['Foto'])): ?>
                                                        <img src="../../assets/img/produk/<?= htmlspecialchars($p['Foto']); ?>" alt="<?= htmlspecialchars($p['NamaProduk']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;" onerror="this.src='../../assets/img/bahlil.jpg';">
                                                    <?php else: ?>
                                                        <i class="fas fa-cube"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <h6 class="product-name"><?= htmlspecialchars($p['NamaProduk']); ?></h6>
                                                <p class="product-price mb-2">Rp <?= number_format($p['Harga'], 0, ',', '.') ?></p>
                                                <span class="product-stock">
                                                    <i class="fas fa-layer-group me-1"></i>Stok: <?= $p['Stok'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="col-lg-5">
                    <div class="cart-section">
                        <div class="cart-header">
                            <h5 class="cart-title">
                                <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
                            </h5>
                            <p class="mb-0 opacity-75 small">Item yang dipilih</p>
                        </div>
                        
                        <div class="p-4">
                            <form action="proses_simpan.php" method="POST" id="formTransaksi">
                                <!-- Customer Name -->
                                <div class="mb-3">
                                    <label class="form-label-modern">
                                        <i class="fas fa-user me-1"></i> Nama Pelanggan
                                    </label>
                                    <input type="text" name="NamaPelanggan" class="form-control modern-input" placeholder="Masukkan nama pelanggan..." required>
                                </div>
                                
                                <!-- Cart Items -->
                                <div class="cart-table">
                                    <table class="table table-sm" id="tabelPesanan">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th width="70">Qty</th>
                                                <th>Total</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <div class="empty-cart">
                                                        <div class="empty-cart-icon">
                                                            <i class="fas fa-shopping-basket"></i>
                                                        </div>
                                                        <p class="text-muted small mb-0">Keranjang masih kosong</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Grand Total -->
                                <div class="grand-total-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="grand-total-label">Total Belanja</span>
                                        <h3 class="grand-total-value mb-0" id="totalHarga">Rp 0</h3>
                                    </div>
                                </div>

                                <!-- Payment Section -->
                                <div class="payment-section">
                                    <label class="form-label-modern">
                                        <i class="fas fa-money-bill-wave me-1"></i> Uang Bayar
                                    </label>
                                    <input type="number" id="uangBayar" class="form-control payment-input" placeholder="0" oninput="hitungKembalian()">
                                    
                                    <div class="change-display">
                                        <span class="change-label">
                                            <i class="fas fa-hand-holding-usd me-1"></i> Kembalian
                                        </span>
                                        <span class="change-value" id="textKembalian">Rp 0</span>
                                    </div>
                                </div>
                                
                                <!-- Checkout Button -->
                                <button type="submit" class="btn btn-checkout w-100" id="btnBayar" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let items = [];

        function tambahItem(id, nama, harga, stokMax) {
            let index = items.findIndex(i => i.id === id);
            if(index !== -1) {
                if(items[index].qty < stokMax) {
                    items[index].qty++;
                    
                    // Add animation
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Item ditambahkan',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup!',
                        text: 'Batas maksimal stok tercapai',
                        confirmButtonColor: '#667eea'
                    });
                }
            } else {
                items.push({ id, nama, harga: parseInt(harga), qty: 1 });
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Produk ditambahkan ke keranjang',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            }
            renderTabel();
        }

        function hapusItem(index) {
            Swal.fire({
                title: 'Hapus Item?',
                text: 'Item akan dihapus dari keranjang',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fa709a',
                cancelButtonColor: '#8492a6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    items.splice(index, 1);
                    renderTabel();
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Item dihapus',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        }

        function hitungKembalian() {
            let total = items.reduce((sum, item) => sum + (item.qty * item.harga), 0);
            let bayar = document.getElementById('uangBayar').value;
            let kembalian = bayar - total;
            
            document.getElementById('textKembalian').innerText = 'Rp ' + (kembalian >= 0 ? kembalian.toLocaleString('id-ID') : 0);
            
            // Validasi: Tombol aktif jika ada item DAN uang cukup
            document.getElementById('btnBayar').disabled = (items.length === 0 || kembalian < 0 || bayar === "");
        }

        function renderTabel() {
            let html = '';
            let grandTotal = 0;
            
            if(items.length === 0) {
                html = `<tr>
                    <td colspan="4" class="p-0">
                        <div class="empty-cart">
                            <div class="empty-cart-icon">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                            <p class="text-muted small mb-0">Keranjang masih kosong</p>
                        </div>
                    </td>
                </tr>`;
            } else {
                items.forEach((item, i) => {
                    let subtotal = item.qty * item.harga;
                    grandTotal += subtotal;
                    html += `<tr class="cart-item">
                        <td>
                            <div class="item-name">${item.nama}</div>
                            <input type="hidden" name="ProdukID[]" value="${item.id}">
                        </td>
                        <td>
                            <input type="number" name="Jumlah[]" class="form-control item-qty" value="${item.qty}" readonly>
                        </td>
                        <td><span class="item-total">Rp ${subtotal.toLocaleString('id-ID')}</span></td>
                        <td>
                            <button type="button" class="btn-remove" onclick="hapusItem(${i})">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }
            
            document.querySelector('#tabelPesanan tbody').innerHTML = html;
            document.getElementById('totalHarga').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            hitungKembalian();
        }

        // Search functionality
        document.getElementById('searchProduct').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let products = document.querySelectorAll('.product-item');
            
            products.forEach(product => {
                let name = product.querySelector('.product-name').textContent.toLowerCase();
                product.style.display = name.includes(filter) ? '' : 'none';
            });
        });

        // Form submit dengan konfirmasi
        document.getElementById('formTransaksi').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Pastikan semua data sudah benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#11998e',
                cancelButtonColor: '#8492a6',
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Cek Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>