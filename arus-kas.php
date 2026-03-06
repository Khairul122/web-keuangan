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

    <title>Arus Kas</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <?php
    require 'koneksi.php';
    require('sidebar.php');
    ?>

    <div id="content">
        <?php require('navbar.php'); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="py-2">
                        <a href="arus-kas-tambah.php" class="btn btn-success" style="margin:5px"><i class="fa fa-plus"></i> Tambah Data</a>
                        <a href="arus-kas-lihat.php" class="btn btn-primary" style="margin:5px"><i class="fa fa-file-alt"></i> Laporan Arus Kas Standar</a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Arus Kas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Nominal (Rp)</th>
                                            <th>Kas Awal</th>
                                            <th>Kategori</th>
                                            <th>Akun Terkait</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT a.*, c.nama_akun
                                                FROM arus_kas a
                                                LEFT JOIN chart_of_accounts c ON a.id_akun = c.id_akun
                                                ORDER BY a.tanggal DESC, a.id_arus_kas DESC";

                                        $result = $koneksi->query($sql);

                                        if ($result === FALSE) {
                                            echo "<tr><td colspan='7'>Error: " . $koneksi->error . "</td></tr>";
                                        } elseif ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                $kategori = 'Tidak valid';
                                                if ((int)$row['status'] === 1) {
                                                    $kategori = 'Operasional';
                                                } elseif ((int)$row['status'] === 2) {
                                                    $kategori = 'Pendanaan';
                                                } elseif ((int)$row['status'] === 3) {
                                                    $kategori = 'Investasi';
                                                }

                                                echo "<tr>
                                                    <td>" . $no . "</td>
                                                    <td>" . date('d F Y', strtotime($row['tanggal'])) . "</td>
                                                    <td>" . htmlspecialchars($row['sumber']) . "</td>
                                                    <td style='text-align:right'>" . number_format((int)$row['jumlah'], 0, ',', '.') . "</td>
                                                    <td style='text-align:right'>" . number_format((int)$row['kas_awal'], 0, ',', '.') . "</td>
                                                    <td>" . $kategori . "</td>
                                                    <td>" . htmlspecialchars($row['nama_akun'] ?? 'Tidak terkait') . "</td>
                                                </tr>";
                                                $no++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

<?php
$koneksi->close();
?>
