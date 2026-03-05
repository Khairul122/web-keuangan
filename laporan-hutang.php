<?php
require 'cek-sesi.php';
// Only allow pemilik to access this page
if ($_SESSION['level'] !== 'pemilik') {
    header("location:index.php?pesan=forbidden");
    exit();
}

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

$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));
$periode_label = $nama_bulan[$bulan] . ' ' . $tahun;
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
                  <a class="dropdown-item" href="export-hutang.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" target="_blank">Export PDF</a>
                  <a class="dropdown-item" href="export-hutang.php?format=excel&bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" target="_blank">Export Excel</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form method="GET" class="mb-3">
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
                      <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                  </div>
                </div>
                <small class="text-muted">Periode terpilih: <?php echo $periode_label; ?></small>
              </form>
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
                    $query = mysqli_query($koneksi, "SELECT * FROM hutang WHERE tgl_hutang BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ORDER BY tgl_hutang DESC");
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
