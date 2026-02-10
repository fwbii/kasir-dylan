<?php 
include '../../main/connect.php';

$id    = $_POST['ProdukID'];
$nama  = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok  = $_POST['Stok'];

// Ambil data produk lama
$result = mysqli_query($conn, "SELECT Foto FROM produk WHERE ProdukID='$id'");
$row = mysqli_fetch_assoc($result);
$foto_lama = $row['Foto'];
$foto_baru = $foto_lama; // default: pakai foto lama

// Handle upload foto baru
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
    $foto_baru = 'produk_' . time() . '_' . uniqid() . '.' . $file_ext;
    $file_path = $upload_dir . $foto_baru;
    
    // Upload file
    if(!move_uploaded_file($file_tmp, $file_path)) {
        header("location:index.php?pesan=gagal&error=Gagal%20mengupload%20file");
        exit();
    }
    
    // Hapus foto lama jika ada
    if(!empty($foto_lama) && file_exists($upload_dir . $foto_lama)) {
        unlink($upload_dir . $foto_lama);
    }
}

// Update dengan prepared statement
$stmt = $conn->prepare("UPDATE produk SET NamaProduk=?, Harga=?, Stok=?, Foto=? WHERE ProdukID=?");
$stmt->bind_param("sdisi", $nama, $harga, $stok, $foto_baru, $id);

if($stmt->execute()) {
    header("location:index.php?pesan=update");
} else {
    header("location:index.php?pesan=gagal");
}

$stmt->close();
$conn->close();
?>