<?php
require 'cek-sesi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan Arus Kas</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

<?php
require 'koneksi.php';
require 'sidebar.php';

$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');

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
$no = 1;
?>

<div id="content">
    <?php require 'navbar.php'; ?>

    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Arus Kas (Format Buku Kas)</h6>
            </div>

            <div class="card-body border-bottom">
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" value="<?php echo $tanggal_awal; ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo $tanggal_akhir; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <a href="arus-kas-lihat.php" class="btn btn-secondary btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Keterangan</th>
                            <th>Bukti/Ref</th>
                            <th style="text-align:right">Penerimaan (Debit)</th>
                            <th style="text-align:right">Pengeluaran (Kredit)</th>
                            <th style="text-align:right">Saldo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="table-light">
                            <td colspan="5"><strong>Saldo Awal Periode</strong></td>
                            <td style="text-align:right"><strong><?php echo number_format($kas_awal_periode, 0, ',', '.'); ?></strong></td>
                        </tr>

                        <?php if (count($data_rows) > 0): ?>
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
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['sumber']); ?></td>
                                    <td><?php echo 'AK-' . str_pad($row['id_arus_kas'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td style="text-align:right"><?php echo number_format($penerimaan, 0, ',', '.'); ?></td>
                                    <td style="text-align:right"><?php echo number_format($pengeluaran, 0, ',', '.'); ?></td>
                                    <td style="text-align:right"><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center">Tidak ada data pada periode ini</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <?php
                        $saldo_hitung = $kas_awal_periode + $total_penerimaan - $total_pengeluaran;
                        $status_balance = (abs($saldo_hitung - $saldo_berjalan) < 0.01) ? 'Balance' : 'Tidak Balance';
                        $badge_class = $status_balance === 'Balance' ? 'success' : 'danger';
                        ?>
                        <tr class="table-secondary">
                            <td colspan="3"><strong>Total</strong></td>
                            <td style="text-align:right"><strong><?php echo number_format($total_penerimaan, 0, ',', '.'); ?></strong></td>
                            <td style="text-align:right"><strong><?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></strong></td>
                            <td style="text-align:right"><strong><?php echo number_format($saldo_berjalan, 0, ',', '.'); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <strong>Status:</strong>
                                <span class="badge badge-<?php echo $badge_class; ?>"><?php echo $status_balance; ?></span>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
<?php require 'logout-modal.php'; ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/demo/datatables-demo.js"></script>
</body>

</html>
