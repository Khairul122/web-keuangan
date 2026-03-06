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

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <?php
    require 'koneksi.php';
    require 'sidebar.php';
    ?>

    <div id="content">
        <?php require 'navbar.php'; ?>

        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data Arus Kas</h6>
                </div>
                <div class="card-body">
                    <form action="tambah-arus-kas.php" method="POST">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>

                        <div class="form-group">
                            <label for="sumber">Deskripsi</label>
                            <input type="text" class="form-control" id="sumber" name="sumber" required>
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah Arus Kas</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                            <small class="form-text text-muted">Masukkan nilai positif untuk kas masuk, nilai negatif untuk kas keluar.</small>
                        </div>

                        <div class="form-group">
                            <label for="kas_awal">Kas Awal Periode</label>
                            <input type="number" class="form-control" id="kas_awal" name="kas_awal" value="0">
                            <small class="form-text text-muted">Isi saat entri pertama dalam periode laporan.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Kategori Aktivitas</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1">Operasional</option>
                                <option value="2">Pendanaan</option>
                                <option value="3">Investasi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_akun">Akun Terkait</label>
                            <select class="form-control" id="id_akun" name="id_akun">
                                <option value="">-- Pilih Akun --</option>
                                <?php
                                $akun_query = mysqli_query($koneksi, "SELECT id_akun, nomor_akun, nama_akun FROM chart_of_accounts WHERE is_active = 1 ORDER BY nomor_akun");
                                while ($akun = mysqli_fetch_assoc($akun_query)) {
                                    echo '<option value="' . $akun['id_akun'] . '">' . $akun['nomor_akun'] . ' - ' . $akun['nama_akun'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
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
