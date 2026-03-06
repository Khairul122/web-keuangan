<?php
require 'cek-sesi.php';
include('koneksi.php');

$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$sumber = mysqli_real_escape_string($koneksi, $_POST['sumber']);
$jumlah = intval($_POST['jumlah']);
$kas_awal = isset($_POST['kas_awal']) ? intval($_POST['kas_awal']) : 0;
$status = intval($_POST['status']);
$id_akun = isset($_POST['id_akun']) && $_POST['id_akun'] !== '' ? intval($_POST['id_akun']) : null;

if ($status < 1 || $status > 3) {
    echo '<script>alert("Kategori aktivitas tidak valid"); window.location.href = "arus-kas-tambah.php";</script>';
    exit;
}

if ($id_akun !== null) {
    $sql = "INSERT INTO arus_kas (tanggal, sumber, jumlah, kas_awal, status, id_akun)
            VALUES ('$tanggal', '$sumber', '$jumlah', '$kas_awal', '$status', '$id_akun')";
} else {
    $sql = "INSERT INTO arus_kas (tanggal, sumber, jumlah, kas_awal, status)
            VALUES ('$tanggal', '$sumber', '$jumlah', '$kas_awal', '$status')";
}

if ($koneksi->query($sql) === TRUE) {
    echo '<script>alert("Data arus kas berhasil ditambahkan"); window.location.href = "arus-kas.php";</script>';
} else {
    echo "Error: " . $koneksi->error;
}

$koneksi->close();
?>
