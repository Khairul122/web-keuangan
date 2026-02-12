<?php
require_once 'koneksi.php';
require_once 'includes/functions-jurnal.php';

$tgl_pengeluaran = mysqli_real_escape_string($koneksi, $_GET['tgl_pengeluaran']);
$jumlah = floatval($_GET['jumlah']);
$sumber = mysqli_real_escape_string($koneksi, $_GET['sumber']);
$id_akun_beban = intval($_GET['id_akun_beban'] ?? 0);
$id_akun_kas = intval($_GET['id_akun_kas'] ?? 0);

if (empty($tgl_pengeluaran) || empty($jumlah) || empty($sumber)) {
    echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
    exit;
}

if (!is_numeric($jumlah) || $jumlah <= 0) {
    echo "<script>alert('Jumlah harus berupa angka dan lebih dari 0!'); window.history.back();</script>";
    exit;
}

if (empty($id_akun_beban) || empty($id_akun_kas)) {
    echo "<script>alert('Harap pilih akun beban dan akun kas!'); window.history.back();</script>";
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $query = mysqli_query($koneksi,
        "INSERT INTO `pengeluaran` (`tgl_pengeluaran`, `jumlah`, `sumber`, `id_akun_beban`, `id_akun_kas`)
         VALUES ('$tgl_pengeluaran', '$jumlah', '$sumber', $id_akun_beban, $id_akun_kas)");

    if (!$query) {
        throw new Exception("Gagal menyimpan data pengeluaran: " . mysqli_error($koneksi));
    }

    $id_pengeluaran = mysqli_insert_id($koneksi);

    $result_jurnal = buatJurnalPengeluaran($id_pengeluaran, $koneksi);

    if (!$result_jurnal['success']) {
        throw new Exception($result_jurnal['message']);
    }

    mysqli_commit($koneksi);

    echo "<script>
        alert('✅ Data pengeluaran berhasil ditambahkan!\\n\\nJurnal {$result_jurnal['nomor_jurnal']} telah dibuat secara otomatis.');
        window.location.href = 'pengeluaran.php';
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
