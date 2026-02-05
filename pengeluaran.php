<?php
require 'cek-sesi.php';
require 'koneksi.php';
require_once 'includes/functions-jurnal.php';
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
    <?php require('sidebar.php'); ?>

    <div id="content">
        <?php require('navbar.php'); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 mb-4">
                </div>
            </div>

            <button type="button" class="btn btn-success" style="margin:5px" data-toggle="modal" data-target="#myModalTambah">
                <i class="fa fa-plus"></i> Pengeluaran
            </button>
            <br>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Transaksi Keluar</h6>
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
                                        $query = mysqli_query($koneksi, "SELECT * FROM pengeluaran");
                                        $no = 1;
                                        while ($data = mysqli_fetch_assoc($query)) {
                                        ?>
                                            <tr>
                                                <td><?= $data['id_pengeluaran'] ?></td>
                                                <td><?= $data['tgl_pengeluaran'] ?></td>
                                                <td>Rp. <?= number_format($data['jumlah'], 2, ',', '.'); ?></td>
                                                <td><?= $data['sumber'] ?></td>
                                                <td>
                                                    <a href="#" type="button" class="fa fa-edit btn btn-primary btn-md" data-toggle="modal" data-target="#myModal<?php echo $data['id_pengeluaran']; ?>"></a>
                                                </td>
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

    <?php
    $query_edit = mysqli_query($koneksi, "SELECT * FROM pengeluaran");
    while ($row = mysqli_fetch_array($query_edit)) {
    ?>
        <div class="modal fade" id="myModal<?php echo $row['id_pengeluaran']; ?>" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Data Pengeluaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form role="form" action="proses-edit-pengeluaran.php" method="get">
                        <div class="modal-body">
                            <input type="hidden" name="id_pengeluaran" value="<?php echo $row['id_pengeluaran']; ?>">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tgl_pengeluaran" class="form-control" value="<?php echo $row['tgl_pengeluaran']; ?>" required>
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
                            <a href="hapus-pengeluaran.php?id_pengeluaran=<?= $row['id_pengeluaran']; ?>" onclick="return confirm('Anda Yakin Ingin Menghapus?')" class="btn btn-danger">Hapus</a>
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
                    <h4 class="modal-title">Tambah Pengeluaran</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="tambah-pengeluaran.php" method="get">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" name="tgl_pengeluaran" required>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Sumber</label>
                            <input type="text" class="form-control" name="sumber" placeholder="Contoh: Pembayaran Gaji Karyawan" required>
                        </div>
                        <div class="form-group">
                            <label>Akun Beban (Debit)</label>
                            <select class="form-control" name="id_akun_beban" required>
                                <option value="">-- Pilih Akun Beban --</option>
                                <?php echo getCOAOptions($koneksi, 'Beban', null); ?>
                            </select>
                            <small class="form-text text-muted">Akun yang didebit (bertambah)</small>
                        </div>
                        <div class="form-group">
                            <label>Akun Kas/Bank (Kredit)</label>
                            <select class="form-control" name="id_akun_kas" required>
                                <option value="">-- Pilih Akun Kas/Bank --</option>
                                <?php echo getCOAOptions($koneksi, 'Asset', null); ?>
                            </select>
                            <small class="form-text text-muted">Akun yang dikredit (berkurang)</small>
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

    <?php require 'footer.php'; ?>
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

    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/datatables-demo.js"></script>

    <script type="text/javascript">
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["7 hari lalu", "6 hari lalu", "5 hari lalu", "4 hari lalu", "3 hari lalu", "2 hari lalu", "1 hari lalu"],
                datasets: [{
                    label: "Pendapatan",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        <?php
                        $tujuhhari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 7 DAY"));
                        $enamhari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 6 DAY"));
                        $limahari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 5 DAY"));
                        $empathari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 4 DAY"));
                        $tigahari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 3 DAY"));
                        $duahari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 2 DAY"));
                        $satuhari = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) as jumlah FROM pengeluaran WHERE tgl_pengeluaran = CURDATE() - INTERVAL 1 DAY"));

                        echo ($tujuhhari['jumlah'] ?? 0) . ", ";
                        echo ($enamhari['jumlah'] ?? 0) . ", ";
                        echo ($limahari['jumlah'] ?? 0) . ", ";
                        echo ($empathari['jumlah'] ?? 0) . ", ";
                        echo ($tigahari['jumlah'] ?? 0) . ", ";
                        echo ($duahari['jumlah'] ?? 0) . ", ";
                        echo ($satuhari['jumlah'] ?? 0);
                        ?>
                    ],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return 'Rp.' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp.' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>