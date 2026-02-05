<?php
require 'cek-sesi.php';
// Only allow admin and pemilik to access this page
if ($_SESSION['level'] !== 'admin' && $_SESSION['level'] !== 'pemilik') {
    header("location:index.php?pesan=forbidden");
    exit();
}

// Business logic calculations
require('koneksi.php');

// Query to get journal entries with totals in a single query
$query = "
    SELECT 
        je.*,
        COALESCE(SUM(jl.debit), 0) as total_debit,
        COALESCE(SUM(jl.kredit), 0) as total_kredit
    FROM journal_entries je
    LEFT JOIN journal_lines jl ON je.id_jurnal = jl.id_jurnal
    GROUP BY je.id_jurnal
    ORDER BY je.tanggal DESC, je.nomor_jurnal DESC
";

$result = mysqli_query($koneksi, $query);
$journals = [];
while ($data = mysqli_fetch_assoc($result)) {
    $data['status_text'] = ($data['total_debit'] == $data['total_kredit']) ? 'Seimbang' : 'Tidak Seimbang';
    $data['status_class'] = ($data['total_debit'] == $data['total_kredit']) ? 'success' : 'warning';
    $journals[] = $data;
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
                    <?php foreach ($journals as $data): ?>
                      <tr>
                        <td><strong><?= htmlspecialchars($data['nomor_jurnal']) ?></strong></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($data['tanggal']))) ?></td>
                        <td><?= htmlspecialchars(substr($data['keterangan'], 0, 50)) ?><?= strlen($data['keterangan']) > 50 ? '...' : '' ?></td>
                        <td>
                          <?php 
                          if($data['tipe_ref_transaksi'] == 'pemasukan') echo '<span class="badge badge-info">Pemasukan</span>';
                          elseif($data['tipe_ref_transaksi'] == 'pengeluaran') echo '<span class="badge badge-danger">Pengeluaran</span>';
                          elseif($data['tipe_ref_transaksi'] == 'hutang') echo '<span class="badge badge-warning">Hutang</span>';
                          else echo '<span class="badge badge-secondary">Manual</span>';
                          ?>
                        </td>
                        <td class="text-right">Rp. <?= number_format($data['total_debit'], 2, ',', '.') ?></td>
                        <td class="text-right">Rp. <?= number_format($data['total_kredit'], 2, ',', '.') ?></td>
                        <td class="text-center"><span class="badge badge-<?= $data['status_class'] ?>"><?= $data['status_text'] ?></span></td>
                        <?php if ($_SESSION['level'] === 'admin'): ?>
                        <td class="text-center">
                          <a href="#" type="button" class="btn btn-info btn-sm detail-btn" 
                             data-id="<?= $data['id_jurnal'] ?>" 
                             data-nomor="<?= htmlspecialchars($data['nomor_jurnal']) ?>" 
                             data-tanggal="<?= htmlspecialchars(date('d/m/Y', strtotime($data['tanggal']))) ?>" 
                             data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>" 
                             data-tipe="<?= $data['tipe_ref_transaksi'] ?>" 
                             data-total-debit="<?= $data['total_debit'] ?>" 
                             data-total-kredit="<?= $data['total_kredit'] ?>" 
                             data-status="<?= $data['status_text'] ?>" 
                             data-is-manual="<?= $data['tipe_ref_transaksi'] == 'manual' ? '1' : '0' ?>"
                             title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                          </a>
                          <?php if($data['tipe_ref_transaksi'] == 'manual'): ?>
                          <a href="#" type="button" class="btn btn-primary btn-sm edit-btn" 
                             data-id="<?= $data['id_jurnal'] ?>" 
                             data-nomor="<?= htmlspecialchars($data['nomor_jurnal']) ?>" 
                             data-tanggal="<?= $data['tanggal'] ?>" 
                             data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>" 
                             title="Edit Jurnal">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="hapus-jurnal.php?id_jurnal=<?= $data['id_jurnal']; ?>" onclick="return confirm('Anda Yakin Ingin Menghapus Jurnal Ini?')" class="btn btn-danger btn-sm" title="Hapus Jurnal">
                            <i class="fas fa-trash"></i>
                          </a>
                          <?php endif; ?>
                        </td>
                        <?php endif; ?>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
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
                            echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".htmlspecialchars($coa['nama_akun'])."</option>";
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
                <button type="button" class="btn btn-info btn-sm" id="add-journal-line">
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Detail Jurnal - <span id="detail-nomor-jurnal"></span></h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
                <p><strong>Keterangan:</strong> <span id="detail-keterangan"></span></p>
                <p><strong>Tipe Transaksi:</strong> <span id="detail-tipe-transaksi"></span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Total Debit:</strong> <span id="detail-total-debit"></span></p>
                <p><strong>Total Kredit:</strong> <span id="detail-total-kredit"></span></p>
                <p><strong>Status:</strong> <span id="detail-status"></span></p>
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
                <tbody id="detail-lines">
                  <!-- Lines will be populated by JavaScript -->
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Edit Jurnal - <span id="edit-nomor-jurnal"></span></h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <form id="edit-jurnal-form" method="post">
            <div class="modal-body">
              <input type="hidden" name="id_jurnal" id="edit-id-jurnal" value="">
              
              <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" id="edit-tanggal" required>
              </div>
              
              <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" id="edit-keterangan" required></textarea>
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
                  <tbody id="edit-lines">
                    <!-- Lines will be populated by JavaScript -->
                  </tbody>
                </table>
                <button type="button" class="btn btn-info btn-sm" id="add-edit-line">
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
    // Single dynamic modal handlers
    $(document).ready(function() {
      // Detail button handler
      $(document).on('click', '.detail-btn', function() {
        const id = $(this).data('id');
        const nomor = $(this).data('nomor');
        const tanggal = $(this).data('tanggal');
        const keterangan = $(this).data('keterangan');
        const tipe = $(this).data('tipe');
        const totalDebit = $(this).data('total-debit');
        const totalKredit = $(this).data('total-kredit');
        const status = $(this).data('status');
        
        // Set basic info
        $('#detail-nomor-jurnal').text(nomor);
        $('#detail-tanggal').text(tanggal);
        $('#detail-keterangan').text(keterangan);
        
        // Format transaction type
        let tipeText = '';
        switch(tipe) {
          case 'pemasukan': tipeText = '<span class="badge badge-info">Pemasukan</span>'; break;
          case 'pengeluaran': tipeText = '<span class="badge badge-danger">Pengeluaran</span>'; break;
          case 'hutang': tipeText = '<span class="badge badge-warning">Hutang</span>'; break;
          default: tipeText = '<span class="badge badge-secondary">Manual</span>';
        }
        $('#detail-tipe-transaksi').html(tipeText);
        
        // Format amounts
        $('#detail-total-debit').text('Rp. ' + parseFloat(totalDebit).toLocaleString('id-ID', {minimumFractionDigits: 2}));
        $('#detail-total-kredit').text('Rp. ' + parseFloat(totalKredit).toLocaleString('id-ID', {minimumFractionDigits: 2}));
        $('#detail-status').html('<span class="badge badge-' + (status === 'Seimbang' ? 'success' : 'warning') + '">' + status + '</span>');
        
        // Load journal lines
        $.ajax({
          url: 'includes/get-journal-lines.php',
          method: 'POST',
          data: { id_jurnal: id },
          dataType: 'json',
          success: function(lines) {
            let html = '';
            if(lines.length > 0) {
              lines.forEach(function(line) {
                html += '<tr>' +
                          '<td>' + line.nama_akun + '</td>' +
                          '<td class="text-right">Rp. ' + parseFloat(line.debit).toLocaleString('id-ID', {minimumFractionDigits: 2}) + '</td>' +
                          '<td class="text-right">Rp. ' + parseFloat(line.kredit).toLocaleString('id-ID', {minimumFractionDigits: 2}) + '</td>' +
                        '</tr>';
              });
            } else {
              html = '<tr><td colspan="3">Tidak ada baris jurnal</td></tr>';
            }
            $('#detail-lines').html(html);
          },
          error: function() {
            $('#detail-lines').html('<tr><td colspan="3">Gagal memuat data baris jurnal</td></tr>');
          }
        });
        
        $('#detailModal').modal('show');
      });
      
      // Edit button handler
      $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const nomor = $(this).data('nomor');
        const tanggal = $(this).data('tanggal');
        const keterangan = $(this).data('keterangan');
        
        // Set basic info
        $('#edit-nomor-jurnal').text(nomor);
        $('#edit-id-jurnal').val(id);
        $('#edit-tanggal').val(tanggal);
        $('#edit-keterangan').val(keterangan);
        
        // Load journal lines
        $.ajax({
          url: 'includes/get-journal-lines.php',
          method: 'POST',
          data: { id_jurnal: id },
          dataType: 'json',
          success: function(lines) {
            let html = '';
            if(lines.length > 0) {
              lines.forEach(function(line) {
                html += '<tr>' +
                          '<td>' +
                            '<select name="akun[]" class="form-control" required>' +
                              '<option value="">-- Pilih Akun --</option>' +
                              <?php 
                              $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                              while ($coa = mysqli_fetch_assoc($coa_query)) {
                                echo '"<option value=\"'.$coa['id_akun'].'\" ' + (line.id_akun == '.$coa['id_akun'].' ? \'selected\' : \'\') + '>'.$coa['nomor_akun'].' - '.htmlspecialchars(addslashes($coa['nama_akun'])).'</option>" + ';
                              }
                              echo '"";';
                              ?> +
                            '</select>' +
                          '</td>' +
                          '<td><input type="number" name="debit[]" class="form-control" value="' + line.debit + '" step="0.01" required></td>' +
                          '<td><input type="number" name="kredit[]" class="form-control" value="' + line.kredit + '" step="0.01" required></td>' +
                          '<td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>' +
                        '</tr>';
              });
            } else {
              html = '<tr>' +
                       '<td>' +
                         '<select name="akun[]" class="form-control" required>' +
                           '<option value="">-- Pilih Akun --</option>' +
                           <?php 
                           $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                           while ($coa = mysqli_fetch_assoc($coa_query)) {
                             echo '"<option value=\"'.$coa['id_akun'].'\">'.$coa['nomor_akun'].' - '.htmlspecialchars(addslashes($coa['nama_akun'])).'</option>" + ';
                           }
                           echo '"";';
                           ?> +
                         '</select>' +
                       '</td>' +
                       '<td><input type="number" name="debit[]" class="form-control" step="0.01" required></td>' +
                       '<td><input type="number" name="kredit[]" class="form-control" step="0.01" required></td>' +
                       '<td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>' +
                     '</tr>';
            }
            $('#edit-lines').html(html);
          },
          error: function() {
            $('#edit-lines').html('<tr><td colspan="4">Gagal memuat data baris jurnal</td></tr>');
          }
        });
        
        $('#editModal').modal('show');
      });
      
      // Handle edit form submission
      $(document).on('submit', '#edit-jurnal-form', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
          url: 'proses-edit-jurnal.php',
          method: 'POST',
          data: formData,
          success: function(response) {
            location.reload(); // Reload page to show updated data
          },
          error: function() {
            alert('Terjadi kesalahan saat menyimpan perubahan');
          }
        });
      });
      
      // Add journal line functionality
      $('#add-journal-line').on('click', function() {
        const newRow = `
          <tr>
            <td>
              <select name="akun[]" class="form-control" required>
                <option value="">-- Pilih Akun --</option>
                <?php
                $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                while ($coa = mysqli_fetch_assoc($coa_query)) {
                  echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".htmlspecialchars($coa['nama_akun'])."</option>";
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
      });
      
      // Add edit line functionality
      $(document).on('click', '#add-edit-line', function() {
        const newRow = `
          <tr>
            <td>
              <select name="akun[]" class="form-control" required>
                <option value="">-- Pilih Akun --</option>
                <?php
                $coa_query = mysqli_query($koneksi, "SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                while ($coa = mysqli_fetch_assoc($coa_query)) {
                  echo "<option value='".$coa['id_akun']."'>".$coa['nomor_akun']." - ".htmlspecialchars($coa['nama_akun'])."</option>";
                }
                ?>
              </select>
            </td>
            <td><input type="number" name="debit[]" class="form-control" step="0.01" required></td>
            <td><input type="number" name="kredit[]" class="form-control" step="0.01" required></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-line">Hapus</button></td>
          </tr>
        `;
        $('#edit-lines').append(newRow);
      });
      
      // Remove line functionality
      $(document).on('click', '.remove-line', function() {
        $(this).closest('tr').remove();
      });
    });
  </script>

</body>

</html>