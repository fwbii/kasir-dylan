<?php 
session_start();
include '../../main/connect.php';
// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-simpan { transition: all 0.3s; border-radius: 10px; }
        .btn-simpan:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(13,110,253,0.3); }
        .card { border-radius: 15px; }
        .input-group-text { border-radius: 10px 0 0 10px; }
        .form-control { border-radius: 10px; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="col-md-6 mx-auto">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Data Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
                  </ol>
                </nav>

                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold m-0 text-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Produk Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="formTambah" action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Produk</label>
                                <input type="text" name="NamaProduk" class="form-control shadow-sm" placeholder="Contoh: Sabun Cuci" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga Jual</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                    <input type="number" name="Harga" class="form-control" placeholder="0" min="0" required>
                                </div>
                                <div class="form-text">Pastikan harga tidak bernilai negatif.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Stok Awal</label>
                                <input type="number" name="Stok" class="form-control shadow-sm" placeholder="0" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Produk</label>
                                <input type="file" name="Foto" class="form-control shadow-sm" accept="image/*" id="inputFoto">
                                <div class="form-text">Format: JPG, PNG. Ukuran max: 2MB</div>
                                <div id="previewFoto" class="mt-2"></div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
    <button type="button" onclick="confirmAdd()" class="btn btn-primary btn-simpan fw-bold py-2 shadow-sm">
        <i class="fas fa-save me-2"></i>Simpan Produk
    </button>
    <a href="index.php" class="btn btn-outline-secondary py-2 fw-bold" style="border-radius: 10px;">
        <i class="fas fa-arrow-left me-2"></i>Batal & Kembali
    </a>
</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Preview foto
    document.getElementById('inputFoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewFoto');
        
        if(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" style="max-width: 150px; border-radius: 8px; border: 2px solid #ddd;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    function confirmAdd() {
        // Validasi Manual Sederhana
        const form = document.getElementById('formTambah');
        const nama = form.NamaProduk.value;
        const harga = form.Harga.value;
        const stok = form.Stok.value;
        const foto = form.Foto.files.length > 0 ? form.Foto.files[0] : null;

        if(!nama || !harga || !stok) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap isi semua kolom sebelum menyimpan!',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        if(harga < 0 || stok < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Input Tidak Valid',
                text: 'Harga dan Stok tidak boleh bernilai negatif!',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        // Validasi ukuran foto (max 2MB)
        if(foto && foto.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran foto maksimal 2MB!',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        Swal.fire({
            title: 'Simpan Produk?',
            text: "Data " + nama + " akan ditambahkan ke sistem.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
    </script>
</body>
</html>