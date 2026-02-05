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
    <title>Laporan Laba Rugi - Otomatis</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <?php require('sidebar.php'); require('navbar.php');

    $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
    $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');
    $id_user = $_SESSION['id'];

    $sql_pendapatan = "SELECT
                        ca.id_akun, ca.nomor_akun, ca.nama_akun,
                        COALESCE(SUM(jl.kredit - jl.debit), 0) AS jumlah
                       FROM chart_of_accounts ca
                       LEFT JOIN journal_lines jl ON ca.id_akun = jl.id_akun
                       LEFT JOIN journal_entries je ON jl.id_jurnal = je.id_jurnal
                           AND je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                           AND je.id_user = '$id_user'
                       WHERE ca.jenis_akun = 'Pendapatan'
                       GROUP BY ca.id_akun
                       HAVING jumlah != 0";
    $result_pendapatan = mysqli_query($koneksi, $sql_pendapatan);

    $sql_hpp = "SELECT
                 ca.id_akun, ca.nomor_akun, ca.nama_akun,
                 COALESCE(SUM(jl.debit - jl.kredit), 0) AS jumlah
                FROM chart_of_accounts ca
                LEFT JOIN journal_lines jl ON ca.id_akun = jl.id_akun
                LEFT JOIN journal_entries je ON jl.id_jurnal = je.id_jurnal
                    AND je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                    AND je.id_user = '$id_user'
                WHERE ca.jenis_akun = 'Beban' AND (ca.nama_akun LIKE '%Harga Pokok%' OR ca.nomor_akun LIKE '5-100%')
                GROUP BY ca.id_akun
                HAVING jumlah != 0";
    $result_hpp = mysqli_query($koneksi, $sql_hpp);

    $sql_beban = "SELECT
                   ca.id_akun, ca.nomor_akun, ca.nama_akun,
                   COALESCE(SUM(jl.debit - jl.kredit), 0) AS jumlah
                  FROM chart_of_accounts ca
                  LEFT JOIN journal_lines jl ON ca.id_akun = jl.id_akun
                  LEFT JOIN journal_entries je ON jl.id_jurnal = je.id_jurnal
                      AND je.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                      AND je.id_user = '$id_user'
                  WHERE ca.jenis_akun = 'Beban' AND ca.nama_akun NOT LIKE '%Harga Pokok%'
                  GROUP BY ca.id_akun
                  HAVING jumlah != 0";
    $result_beban = mysqli_query($koneksi, $sql_beban);

    $total_pendapatan = 0;
    while ($row = mysqli_fetch_assoc($result_pendapatan)) {
        $total_pendapatan += $row['jumlah'];
    }
    mysqli_data_seek($result_pendapatan, 0);

    $total_hpp = 0;
    while ($row = mysqli_fetch_assoc($result_hpp)) {
        $total_hpp += $row['jumlah'];
    }
    mysqli_data_seek($result_hpp, 0);

    $total_beban_operasional = 0;
    while ($row = mysqli_fetch_assoc($result_beban)) {
        $total_beban_operasional += $row['jumlah'];
    }
    mysqli_data_seek($result_beban, 0);

    $laba_kotor = $total_pendapatan - $total_hpp;
    $laba_operasional = $laba_kotor - $total_beban_operasional;
    $pajak_penghasilan = $laba_operasional > 0 ? $laba_operasional * 0.25 : 0;
    $laba_bersih = $laba_operasional - $pajak_penghasilan;
    ?>

    <div id="content">
        <div class="container-fluid">
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

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line"></i> Laporan Laba Rugi (Otomatis)</h6>
                    <a href="export-laba-rugi-auto.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Export PDF</a>
                </div>
                <div class="card-body">
                    <h5 class="text-center mb-2">CV BINA PADI SABATANG</h5>
                    <h6 class="text-center mb-4">Laporan Laba Rugi<br>Periode: <?php echo date('d F Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d F Y', strtotime($tanggal_akhir)); ?></h6>

                    <table class="table table-bordered">
                        <tr class="table-secondary"><th colspan="2">PENDAPATAN</th></tr>

                        <?php while ($row = mysqli_fetch_assoc($result_pendapatan)): ?>
                            <tr>
                                <td><?php echo $row['nama_akun']; ?></td>
                                <td class="text-right"><?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="table-light">
                            <td><strong>Total Pendapatan</strong></td>
                            <td class="text-right"><strong><?php echo number_format($total_pendapatan, 0, ',', '.'); ?></strong></td>
                        </tr>

                        <tr><th colspan="2">HARGA POKOK PENJUALAN</th></tr>
                        <?php while ($row = mysqli_fetch_assoc($result_hpp)): ?>
                            <tr>
                                <td><?php echo $row['nama_akun']; ?></td>
                                <td class="text-right"><?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="table-light">
                            <td><strong>Total Harga Pokok</strong></td>
                            <td class="text-right"><strong>(<?php echo number_format($total_hpp, 0, ',', '.'); ?>)</strong></td>
                        </tr>

                        <tr class="table-info">
                            <td><strong>LABA KOTOR</strong></td>
                            <td class="text-right"><strong><?php echo number_format($laba_kotor, 0, ',', '.'); ?></strong></td>
                        </tr>

                        <tr><th colspan="2">BEBAN OPERASIONAL</th></tr>
                        <?php while ($row = mysqli_fetch_assoc($result_beban)): ?>
                            <tr>
                                <td><?php echo $row['nama_akun']; ?></td>
                                <td class="text-right"><?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="table-light">
                            <td><strong>Total Beban Operasional</strong></td>
                            <td class="text-right"><strong>(<?php echo number_format($total_beban_operasional, 0, ',', '.'); ?>)</strong></td>
                        </tr>

                        <tr class="table-warning">
                            <td><strong>LABA OPERASIONAL</strong></td>
                            <td class="text-right"><strong><?php echo number_format($laba_operasional, 0, ',', '.'); ?></strong></td>
                        </tr>

                        <tr>
                            <td>Beban Pajak Penghasilan (25%)</td>
                            <td class="text-right">(<?php echo number_format($pajak_penghasilan, 0, ',', '.'); ?>)</td>
                        </tr>

                        <tr class="table-success">
                            <td><strong>LABA BERSIH</strong></td>
                            <td class="text-right"><strong><?php echo number_format($laba_bersih, 0, ',', '.'); ?></strong></td>
                        </tr>
                    </table>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Laporan di-generate otomatis dari data transaksi.</strong>
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
