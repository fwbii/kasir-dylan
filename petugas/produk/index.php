<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Stok Produk - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0 text-primary">Cek Stok Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok Tersisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $sql = mysqli_query($conn, "SELECT * FROM produk");
                            while($d = mysqli_fetch_array($sql)){
                                $status = ($d['Stok'] > 0) ? "<span class='badge bg-success'>Tersedia</span>" : "<span class='badge bg-danger'>Habis</span>";
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $d['NamaProduk']; ?></td>
                                <td>Rp <?= number_format($d['Harga']); ?></td>
                                <td><?= $d['Stok']; ?></td>
                                <td><?= $status; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>