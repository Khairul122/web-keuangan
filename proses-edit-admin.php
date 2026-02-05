<?php
session_start();
require 'koneksi.php';

// Only allow pemilik to add new admins
if ($_SESSION['level'] !== 'pemilik') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$nama = $_POST['nama'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$level = $_POST['level'];

// Check if email already exists
$check_email = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email'");
if(mysqli_num_rows($check_email) > 0) {
    header("location:tambah-admin.php?pesan=email_exists");
    exit();
}

// Insert new admin
$query = mysqli_query($koneksi, "INSERT INTO admin (nama, email, pass, level) VALUES ('$nama', '$email', '$pass', '$level')");

if($query) {
    header("location:index.php?pesan=berhasil_tambah");
} else {
    header("location:tambah-admin.php?pesan=gagal");
}
?>