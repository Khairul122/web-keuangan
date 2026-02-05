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

  <title>Jurnal Umum - CV Bina Padi Sabatang</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php
  require('koneksi.php');
  require('sidebar.php');
  require_once 'includes/functions-jurnal.php';
  ?>

  <div id="content">

    <?php require('navbar.php'); ?>

    <div class="container-fluid">
      <div class="row">

        <div class="col-12">
          <?php if ($_SESSION['level'] === 'admin'): ?>
          <button type="button" class="btn btn-success mb-3" style="margin:5px" data-toggle="modal" data-target="#myModalTambah"><i class="fa fa-plus"> Tambah Jurnal</i></button>
          <?php endif; ?>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Jurnal Umum</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No Jurnal</th>
                      <th>Tanggal</th>
                      <th>Keterangan</th>
                      <th>Tipe Transaksi</th>
                      <th>Total Debit</th>
                      <th>Total Kredit</th>
                      <?php if ($_SESSION['level'] === 'admin'): ?>
                      <th>Aksi</th>
                      <?php endif; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM journal_entries ORDER BY tanggal DESC, nomor_jurnal DESC");
                    while ($data = mysqli_fetch_assoc($query)) {
                      // Calculate totals for this journal
                      $total_debit = 0;
                      $total_kredit = 0;
                      
                      $lines_query = mysqli_query($koneksi, "SELECT SUM(debit) as sum_debit, SUM(kredit) as sum_kredit FROM journal_lines WHERE id_jurnal = ".$data['id_jurnal']);
                      $lines_result = mysqli_fetch_assoc($lines_query);
                      
                      $total_debit = $lines_result['sum_debit'];
                      $total_kredit = $lines_result['sum_kredit'];
                    ?>
                      <tr>
                        <td><?= $data['nomor_jurnal'] ?></td>
                        <td><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                        <td><?= $data['keterangan'] ?></td>
                        <td>
                          <?php 
                          if($data['tipe_ref_transaksi'] == 'pemasukan') echo 'Pemasukan';
                          elseif($data['tipe_ref_transaksi'] == 'pengeluaran') echo 'Pengeluaran';
                          elseif($data['tipe_ref_transaksi'] == 'hutang') echo 'Hutang';
                          else echo 'Manual';
                          ?>
                        </td>
                        <td>Rp. <?= number_format($total_debit, 2, ',', '.') ?></td>
                        <td>Rp. <?= number_format($total_kredit, 2, ',', '.') ?></td>
                        <?php if ($_SESSION['level'] === 'admin'): ?>
                        <td>
                          <a href="#" type="button" class="fa fa-eye btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal<?= $data['id_jurnal']; ?>"> Detail</a>
                          <?php if($data['tipe_ref_transaksi'] == 'manual'): ?>
                          <a href="#" type="button" class="fa fa-edit btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $data['id_jurnal']; ?>"> Edit</a>
                          <a href="hapus-jurnal.php?id_jurnal=<?= $data['id_jurnal']; ?>" onclick="return confirm('Anda Yakin Ingin Menghapus Jurnal Ini?')" class="btn btn-danger btn-sm">Hapus</a>
                          <?php endif; ?>
                        </td>
                        <?php endif; ?>
                      </tr>
                      
                      <!-- Detail Modal -->
                      <div class="modal fade" id="detailModal<?= $data['id_jurnal']; ?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Detail Jurnal - <?= $data['nomor_jurnal'] ?></h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <p><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($data['tanggal'])) ?></p>
                                  <p><strong>Keterangan:</strong> <?= $data['keterangan'] ?></p>
                                  <p><strong>Tipe Transaksi:</strong> 
                                    <?php 
                                    if($data['tipe_ref_transaksi'] == 'pemasukan') echo 'Pemasukan';
                                    elseif($data['tipe_ref_transaksi'] == 'pengeluaran') echo 'Pengeluaran';
                                    elseif($data['tipe_ref_transaksi'] == 'hutang') echo 'Hutang';
                                    else echo 'Manual';
                                    ?>
                                  </p>
                                </div>
                                <div class="col-md-6">
                                  <p><strong>Total Debit:</strong> Rp. <?= number_format($total_debit, 2, ',', '.') ?></p>
                                  <p><strong>Total Kredit:</strong> Rp. <?= number_format($total_kredit, 2, ',', '.') ?></p>
                                  <p><strong>Seimbang:</strong> 
                                    <?php 
                                    if($total_debit == $total_kredit) echo '<span class="text-success">Ya</span>';
                                    else echo '<span class="text-danger">Tidak</span>';
                                    ?>
                                  </p>
                                </div>
                              </div>
                              
                              <h5>Baris Jurnal:</h5>
                              <div class="table-responsive">
                                <table class="table table-sm">
                                  <thead>
                                    <tr>
                                      <th>Nama Akun</th>
                                      <th>Debit</th>
                                      <th>Kredit</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $lines_query = mysqli_query($koneksi, "SELECT jl.*, coa.nama_akun FROM journal_lines jl JOIN chart_of_accounts coa ON jl.id_akun = coa.id_akun WHERE jl.id_jurnal = ".$data['id_jurnal']." ORDER BY jl.id_line ASC");
                                    while ($line = mysqli_fetch_assoc($lines_query)) {
                                    ?>
                                      <tr>
                                        <td><?= $line['nama_akun'] ?></td>
                                        <td>Rp. <?= number_format($line['debit'], 2, ',', '.') ?></td>
                                        <td>Rp. <?= number_format($line['kredit'], 2, ',', '.') ?></td>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Edit Modal for Manual Journals -->
                      <?php if($data['tipe_ref_transaksi'] == 'manual'): ?>
                      <div class="modal fade" id="editModal<?= $data['id_jurnal']; ?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Jurnal - <?= $data['nomor_jurnal'] ?></h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form role="form" action="proses-edit-jurnal.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_jurnal" value="<?= $data['id_jurnal']; ?>">
                                
                                <div class="form-group">
                                  <label>Tanggal</label>
                                  <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                  <label>Keterangan</label>
                                  <textarea name="keterangan" class="form-control" rows="3" required><?= $data['keterangan']; ?></textarea>
                                </div>
                                
                                <h5>Baris Jurnal:</h5>
                                <div class="table-responsive">
                                  <table class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th>Nama Akun</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                        <th>Aksi</th>
                                      </tr>
                                    </thead>
                                    <tbody id="journal-lines-<?= $data['id_jurnal']; ?>">
                                      <?php
                                      $lines_query = mysqli_query($koneksi, "SELECT jl.*, coa.nama_akun FROM journal_lines jl JOIN chart_of_accounts coa ON jl.id_akun = coa.id_akun WHERE jl.id_jurnal = ".$data['id_jurnal']." ORDER BY jl.id_line ASC");
                                      $line_index = 0;
                                      while ($line = mysqli_fetch_assoc($lines_query)) {
                                      ?>
                                        <tr>
                                          <td>
                                            <select name="akun[]" class="form-control" required>
                                              <option value="">-- Pilih Akun --</option>
                                              <?php
                                              $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                                              while ($coa = mysqli_fetch_assoc($coa_query)) {
                                                $selected = ($coa['id_akun'] == $line['id_akun']) ? 'selected' : '';
                                                echo "<option value='".$coa['id_akun']."' $selected>".$coa['nomor_akun']." - ".$coa['nama_akun']."</option>";
                                              }
                                              ?>
                                            </select>
                                          </td>
                                          <td><input type="number" name="debit[]" class="form-control" value="<?= $line['debit']; ?>" step="0.01" required></td>
                                          <td><input type="number" name="kredit[]" class="form-control" value="<?= $line['kredit']; ?>" step="0.01" required></td>
                                          <td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>
                                        </tr>
                                      <?php $line_index++; } ?>
                                    </tbody>
                                  </table>
                                  <button type="button" class="btn btn-info btn-sm" onclick="addJournalLine(<?= $data['id_jurnal']; ?>)">Tambah Baris</button>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <?php endif; ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Tambah -->
        <div id="myModalTambah" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Tambah Jurnal Umum Baru</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form action="tambah-jurnal.php" method="post">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan transaksi jurnal..." required></textarea>
                  </div>
                  
                  <h5>Baris Jurnal:</h5>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Nama Akun</th>
                          <th>Debit</th>
                          <th>Kredit</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="journal-lines">
                        <tr>
                          <td>
                            <select name="akun[]" class="form-control" required>
                              <option value="">-- Pilih Akun --</option>
                              <?php
                              $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                              while ($coa = mysqli_fetch_assoc($coa_query)) {
                                echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".$coa['nama_akun']."</option>";
                              }
                              ?>
                            </select>
                          </td>
                          <td><input type="number" name="debit[]" class="form-control" step="0.01" required></td>
                          <td><input type="number" name="kredit[]" class="form-control" step="0.01" required></td>
                          <td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>
                        </tr>
                      </tbody>
                    </table>
                    <button type="button" class="btn btn-info btn-sm" onclick="addJournalLineNew()">Tambah Baris</button>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Tambah Jurnal</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
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
  
  <script>
    function addJournalLine(jurnalId) {
      var newRow = `
        <tr>
          <td>
            <select name="akun[]" class="form-control" required>
              <option value="">-- Pilih Akun --</option>
              <?php
              $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
              while ($coa = mysqli_fetch_assoc($coa_query)) {
                echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".$coa['nama_akun']."</option>";
              }
              ?>
            </select>
          </td>
          <td><input type="number" name="debit[]" class="form-control" step="0.01" required></td>
          <td><input type="number" name="kredit[]" class="form-control" step="0.01" required></td>
          <td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>
        </tr>
      `;
      $('#journal-lines-' + jurnalId).append(newRow);
    }
    
    function addJournalLineNew() {
      var newRow = `
        <tr>
          <td>
            <select name="akun[]" class="form-control" required>
              <option value="">-- Pilih Akun --</option>
              <?php
              $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
              while ($coa = mysqli_fetch_assoc($coa_query)) {
                echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".$coa['nama_akun']."</option>";
              }
              ?>
            </select>
          </td>
          <td><input type="number" name="debit[]" class="form-control" step="0.01" required></td>
          <td><input type="number" name="kredit[]" class="form-control" step="0.01" required></td>
          <td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>
        </tr>
      `;
      $('#journal-lines').append(newRow);
    }
    
    $(document).on('click', '.remove-line', function() {
      $(this).closest('tr').remove();
    });
  </script>

</body>

</html>