<?php
// Include the mpdf library
require_once 'vendor/vendor/autoload.php'; // Sesuaikan path sesuai struktur proyek Anda

// Start session
session_start();

// Get format parameter (default to pdf)
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';

// Get pimpinan name from session
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';

// Create an instance of the mPDF class
if($format === 'excel') {
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
    $filename = 'Laporan_Hutang_' . date('Y-m-d') . '.xlsx';
    $disposition = 'I'; // Inline for Excel
} else {
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
    $filename = 'Laporan_Hutang_' . date('Y-m-d') . '.pdf';
    $disposition = 'I'; // Inline for PDF
}

// Start buffering the output
ob_start();

// HTML content for the report
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
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8em;
    }
    
    .status-warning { background-color: #ffc107; color: #000; }
    .status-primary { background-color: #007bff; color: #fff; }
    .status-success { background-color: #28a745; color: #fff; }
    .status-secondary { background-color: #6c757d; color: #fff; }
</style>

<h1>CV BINA PADI SABATANG</h1>
<h4>Jl. Pulai, Batang Kabung Ganting</h4>
<h4>Kec. Koto Tangah, Kota Padang, Sumatera Barat 25586</h4>
<hr class="custom-line">

<h4 style="text-align: center; margin-bottom: 5px;">LAPORAN HUTANG</h4>
<h4 style="text-align: center; margin-top: 0px; margin-bottom: 20px;">Periode: <?php echo date('d F Y'); ?></h4>

<!-- Tabel data -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Nama Penghutang</th>
            <th>Alasan</th>
            <th>Jumlah</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Sisipkan file koneksi.php yang berisi koneksi ke database
        include('koneksi.php');

        // Query untuk menampilkan data dari tabel hutang
        $query_hutang = "SELECT * FROM hutang ORDER BY tgl_hutang DESC";

        // Jalankan query hutang
        $result_hutang = mysqli_query($koneksi, $query_hutang);

        // Inisialisasi variabel total hutang
        $total_hutang = 0;
        $no = 1;

        // Periksa apakah query hutang berhasil dijalankan
        if ($result_hutang && mysqli_num_rows($result_hutang) > 0) {
            // Loop untuk menampilkan data hutang
            while ($row = mysqli_fetch_assoc($result_hutang)) {
                $status_text = '';
                $status_class = '';
                
                switch($row['status']) {
                    case 1:
                        $status_text = 'Belum Dibayar';
                        $status_class = 'status-warning';
                        break;
                    case 2:
                        $status_text = 'Sebagian Dibayar';
                        $status_class = 'status-primary';
                        break;
                    case 3:
                        $status_text = 'Lunas';
                        $status_class = 'status-success';
                        break;
                    default:
                        $status_text = 'Tidak Dikenal';
                        $status_class = 'status-secondary';
                }
                
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['tgl_hutang'])) . "</td>";
                echo "<td>" . $row['penghutang'] . "</td>";
                echo "<td>" . $row['alasan'] . "</td>";
                echo "<td style='text-align:right'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                echo "<td><span class='status-badge " . $status_class . "'>" . $status_text . "</span></td>";
                echo "</tr>";

                // Tambahkan nilai jumlah hutang ke dalam total_hutang
                $total_hutang += $row['jumlah'];
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data hutang</td></tr>";
        }

        // Tampilkan total hutang
        echo "<tr><td colspan='4'><strong>Total Hutang Keseluruhan</strong></td><td style='text-align:right'><strong>" . number_format($total_hutang, 0, ',', '.') . "</strong></td><td></td></tr>";
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

// Set PDF/Excel headers and output
$mpdf->Output($filename, $disposition);

// Exit to prevent any additional output
exit;
?>