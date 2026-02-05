<?php
require_once 'koneksi.php';
require_once 'includes/functions-jurnal.php';

$tgl_pemasukan = mysqli_real_escape_string($koneksi, $_GET['tgl_pemasukan']);
$jumlah = floatval($_GET['jumlah']);
$sumber = mysqli_real_escape_string($koneksi, $_GET['sumber']);
$id_akun_kas = intval($_GET['id_akun_kas'] ?? 0);
$id_akun_pendapatan = intval($_GET['id_akun_pendapatan'] ?? 0);
$id_user = $_SESSION['id'] ?? 1;

if (empty($tgl_pemasukan) || empty($jumlah) || empty($sumber)) {
    echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
    exit;
}

if (!is_numeric($jumlah) || $jumlah <= 0) {
    echo "<script>alert('Jumlah harus berupa angka dan lebih dari 0!'); window.history.back();</script>";
    exit;
}

if (empty($id_akun_kas) || empty($id_akun_pendapatan)) {
    echo "<script>alert('Harap pilih akun kas dan akun pendapatan!'); window.history.back();</script>";
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $query = mysqli_query($koneksi,
        "INSERT INTO `pemasukan` (`tgl_pemasukan`, `jumlah`, `sumber`, `id_akun_kas`, `id_akun_pendapatan`)
         VALUES ('$tgl_pemasukan', '$jumlah', '$sumber', $id_akun_kas, $id_akun_pendapatan)");

    if (!$query) {
        throw new Exception("Gagal menyimpan data pemasukan: " . mysqli_error($koneksi));
    }

    $id_pemasukan = mysqli_insert_id($koneksi);

    $result_jurnal = buatJurnalPemasukan($id_pemasukan, $koneksi);

    if (!$result_jurnal['success']) {
        throw new Exception($result_jurnal['message']);
    }

    mysqli_commit($koneksi);

    echo "<script>
        alert('✅ Data pemasukan berhasil ditambahkan!\\n\\nJurnal {$result_jurnal['nomor_jurnal']} telah dibuat secara otomatis.');
        window.location.href = 'pendapatan.php';
    </script>";

} catch (Exception $e) {
    mysqli_rollback($koneksi);

    echo "<script>
        alert('❌ Terjadi kesalahan:\\n\\n" . addslashes($e->getMessage()) . "');
        window.history.back();
    </script>";
}

mysqli_close($koneksi);
?>
