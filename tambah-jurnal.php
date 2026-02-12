<?php
session_start();
require 'koneksi.php';

// Only allow admin to add new journals
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php?pesan=forbidden");
    exit();
}

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

// Generate journal number
$nomor_jurnal = generateNomorJurnal($tanggal, $koneksi);

// Begin transaction
mysqli_begin_transaction($koneksi);

try {
    // Insert journal header
    $insert_header = mysqli_query($koneksi, "INSERT INTO journal_entries (nomor_jurnal, tanggal, keterangan, id_ref_transaksi, tipe_ref_transaksi) VALUES ('$nomor_jurnal', '$tanggal', '$keterangan', NULL, 'manual')");
    
    if(!$insert_header) {
        throw new Exception("Gagal menyimpan header jurnal");
    }
    
    $id_jurnal = mysqli_insert_id($koneksi);
    
    // Insert journal lines
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
    
    header("location:jurnal-umum.php?pesan=berhasil_tambah");
    
} catch(Exception $e) {
    // Rollback transaction
    mysqli_rollback($koneksi);
    header("location:jurnal-umum.php?pesan=gagal&error=".$e->getMessage());
}
?>

<?php
// Function to generate journal number
function generateNomorJurnal($tanggal, $koneksi) {
    $date = date('Ymd', strtotime($tanggal));

    $sql = "SELECT nomor_jurnal FROM journal_entries
            WHERE nomor_jurnal LIKE 'JV-$date%'
            ORDER BY id_jurnal DESC LIMIT 1";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $last_nomor = mysqli_fetch_assoc($result)['nomor_jurnal'];
        $last_urut = intval(substr($last_nomor, -4));
        $new_urut = $last_urut + 1;
    } else {
        $new_urut = 1;
    }

    $nomor_jurnal = 'JV-' . $date . '-' . str_pad($new_urut, 4, '0', STR_PAD_LEFT);
    return $nomor_jurnal;
}
?>