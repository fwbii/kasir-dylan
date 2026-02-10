<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

header('Content-Type: application/json; charset=utf-8');

include '../../main/connect.php';

if(!$conn){
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Cek login
if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    echo json_encode(['success'=>false,'message'=>'Silakan login']);
    exit;
}

// Cek role admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo json_encode(['success'=>false,'message'=>'Akses ditolak']);
    exit;
}

// Ambil data
$id       = intval($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$role     = trim($_POST['role'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi
if($id <= 0 || $username === '' || $role === ''){
    echo json_encode(['success'=>false,'message'=>'Data tidak lengkap']);
    exit;
}

// Cek username (gunakan prepared statement untuk keamanan)
$check_query = "SELECT UserID FROM user WHERE Username = ? AND UserID != ?";
$stmt = mysqli_prepare($conn, $check_query);
if(!$stmt){
    echo json_encode(['success'=>false,'message'=>'Persiapan query gagal: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "si", $username, $id);
mysqli_stmt_execute($stmt);
$check_result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($check_result) > 0){
    echo json_encode(['success'=>false,'message'=>'Username sudah digunakan']);
    mysqli_stmt_close($stmt);
    exit;
}

mysqli_stmt_close($stmt);

// Query update dengan prepared statement
if($password !== ''){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $update_query = "UPDATE user SET Username = ?, Password = ?, Role = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    if(!$stmt){
        echo json_encode(['success'=>false,'message'=>'Persiapan query update gagal: ' . mysqli_error($conn)]);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "sssi", $username, $hash, $role, $id);
} else {
    $update_query = "UPDATE user SET Username = ?, Role = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    if(!$stmt){
        echo json_encode(['success'=>false,'message'=>'Persiapan query update gagal: ' . mysqli_error($conn)]);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $id);
}

// Eksekusi
if(mysqli_stmt_execute($stmt)){
    // Format role untuk tampilan (handle berbagai case)
    $role_display = strtolower($role) === 'admin' ? 'Admin' : 'Petugas';
    
    echo json_encode([
        'success'=>true,
        'message'=> $role_display . ' berhasil diupdate!',
        'role' => $role
    ]);
} else {
    echo json_encode(['success'=>false,'message'=>'Error eksekusi: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
?>
