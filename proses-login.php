<?php
session_start();

include 'koneksi.php';

$email =mysqli_real_escape_string($koneksi,$_POST['email']);
$pass =mysqli_real_escape_string($koneksi, $_POST['pass']);

$data = mysqli_query($koneksi,"select * from admin where email='$email' and pass='$pass'");

$cek = mysqli_num_rows($data);

if($cek > 0){
$sesi = mysqli_query($koneksi,"select * from admin where email='$email' and pass='$pass'");
$sesi = mysqli_fetch_assoc($sesi);
	$_SESSION['id'] = $sesi['id_admin'];
	$_SESSION['nama'] = $sesi['nama'];
	$_SESSION['status'] = "login";
	header("location:index.php");
}else{
	header("location:login.php?pesan=gagal");
}
?>