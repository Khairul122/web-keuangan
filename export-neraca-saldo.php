<?php
// Include the mpdf library
require_once 'vendor/vendor/autoload.php'; // Sesuaikan path sesuai struktur proyek Anda

// Start session
session_start();

// Get filter parameters
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-t');
$id_user = $_SESSION['id'];

// Format tanggal untuk tampilan
$tgl_awal_fmt = date('d F Y', strtotime($tanggal_awal));
$tgl_akhir_fmt = date('d F Y', strtotime($tanggal_akhir));

// Get pimpinan name from session
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

// Create an instance of the mPDF class
$mpdf = new \Mpdf\Mpdf();

// Start buffering the output
ob_start();

// HTML content for the PDF
?>
<style>
    h1,
    h4 {
        text-align: center;
    }

    hr.custom-line {
        margin-top: 0px;
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #000;
    }

    .right-info {
        float: right;
        text-align: right;
        padding-top: 100px;
    }

    /* Tambahkan gaya untuk tabel */
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }
</style>

<h1>CV BINA PADI SABATANG</h1>
<h4>Jl. Pulai, Batang Kabung Ganting</h4>
<h4>Kec. Koto Tangah, Kota Padang, Sumatera Barat 25586</h4>
<hr class="custom-line">

<h4 style="text-align: center; margin-bottom: 5px;">LAPORAN NERACA SALDO</h4>
<h4 style="text-align: center; margin-top: 0px; margin-bottom: 20px;">Periode: <?php echo $tgl_awal_fmt; ?> s/d <?php echo $tgl_akhir_fmt; ?></h4>

<!-- Tabel data -->
<table>
    <tr>
        <th rowspan="2" style="text-align:center">No. Akun</th>
        <th rowspan="2" style="text-align:center">Nama Akun</th>
        <th colspan="2" style="text-align:center">Saldo Awal</th>
        <th colspan="2" style="text-align:center">Pergerakan</th>
        <th colspan="2" style="text-align:center">Saldo Akhir</th>

    </tr>
    <tr>
        <th style="text-align:center">Debit</th>
        <th style="text-align:center">Credit</th>
        <th style="text-align:center">Debit</th>
        <th style="text-align:center">Credit</th>
        <th style="text-align:center">Debit</th>
        <th style="text-align:center">Credit</th>
    </tr>
    <tr>
        <th colspan="8" style="text-align:left">Asset</th>
    </tr>
    <?php
    include('koneksi.php');
    $sql = "SELECT * FROM neraca_saldo WHERE status = 1
            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            AND id_user = '$id_user'
            ORDER BY nomor_akun ASC";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
            echo "<td>" . $row["nama_akun"] . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
            $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
            $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
    }
    $koneksi->close();
    ?>
    <tr>
        <th colspan="8" style="text-align:left">Kewajiban</th>
    </tr>
    <?php
    include('koneksi.php');
    $sql = "SELECT * FROM neraca_saldo WHERE status = 2
            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            AND id_user = '$id_user'
            ORDER BY nomor_akun ASC";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
            echo "<td>" . $row["nama_akun"] . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
            $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
            $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
    }
    $koneksi->close();
    ?>
    <tr>
        <th colspan="8" style="text-align:left">Ekuitas</th>
    </tr>
    <?php
    include('koneksi.php');
    $sql = "SELECT * FROM neraca_saldo WHERE status = 3
            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            AND id_user = '$id_user'
            ORDER BY nomor_akun ASC";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
            echo "<td>" . $row["nama_akun"] . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
            $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
            $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
    }
    $koneksi->close();
    ?>
    <tr>
        <th colspan="8" style="text-align:left">Pendapatan</th>
    </tr>
    <?php
    include('koneksi.php');
    $sql = "SELECT * FROM neraca_saldo WHERE status = 4
            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            AND id_user = '$id_user'
            ORDER BY nomor_akun ASC";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
            echo "<td>" . $row["nama_akun"] . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
            $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
            $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
    }
    $koneksi->close();
    ?>
    <tr>
        <th colspan="8" style="text-align:left">Beban</th>
    </tr>
    <?php
    include('koneksi.php');
    $sql = "SELECT * FROM neraca_saldo WHERE status = 5
            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
            AND id_user = '$id_user'
            ORDER BY nomor_akun ASC";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ($row["nomor_akun"] ?? '-') . "</td>";
            echo "<td>" . $row["nama_akun"] . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["saldo_awal_kredit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_debit"], 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($row["pergerakan_kredit"], 0, ',', '.') . "</td>";
            $saldo_akhir_debit = $row["saldo_awal_debit"] + $row["pergerakan_debit"];
            $saldo_akhir_kredit = $row["saldo_awal_kredit"] + $row["pergerakan_kredit"];
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_debit, 0, ',', '.') . "</td>";
            echo "<td style='text-align:right'>" . number_format($saldo_akhir_kredit, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center'>Tidak ada data</td></tr>";
    }
    $koneksi->close();
    ?>
    <tr>
        <th style="text-align:left">Total</th>
        <?php
        include('koneksi.php');

        // Query untuk menghitung total dengan filter tanggal dan user
        $sql_total_debit = "SELECT SUM(saldo_awal_debit) AS total_debit FROM neraca_saldo
                           WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                           AND id_user = '$id_user'";
        $result_total_debit = $koneksi->query($sql_total_debit);
        $total_debit = $result_total_debit ? $result_total_debit->fetch_assoc()['total_debit'] : 0;

        $sql_total_kredit = "SELECT SUM(saldo_awal_kredit) AS total_kredit FROM neraca_saldo
                            WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                            AND id_user = '$id_user'";
        $result_total_kredit = $koneksi->query($sql_total_kredit);
        $total_kredit = $result_total_kredit ? $result_total_kredit->fetch_assoc()['total_kredit'] : 0;

        $sql_total_pergerakan_debit = "SELECT SUM(pergerakan_debit) AS total_pergerakan_debit FROM neraca_saldo
                                        WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                        AND id_user = '$id_user'";
        $result_total_pergerakan_debit = $koneksi->query($sql_total_pergerakan_debit);
        $total_pergerakan_debit = $result_total_pergerakan_debit ? $result_total_pergerakan_debit->fetch_assoc()['total_pergerakan_debit'] : 0;

        $sql_total_pergerakan_kredit = "SELECT SUM(pergerakan_kredit) AS total_pergerakan_kredit FROM neraca_saldo
                                        WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                        AND id_user = '$id_user'";
        $result_total_pergerakan_kredit = $koneksi->query($sql_total_pergerakan_kredit);
        $total_pergerakan_kredit = $result_total_pergerakan_kredit ? $result_total_pergerakan_kredit->fetch_assoc()['total_pergerakan_kredit'] : 0;

        $sql_total_saldo_akhir_debit = "SELECT SUM(saldo_awal_debit + pergerakan_debit) AS total_saldo_akhir_debit FROM neraca_saldo
                                        WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                        AND id_user = '$id_user'";
        $result_total_saldo_akhir_debit = $koneksi->query($sql_total_saldo_akhir_debit);
        $total_saldo_akhir_debit = $result_total_saldo_akhir_debit ? $result_total_saldo_akhir_debit->fetch_assoc()['total_saldo_akhir_debit'] : 0;

        $sql_total_saldo_akhir_kredit = "SELECT SUM(saldo_awal_kredit + pergerakan_kredit) AS total_saldo_akhir_kredit FROM neraca_saldo
                                        WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                        AND id_user = '$id_user'";
        $result_total_saldo_akhir_kredit = $koneksi->query($sql_total_saldo_akhir_kredit);
        $total_saldo_akhir_kredit = $result_total_saldo_akhir_kredit ? $result_total_saldo_akhir_kredit->fetch_assoc()['total_saldo_akhir_kredit'] : 0;

        $koneksi->close();
        ?>
        <td></td>
        <td style='text-align:right'><strong><?php echo number_format($total_debit, 0, ',', '.'); ?></strong></td>
        <td style='text-align:right'><strong><?php echo number_format($total_kredit, 0, ',', '.'); ?></strong></td>
        <td style='text-align:right'><strong><?php echo number_format($total_pergerakan_debit, 0, ',', '.'); ?></strong></td>
        <td style='text-align:right'><strong><?php echo number_format($total_pergerakan_kredit, 0, ',', '.'); ?></strong></td>
        <td style='text-align:right'><strong><?php echo number_format($total_saldo_akhir_debit, 0, ',', '.'); ?></strong></td>
        <td style='text-align:right'><strong><?php echo number_format($total_saldo_akhir_kredit, 0, ',', '.'); ?></strong></td>
    </tr>
</table>

<div style="margin-top: 20px; text-align: left;">
    <div style="float: right;">
        Padang, <?php echo date('j F Y'); ?><br>
        <?php echo htmlspecialchars($nama_pimpinan); ?>
        <br>
        <br>
        <br>
        <br>
        (<?php echo htmlspecialchars($nama_pimpinan); ?>)
    </div>
</div>

<?php

// Get the buffered content
$html = ob_get_clean();

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Set PDF headers - Preview di browser dulu (I = Inline)
$mpdf->Output('Laporan_Neraca_Saldo.pdf', 'I');

// Exit to prevent any additional output
exit;
?>
