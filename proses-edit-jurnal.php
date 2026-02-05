<?php
session_start();
require 'koneksi.php';

// Only allow admin to edit journals
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$id_jurnal = $_POST['id_jurnal'];
$tanggal = $_POST['tanggal'];
$keterangan = $_POST['keterangan'];
$akun = $_POST['akun'];
$debit = $_POST['debit'];
$kredit = $_POST['kredit'];

// Validate that we have at least one line
if(empty($akun)) {
    header("location:jurnal-umum.php?pesan=tidak_ada_baris");
    exit();
}

// Validate that debit and credit totals are equal
$total_debit = array_sum(array_map('floatval', $debit));
$total_kredit = array_sum(array_map('floatval', $kredit));

if(abs($total_debit - $total_kredit) > 0.01) { // Allow small rounding differences
    header("location:jurnal-umum.php?pesan=jurnal_tidak_seimbang");
    exit();
}

// Begin transaction
mysqli_begin_transaction($koneksi);

try {
    // Update journal header
    $update_header = mysqli_query($koneksi, "UPDATE journal_entries SET tanggal='$tanggal', keterangan='$keterangan' WHERE id_jurnal=$id_jurnal");
    
    if(!$update_header) {
        throw new Exception("Gagal memperbarui header jurnal");
    }
    
    // Delete existing journal lines
    $delete_lines = mysqli_query($koneksi, "DELETE FROM journal_lines WHERE id_jurnal=$id_jurnal");
    
    if(!$delete_lines) {
        throw new Exception("Gagal menghapus baris jurnal lama");
    }
    
    // Insert new journal lines
    for($i = 0; $i < count($akun); $i++) {
        $id_akun = $akun[$i];
        $dr = floatval($debit[$i]);
        $cr = floatval($kredit[$i]);
        
        if($dr != 0 || $cr != 0) { // Only insert if there's a value
            $insert_line = mysqli_query($koneksi, "INSERT INTO journal_lines (id_jurnal, id_akun, debit, kredit) VALUES ($id_jurnal, $id_akun, $dr, $cr)");
            
            if(!$insert_line) {
                throw new Exception("Gagal menyimpan baris jurnal");
            }
        }
    }
    
    // Commit transaction
    mysqli_commit($koneksi);
    
    header("location:jurnal-umum.php?pesan=berhasil_ubah");
    
} catch(Exception $e) {
    // Rollback transaction
    mysqli_rollback($koneksi);
    header("location:jurnal-umum.php?pesan=gagal&error=".$e->getMessage());
}
?>