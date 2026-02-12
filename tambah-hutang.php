<?php
//include('dbconnected.php');
include('koneksi.php');

$jumlah = $_GET['jumlah'];
$tgl_hutang = $_GET['tgl_hutang'];
$penghutang = $_GET['penghutang'];
$alasan = $_GET['alasan'];
$id_akun_debet = $_GET['id_akun_debet'] ?? NULL; // Ambil id_akun_debet dari formulir, jika tidak ada set NULL
$id_akun_kredit = $_GET['id_akun_kredit'] ?? NULL; // Ambil id_akun_kredit dari formulir, jika tidak ada set NULL


//query update
if ($id_akun_debet && $id_akun_kredit) {
    $query = mysqli_query($koneksi,"INSERT INTO `hutang` (`jumlah`, `tgl_hutang`, `alasan`, `penghutang`, `id_akun_debet`, `id_akun_kredit`) VALUES ('$jumlah', '$tgl_hutang', '$alasan','$penghutang', '$id_akun_debet', '$id_akun_kredit')");
} elseif ($id_akun_debet) {
    $query = mysqli_query($koneksi,"INSERT INTO `hutang` (`jumlah`, `tgl_hutang`, `alasan`, `penghutang`, `id_akun_debet`) VALUES ('$jumlah', '$tgl_hutang', '$alasan','$penghutang', '$id_akun_debet')");
} elseif ($id_akun_kredit) {
    $query = mysqli_query($koneksi,"INSERT INTO `hutang` (`jumlah`, `tgl_hutang`, `alasan`, `penghutang`, `id_akun_kredit`) VALUES ('$jumlah', '$tgl_hutang', '$alasan','$penghutang', '$id_akun_kredit')");
} else {
    $query = mysqli_query($koneksi,"INSERT INTO `hutang` (`jumlah`, `tgl_hutang`, `alasan`, `penghutang`) VALUES ('$jumlah', '$tgl_hutang', '$alasan','$penghutang')");
}

if ($query) {
 # credirect ke page index
 header("location:hutang.php");
}
else{
 echo "ERROR, data gagal diupdate". mysqli_error($koneksi);
}

//mysql_close($host);
?>