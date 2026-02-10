<?php 
session_start(); // Tambahkan session untuk validasi
include '../../main/connect.php';

// Cek apakah form dikirim
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("location:index.php?pesan=gagal&status=danger");
    exit();
}

// Gunakan nama input sesuai dengan form HTML (huruf kecil)
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';
$nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';

// Validasi data tidak kosong
if(empty($username) || empty($password) || empty($role)) {
    header("location:index.php?pesan=field_kosong&status=danger");
    exit();
}

// Validasi role
if($role !== 'admin' && $role !== 'petugas') {
    header("location:index.php?pesan=role_invalid&status=danger");
    exit();
}

// Cek apakah username sudah ada
$check = mysqli_query($conn, "SELECT * FROM user WHERE Username = '$username'");
if(mysqli_num_rows($check) > 0) {
    header("location:index.php?pesan=username_exists&status=danger");
    exit();
}

// Hash password (penting untuk keamanan!)
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Query INSERT yang benar
// PERHATIAN: Sesuaikan dengan struktur tabel yang sebenarnya!
$query = mysqli_query($conn, "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password_hash', '$role')");

if($query) {
    // Format role untuk notifikasi
    $role_display = strtolower($role) === 'admin' ? 'Admin' : 'Petugas';
    header("location:index.php?pesan=sukses&status=success&role=$role_display");
    exit();
} else {
    // Redirect dengan pesan error database
    header("location:index.php?pesan=db_error&status=danger");
    exit();
}
?>