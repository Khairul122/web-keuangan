<?php
// Sisipkan file koneksi.php yang berisi koneksi ke database
include('koneksi.php');

// Ambil nilai dari formulir
$tanggal = $_POST['tanggal'];
$sumber = $_POST['sumber'];
$jumlah = $_POST['jumlah'];
$status = $_POST['status'];
$id_akun = $_POST['id_akun'] ?? NULL; // Ambil id_akun dari formulir, jika tidak ada set NULL

// Siapkan pernyataan SQL INSERT
if ($id_akun) {
    $sql = "INSERT INTO arus_kas (tanggal, sumber, jumlah, status, id_akun) VALUES ('$tanggal', '$sumber', '$jumlah', '$status', '$id_akun')";
} else {
    $sql = "INSERT INTO arus_kas (tanggal, sumber, jumlah, status) VALUES ('$tanggal', '$sumber', '$jumlah', '$status')";
}

// Lakukan pengecekan apakah data berhasil dimasukkan ke dalam database atau tidak
if ($koneksi->query($sql) === TRUE) {
    // Jika berhasil, arahkan pengguna ke halaman arus_kas.php
    echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "arus-kas.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

// Tutup koneksi (tidak diperlukan jika Anda menggunakan koneksi persistent)
$koneksi->close();
