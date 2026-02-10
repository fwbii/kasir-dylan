<?php
// petugas/index.php
session_start();
// Proteksi halaman: Jika belum login, lempar ke halaman login
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
// Cek jika bukan admin
if($_SESSION['role'] != "admin"){
    header("location:../dashboard/index.php?pesan=akses_ditolak");
}

include '../../main/connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Petugas - Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

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

        .stats-card {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .form-control-custom {
            border-radius: 15px;
            border: 2px solid #e0e0e0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

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

        .role-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .role-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .role-petugas {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
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
                    <div class="welcome-text">Registrasi Petugas</div>
                    <p class="text-muted mb-0 mt-2">
                        <i class="fas fa-user-cog me-2"></i>
                        Kelola data petugas dan staff kasir
                    </p>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Login sebagai:</div>
                    <h4 class="mb-0 fw-bold" style="color: #667eea;">
                        <i class="fas fa-user-circle me-2"></i><?php echo $_SESSION['username']; ?>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Alert untuk pesan -->
        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] ?? 'success'; ?> alert-dismissible fade show" role="alert">
                <?php 
                $pesan = $_GET['pesan'];
                if($pesan == 'sukses') echo "Petugas berhasil ditambahkan!";
                elseif($pesan == 'gagal') echo "Gagal menambahkan petugas!";
                elseif($pesan == 'hapus_sukses') echo "Petugas berhasil dihapus!";
                elseif($pesan == 'hapus_gagal') echo "Gagal menghapus petugas!";
                elseif($pesan == 'update_sukses') echo "Petugas berhasil diupdate!";
                elseif($pesan == 'update_gagal') echo "Gagal mengupdate petugas!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card stats-card text-white shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Total Petugas</div>
                            <?php 
                                $query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM user");
                                $data_total = mysqli_fetch_assoc($query_total);
                                echo "<h2 class='stats-number'>" . $data_total['total'] . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card stats-card text-white shadow-lg" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Admin</div>
                            <?php 
                                $query_admin = mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE role = 'admin'");
                                $data_admin = mysqli_fetch_assoc($query_admin);
                                echo "<h2 class='stats-number'>" . $data_admin['total'] . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-user-shield fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card stats-card text-white shadow-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label mb-2">Petugas</div>
                            <?php 
                                $query_petugas = mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE role = 'petugas'");
                                $data_petugas = mysqli_fetch_assoc($query_petugas);
                                echo "<h2 class='stats-number'>" . $data_petugas['total'] . "</h2>";
                            ?>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Form Tambah Petugas -->
            <div class="col-lg-5 mb-4">
                <div class="glass-card">
                    <div class="glass-header">
                        <h5 class="m-0 fw-bold" style="color: #667eea;">
                            <i class="fas fa-user-plus me-2"></i>
                            Tambah Petugas Baru
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Isi form untuk menambahkan petugas baru</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_tambah.php" method="POST" id="formTambahPetugas">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control form-control-custom" id="username" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-custom" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 6 karakter</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-custom" id="nama_lengkap" name="nama_lengkap" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select form-control-custom" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas Kasir</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-modern btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Simpan Petugas
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Petugas -->
            <div class="col-lg-7">
                <div class="glass-card">
                    <div class="glass-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0 fw-bold" style="color: #667eea;">
                                <i class="fas fa-list me-2"></i>
                                Daftar Petugas
                            </h5>
                            <p class="text-muted small mb-0 mt-1">Semua user yang terdaftar dalam sistem</p>
                        </div>
                        <button class="btn btn-modern btn-outline-primary" onclick="refreshTable()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabelPetugas">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Role</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM user ORDER BY role DESC, username ASC");
                                    $no = 1;
                                    while($data = mysqli_fetch_assoc($query)):
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($data['username']); ?></td>
                                        <td><?= htmlspecialchars($data['nama_lengkap']); ?></td>
                                        <td>
                                            <span class="role-badge <?= $data['role'] == 'admin' ? 'role-admin' : 'role-petugas'; ?>">
                                                <?= ucfirst($data['role']); ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($data['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-warning" onclick="editPetugas(<?= $data['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if($data['username'] != $_SESSION['username']): ?>
                                                <button type="button" class="btn btn-danger" onclick="hapusPetugas(<?= $data['id']; ?>, '<?= $data['username']; ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Edit Petugas -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Petugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditPetugas">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas Kasir</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanEdit()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<?php include '../../template/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Refresh table function
    function refreshTable() {
        location.reload();
    }

    // Edit petugas
    function editPetugas(id) {
        fetch('get_petugas.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('edit_id').value = data.data.id;
                    document.getElementById('edit_username').value = data.data.username;
                    document.getElementById('edit_nama_lengkap').value = data.data.nama_lengkap;
                    document.getElementById('edit_role').value = data.data.role;
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
                    modal.show();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Simpan edit
    function simpanEdit() {
        const formData = new FormData(document.getElementById('formEditPetugas'));
        
        fetch('proses_edit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Data berhasil diupdate!');
                location.reload();
            } else {
                alert('Gagal mengupdate data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan!');
        });
    }

    // Hapus petugas
    function hapusPetugas(id, username) {
        if(confirm(`Apakah Anda yakin ingin menghapus petugas "${username}"?`)) {
            fetch('proses_hapus.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Petugas berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus petugas: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan!');
                });
        }
    }

    // Form validation
    document.getElementById('formTambahPetugas').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        if(password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
        return true;
    });
</script>
</body>
</html>