<?php
//include('dbconnected.php');
include('koneksi.php');

$nama = $_GET['nama'];
$posisi = $_GET['posisi'];
$alamat = $_GET['alamat'];
$umur = $_GET['umur'];
$kontak = $_GET['kontak'];

// Validasi data kosong
if (empty($nama) || empty($posisi) || empty($alamat) || empty($umur) || empty($kontak)) {
    echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
    exit;
}

// Validasi umur harus angka dan dalam range yang wajar
if (!is_numeric($umur) || $umur < 17 || $umur > 60) {
    echo "<script>alert('Umur harus berupa angka antara 17-60 tahun!'); window.history.back();</script>";
    exit;
}

//query insert
$query = mysqli_query($koneksi,"INSERT INTO `karyawan` (`id_karyawan`, `nama`, `posisi`, `alamat`, `umur`, `kontak`) VALUES (null, '$nama', '$posisi', '$alamat', '$umur', '$kontak')");

if ($query) {
 # credirect ke page index
 header("location:karyawan.php");
}
else{
 echo "ERROR, data gagal diupdate". mysqli_error($koneksi);
}

//mysql_close($host);
?>