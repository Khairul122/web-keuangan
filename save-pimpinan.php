<?php
// ================================================================
// SAVE PIMPINAN - Simpan Nama Pimpinan ke Session
// CV BINA PADI SABATANG
// Tanggal: 19 Januari 2026
// ================================================================
//
// File ini menangani form submission dari laporan.php
// untuk menyimpan nama pimpinan ke SESSION (bukan database)
//
// ================================================================

// Start session
session_start();

// Cek apakah user sudah login
require 'cek-sesi.php';

// ================================================================
// 1. AMBIL DATA DARI FORM
// ================================================================

// Cek apakah form disubmit dengan method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil nama pimpinan dari form
    $pimpinan = isset($_POST['pimpinan']) ? trim($_POST['pimpinan']) : '';

    // Validasi input
    if (empty($pimpinan)) {
        // Jika kosong, set default value
        $pimpinan = 'Pimpinan';
    }

    // ================================================================
    // 2. SIMPAN KE SESSION (BUKAN DATABASE)
    // ================================================================

    // Simpan nama pimpinan ke session
    $_SESSION['pimpinan'] = $pimpinan;

    // Set success message
    $_SESSION['success_message'] = "Nama pimpinan berhasil disimpan!";

} else {
    // Jika bukan POST request
    $_SESSION['error_message'] = "Metode request tidak valid!";
}

// ================================================================
// 3. REDIRECT KEMBALI KE LAPORAN.PHP
// ================================================================

header("Location: laporan.php");
exit;

// ================================================================
// SELESAI
// ================================================================
?>
