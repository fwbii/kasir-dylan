<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Terapkan Poppins font ke semua elemen */
    * {
        font-family: 'Poppins', sans-serif;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }
.btn {
    transition: all 0.3s ease; /* Membuat perubahan warna/ukuran jadi mulus */
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

/* Efek saat Mouse Menempel (Hover) */
.btn:hover {
    transform: translateY(-3px); /* Tombol sedikit terangkat ke atas */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Memberikan bayangan lembut */
    filter: brightness(1.1); /* Membuat warna sedikit lebih terang */
}

/* Efek saat Tombol Diklik (Active) */
.btn:active {
    transform: translateY(1px); /* Tombol seolah tertekan ke bawah */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Efek Khusus untuk Sidebar Menu */
.nav-link {
    transition: all 0.3s;
    border-radius: 5px;
    margin-bottom: 5px;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    padding-left: 20px; /* Efek bergeser ke kanan sedikit */
    color: #fff !important;
}
/* Efek Hover Menu Sidebar */
.nav-link {
    padding: 12px 15px;
    transition: all 0.3s ease;
    border-radius: 10px !important;
    margin: 4px 0;
}

.nav-link:hover:not(.active) {
    background-color: rgba(255, 255, 255, 0.1) !important;
    transform: translateX(8px); /* Efek menu bergeser ke kanan saat hover */
    color: #0d6efd !important;
}

/* Efek saat Menu Aktif */
.nav-link.active {
    font-weight: bold;
    transform: scale(1.05); /* Sedikit membesar saat aktif */
}

/* Animasi klik pada tombol keluar */
.btn-danger:active {
    transform: scale(0.95);
}
</style>