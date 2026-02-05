<?php
session_start();
require 'koneksi.php';

// Only allow admin to deactivate COA
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$id_akun = $_GET['id_akun'];

// Check if account is used in any journal entries
$check_usage = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM journal_lines WHERE id_akun = '$id_akun'");
$result = mysqli_fetch_assoc($check_usage);

if($result['count'] > 0) {
    header("location:coa.php?pesan=akun_digunakan");
    exit();
}

// Deactivate COA
$query = mysqli_query($koneksi, "UPDATE chart_of_accounts SET is_active=0 WHERE id_akun='$id_akun'");

if($query) {
    header("location:coa.php?pesan=berhasil_nonaktif");
} else {
    header("location:coa.php?pesan=gagal");
}
?>