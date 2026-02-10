<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID='$id'");
$d = mysqli_fetch_array($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-update { transition: all 0.3s; border-radius: 10px; }
        .btn-update:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(255,193,7,0.4); }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="col-md-6 mx-auto">
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3 text-warning">
                        <h5 class="fw-bold m-0"><i class="fas fa-edit me-2"></i>Edit Produk</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="formEdit" action="proses_edit.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="ProdukID" value="<?= $d['ProdukID']; ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Produk</label>
                                <input type="text" name="NamaProduk" class="form-control" value="<?= $d['NamaProduk']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="Harga" class="form-control" value="<?= $d['Harga']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Stok</label>
                                <input type="number" name="Stok" class="form-control" value="<?= $d['Stok']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Produk</label>
                                <?php if(!empty($d['Foto'])): ?>
                                    <div class="mb-2">
                                        <img src="../../assets/img/produk/<?= htmlspecialchars($d['Foto']); ?>" alt="<?= htmlspecialchars($d['NamaProduk']); ?>" style="max-width: 150px; border-radius: 8px; border: 2px solid #ddd;" onerror="this.src='../../assets/img/bahlil.jpg';">
                                        <p class="text-muted small mt-2">Foto saat ini</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="Foto" class="form-control" accept="image/*" id="inputFoto">
                                <div class="form-text">Format: JPG, PNG. Ukuran max: 2MB. Kosongkan jika tidak ingin mengganti.</div>
                                <div id="previewFoto" class="mt-2"></div>
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button type="button" onclick="confirmEdit()" class="btn btn-warning text-white fw-bold btn-update">Update Produk</button>
                                <a href="index.php" class="btn btn-light">Batal</a>
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

    function confirmEdit() {
        const form = document.getElementById('formEdit');
        const foto = document.getElementById('inputFoto').files.length > 0 ? document.getElementById('inputFoto').files[0] : null;
        
        // Validasi ukuran foto (max 2MB)
        if(foto && foto.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran foto maksimal 2MB!',
                confirmButtonColor: '#ffc107'
            });
            return;
        }
        
        Swal.fire({
            title: 'Update data?',
            text: "Data produk akan segera diperbarui",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Ya, Update!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
    </script>
</body>
</html>