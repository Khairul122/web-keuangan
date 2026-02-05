<?php
require 'cek-sesi.php';
require_once 'koneksi.php';
require_once 'includes/functions-jurnal.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laporan Neraca Saldo - Otomatis</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <?php
    require('sidebar.php');
    require('navbar.php');

    // Ambil filter tanggal
    $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
    $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');
    $id_user = $_SESSION['id'];

    // Query untuk mengambil data dari journal_lines dan chart_of_accounts
    $sql = "SELECT
                ca.id_akun,
                ca.nomor_akun,
                ca.nama_akun,
                ca.jenis_akun,
                ca.saldo_normal,
                COALESCE(SUM(jl.debit), 0) AS total_debit,
                COALESCE(SUM(jl.kredit), 0) AS total_kredit,
                COALESCE(SUM(jl.debit - jl.kredit), 0) AS mutasi,
                CASE
                    WHEN ca.saldo_normal = 'Debit' THEN COALESCE(SUM(jl.debit - jl.kredit), 0)
                    ELSE COALESCE(SUM(jl.kredit - jl.debit), 0)
                END AS saldo_akhir
            FROM chart_of_accounts ca
            LEFT JOIN journal_lines jl ON ca.id_akun = jl.id_akun
            LEFT JOIN journal_entries je ON jl.id_jurnal = je.id_jurnal
                AND je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                AND je.id_user = '$id_user'
            WHERE ca.is_active = 1
            GROUP BY ca.id_akun
            HAVING saldo_akhir != 0 OR total_debit > 0 OR total_kredit > 0
            ORDER BY ca.nomor_akun";

    $result = mysqli_query($koneksi, $sql);

    // Hitung total
    $sum_debit = 0;
    $sum_kredit = 0;
    $sum_saldo_akhir = 0;

    // Group by jenis akun
    $neraca_by_jenis = [
        'Asset' => [],
        'Kewajiban' => [],
        'Ekuitas' => [],
        'Pendapatan' => [],
        'Beban' => []
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $neraca_by_jenis[$row['jenis_akun']][] = $row;
        $sum_debit += $row['total_debit'];
        $sum_kredit += $row['total_kredit'];
        $sum_saldo_akhir += $row['saldo_akhir'];
    }
    ?>

    <div id="content">
        <div class="container-fluid">
            <!-- Filter Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter"></i> Filter Periode
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="">
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
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Laporan Neraca Saldo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Neraca Saldo (Otomatis)
                    </h6>
                    <div>
                        <a href="export-neraca-saldo-auto.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="export-excel-neraca-saldo-auto.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="text-center mb-4">CV BINA PADI SABATANG</h5>
                    <h6 class="text-center mb-4">Neraca Saldo Periode: <?php echo date('d F Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d F Y', strtotime($tanggal_akhir)); ?></h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No. Akun</th>
                                    <th>Nama Akun</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Kredit</th>
                                    <th class="text-right">Mutasi</th>
                                    <th class="text-right">Saldo Akhir</th>
                                </tr>
                            </thead>

                            <?php
                            $jenis_akun_list = ['Asset', 'Kewajiban', 'Ekuitas', 'Pendapatan', 'Beban'];
                            $warna_jenis = ['Asset' => '', 'Kewajiban' => '', 'Ekuitas' => '', 'Pendapatan' => '', 'Beban' => ''];

                            foreach ($jenis_akun_list as $jenis):
                                if (empty($neraca_by_jenis[$jenis])) continue;
                            ?>
                                <tr class="table-secondary">
                                    <th colspan="6"><?php echo $jenis; ?></th>
                                </tr>
                                <?php foreach ($neraca_by_jenis[$jenis] as $row): ?>
                                    <tr>
                                        <td><?php echo $row['nomor_akun']; ?></td>
                                        <td><?php echo $row['nama_akun']; ?></td>
                                        <td class="text-right"><?php echo number_format($row['total_debit'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?php echo number_format($row['total_kredit'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?php echo number_format($row['mutasi'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><strong><?php echo number_format($row['saldo_akhir'], 0, ',', '.'); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>

                            <tr class="table-dark">
                                <th colspan="2">TOTAL</th>
                                <th class="text-right"><?php echo number_format($sum_debit, 0, ',', '.'); ?></th>
                                <th class="text-right"><?php echo number_format($sum_kredit, 0, ',', '.'); ?></th>
                                <th class="text-right"><?php echo number_format($sum_debit - $sum_kredit, 0, ',', '.'); ?></th>
                                <th class="text-right"><?php echo number_format($sum_saldo_akhir, 0, ',', '.'); ?></th>
                            </tr>

                            <?php if ($sum_debit == $sum_kredit): ?>
                                <tr class="table-success">
                                    <td colspan="6" class="text-center text-success">
                                        <strong><i class="fas fa-check-circle"></i> BALANCE - Debit = Kredit</strong>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr class="table-danger">
                                    <td colspan="6" class="text-center text-danger">
                                        <strong><i class="fas fa-exclamation-triangle"></i> NOT BALANCE - Selisih: <?php echo number_format(abs($sum_debit - $sum_kredit), 0, ',', '.'); ?></strong>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Laporan ini di-generate otomatis dari data transaksi (Jurnal).</strong><br>
                        Total Debit dan Kredit harus sama (Balance) untuk memastikan pencatatan sudah benar.
                    </div>
                </div>
            </div>
        </div>

        <?php require 'footer.php'; ?>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <?php require 'logout-modal.php'; ?>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
