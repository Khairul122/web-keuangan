<?php
require 'cek-sesi.php';
require_once 'vendor/vendor/autoload.php';
require_once 'koneksi.php';

use Mpdf\Mpdf;

$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

$tanggal_awal_raw = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir_raw = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');

$tanggal_awal = mysqli_real_escape_string($koneksi, $tanggal_awal_raw);
$tanggal_akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir_raw);

// Samakan logika saldo awal dengan arus-kas-auto.php
$sql_saldo_awal = "SELECT COALESCE(SUM(jl.debit - jl.kredit), 0) AS saldo_awal
                   FROM journal_lines jl
                   INNER JOIN journal_entries je ON je.id_jurnal = jl.id_jurnal
                   INNER JOIN chart_of_accounts ca ON ca.id_akun = jl.id_akun
                   WHERE ca.nomor_akun LIKE '1-%'
                     AND (ca.nama_akun LIKE '%Kas%' OR ca.nama_akun LIKE '%Bank%')
                     AND je.tanggal < '$tanggal_awal'";
$result_saldo_awal = mysqli_query($koneksi, $sql_saldo_awal);
$saldo_awal = 0;
if ($result_saldo_awal && $row_saldo = mysqli_fetch_assoc($result_saldo_awal)) {
    $saldo_awal = (float)$row_saldo['saldo_awal'];
}

// Samakan logika data arus kas dengan arus-kas-auto.php
$sql_data = "SELECT
                je.id_jurnal,
                je.nomor_jurnal,
                je.tanggal,
                je.keterangan,
                COALESCE(SUM(CASE WHEN ca.nomor_akun LIKE '1-%' AND (ca.nama_akun LIKE '%Kas%' OR ca.nama_akun LIKE '%Bank%') THEN jl.debit ELSE 0 END), 0) AS penerimaan,
                COALESCE(SUM(CASE WHEN ca.nomor_akun LIKE '1-%' AND (ca.nama_akun LIKE '%Kas%' OR ca.nama_akun LIKE '%Bank%') THEN jl.kredit ELSE 0 END), 0) AS pengeluaran
             FROM journal_entries je
             INNER JOIN journal_lines jl ON je.id_jurnal = jl.id_jurnal
             INNER JOIN chart_of_accounts ca ON jl.id_akun = ca.id_akun
             WHERE je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
             GROUP BY je.id_jurnal, je.nomor_jurnal, je.tanggal, je.keterangan
             HAVING penerimaan > 0 OR pengeluaran > 0
             ORDER BY je.tanggal ASC, je.id_jurnal ASC";

$result_data = mysqli_query($koneksi, $sql_data);

$rows = [];
$total_penerimaan = 0;
$total_pengeluaran = 0;
$saldo_berjalan = $saldo_awal;

if ($result_data) {
    while ($row = mysqli_fetch_assoc($result_data)) {
        $penerimaan = (float)$row['penerimaan'];
        $pengeluaran = (float)$row['pengeluaran'];

        $saldo_berjalan += ($penerimaan - $pengeluaran);

        $row['saldo'] = $saldo_berjalan;
        $rows[] = $row;

        $total_penerimaan += $penerimaan;
        $total_pengeluaran += $pengeluaran;
    }
}

$saldo_hitung = $saldo_awal + $total_penerimaan - $total_pengeluaran;
$is_balance = abs($saldo_hitung - $saldo_berjalan) < 0.01;

$mpdf = new Mpdf();
ob_start();
?>
<style>
    body { font-family: sans-serif; font-size: 11px; }
    h1, h4 { text-align: center; margin: 0; }
    hr.custom-line { margin-top: 8px; margin-bottom: 16px; border: 0; border-top: 1px solid #000; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    table, th, td { border: 1px solid #000; }
    th, td { padding: 6px; }
    .right { text-align: right; }
    .center { text-align: center; }
    .head { background: #efefef; font-weight: bold; }
    .total { background: #efefef; font-weight: bold; }
</style>

<h1>CV BINA PADI SABATANG</h1>
<h4>Jl. Pulai, Batang Kabung Ganting</h4>
<h4>Kec. Koto Tangah, Kota Padang, Sumatera Barat 25586</h4>
<hr class="custom-line">

<h4>LAPORAN ARUS KAS OTOMATIS (DARI JURNAL)</h4>
<h4>Periode: <?php echo date('d F Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d F Y', strtotime($tanggal_akhir)); ?></h4>

<table>
    <thead>
        <tr class="head">
            <th style="width:6%">No</th>
            <th style="width:34%">Keterangan</th>
            <th style="width:16%">Bukti/Ref</th>
            <th style="width:15%" class="right">Penerimaan (Debit)</th>
            <th style="width:15%" class="right">Pengeluaran (Kredit)</th>
            <th style="width:14%" class="right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5"><strong>Saldo Awal Periode</strong></td>
            <td class="right"><strong><?php echo number_format($saldo_awal, 0, ',', '.'); ?></strong></td>
        </tr>

        <?php if (count($rows) > 0): ?>
            <?php $no = 1; ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nomor_jurnal']); ?></td>
                    <td class="right"><?php echo number_format($row['penerimaan'], 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($row['pengeluaran'], 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($row['saldo'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="center">Tidak ada data arus kas pada periode ini</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr class="total">
            <td colspan="3">Total</td>
            <td class="right"><?php echo number_format($total_penerimaan, 0, ',', '.'); ?></td>
            <td class="right"><?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></td>
            <td class="right"><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Status: <?php echo $is_balance ? 'Balance' : 'Tidak Balance'; ?></strong></td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 20px; text-align: right;">
    Padang, <?php echo date('j F Y'); ?><br>
    <?php echo htmlspecialchars($nama_pimpinan); ?><br><br><br><br>
    (<?php echo htmlspecialchars($nama_pimpinan); ?>)
</div>

<?php
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->Output('Laporan_Arus_Kas_Otomatis.pdf', 'I');

mysqli_close($koneksi);
exit;
?>
