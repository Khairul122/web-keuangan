<?php
// Include the mpdf library
require_once 'vendor/vendor/autoload.php'; // Sesuaikan path sesuai struktur proyek Anda

use Mpdf\Mpdf;

// Start session
session_start();

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if ($bulan < 1 || $bulan > 12) {
    $bulan = (int)date('n');
}

if ($tahun < 2000 || $tahun > 2100) {
    $tahun = (int)date('Y');
}

$tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));
$periode_label = date('F Y', strtotime($tanggal_awal));

// Get pimpinan name from session
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

// Create an instance of the mPDF class
$mpdf = new Mpdf();

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

<h4 style="text-align: center; margin-bottom: 5px;">LAPORAN ARUS KAS</h4>
<h4 style="text-align: center; margin-top: 0px; margin-bottom: 20px;">Periode: <?php echo $periode_label; ?></h4>

<!-- Tabel data -->
<table>
    <thead>
        <tr>
            <th colspan="2">Arus Kas Dari Data Operasional</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Sisipkan file koneksi.php yang berisi koneksi ke database
        include('koneksi.php');

        // Query SQL untuk mengambil data dari tabel arus_kas dengan status 1 (operasional) atau 2 (keuangan)
        $sql = "SELECT sumber, jumlah, status FROM arus_kas WHERE status IN (1, 2) AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";

        // Lakukan query untuk data operasional dan keuangan
        $result = $koneksi->query($sql);

        // Variabel untuk menyimpan total arus kas dari data operasional dan keuangan
        $total_arus_kas_operasional = 0;
        $total_arus_kas_keuangan = 0;
        $total_arus_kas_semua = 0;

        // Jika hasil query tidak kosong
        if ($result->num_rows > 0) {
            // Output data dari setiap baris
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                                            <td>" . $row['sumber'] . "</td>
                                            <td>" . $row['jumlah'] . "</td>
                                        </tr>";

                // Tambahkan jumlah ke total arus kas sesuai status
                if ($row['status'] == 1) {
                    $total_arus_kas_operasional += $row['jumlah'];
                } elseif ($row['status'] == 2) {
                    $total_arus_kas_keuangan += $row['jumlah'];
                }

                // Tambahkan jumlah ke total arus kas semua
                $total_arus_kas_semua += $row['jumlah'];
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data dengan status operasional atau keuangan</td></tr>";
        }

        // Tutup koneksi
        $koneksi->close();
        ?>
    </tbody>
    <tr>
        <td>Total Arus Kas Dari Data Operasional</td>
        <td><?php echo $total_arus_kas_operasional; ?></td>
    </tr>
    <tbody>
        <tr>
            <th colspan="2">Arus Kas Dari Data Keuangan</th>
        </tr>
        <tr>
            <td>Total Arus Kas Dari Data Keuangan</td>
            <td><?php echo $total_arus_kas_keuangan; ?></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>Saldo Kas</td>
            <td><?php echo $total_arus_kas_semua; ?></td>
        </tr>
    </tfoot>
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
$mpdf->Output('Laporan_Arus_Kas.pdf', 'I');

// Exit to prevent any additional output
exit;
?>
