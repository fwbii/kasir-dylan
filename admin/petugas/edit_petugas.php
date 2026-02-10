<?php
// petugas/proses_edit.php
session_start();
include '../../main/connect.php';

// Cek jika bukan admin
if($_SESSION['role'] != "admin"){
    echo json_encode(['success' => false, 'message' => 'Akses ditolak!']);
    exit;
}

// Ambil data dari form
$id = mysqli_real_escape_string($conn, $_POST['id']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$role = mysqli_real_escape_string($conn, $_POST['role']);
$password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

// Validasi
if(empty($id) || empty($username) || empty($role)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap!']);
    exit;
}

// Cek apakah username sudah digunakan oleh user lain
$cek_username = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND id != '$id'");
if(mysqli_num_rows($cek_username) > 0) {
    echo json_encode(['success' => false, 'message' => 'Username sudah digunakan!']);
    exit;
}

// Update query
if(!empty($password)) {
    // Jika password diubah
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user SET 
              username = '$username',
              password = '$hashed_password',
              role = '$role'
              WHERE id = '$id'";
} else {
    // Jika password tidak diubah
    $query = "UPDATE user SET 
              username = '$username',
              role = '$role'
              WHERE id = '$id'";
}

if(mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Data berhasil diupdate!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengupdate data!']);
}
?>