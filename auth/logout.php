<?php 
// Memulai session
session_start();

// Menghapus semua data session
session_unset();
session_destroy();

// Mengarahkan kembali ke halaman login
// Sesuaikan nama file login kamu (biasanya login.php atau index.php di folder auth)
header("location:login.php?pesan=logout");
exit();
?>