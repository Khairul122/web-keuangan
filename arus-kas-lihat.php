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
                    <h6 class="m-0 font-weight-bold text-primary">Arus Kas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th colspan="2">Arus Kas Dari Data Operasional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Sisipkan file koneksi.php yang berisi koneksi ke database
                                include('koneksi.php');

                                // Query SQL untuk mengambil data dari tabel arus_kas dengan status 1 (operasional) atau 2 (keuangan)
                                $sql = "SELECT sumber, jumlah, status FROM arus_kas WHERE status IN (1, 2)";

                                // Lakukan query untuk data operasional dan keuangan
                                $result = $koneksi->query($sql);

                                // Variabel untuk menyimpan total arus kas dari data operasional dan keuangan
                                $total_arus_kas_operasional = 0;
                                $total_arus_kas_keuangan = 0;
                                $total_arus_kas_semua = 0;

                                // Jika hasil query tidak kosong
                                if ($result->num_rows > 0) {
                                    // Output data dari setiap baris
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>" . $row['sumber'] . "</td>
                                            <td>" . $row['jumlah'] . "</td>
                                        </tr>";

                                        // Tambahkan jumlah ke total arus kas sesuai status
                                        if ($row['status'] == 1) {
                                            $total_arus_kas_operasional += $row['jumlah'];
                                        } elseif ($row['status'] == 2) {
                                            $total_arus_kas_keuangan += $row['jumlah'];
                                        }

                                        // Tambahkan jumlah ke total arus kas semua
                                        $total_arus_kas_semua += $row['jumlah'];
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>Tidak ada data dengan status operasional atau keuangan</td></tr>";
                                }

                                // Tutup koneksi
                                $koneksi->close();
                                ?>
                            </tbody>
                            <tr>
                                <td>Total Arus Kas Dari Data Operasional</td>
                                <td><?php echo $total_arus_kas_operasional; ?></td>
                            </tr>
                            <tbody>
                                <tr>
                                    <th colspan="2">Arus Kas Dari Data Keuangan</th>
                                </tr>
                                <tr>
                                    <td>Total Arus Kas Dari Data Keuangan</td>
                                    <td><?php echo $total_arus_kas_keuangan; ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Saldo Kas</td>
                                    <td><?php echo $total_arus_kas_semua; ?></td>
                                </tr>
                            </tfoot>
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