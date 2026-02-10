<?php
session_start();
include '../../main/connect.php';
date_default_timezone_set('Asia/Jakarta');

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$nama = mysqli_real_escape_string($conn, $_POST['NamaPelanggan']);
$tgl  = date('Y-m-d H:i:s'); 
$pids = $_POST['ProdukID'];
$qtys = $_POST['Jumlah'];

// 1. Simpan Pelanggan
mysqli_query($conn, "INSERT INTO pelanggan (NamaPelanggan) VALUES ('$nama')");
$id_pelanggan = mysqli_insert_id($conn);

// 2. Simpan Penjualan
mysqli_query($conn, "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES ('$tgl', 0, '$id_pelanggan')");
$id_penjualan = mysqli_insert_id($conn);

$total_akhir = 0;

// 3. Loop Detail & Potong Stok
foreach($pids as $key => $id_produk) {
    $qty = $qtys[$key];
    
    // Ambil Harga & Stok Terkini
    $res = mysqli_query($conn, "SELECT Harga, Stok FROM produk WHERE ProdukID = '$id_produk'");
    $dp = mysqli_fetch_assoc($res);
    
    $subtotal = $dp['Harga'] * $qty;
    $total_akhir += $subtotal;

    // Insert Detail
    mysqli_query($conn, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                         VALUES ('$id_penjualan', '$id_produk', '$qty', '$subtotal')");

    // Potong Stok
    $stok_baru = $dp['Stok'] - $qty;
    mysqli_query($conn, "UPDATE produk SET Stok = '$stok_baru' WHERE ProdukID = '$id_produk'");
}

// 4. Update Total Akhir
mysqli_query($conn, "UPDATE penjualan SET TotalHarga = '$total_akhir' WHERE PenjualanID = '$id_penjualan'");

// 5. ALUR TERBAIK: Langsung buka Nota/Detail
header("location:detail.php?id=$id_penjualan");
exit;
?>