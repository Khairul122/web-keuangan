<?php
// Sisipkan file koneksi.php yang berisi koneksi ke database
include('koneksi.php');

// Ambil nilai dari formulir
$tanggal = $_POST['tanggal'];
$sumber = $_POST['sumber'];
$jumlah = $_POST['jumlah'];
$status = $_POST['status'];

// Tetapkan id_user (misalnya, di sini saya tetapkan sebagai 1)
$id_user = 1;

// Siapkan pernyataan SQL INSERT
$sql = "INSERT INTO arus_kas (tanggal, sumber, jumlah, status, id_user) VALUES ('$tanggal', '$sumber', '$jumlah', '$status', '$id_user')";

// Lakukan pengecekan apakah data berhasil dimasukkan ke dalam database atau tidak
if ($koneksi->query($sql) === TRUE) {
    // Jika berhasil, arahkan pengguna ke halaman arus_kas.php
    echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "arus-kas.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

// Tutup koneksi (tidak diperlukan jika Anda menggunakan koneksi persistent)
$koneksi->close();
