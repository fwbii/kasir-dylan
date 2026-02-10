<?php
session_start();
include '../main/connect.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Cari user berdasarkan username
$query = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username'");
$cek = mysqli_num_rows($query);

if($cek > 0) {
    $data = mysqli_fetch_assoc($query);
    
    // Verifikasi password - dukung keduanya: hash (baru) dan plain text (lama)
    $passwordValid = false;
    
    // Cek apakah password di database adalah hash (dimulai dengan $2)
    if(substr($data['Password'], 0, 2) === '$2') {
        // Gunakan password_verify untuk hash password
        $passwordValid = password_verify($password, $data['Password']);
    } else {
        // Fallback untuk password plain text (lama)
        $passwordValid = ($password === $data['Password']);
    }
    
    if($passwordValid) {
        // Menyimpan SEMUA data ke session
        $_SESSION['id'] = $data['id']; // Tambahkan ini
        $_SESSION['username'] = $data['Username']; // Gunakan 'Username' (sesuai database)
        $_SESSION['nama_lengkap'] = $data['NamaLengkap'] ?? $data['nama_lengkap'] ?? ''; // Sesuaikan dengan nama kolom
        $_SESSION['role'] = $data['Role']; // 'Role' dengan huruf besar
        $_SESSION['status'] = "login";

        // Debug (hapus di production)
        // error_log("Login successful: " . $_SESSION['username'] . " - Role: " . $_SESSION['role']);

        // Redirect berdasarkan Role
        if($data['Role'] == "admin") {
            header("location:../admin/dashboard/index.php");
        } else {
            header("location:../petugas/dashboard/index.php");
        }
        exit();
    } else {
        // Password salah
        header("location:login.php?pesan=gagal");
        exit();
    }
} else {
    // Username tidak ditemukan
    header("location:login.php?pesan=gagal");
    exit();
}
?>