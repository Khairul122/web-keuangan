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

  <title>Dashboard - Admin</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php
  require 'koneksi.php';
  require('sidebar.php');
  require_once 'includes/functions-jurnal.php'; ?>
  <div id="content">

    <?php require('navbar.php'); ?>

    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-6 mb-4">

        </div>

        <div class="col-12">
          <button type="button" class="btn btn-success" style="margin:5px" data-toggle="modal" data-target="#myModalTambah"><i class="fa fa-plus"> Pemasukan</i></button><br>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Transaksi Masuk</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>Jumlah</th>
                      <th>Sumber</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM pemasukan");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                      <tr>
                        <td><?= $data['id_pemasukan'] ?></td>
                        <td><?= $data['tgl_pemasukan'] ?></td>
                        <td>Rp. <?= number_format($data['jumlah'], 2, ',', '.'); ?></td>
                        <td><?= $data['sumber'] ?></td>
                        <td>
                          <a href="#" type="button" class="fa fa-edit btn btn-primary btn-md" data-toggle="modal" data-target="#myModal<?php echo $data['id_pemasukan']; ?>"></a>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <?php
        $query_edit = mysqli_query($koneksi, "SELECT * FROM pemasukan");
        while ($row = mysqli_fetch_array($query_edit)) {
        ?>
          <div class="modal fade" id="myModal<?php echo $row['id_pemasukan']; ?>" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Ubah Data Pemasukan</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" action="proses-edit-pemasukan.php" method="get">
                  <div class="modal-body">
                    <input type="hidden" name="id_pemasukan" value="<?php echo $row['id_pemasukan']; ?>">
                    <div class="form-group">
                      <label>Tanggal</label>
                      <input type="date" name="tgl_pemasukan" class="form-control" value="<?php echo $row['tgl_pemasukan']; ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Jumlah</label>
                      <input type="number" name="jumlah" class="form-control" value="<?php echo $row['jumlah']; ?>" required min="1">
                    </div>
                    <div class="form-group">
                      <label>Sumber</label>
                      <input type="text" name="sumber" class="form-control" value="<?php echo $row['sumber']; ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ubah</button>
                    <a href="hapus-pemasukan.php?id_pemasukan=<?= $row['id_pemasukan']; ?>" onclick="return confirm('Anda Yakin Ingin Menghapus?')" class="btn btn-danger">Hapus</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php } ?>

        <div id="myModalTambah" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Tambah Pendapatan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form action="tambah-pendapatan.php" method="get">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="tgl_pemasukan" required>
                  </div>
                  <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" required min="1">
                  </div>
                  <div class="form-group">
                    <label>Sumber</label>
                    <input type="text" class="form-control" name="sumber" placeholder="Contoh: Servis Motor Honda Beat" required>
                  </div>
                  <div class="form-group">
                    <label>Akun Kas/Bank (Debit)</label>
                    <select class="form-control" name="id_akun_kas" required>
                      <option value="">-- Pilih Akun Kas/Bank --</option>
                      <?php echo getCOAOptions($koneksi, 'Asset', null); ?>
                    </select>
                    <small class="form-text text-muted">Akun yang didebit (bertambah)</small>
                  </div>
                  <div class="form-group">
                    <label>Akun Pendapatan (Kredit)</label>
                    <select class="form-control" name="id_akun_pendapatan" required>
                      <option value="">-- Pilih Akun Pendapatan --</option>
                      <?php echo getCOAOptions($koneksi, 'Pendapatan', null); ?>
                    </select>
                    <small class="form-text text-muted">Akun yang dikredit (bertambah)</small>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Tambah & Buat Jurnal</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                </div>
              </form>
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