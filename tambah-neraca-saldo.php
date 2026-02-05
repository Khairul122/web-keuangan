<?php
// Sisipkan file koneksi.php yang berisi koneksi ke database
include('koneksi.php');

// Tangkap data dari formulir
$nama_akun = $_POST['nama_akun'];
$saldo_awal_debit = $_POST['saldo_awal_debit'];
$saldo_awal_kredit = $_POST['saldo_awal_kredit'];
$pergerakan_debit = $_POST['pergerakan_debit'];
$pergerakan_kredit = $_POST['pergerakan_kredit'];
$status = $_POST['status'];
$tanggal = $_POST['tanggal'];
$id_user = 1; // Set id_user menjadi 1

// Buat query SQL untuk menyimpan data ke dalam database
$sql = "INSERT INTO neraca_saldo (nama_akun, saldo_awal_debit, saldo_awal_kredit, pergerakan_debit, pergerakan_kredit, status, tanggal, id_user)
        VALUES ('$nama_akun', '$saldo_awal_debit', '$saldo_awal_kredit', '$pergerakan_debit', '$pergerakan_kredit', '$status', '$tanggal', '$id_user')";

// Jalankan query
if ($koneksi->query($sql) === TRUE) {
    echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "neraca-saldo.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

// Tutup koneksi
$koneksi->close();
?>
