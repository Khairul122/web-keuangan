<?php
session_start();
require 'koneksi.php';

// Only allow admin to delete journals
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

$id_jurnal = $_GET['id_jurnal'];

// Begin transaction
mysqli_begin_transaction($koneksi);

try {
    // Delete journal lines first
    $delete_lines = mysqli_query($koneksi, "DELETE FROM journal_lines WHERE id_jurnal=$id_jurnal");
    
    if(!$delete_lines) {
        throw new Exception("Gagal menghapus baris jurnal");
    }
    
    // Delete journal header
    $delete_header = mysqli_query($koneksi, "DELETE FROM journal_entries WHERE id_jurnal=$id_jurnal");
    
    if(!$delete_header) {
        throw new Exception("Gagal menghapus header jurnal");
    }
    
    // Commit transaction
    mysqli_commit($koneksi);
    
    header("location:jurnal-umum.php?pesan=berhasil_hapus");
    
} catch(Exception $e) {
    // Rollback transaction
    mysqli_rollback($koneksi);
    header("location:jurnal-umum.php?pesan=gagal&error=".$e->getMessage());
}
?>