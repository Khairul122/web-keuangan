<?php
session_start();

include 'koneksi.php';

$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$pass = mysqli_real_escape_string($koneksi, $_POST['pass']);

// Query to get user data including level
$data = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email' AND pass='$pass'");

$cek = mysqli_num_rows($data);

if($cek > 0) {
    $sesi = mysqli_fetch_assoc($data);
    
    // Store user information in session
    $_SESSION['id'] = $sesi['id_admin'];
    $_SESSION['nama'] = $sesi['nama'];
    $_SESSION['email'] = $sesi['email'];
    $_SESSION['level'] = $sesi['level']; // Store user level (admin or pemilik)
    $_SESSION['status'] = "login";
    
    header("location:index.php");
} else {
    header("location:login.php?pesan=gagal");
}
?>