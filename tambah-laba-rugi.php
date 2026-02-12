<?php
// Sisipkan file koneksi.php yang berisi koneksi ke database
include('koneksi.php');

// Tangkap data yang dikirimkan melalui form
$tanggal = $_POST['tanggal'];
$sumber = $_POST['sumber'];
$jumlah = $_POST['jumlah'];
$status = $_POST['status'];
$id_akun = $_POST['id_akun'] ?? NULL; // Ambil id_akun dari formulir, jika tidak ada set NULL

// Buat query untuk menyimpan data ke dalam tabel laba_rugi
if ($id_akun) {
    $query = "INSERT INTO laba_rugi (tanggal, sumber, jumlah, status, id_akun) VALUES ('$tanggal', '$sumber', '$jumlah', '$status', '$id_akun')";
} else {
    $query = "INSERT INTO laba_rugi (tanggal, sumber, jumlah, status) VALUES ('$tanggal', '$sumber', '$jumlah', '$status')";
}

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
