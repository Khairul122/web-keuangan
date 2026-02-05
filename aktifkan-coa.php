<?php
session_start();
require 'koneksi.php';

// Only allow admin to activate COA
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$id_akun = $_GET['id_akun'];

// Activate COA
$query = mysqli_query($koneksi, "UPDATE chart_of_accounts SET is_active=1 WHERE id_akun='$id_akun'");

if($query) {
    header("location:coa.php?pesan=berhasil_aktif");
} else {
    header("location:coa.php?pesan=gagal");
}
?>