<?php
// petugas/proses_tambah.php
session_start();
include '../../main/connect.php';

// Cek jika bukan admin
if($_SESSION['role'] != "admin"){
    echo json_encode(['success' => false, 'message' => 'Akses ditolak!']);
    exit;
}

// Ambil data dari form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$role = mysqli_real_escape_string($conn, $_POST['role']);

// Validasi input
if(empty($username) || empty($password) || empty($nama_lengkap) || empty($role)) {
    header("location:../petugas/index.php?pesan=gagal&status=danger");
    exit;
}

// Cek apakah username sudah ada
$cek_username = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
if(mysqli_num_rows($cek_username) > 0) {
    header("location:../petugas/index.php?pesan=username_ada&status=warning");
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data ke database
$query = "INSERT INTO user (username, password, nama_lengkap, role, created_at) 
          VALUES ('$username', '$hashed_password', '$nama_lengkap', '$role', NOW())";

if(mysqli_query($conn, $query)) {
    header("location:../petugas/index.php?pesan=sukses&status=success");
} else {
    header("location:../petugas/index.php?pesan=gagal&status=danger");
}
?>