<?php
// admin/petugas/get_petugas.php
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
    echo json_encode(['success' => false, 'message' => 'Silakan login']);
    exit;
}

// Cek role admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

$id = intval($_GET['id']);

// PENTING: Gunakan UserID (bukan id) - kolom yang sesuai dengan database
// Gunakan prepared statement untuk keamanan
$query_str = "SELECT UserID, Username, Role FROM user WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $query_str);

if(!$stmt){
    echo json_encode(['success' => false, 'message' => 'Error prepare: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($query) == 0){
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    mysqli_stmt_close($stmt);
    exit;
}

$data = mysqli_fetch_assoc($query);

// Mapping agar frontend dapat relational field dengan benar
echo json_encode([
    'success' => true,
    'data' => [
        'id' => $data['UserID'],              // lowercase untuk form HTML
        'username' => $data['Username'],      // lowercase untuk form HTML
        'role' => $data['Role'],              // lowercase untuk form HTML
        'UserID' => $data['UserID'],          // original fields jika diperlukan
        'Username' => $data['Username'],
        'Role' => $data['Role']
    ]
]);

mysqli_stmt_close($stmt);
?>