<?php
// Mengambil nama folder aktif untuk menentukan menu mana yang 'active'
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<!-- Sidebar template: merender menu navigasi. Visibilitas link berdasarkan role diatur oleh PHP di bawah. -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>

    .poppins-thin {
  font-family: "Poppins", sans-serif;
  font-weight: 100;
  font-style: normal;
}
    /* ===== ANIMASI CAHAYA BARU ===== */
    /* Animasi cahaya bergerak */
    @keyframes lightGlow {
        0%, 100% {
            opacity: 0.6;
            background-position: 0% 50%;
        }
        50% {
            opacity: 0.8;
            background-position: 100% 50%;
        }
    }

    @keyframes floatingLights {
        0% {
            transform: translateY(0px) translateX(0px);
        }
        25% {
            transform: translateY(-10px) translateX(10px);
        }
        50% {
            transform: translateY(5px) translateX(-5px);
        }
        75% {
            transform: translateY(-5px) translateX(5px);
        }
        100% {
            transform: translateY(0px) translateX(0px);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 
                0 0 20px rgba(102, 126, 234, 0.3),
                0 0 40px rgba(118, 75, 162, 0.2);
        }
        50% {
            box-shadow: 
                0 0 30px rgba(102, 126, 234, 0.5),
                0 0 60px rgba(118, 75, 162, 0.3);
        }
    }

    /* Container sidebar dengan efek cahaya */
    .sidebar-container {
        background: linear-gradient(135deg, 
            rgba(26, 26, 46, 0.95) 0%, 
            rgba(22, 33, 62, 0.95) 100%);
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 240px;
        z-index: 1000;
        border-right: 1px solid rgba(102, 126, 234, 0.3);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Overlay efek cahaya */
    .sidebar-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            45deg,
            transparent 0%,
            rgba(102, 126, 234, 0.1) 25%,
            rgba(118, 75, 162, 0.1) 50%,
            rgba(102, 126, 234, 0.1) 75%,
            transparent 100%
        );
        background-size: 200% 200%;
        animation: lightGlow 4s ease-in-out infinite;
        pointer-events: none;
        z-index: 1;
    }

    /* Cahaya melayang */
    .floating-light {
        position: absolute;
        width: 100px;
        height: 100px;
        background: radial-gradient(
            circle,
            rgba(102, 126, 234, 0.3) 0%,
            transparent 70%
        );
        border-radius: 50%;
        animation: floatingLights 3s ease-in-out infinite;
        z-index: 0;
    }

    .light-1 {
        top: 20%;
        left: -30px;
        animation-delay: 0s;
    }

    .light-2 {
        bottom: 30%;
        right: -20px;
        animation-delay: 3s;
    }

    .light-3 {
        top: 70%;
        left: -10px;
        animation-delay: 6s;
    }

    /* Header sidebar dengan efek cahaya */
    .sidebar-header {
        background: linear-gradient(45deg, 
            rgba(102, 126, 234, 0.2) 0%, 
            rgba(118, 75, 162, 0.2) 100%);
        padding: 25px 20px;
        margin: -12px -12px 20px -12px;
        transform: skewY(-3deg);
        position: relative;
        overflow: hidden;
        z-index: 2;
        backdrop-filter: blur(10px);
        animation: pulseGlow 4s ease-in-out infinite;
        flex-shrink: 0;
    }

    /* Efek shimmer pada header */
    .sidebar-header::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 30%,
            rgba(255, 255, 255, 0.1) 50%,
            transparent 70%
        );
        transform: rotate(45deg);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }

    .sidebar-header > * {
        transform: skewY(3deg);
        position: relative;
        z-index: 1;
    }

    /* Konten menu - fleksibel untuk mengisi ruang */
    .sidebar-menu {
        flex: 1;
        padding: 0 12px;
        position: relative;
        z-index: 2;
        overflow-y: auto;
        min-height: 0; /* Penting untuk flex scrolling */
    }

    /* ===== CSS LAMA (tetap dipertahankan dengan sedikit modifikasi) ===== */
    /* CSS Kustom untuk Efek Hover dan Active */
    .nav-pills .nav-link {
        transition: all 0.3s ease;
        border-radius: 10px;
        margin-bottom: 8px;
        padding: 12px 20px;
        color: rgba(255,255,255,0.8) !important;
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(5px);
        z-index: 2;
        width: 100%;
    }

    /* Efek Background Miring ke Atas */
    .nav-pills .nav-link::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0%;
        background: linear-gradient(45deg, 
            rgba(102, 126, 234, 0.3) 0%, 
            rgba(118, 75, 162, 0.2) 100%);
        transition: height 0.4s ease;
        transform: skewY(-3deg);
        transform-origin: bottom left;
        z-index: -1;
    }

    /* Efek glow pada hover */
    .nav-pills .nav-link:hover:not(.active) {
        color: #fff !important;
        transform: translateY(-5px) skewY(-2deg);
        box-shadow: 
            0 6px 12px rgba(0,0,0,0.3),
            0 0 15px rgba(102, 126, 234, 0.3);
    }

    .nav-pills .nav-link:hover::before {
        height: 100%;
    }

    /* Efek Saat Menu Aktif */
    .nav-pills .nav-link.active {
        background: linear-gradient(45deg, 
            rgba(102, 126, 234, 0.8) 0%, 
            rgba(118, 75, 162, 0.7) 100%) !important;
        color: #fff !important;
        font-weight: bold;
        transform: translateY(-3px) skewY(-2deg);
        box-shadow: 
            0 6px 15px rgba(13, 110, 253, 0.5),
            0 0 20px rgba(102, 126, 234, 0.4);
    }

    /* Efek Ikon di dalam Menu */
    .nav-link i {
        margin-right: 10px;
        transition: all 0.3s;
    }

    .nav-link:hover i {
        transform: translateY(-3px) rotate(15deg) scale(1.2);
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
    }

    .nav-link.active i {
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.7));
    }

    /* Divider dengan efek cahaya */
    hr {
        border-color: rgba(102, 126, 234, 0.3);
        transform: skewY(-1deg);
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.2);
        margin: 15px 0;
    }

    /* Footer sidebar untuk tombol logout */
    .sidebar-footer {
        padding: 12px;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(102, 126, 234, 0.2);
        margin-top: auto; /* Dorong ke bawah */
    }

    /* Tombol Keluar dengan efek cahaya */
    .btn-logout {
        background: linear-gradient(45deg, 
            rgba(220, 53, 69, 0.8) 0%, 
            rgba(176, 42, 55, 0.7) 100%);
        border: none;
        transform: skewY(-2deg);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(5px);
        width: 100%;
        padding: 12px;
        border-radius: 10px;
    }

    .btn-logout::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0%;
        background: linear-gradient(45deg, 
            rgba(255, 71, 87, 0.9) 0%, 
            rgba(220, 53, 69, 0.8) 100%);
        transition: height 0.3s ease;
    }

    .btn-logout:hover::before {
        height: 100%;
    }

    .btn-logout:hover {
        transform: translateY(-5px) skewY(-2deg);
        box-shadow: 
            0 8px 16px rgba(220, 53, 69, 0.5),
            0 0 20px rgba(220, 53, 69, 0.3);
    }

    .btn-logout span {
        display: inline-block;
        transform: skewY(2deg);
        position: relative;
        z-index: 1;
        font-weight: 500;
    }

    /* Animasi untuk seluruh sidebar */
    .sidebar-content {
        animation: slideInLeft 0.5s ease-out;
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%) skewY(-2deg);
            opacity: 0;
        }
        to {
            transform: translateX(0) skewY(0);
            opacity: 1;
        }
    }

    /* Scrollbar custom untuk menu */
    .sidebar-menu::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.5);
        border-radius: 10px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.8);
    }

    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .sidebar-container {
            width: 200px;
            animation: pulseGlow 4s ease-in-out infinite;
        }
        
        .floating-light {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="sidebar-container shadow">
    <!-- Cahaya melayang -->
    <div class="floating-light light-1"></div>
    <div class="floating-light light-2"></div>
    <div class="floating-light light-3"></div>
    
    <!-- Konten sidebar -->
    <div class="sidebar-content p-3 text-light">
        <div class="sidebar-header text-center mb-2">
           <h4 class="fw-bold pt-4"><i class="fas fa-store me-2"></i>KASIR DYLAN</h4>
            <small class="text-uppercase"><?= $_SESSION['role']; ?></small> 
        </div>
        <hr class="mb-4">
        
        <div class="sidebar-menu">
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="../dashboard/index.php" class="nav-link <?= ($current_dir == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a href="../penjualan/index.php" class="nav-link <?= ($current_dir == 'penjualan') ? 'active' : ''; ?>">
                        <i class="fas fa-cash-register"></i> Penjualan
                    </a>
                </li>

                <li>
                    <a href="../produk/index.php" class="nav-link <?= ($current_dir == 'produk') ? 'active' : ''; ?>">
                        <i class="fas fa-box"></i> Data Produk
                    </a>
                </li>
                
                <?php if($_SESSION['role'] == 'admin'): ?>
                <li>
                    <a href="../petugas/index.php" class="nav-link <?= ($current_dir == 'petugas') ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield"></i> Registrasi 
                    </a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="../laporan/index.php" class="nav-link <?= ($current_dir == 'laporan') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i> Laporan
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <hr>
            <a href="../../auth/logout.php" class="btn btn-logout text-white" onclick="return confirm('Yakin ingin keluar?')">
                <span><i class="fas fa-sign-out-alt me-2"></i> Keluar</span>
            </a>
        </div>
    </div>
</div>

<div class="d-none d-md-block" style="width: 240px; flex-shrink: 0;"></div>