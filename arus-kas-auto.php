<?php
require 'cek-sesi.php';
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laporan Arus Kas Otomatis</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<?php require('sidebar.php'); ?>

<div id="content">
    <?php require('navbar.php'); ?>

    <div class="container-fluid">
        <?php
        $tanggal_awal_raw = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
        $tanggal_akhir_raw = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');

        $tanggal_awal = mysqli_real_escape_string($koneksi, $tanggal_awal_raw);
        $tanggal_akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir_raw);

        // Saldo awal: net kas/bank sebelum periode
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

        // Data arus kas per jurnal dalam periode
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
        ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" value="<?php echo htmlspecialchars($tanggal_awal); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo htmlspecialchars($tanggal_akhir); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-bill-wave"></i> Arus Kas Otomatis (Dari Jurnal)</h6>
            </div>
            <div class="card-body">
                <h5 class="text-center mb-2">CV BINA PADI SABATANG</h5>
                <h6 class="text-center mb-4">Laporan Arus Kas<br>Periode: <?php echo date('d F Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d F Y', strtotime($tanggal_akhir)); ?></h6>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Bukti/Ref</th>
                                <th class="text-right">Penerimaan (Debit)</th>
                                <th class="text-right">Pengeluaran (Kredit)</th>
                                <th class="text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-light">
                                <td colspan="6"><strong>Saldo Awal Periode</strong></td>
                                <td class="text-right"><strong><?php echo number_format($saldo_awal, 0, ',', '.'); ?></strong></td>
                            </tr>

                            <?php if (count($rows) > 0): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nomor_jurnal']); ?></td>
                                        <td class="text-right"><?php echo number_format($row['penerimaan'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?php echo number_format($row['pengeluaran'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?php echo number_format($row['saldo'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data arus kas pada periode ini</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <td colspan="4"><strong>Total</strong></td>
                                <td class="text-right"><strong><?php echo number_format($total_penerimaan, 0, ',', '.'); ?></strong></td>
                                <td class="text-right"><strong><?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></strong></td>
                                <td class="text-right"><strong><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <strong>Status:</strong>
                                    <span class="badge badge-<?php echo $is_balance ? 'success' : 'danger'; ?>">
                                        <?php echo $is_balance ? 'Balance' : 'Tidak Balance'; ?>
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>
</div>

<?php require 'logout-modal.php'; ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
<?php mysqli_close($koneksi); ?>
