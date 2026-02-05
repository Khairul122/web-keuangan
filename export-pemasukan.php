<?php
// Include the mpdf library
require_once 'vendor/vendor/autoload.php'; // Sesuaikan path sesuai struktur proyek Anda

// Start session
session_start();

// Get user ID
$id_user = $_SESSION['id'];

// Get pimpinan name from session
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

// Create an instance of the mPDF class
$mpdf = new \Mpdf\Mpdf();

// Start buffering the output
ob_start();

// HTML content for the PDF
?>
<style>
    h1, h4 {
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
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
</style>

<h1>CV BINA PADI SABATANG</h1>
<h4>Jl. Pulai, Batang Kabung Ganting</h4>
<h4>Kec. Koto Tangah, Kota Padang, Sumatera Barat 25586</h4>
<hr class="custom-line">

<h4 style="text-align: center; margin-bottom: 20px;">LAPORAN PEMASUKAN</h4>

<table>
    <thead>
        <tr>
            <th style="text-align:center">No</th>
            <th style="text-align:center">Tanggal Pemasukan</th>
            <th style="text-align:center">Sumber</th>
            <th style="text-align:center">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Load file koneksi.php
        include "koneksi.php";

        // Query untuk menampilkan data pemasukan
        $query = mysqli_query($koneksi, "SELECT * FROM pemasukan ORDER BY tgl_pemasukan DESC");

        $no = 1;
        $total_pemasukan = 0;

        while ($data = mysqli_fetch_array($query)) {
            $tgl_formatted = date('d F Y', strtotime($data['tgl_pemasukan']));
            $jumlah_formatted = number_format($data['jumlah'], 0, ',', '.');
            $total_pemasukan += $data['jumlah'];

            echo "<tr>";
            echo "<td style='text-align:center'>" . $no . "</td>";
            echo "<td>" . $tgl_formatted . "</td>";
            echo "<td>" . $data['sumber'] . "</td>";
            echo "<td style='text-align:right'>" . $jumlah_formatted . "</td>";
            echo "</tr>";
            $no++;
        }

        // Tampilkan total
        echo "<tr style='font-weight:bold; background-color: #f9f9f9;'>";
        echo "<td colspan='3' style='text-align:right'>TOTAL PEMASUKAN</td>";
        echo "<td style='text-align:right'>" . number_format($total_pemasukan, 0, ',', '.') . "</td>";
        echo "</tr>";
        ?>
    </tbody>
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
$mpdf->Output('Laporan_Pemasukan.pdf', 'I');

// Exit to prevent any additional output
exit;
?>
