<?php 
include '../../main/connect.php';

$nama  = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok  = $_POST['Stok'];
$foto  = null;

// Handle upload foto
if(isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
    $file_tmp = $_FILES['Foto']['tmp_name'];
    $file_name = $_FILES['Foto']['name'];
    $file_size = $_FILES['Foto']['size'];
    $file_type = $_FILES['Foto']['type'];
    
    // Validasi tipe file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if(!in_array($file_type, $allowed_types)) {
        header("location:index.php?pesan=gagal&error=Tipe%20file%20tidak%20didukung");
        exit();
    }
    
    // Validasi ukuran (max 2MB)
    if($file_size > 2 * 1024 * 1024) {
        header("location:index.php?pesan=gagal&error=Ukuran%20file%20terlalu%20besar");
        exit();
    }
    
    // Buat folder jika belum ada
    $upload_dir = dirname(__FILE__) . '/../../assets/img/produk/';
    if(!is_dir($upload_dir)) {
        @mkdir($upload_dir, 0755, true);
    }
    
    // Generate nama file unik
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $foto = 'produk_' . time() . '_' . uniqid() . '.' . $file_ext;
    $file_path = $upload_dir . $foto;
    
    // Upload file
    if(!move_uploaded_file($file_tmp, $file_path)) {
        header("location:index.php?pesan=gagal&error=Gagal%20mengupload%20file");
        exit();
    }
}

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("INSERT INTO produk (NamaProduk, Harga, Stok, Foto) VALUES (?, ?, ?, ?)");

// Jika tidak ada foto, set NULL
$foto_value = $foto ?? null;
$stmt->bind_param("sdis", $nama, $harga, $stok, $foto_value);

if($stmt->execute()) {
    header("location:index.php?pesan=sukses");
} else {
    // Hapus file jika insert gagal
    if($foto && file_exists($upload_dir . $foto)) {
        unlink($upload_dir . $foto);
    }
    header("location:index.php?pesan=gagal&error=" . urlencode($stmt->error));
}

$stmt->close();
$conn->close();
?>