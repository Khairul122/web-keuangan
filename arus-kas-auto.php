<?php
require 'cek-sesi.php';
require_once 'koneksi.php';
require_once 'includes/functions-jurnal.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laporan Arus Kas - Otomatis</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <?php require('sidebar.php'); require('navbar.php');

    $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
    $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');

    // Query untuk mengambil data arus kas berdasarkan kategori
    $sql = "SELECT
                ca.id_akun,
                ca.nomor_akun,
                ca.nama_akun,
                ca.kategori_arus_kas,
                ca.jenis_akun,
                ca.saldo_normal,
                COALESCE(SUM(
                    CASE WHEN ca.saldo_normal = 'Debit' THEN jl.debit - jl.kredit ELSE jl.kredit - jl.debit END
                ), 0) AS jumlah
            FROM chart_of_accounts ca
            LEFT JOIN journal_lines jl ON ca.id_akun = jl.id_akun
            LEFT JOIN journal_entries je ON jl.id_jurnal = je.id_jurnal
                AND je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            WHERE ca.kategori_arus_kas IN ('Operasional', 'Investasi', 'Pendanaan')
            GROUP BY ca.id_akun, ca.kategori_arus_kas
            HAVING jumlah != 0
            ORDER BY ca.kategori_arus_kas, ca.nomor_akun";

    $result = mysqli_query($koneksi, $sql);

    // Group data by kategori
    $arus_kas = [
        'Operasional' => ['items' => [], 'total' => 0],
        'Investasi' => ['items' => [], 'total' => 0],
        'Pendanaan' => ['items' => [], 'total' => 0]
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $kategori = $row['kategori_arus_kas'];
        $arus_kas[$kategori]['items'][] = $row;
        $arus_kas[$kategori]['total'] += $row['jumlah'];
    }

    $total_arus_kas_bersih = $arus_kas['Operasional']['total'] + $arus_kas['Investasi']['total'] + $arus_kas['Pendanaan']['total'];
    ?>

    <div id="content">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="form-control" value="<?php echo $tanggal_awal; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo $tanggal_akhir; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Laporan Arus Kas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-bill-wave"></i> Laporan Arus Kas (Otomatis)</h6>
                    <a href="export-arus-kas-auto.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Export PDF</a>
                </div>
                <div class="card-body">
                    <h5 class="text-center mb-2">CV BINA PADI SABATANG</h5>
                    <h6 class="text-center mb-4">Laporan Arus Kas<br>Periode: <?php echo date('d F Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d F Y', strtotime($tanggal_akhir)); ?></h6>

                    <table class="table table-bordered">
                        <?php foreach ($arus_kas as $kategori => $data): ?>
                            <?php if (empty($data['items'])) continue; ?>
                            <tr class="table-secondary">
                                <th colspan="2">ARUS KAS DARI AKTIVITAS <?php echo strtoupper($kategori); ?></th>
                            </tr>
                            <?php foreach ($data['items'] as $item): ?>
                                <tr>
                                    <td><?php echo $item['nama_akun']; ?></td>
                                    <td class="text-right"><?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light">
                                <td><strong>Arus Kas dari Aktivitas <?php echo $kategori; ?></strong></td>
                                <td class="text-right"><strong><?php echo number_format($data['total'], 0, ',', '.'); ?></strong></td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                        <?php endforeach; ?>

                        <tr class="table-primary">
                            <td><strong>ARUS KAS BERSIH</strong></td>
                            <td class="text-right"><strong><?php echo number_format($total_arus_kas_bersih, 0, ',', '.'); ?></strong></td>
                        </tr>
                    </table>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Laporan ini di-generate otomatis dari data jurnal transaksi.</strong><br>
                        Menampilkan pergerakan kas berdasarkan kategori aktivitas (Operasional, Investasi, Pendanaan).
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
