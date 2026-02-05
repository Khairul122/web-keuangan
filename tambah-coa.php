<?php
session_start();
require 'koneksi.php';

// Only allow admin to add new COA
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$nomor_akun = $_POST['nomor_akun'];
$nama_akun = $_POST['nama_akun'];
$jenis_akun = $_POST['jenis_akun'];
$saldo_normal = $_POST['saldo_normal'];
$kategori_arus_kas = $_POST['kategori_arus_kas'];

// Check if account number already exists
$check_akun = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE nomor_akun='$nomor_akun'");
if(mysqli_num_rows($check_akun) > 0) {
    header("location:coa.php?pesan=nomor_akun_exists");
    exit();
}

// Insert new COA
$query = mysqli_query($koneksi, "INSERT INTO chart_of_accounts (nomor_akun, nama_akun, jenis_akun, saldo_normal, kategori_arus_kas, is_active) VALUES ('$nomor_akun', '$nama_akun', '$jenis_akun', '$saldo_normal', '$kategori_arus_kas', 1)");

if($query) {
    header("location:coa.php?pesan=berhasil_tambah");
} else {
    header("location:coa.php?pesan=gagal");
}
?>