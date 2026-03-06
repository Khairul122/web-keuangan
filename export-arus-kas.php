<?php
require_once 'vendor/vendor/autoload.php';
use Mpdf\Mpdf;

session_start();

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if ($bulan < 1 || $bulan > 12) {
    $bulan = (int)date('n');
}
if ($tahun < 2000 || $tahun > 2100) {
    $tahun = (int)date('Y');
}

$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));
$periode_label = date('F Y', strtotime($tanggal_awal));
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

include('koneksi.php');

$sql = "SELECT id_arus_kas, tanggal, sumber, jumlah, kas_awal
        FROM arus_kas
        WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
        ORDER BY tanggal ASC, id_arus_kas ASC";
$result = $koneksi->query($sql);

$data_rows = [];
$kas_awal_periode = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($kas_awal_periode === 0 && (int)$row['kas_awal'] !== 0) {
            $kas_awal_periode = (int)$row['kas_awal'];
        }
        $data_rows[] = $row;
    }
}

$saldo_berjalan = $kas_awal_periode;
$total_penerimaan = 0;
$total_pengeluaran = 0;

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

<h4>LAPORAN ARUS KAS</h4>
<h4>Periode: <?php echo $periode_label; ?></h4>

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
            <td class="right"><strong><?php echo number_format($kas_awal_periode, 0, ',', '.'); ?></strong></td>
        </tr>

        <?php if (count($data_rows) > 0): ?>
            <?php $no = 1; ?>
            <?php foreach ($data_rows as $row): ?>
                <?php
                $jumlah = (int)$row['jumlah'];
                $penerimaan = $jumlah > 0 ? $jumlah : 0;
                $pengeluaran = $jumlah < 0 ? abs($jumlah) : 0;

                $total_penerimaan += $penerimaan;
                $total_pengeluaran += $pengeluaran;
                $saldo_berjalan += ($penerimaan - $pengeluaran);
                ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['sumber']); ?></td>
                    <td><?php echo 'AK-' . str_pad($row['id_arus_kas'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td class="right"><?php echo number_format($penerimaan, 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($pengeluaran, 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="center">Tidak ada data pada periode ini</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <?php
        $saldo_hitung = $kas_awal_periode + $total_penerimaan - $total_pengeluaran;
        $status_balance = (abs($saldo_hitung - $saldo_berjalan) < 0.01) ? 'Balance' : 'Tidak Balance';
        ?>
        <tr class="total">
            <td colspan="3">Total</td>
            <td class="right"><?php echo number_format($total_penerimaan, 0, ',', '.'); ?></td>
            <td class="right"><?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></td>
            <td class="right"><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Status: <?php echo $status_balance; ?></strong></td>
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
$mpdf->Output('Laporan_Arus_Kas.pdf', 'I');
exit;
?>
