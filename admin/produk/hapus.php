<?php 
session_start();
include '../../main/connect.php';

// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    </style>
</head>
<body>

<?php 
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. CEK: Apakah produk ini sudah pernah terjual? 
    // Jika ada di tabel detailpenjualan, produk tidak boleh dihapus agar data laporan tetap akurat.
    $cek_transaksi = mysqli_query($conn, "SELECT * FROM detailpenjualan WHERE ProdukID='$id'");
    
    if (mysqli_num_rows($cek_transaksi) > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tidak Bisa Dihapus!',
                text: 'Produk ini sudah memiliki riwayat transaksi. Gunakan fitur edit untuk menonaktifkan atau mengubah stok.',
                confirmButtonColor: '#0d6efd'
            }).then(() => { window.location.href = 'index.php'; });
        </script>";
    } else {
        // 2. PROSES HAPUS (Jika tidak ada riwayat transaksi)
        $query = mysqli_query($conn, "DELETE FROM produk WHERE ProdukID='$id'");

        if ($query) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data produk telah dihapus dari sistem.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => { window.location.href = 'index.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus data.',
                    confirmButtonColor: '#0d6efd'
                }).then(() => { window.location.href = 'index.php'; });
            </script>";
        }
    }
} else {
    header("location:index.php");
}
?>

</body>
</html>