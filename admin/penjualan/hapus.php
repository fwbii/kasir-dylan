<?php 
session_start();
include '../../main/connect.php';

$id = $_GET['id'];

// 1. Ambil detail barang yang dibeli untuk mengembalikan stok
$detail = mysqli_query($conn, "SELECT * FROM detailpenjualan WHERE PenjualanID = '$id'");
while($d = mysqli_fetch_array($detail)){
    $produkID = $d['ProdukID'];
    $qty = $d['JumlahProduk'];
    
    // Tambahkan kembali stoknya
    mysqli_query($conn, "UPDATE produk SET Stok = Stok + $qty WHERE ProdukID = '$produkID'");
}

// 2. Hapus data di detailpenjualan
mysqli_query($conn, "DELETE FROM detailpenjualan WHERE PenjualanID = '$id'");

// 3. Hapus data di penjualan
mysqli_query($conn, "DELETE FROM penjualan WHERE PenjualanID = '$id'");

header("location:index.php?pesan=terhapus");
?>