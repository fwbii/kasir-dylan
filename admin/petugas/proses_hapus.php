<?php
session_start();
include '../../main/connect.php';

// Set header JSON
header('Content-Type: application/json');

// Cek session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu!']);
    exit;
}

// Cek jika bukan admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    echo json_encode(['success' => false, 'message' => 'Akses ditolak! Hanya admin yang dapat menghapus petugas.']);
    exit;
}

// Debug log
error_log("DELETE REQUEST - GET ID: " . ($_GET['id'] ?? 'NULL'));

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid (<= 0)!']);
        exit;
    }
    
    // PERUBAHAN PENTING: Gunakan UserID bukan id
    $cek_user = mysqli_query($conn, "SELECT * FROM user WHERE UserID = $id");
    
    if(!$cek_user) {
        echo json_encode(['success' => false, 'message' => 'Query error: ' . mysqli_error($conn)]);
        exit;
    }
    
    if(mysqli_num_rows($cek_user) == 0) {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan dengan UserID = ' . $id]);
        exit;
    }
    
    $user = mysqli_fetch_assoc($cek_user);
    
    // Debug info
    error_log("User found: " . print_r($user, true));
    
    // Tentukan nama kolom yang benar
    $db_username = isset($user['Username']) ? $user['Username'] : (isset($user['username']) ? $user['username'] : '');
    $user_role = isset($user['Role']) ? trim($user['Role']) : (isset($user['role']) ? trim($user['role']) : '');
    $session_username = isset($_SESSION['Username']) ? $_SESSION['Username'] : (isset($_SESSION['username']) ? $_SESSION['username'] : '');
    
    // Tidak boleh menghapus diri sendiri
    if($db_username && $session_username && $db_username == $session_username) {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri!']);
        exit;
    }
    
    // Hanya cek constraint "last admin" jika user yang akan dihapus adalah admin
    $normalized_role = strtolower($user_role);
    if($normalized_role === 'admin') {
        $count_admin = mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE LOWER(Role) = 'admin'");
        if($count_admin) {
            $admin_data = mysqli_fetch_assoc($count_admin);
            if(intval($admin_data['total']) <= 1) {
                echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus satu-satunya admin!']);
                exit;
            }
        }
    }
    
    // Mulai transaksi
    mysqli_begin_transaction($conn);
    
    try {
        // PERUBAHAN PENTING: Gunakan UserID bukan id
        $query = "DELETE FROM user WHERE UserID = $id";
        
        if(mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            
            // Format role untuk tampilan (handle berbagai case & nilai)
            $role_display = strtolower($user_role) === 'admin' ? 'Admin' : 'Petugas';
            
            echo json_encode([
                'success' => true, 
                'message' => $role_display . ' berhasil dihapus!',
                'username' => $db_username,
                'role' => $user_role
            ]);
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menghapus user: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parameter ID tidak ditemukan!']);
}

mysqli_close($conn);
?>