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
                    <h6 class="m-0 font-weight-bold text-primary">Neraca Saldo</h6>
                </div>

                <!-- Filter Periode -->
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="form-control" value="<?php echo isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t'); ?>">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <a href="neraca-saldo-lihat.php" class="btn btn-secondary btn-block">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <tr>
                                <th rowspan="2" style="text-align:center">No. Akun</th>
                                <th rowspan="2" style="text-align:center">Nama Akun</th>
                                <th colspan="2" style="text-align:center">Saldo Awal</th>
                                <th colspan="2" style="text-align:center">Pergerakan</th>
                                <th colspan="2" style="text-align:center">Saldo Akhir</th>

                            </tr>
                            <tr>
                                <th style="text-align:center">Debit</th>
                                <th style="text-align:center">Credit</th>
                                <th style="text-align:center">Debit</th>
                                <th style="text-align:center">Credit</th>
                                <th style="text-align:center">Debit</th>
                                <th style="text-align:center">Credit</th>
                            </tr>
                            <tr>
                                <th colspan="8" style="text-align:left">Asset</th>
                            </tr>
                            <?php
                            include('koneksi.php');

                            // Filter tanggal
                            $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
                            $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');
                            $id_user = $_SESSION['id'];

                            $sql = "SELECT * FROM neraca_saldo WHERE status = 1
                                    AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                    AND id_user = '$id_user'
                                    ORDER BY nomor_akun ASC";
                            $result = $koneksi->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data dari setiap baris
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
                                    echo "<td>" . $row["nama_akun"] . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
                                    $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
                                    $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
                            }
                            $koneksi->close();
                            ?>
                            <tr>
                                <th colspan="8" style="text-align:left">Kewajiban</th>
                            </tr>
                            <?php
                            include('koneksi.php');
                            $sql = "SELECT * FROM neraca_saldo WHERE status = 2
                                    AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                    AND id_user = '$id_user'
                                    ORDER BY nomor_akun ASC";
                            $result = $koneksi->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
                                    echo "<td>" . $row["nama_akun"] . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
                                    $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
                                    $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
                            }
                            $koneksi->close();
                            ?>
                            <tr>
                                <th colspan="8" style="text-align:left">Ekuitas</th>
                            </tr>
                            <?php
                            include('koneksi.php');
                            $sql = "SELECT * FROM neraca_saldo WHERE status = 3
                                    AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                    AND id_user = '$id_user'
                                    ORDER BY nomor_akun ASC";
                            $result = $koneksi->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
                                    echo "<td>" . $row["nama_akun"] . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
                                    $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
                                    $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
                            }
                            $koneksi->close();
                            ?>
                            <tr>
                                <th colspan="8" style="text-align:left">Pendapatan</th>
                            </tr>
                            <?php
                            include('koneksi.php');
                            $sql = "SELECT * FROM neraca_saldo WHERE status = 4
                                    AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                    AND id_user = '$id_user'
                                    ORDER BY nomor_akun ASC";
                            $result = $koneksi->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
                                    echo "<td>" . $row["nama_akun"] . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
                                    $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
                                    $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
                            }
                            $koneksi->close();
                            ?>
                            <tr>
                                <th colspan="8" style="text-align:left">Beban</th>
                            </tr>
                            <?php
                            include('koneksi.php');
                            $sql = "SELECT * FROM neraca_saldo WHERE status = 5
                                    AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                    AND id_user = '$id_user'
                                    ORDER BY nomor_akun ASC";
                            $result = $koneksi->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
                                    echo "<td>" . $row["nama_akun"] . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
                                    $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
                                    $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
                                    echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
                            }
                            $koneksi->close();
                            ?>
                            <tr>
                                <th style="text-align:left">Total</th>
                                <?php
                                include('koneksi.php');

                                // Query untuk menghitung total dengan filter tanggal dan user
                                $sql_total_debit = "SELECT SUM(saldo_awal_debit) AS total_debit FROM neraca_saldo
                                                   WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                   AND id_user = '$id_user'";
                                $result_total_debit = $koneksi->query($sql_total_debit);
                                $total_debit = $result_total_debit ? $result_total_debit->fetch_assoc()['total_debit'] : 0;

                                $sql_total_kredit = "SELECT SUM(saldo_awal_kredit) AS total_kredit FROM neraca_saldo
                                                    WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                    AND id_user = '$id_user'";
                                $result_total_kredit = $koneksi->query($sql_total_kredit);
                                $total_kredit = $result_total_kredit ? $result_total_kredit->fetch_assoc()['total_kredit'] : 0;

                                $sql_total_pergerakan_debit = "SELECT SUM(pergerakan_debit) AS total_pergerakan_debit FROM neraca_saldo
                                                                WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                                AND id_user = '$id_user'";
                                $result_total_pergerakan_debit = $koneksi->query($sql_total_pergerakan_debit);
                                $total_pergerakan_debit = $result_total_pergerakan_debit ? $result_total_pergerakan_debit->fetch_assoc()['total_pergerakan_debit'] : 0;

                                $sql_total_pergerakan_kredit = "SELECT SUM(pergerakan_kredit) AS total_pergerakan_kredit FROM neraca_saldo
                                                                WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                                AND id_user = '$id_user'";
                                $result_total_pergerakan_kredit = $koneksi->query($sql_total_pergerakan_kredit);
                                $total_pergerakan_kredit = $result_total_pergerakan_kredit ? $result_total_pergerakan_kredit->fetch_assoc()['total_pergerakan_kredit'] : 0;

                                $sql_total_saldo_akhir_debit = "SELECT SUM(saldo_awal_debit + pergerakan_debit) AS total_saldo_akhir_debit FROM neraca_saldo
                                                                WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                                AND id_user = '$id_user'";
                                $result_total_saldo_akhir_debit = $koneksi->query($sql_total_saldo_akhir_debit);
                                $total_saldo_akhir_debit = $result_total_saldo_akhir_debit ? $result_total_saldo_akhir_debit->fetch_assoc()['total_saldo_akhir_debit'] : 0;

                                $sql_total_saldo_akhir_kredit = "SELECT SUM(saldo_awal_kredit + pergerakan_kredit) AS total_saldo_akhir_kredit FROM neraca_saldo
                                                                WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                                                AND id_user = '$id_user'";
                                $result_total_saldo_akhir_kredit = $koneksi->query($sql_total_saldo_akhir_kredit);
                                $total_saldo_akhir_kredit = $result_total_saldo_akhir_kredit ? $result_total_saldo_akhir_kredit->fetch_assoc()['total_saldo_akhir_kredit'] : 0;

                                $koneksi->close();
                                ?>
                                <td></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_debit, 0, ',', '.'); ?></strong></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_kredit, 0, ',', '.'); ?></strong></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_pergerakan_debit, 0, ',', '.'); ?></strong></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_pergerakan_kredit, 0, ',', '.'); ?></strong></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_saldo_akhir_debit, 0, ',', '.'); ?></strong></td>
                                <td style='text-align:right'><strong><?php echo number_format($total_saldo_akhir_kredit, 0, ',', '.'); ?></strong></td>
                            </tr>


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
