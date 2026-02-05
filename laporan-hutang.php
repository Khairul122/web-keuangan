<?php
require 'cek-sesi.php';
// Only allow pemilik to access this page
if ($_SESSION['level'] !== 'pemilik') {
    header("location:index.php?pesan=forbidden");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Laporan Hutang - CV Bina Padi Sabatang</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php
  require('koneksi.php');
  require('sidebar.php');
  ?>

  <div id="content">

    <?php require('navbar.php'); ?>

    <div class="container-fluid">
      <div class="row">

        <div class="col-12">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Laporan Hutang</h6>
              <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                  <div class="dropdown-header">Opsi Laporan:</div>
                  <a class="dropdown-item" href="export-hutang.php" target="_blank">Export PDF</a>
                  <a class="dropdown-item" href="export-hutang.php?format=excel" target="_blank">Export Excel</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>Nama Penghutang</th>
                      <th>Alasan</th>
                      <th>Jumlah</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM hutang ORDER BY tgl_hutang DESC");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                      $status_text = '';
                      $status_class = '';
                      
                      switch($data['status']) {
                        case 1:
                          $status_text = 'Belum Dibayar';
                          $status_class = 'warning';
                          break;
                        case 2:
                          $status_text = 'Sebagian Dibayar';
                          $status_class = 'primary';
                          break;
                        case 3:
                          $status_text = 'Lunas';
                          $status_class = 'success';
                          break;
                        default:
                          $status_text = 'Tidak Dikenal';
                          $status_class = 'secondary';
                      }
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($data['tgl_hutang'])) ?></td>
                        <td><?= $data['penghutang'] ?></td>
                        <td><?= $data['alasan'] ?></td>
                        <td>Rp. <?= number_format($data['jumlah'], 2, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $status_class ?>"><?= $status_text ?></span></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>

  <?php require 'footer.php' ?>

  </div>

  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

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