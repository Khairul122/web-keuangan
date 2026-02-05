<?php
// Sisipkan file koneksi.php yang berisi koneksi ke database
include('koneksi.php');

// Tangkap data yang dikirimkan melalui form
$tanggal = $_POST['tanggal'];
$sumber = $_POST['sumber'];
$jumlah = $_POST['jumlah'];
$status = $_POST['status'];
$id_user = 1; // Tetapkan id_user sebagai 1

// Buat query untuk menyimpan data ke dalam tabel laba_rugi
$query = "INSERT INTO laba_rugi (tanggal, sumber, jumlah, status, id_user) VALUES ('$tanggal', '$sumber', '$jumlah', '$status', '$id_user')";

// Jalankan query
$result = mysqli_query($koneksi, $query);

// Periksa apakah query berhasil dijalankan
if ($result) {
    echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "laba-rugi.php";</script>';
} else {
    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
}

// Tutup koneksi ke database
mysqli_close($koneksi);
