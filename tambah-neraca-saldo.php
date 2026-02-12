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
$id_akun = $_POST['id_akun'] ?? NULL; // Ambil id_akun dari formulir, jika tidak ada set NULL

// Buat query SQL untuk menyimpan data ke dalam database
if ($id_akun) {
    $sql = "INSERT INTO neraca_saldo (nama_akun, saldo_awal_debit, saldo_awal_kredit, pergerakan_debit, pergerakan_kredit, status, tanggal, id_akun)
            VALUES ('$nama_akun', '$saldo_awal_debit', '$saldo_awal_kredit', '$pergerakan_debit', '$pergerakan_kredit', '$status', '$tanggal', '$id_akun')";
} else {
    $sql = "INSERT INTO neraca_saldo (nama_akun, saldo_awal_debit, saldo_awal_kredit, pergerakan_debit, pergerakan_kredit, status, tanggal)
            VALUES ('$nama_akun', '$saldo_awal_debit', '$saldo_awal_kredit', '$pergerakan_debit', '$pergerakan_kredit', '$status', '$tanggal')";
}

// Jalankan query
if ($koneksi->query($sql) === TRUE) {
    echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "neraca-saldo.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

// Tutup koneksi
$koneksi->close();
?>
