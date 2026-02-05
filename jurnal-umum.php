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
  ?>

  <div id="content">

    <?php require('navbar.php'); ?>

    <div class="container-fluid">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Jurnal Umum</h1>
        <?php if ($_SESSION['level'] === 'admin'): ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalTambah">
          <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Jurnal
        </button>
        <?php endif; ?>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Daftar Jurnal Umum</h6>
              <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                  <div class="dropdown-header">Opsi:</div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#myModalTambah">Tambah Jurnal</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModalLong">Bantuan</a>
                </div>
              </div>
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
                      <th class="text-right">Total Debit</th>
                      <th class="text-right">Total Kredit</th>
                      <th>Status</th>
                      <?php if ($_SESSION['level'] === 'admin'): ?>
                      <th class="text-center">Aksi</th>
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
                      
                      // Determine status based on balance
                      $status_text = ($total_debit == $total_kredit) ? 'Seimbang' : 'Tidak Seimbang';
                      $status_class = ($total_debit == $total_kredit) ? 'success' : 'warning';
                    ?>
                      <tr>
                        <td><strong><?= $data['nomor_jurnal'] ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                        <td><?= htmlspecialchars(substr($data['keterangan'], 0, 50)) ?><?= strlen($data['keterangan']) > 50 ? '...' : '' ?></td>
                        <td>
                          <?php 
                          if($data['tipe_ref_transaksi'] == 'pemasukan') echo '<span class="badge badge-info">Pemasukan</span>';
                          elseif($data['tipe_ref_transaksi'] == 'pengeluaran') echo '<span class="badge badge-danger">Pengeluaran</span>';
                          elseif($data['tipe_ref_transaksi'] == 'hutang') echo '<span class="badge badge-warning">Hutang</span>';
                          else echo '<span class="badge badge-secondary">Manual</span>';
                          ?>
                        </td>
                        <td class="text-right">Rp. <?= number_format($total_debit, 2, ',', '.') ?></td>
                        <td class="text-right">Rp. <?= number_format($total_kredit, 2, ',', '.') ?></td>
                        <td class="text-center"><span class="badge badge-<?= $status_class ?>"><?= $status_text ?></span></td>
                        <?php if ($_SESSION['level'] === 'admin'): ?>
                        <td class="text-center">
                          <a href="#" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal<?= $data['id_jurnal']; ?>" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                          </a>
                          <?php if($data['tipe_ref_transaksi'] == 'manual'): ?>
                          <a href="#" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $data['id_jurnal']; ?>" title="Edit Jurnal">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="hapus-jurnal.php?id_jurnal=<?= $data['id_jurnal']; ?>" onclick="return confirm('Anda Yakin Ingin Menghapus Jurnal Ini?')" class="btn btn-danger btn-sm" title="Hapus Jurnal">
                            <i class="fas fa-trash"></i>
                          </a>
                          <?php endif; ?>
                        </td>
                        <?php endif; ?>
                      </tr>
                      
                      <!-- Detail Modal -->
                      <div class="modal fade" id="detailModal<?= $data['id_jurnal']; ?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                              <h4 class="modal-title">Detail Jurnal - <?= $data['nomor_jurnal'] ?></h4>
                              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <div class="row mb-3">
                                <div class="col-md-6">
                                  <p><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($data['tanggal'])) ?></p>
                                  <p><strong>Keterangan:</strong> <?= htmlspecialchars($data['keterangan']) ?></p>
                                  <p><strong>Tipe Transaksi:</strong> 
                                    <?php 
                                    if($data['tipe_ref_transaksi'] == 'pemasukan') echo '<span class="badge badge-info">Pemasukan</span>';
                                    elseif($data['tipe_ref_transaksi'] == 'pengeluaran') echo '<span class="badge badge-danger">Pengeluaran</span>';
                                    elseif($data['tipe_ref_transaksi'] == 'hutang') echo '<span class="badge badge-warning">Hutang</span>';
                                    else echo '<span class="badge badge-secondary">Manual</span>';
                                    ?>
                                  </p>
                                </div>
                                <div class="col-md-6">
                                  <p><strong>Total Debit:</strong> Rp. <?= number_format($total_debit, 2, ',', '.') ?></p>
                                  <p><strong>Total Kredit:</strong> Rp. <?= number_format($total_kredit, 2, ',', '.') ?></p>
                                  <p><strong>Status:</strong> 
                                    <?php 
                                    if($total_debit == $total_kredit) echo '<span class="badge badge-success">Seimbang</span>';
                                    else echo '<span class="badge badge-warning">Tidak Seimbang</span>';
                                    ?>
                                  </p>
                                </div>
                              </div>
                              
                              <h5 class="mb-3">Baris Jurnal:</h5>
                              <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>Nama Akun</th>
                                      <th class="text-right">Debit</th>
                                      <th class="text-right">Kredit</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $lines_query = mysqli_query($koneksi, "SELECT jl.*, coa.nama_akun FROM journal_lines jl JOIN chart_of_accounts coa ON jl.id_akun = coa.id_akun WHERE jl.id_jurnal = ".$data['id_jurnal']." ORDER BY jl.id_line ASC");
                                    while ($line = mysqli_fetch_assoc($lines_query)) {
                                    ?>
                                      <tr>
                                        <td><?= htmlspecialchars($line['nama_akun']) ?></td>
                                        <td class="text-right">Rp. <?= number_format($line['debit'], 2, ',', '.') ?></td>
                                        <td class="text-right">Rp. <?= number_format($line['kredit'], 2, ',', '.') ?></td>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Edit Modal for Manual Journals -->
                      <?php if($data['tipe_ref_transaksi'] == 'manual'): ?>
                      <div class="modal fade" id="editModal<?= $data['id_jurnal']; ?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                              <h4 class="modal-title">Edit Jurnal - <?= $data['nomor_jurnal'] ?></h4>
                              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                                
                                <h5 class="mt-4">Baris Jurnal:</h5>
                                <div class="table-responsive">
                                  <table class="table table-sm table-striped">
                                    <thead class="thead-light">
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
                                  <button type="button" class="btn btn-info btn-sm" onclick="addJournalLine(<?= $data['id_jurnal']; ?>)">
                                    <i class="fas fa-plus"></i> Tambah Baris
                                  </button>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                  <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
              <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Tambah Jurnal Umum Baru</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                  
                  <h5 class="mt-4">Baris Jurnal:</h5>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped">
                      <thead class="thead-light">
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
                    <button type="button" class="btn btn-info btn-sm" onclick="addJournalLineNew()">
                      <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Jurnal
                  </button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Bantuan Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Bantuan Jurnal Umum</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p><strong>Jurnal Umum</strong> adalah buku jurnal yang digunakan untuk mencatat semua transaksi keuangan perusahaan secara kronologis.</p>
                <p><strong>Catatan Penting:</strong></p>
                <ul>
                  <li>Setiap jurnal harus memiliki total debit dan kredit yang seimbang</li>
                  <li>Gunakan akun yang sudah terdaftar dalam Chart of Accounts</li>
                  <li>Jurnal otomatis dibuat saat melakukan transaksi pemasukan/pengeluaran/hutang</li>
                  <li>Hanya jurnal manual yang dapat diedit atau dihapus</li>
                </ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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