<?php
session_start();
require 'koneksi.php';

// Only allow admin to edit COA
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$id_akun = $_POST['id_akun'];
$nomor_akun = $_POST['nomor_akun'];
$nama_akun = $_POST['nama_akun'];
$jenis_akun = $_POST['jenis_akun'];
$saldo_normal = $_POST['saldo_normal'];
$kategori_arus_kas = $_POST['kategori_arus_kas'];

// Check if account number already exists (excluding current record)
$check_akun = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE nomor_akun='$nomor_akun' AND id_akun != '$id_akun'");
if(mysqli_num_rows($check_akun) > 0) {
    header("location:coa.php?pesan=nomor_akun_exists");
    exit();
}

// Update COA
$query = mysqli_query($koneksi, "UPDATE chart_of_accounts SET nomor_akun='$nomor_akun', nama_akun='$nama_akun', jenis_akun='$jenis_akun', saldo_normal='$saldo_normal', kategori_arus_kas='$kategori_arus_kas' WHERE id_akun='$id_akun'");

if($query) {
    header("location:coa.php?pesan=berhasil_ubah");
} else {
    header("location:coa.php?pesan=gagal");
}
?>