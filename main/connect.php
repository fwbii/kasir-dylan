<?php
$host     = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "kasir"; 

$conn = mysqli_connect($host, $username, $password, $database);
date_default_timezone_set('Asia/Jakarta');
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


?>
