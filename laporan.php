<?php
require 'cek-sesi.php';

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if ($bulan < 1 || $bulan > 12) {
    $bulan = (int)date('n');
}

if ($tahun < 2000 || $tahun > 2100) {
    $tahun = (int)date('Y');
}

$nama_bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan Keuangan</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <?php
    require 'koneksi.php';
    require 'sidebar.php'; ?>

    <!-- Main Content -->
    <div id="content">

        <?php require 'navbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan</h6>
                </div>
                <div class="card-body">

                    <!-- Form Input Nama Pimpinan -->
                    <div class="card mb-4">
                        <div class="card-header py-3 bg-light">
                            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Nama Pimpinan</h6>
                        </div>
                        <div class="card-body">
                            <form action="save-pimpinan.php" method="POST">
                                <div class="form-group">
                                    <label for="pimpinan">Nama Pimpinan:</label>
                                    <input type="text" class="form-control" id="pimpinan" name="pimpinan"
                                           placeholder="Masukkan nama pimpinan (misal: Budi Santoso)"
                                           value="<?php echo isset($_SESSION['pimpinan']) ? htmlspecialchars($_SESSION['pimpinan']) : 'Pimpinan'; ?>"
                                           required>
                                    <small class="form-text text-muted">
                                        Nama pimpinan akan ditampilkan di bagian tanda tangan pada semua laporan PDF.
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan Nama Pimpinan
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- End Form Input Nama Pimpinan -->
                    <div class="card mb-4">
                        <div class="card-header py-3 bg-light">
                            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="laporan.php">
                                <div class="form-row align-items-end">
                                    <div class="form-group col-md-4">
                                        <label for="bulan">Bulan</label>
                                        <select class="form-control" id="bulan" name="bulan" required>
                                            <?php foreach ($nama_bulan as $nilai_bulan => $label_bulan) { ?>
                                                <option value="<?php echo $nilai_bulan; ?>" <?php echo $nilai_bulan === $bulan ? 'selected' : ''; ?>>
                                                    <?php echo $label_bulan; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="tahun">Tahun</label>
                                        <select class="form-control" id="tahun" name="tahun" required>
                                            <?php
                                            $tahun_sekarang = (int)date('Y');
                                            for ($y = $tahun_sekarang - 5; $y <= $tahun_sekarang + 1; $y++) {
                                            ?>
                                                <option value="<?php echo $y; ?>" <?php echo $y === $tahun ? 'selected' : ''; ?>>
                                                    <?php echo $y; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-filter"></i> Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Periode terpilih: <?php echo $nama_bulan[$bulan] . ' ' . $tahun; ?></small>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Jumlah Total Uang</th>
                                    <th>Download</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $filter_pemasukan = "WHERE MONTH(tgl_pemasukan) = $bulan AND YEAR(tgl_pemasukan) = $tahun";
                                $filter_pengeluaran = "WHERE MONTH(tgl_pengeluaran) = $bulan AND YEAR(tgl_pengeluaran) = $tahun";

                                $summary_pemasukan = mysqli_query($koneksi, "SELECT COUNT(*) AS total, SUM(jumlah) AS total_jumlah FROM pemasukan $filter_pemasukan");
                                $row_pemasukan = mysqli_fetch_assoc($summary_pemasukan);
                                $query1 = (int)$row_pemasukan['total'];
                                $jumlahmasuk = $row_pemasukan['total_jumlah'] ? (float)$row_pemasukan['total_jumlah'] : 0;

                                $summary_pengeluaran = mysqli_query($koneksi, "SELECT COUNT(*) AS total, SUM(jumlah) AS total_jumlah FROM pengeluaran $filter_pengeluaran");
                                $row_pengeluaran = mysqli_fetch_assoc($summary_pengeluaran);
                                $query2 = (int)$row_pengeluaran['total'];
                                $jumlahkeluar = $row_pengeluaran['total_jumlah'] ? (float)$row_pengeluaran['total_jumlah'] : 0;
                                $no = 1;
                                ?>

                                <!-- Pemasukan -->
                                <tr>
                                    <td>Pemasukan</td>
                                    <td><?= $query1 ?></td>
                                    <td>Rp. <?= number_format($jumlahmasuk, 2, ',', '.'); ?></td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-pemasukan.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>

                                <!-- Pengeluaran -->
                                <tr>
                                    <td>Pengeluaran</td>
                                    <td><?= $query2 ?></td>
                                    <td>Rp. <?= number_format($jumlahkeluar, 2, ',', '.'); ?></td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-pengeluaran.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>

                                <!-- Laba Rugi -->
                                <tr>
                                    <td colspan="3" style="text-align: left;">Laba Rugi</td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-laba-rugi.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md" target="_blank"><i class="fa fa-download"></i></a>

                                    </td>
                                </tr>

                                <!-- Neraca Saldo -->
                                <tr>
                                    <td colspan="3" style="text-align: left;">Neraca Saldo</td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-neraca-saldo.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>

                                <!-- Arus Kas -->
                                <tr>
                                    <td colspan="3" style="text-align: left;">Arus Kas</td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-arus-kas.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>
                                
                                <!-- Hutang -->
                                <tr>
                                    <td colspan="3" style="text-align: left;">Hutang</td>
                                    <td>
                                        <!-- Button untuk modal -->
                                        <a href="export-hutang.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" type="button" class="btn btn-primary btn-md"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <?php require 'footer.php' ?>

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require 'logout-modal.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
