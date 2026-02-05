<?php
require 'cek-sesi.php';
// Only allow admin and pemilik to access this page
if ($_SESSION['level'] !== 'admin' && $_SESSION['level'] !== 'pemilik') {
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

  <title>COA - CV Bina Padi Sabatang</title>

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
          <?php if ($_SESSION['level'] === 'admin'): ?>
          <button type="button" class="btn btn-success mb-3" style="margin:5px" data-toggle="modal" data-target="#myModalTambah"><i class="fa fa-plus"> Tambah Akun</i></button>
          <?php endif; ?>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Daftar Akun (Chart of Accounts)</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nomor Akun</th>
                      <th>Nama Akun</th>
                      <th>Jenis Akun</th>
                      <th>Saldo Normal</th>
                      <th>Kategori Arus Kas</th>
                      <th>Status</th>
                      <?php if ($_SESSION['level'] === 'admin'): ?>
                      <th>Aksi</th>
                      <?php endif; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts ORDER BY nomor_akun ASC");
                    while ($data = mysqli_fetch_assoc($query)) {
                      $status_text = $data['is_active'] == 1 ? 'Aktif' : 'Non-Aktif';
                      $status_class = $data['is_active'] == 1 ? 'success' : 'secondary';
                    ?>
                      <tr>
                        <td><?= $data['id_akun'] ?></td>
                        <td><?= $data['nomor_akun'] ?></td>
                        <td><?= $data['nama_akun'] ?></td>
                        <td><?= $data['jenis_akun'] ?></td>
                        <td><?= $data['saldo_normal'] ?></td>
                        <td><?= $data['kategori_arus_kas'] ? $data['kategori_arus_kas'] : '-' ?></td>
                        <td><span class="badge badge-<?= $status_class ?>"><?= $status_text ?></span></td>
                        <?php if ($_SESSION['level'] === 'admin'): ?>
                        <td>
                          <a href="#" type="button" class="fa fa-edit btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal<?= $data['id_akun']; ?>"></a>
                          <?php if($data['is_active'] == 1): ?>
                          <a href="hapus-coa.php?id_akun=<?= $data['id_akun']; ?>" onclick="return confirm('Anda Yakin Ingin Menonaktifkan Akun Ini?')" class="btn btn-warning btn-sm">Nonaktifkan</a>
                          <?php else: ?>
                          <a href="aktifkan-coa.php?id_akun=<?= $data['id_akun']; ?>" onclick="return confirm('Anda Yakin Ingin Mengaktifkan Akun Ini?')" class="btn btn-success btn-sm">Aktifkan</a>
                          <?php endif; ?>
                        </td>
                        <?php endif; ?>
                      </tr>
                      
                      <!-- Modal Edit -->
                      <div class="modal fade" id="myModal<?= $data['id_akun']; ?>" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Ubah Data Akun</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form role="form" action="proses-edit-coa.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_akun" value="<?= $data['id_akun']; ?>">
                                <div class="form-group">
                                  <label>Nomor Akun</label>
                                  <input type="text" name="nomor_akun" class="form-control" value="<?= $data['nomor_akun']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <label>Nama Akun</label>
                                  <input type="text" name="nama_akun" class="form-control" value="<?= $data['nama_akun']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <label>Jenis Akun</label>
                                  <select name="jenis_akun" class="form-control" required>
                                    <option value="Asset" <?= $data['jenis_akun'] == 'Asset' ? 'selected' : '' ?>>Asset</option>
                                    <option value="Kewajiban" <?= $data['jenis_akun'] == 'Kewajiban' ? 'selected' : '' ?>>Kewajiban</option>
                                    <option value="Ekuitas" <?= $data['jenis_akun'] == 'Ekuitas' ? 'selected' : '' ?>>Ekuitas</option>
                                    <option value="Pendapatan" <?= $data['jenis_akun'] == 'Pendapatan' ? 'selected' : '' ?>>Pendapatan</option>
                                    <option value="Beban" <?= $data['jenis_akun'] == 'Beban' ? 'selected' : '' ?>>Beban</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label>Saldo Normal</label>
                                  <select name="saldo_normal" class="form-control" required>
                                    <option value="Debit" <?= $data['saldo_normal'] == 'Debit' ? 'selected' : '' ?>>Debit</option>
                                    <option value="Kredit" <?= $data['saldo_normal'] == 'Kredit' ? 'selected' : '' ?>>Kredit</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label>Kategori Arus Kas</label>
                                  <select name="kategori_arus_kas" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Operasional" <?= $data['kategori_arus_kas'] == 'Operasional' ? 'selected' : '' ?>>Operasional</option>
                                    <option value="Investasi" <?= $data['kategori_arus_kas'] == 'Investasi' ? 'selected' : '' ?>>Investasi</option>
                                    <option value="Pendanaan" <?= $data['kategori_arus_kas'] == 'Pendanaan' ? 'selected' : '' ?>>Pendanaan</option>
                                  </select>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Ubah</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Tambah -->
        <div id="myModalTambah" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Tambah Akun Baru</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form action="tambah-coa.php" method="post">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Nomor Akun</label>
                    <input type="text" class="form-control" name="nomor_akun" placeholder="Contoh: 1-1001" required>
                  </div>
                  <div class="form-group">
                    <label>Nama Akun</label>
                    <input type="text" class="form-control" name="nama_akun" placeholder="Contoh: Kas" required>
                  </div>
                  <div class="form-group">
                    <label>Jenis Akun</label>
                    <select name="jenis_akun" class="form-control" required>
                      <option value="">-- Pilih Jenis Akun --</option>
                      <option value="Asset">Asset</option>
                      <option value="Kewajiban">Kewajiban</option>
                      <option value="Ekuitas">Ekuitas</option>
                      <option value="Pendapatan">Pendapatan</option>
                      <option value="Beban">Beban</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Saldo Normal</label>
                    <select name="saldo_normal" class="form-control" required>
                      <option value="">-- Pilih Saldo Normal --</option>
                      <option value="Debit">Debit</option>
                      <option value="Kredit">Kredit</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Kategori Arus Kas</label>
                    <select name="kategori_arus_kas" class="form-control">
                      <option value="">-- Pilih Kategori --</option>
                      <option value="Operasional">Operasional</option>
                      <option value="Investasi">Investasi</option>
                      <option value="Pendanaan">Pendanaan</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Tambah</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                </div>
              </form>
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