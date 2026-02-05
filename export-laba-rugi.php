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

<h4 style="text-align: center; margin-bottom: 5px;">LAPORAN LABA RUGI</h4>
<h4 style="text-align: center; margin-top: 0px; margin-bottom: 20px;">Periode: <?php echo $tgl_awal_fmt; ?> s/d <?php echo $tgl_akhir_fmt; ?></h4>

<!-- Tabel data -->
<table>
    <thead>
        <tr>
            <th colspan="3">Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Pendapatan dari Penjualan</td>
        </tr>
        <?php
        // Sisipkan file koneksi.php yang berisi koneksi ke database
        include('koneksi.php');

        // Query untuk menampilkan data dari tabel laba_rugi yang memiliki status 1
        $query_pendapatan = "SELECT sumber, jumlah FROM laba_rugi WHERE status = 1
                            AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                            AND id_user = '$id_user'";

        // Jalankan query pendapatan
        $result_pendapatan = mysqli_query($koneksi, $query_pendapatan);

        // Inisialisasi variabel total pendapatan
        $total_pendapatan = 0;

        // Periksa apakah query pendapatan berhasil dijalankan
        if ($result_pendapatan && mysqli_num_rows($result_pendapatan) > 0) {
            // Loop untuk menampilkan data pendapatan
            while ($row = mysqli_fetch_assoc($result_pendapatan)) {
                echo "<tr>";
                echo "<td>" . $row['sumber'] . "</td>";
                echo "<td style='text-align:right'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                echo "</tr>";

                // Tambahkan nilai jumlah pendapatan ke dalam total_pendapatan
                $total_pendapatan += $row['jumlah'];
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data pendapatan dari penjualan</td></tr>";
        }

        // Tampilkan total pendapatan dari penjualan
        echo "<tr><td colspan='1'><strong>Total Pendapatan dari Penjualan</strong></td><td style='text-align:right'><strong>" . number_format($total_pendapatan, 0, ',', '.') . "</strong></td></tr>";

        // Tambahkan label "Harga Pokok Penjualan"
        echo "<tr><td colspan='2'>Harga Pokok Penjualan</td></tr>";

        // Query untuk menampilkan data dari tabel laba_rugi yang memiliki status 2
        $query_harga_pokok = "SELECT sumber, jumlah FROM laba_rugi WHERE status = 2
                              AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                              AND id_user = '$id_user'";

        // Jalankan query harga pokok
        $result_harga_pokok = mysqli_query($koneksi, $query_harga_pokok);

        // Inisialisasi variabel total harga pokok
        $total_harga_pokok = 0;

        // Periksa apakah query harga pokok berhasil dijalankan
        if ($result_harga_pokok && mysqli_num_rows($result_harga_pokok) > 0) {
            // Loop untuk menampilkan data harga pokok
            while ($row = mysqli_fetch_assoc($result_harga_pokok)) {
                echo "<tr>";
                echo "<td>" . $row['sumber'] . "</td>";
                echo "<td style='text-align:right'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                echo "</tr>";

                // Tambahkan nilai jumlah harga pokok ke dalam total_harga_pokok
                $total_harga_pokok += $row['jumlah'];
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data harga pokok penjualan</td></tr>";
        }

        // Tampilkan total harga pokok penjualan
        echo "<tr><td colspan='1'><strong>Total Harga Pokok Penjualan</strong></td><td style='text-align:right'><strong>" . number_format($total_harga_pokok, 0, ',', '.') . "</strong></td></tr>";

        // Hitung laba kotor (Total Pendapatan - Total Harga Pokok)
        $laba_kotor = $total_pendapatan - $total_harga_pokok;

        // Tampilkan laba kotor
        echo "<tr><td colspan='1'><strong>Laba Kotor</strong></td><td style='text-align:right'><strong>" . number_format($laba_kotor, 0, ',', '.') . "</strong></td></tr>";

        // Tambahkan label "Biaya Operasional"
        echo "<tr><td colspan='2'>Biaya Operasional</td></tr>";

        // Query untuk menampilkan data dari tabel laba_rugi yang memiliki status 3
        $query_biaya_operasional = "SELECT sumber, jumlah FROM laba_rugi WHERE status = 3
                                   AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                   AND id_user = '$id_user'";

        // Jalankan query biaya operasional
        $result_biaya_operasional = mysqli_query($koneksi, $query_biaya_operasional);

        // Inisialisasi variabel total biaya operasional
        $total_biaya_operasional = 0;

        // Periksa apakah query biaya operasional berhasil dijalankan
        if ($result_biaya_operasional && mysqli_num_rows($result_biaya_operasional) > 0) {
            // Loop untuk menampilkan data biaya operasional
            while ($row = mysqli_fetch_assoc($result_biaya_operasional)) {
                echo "<tr>";
                echo "<td>" . $row['sumber'] . "</td>";
                echo "<td style='text-align:right'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                echo "</tr>";

                // Tambahkan nilai jumlah biaya operasional ke dalam total_biaya_operasional
                $total_biaya_operasional += $row['jumlah'];
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data biaya operasional</td></tr>";
        }

        // Tampilkan total biaya operasional
        echo "<tr><td colspan='1'><strong>Total Biaya Operasional</strong></td><td style='text-align:right'><strong>" . number_format($total_biaya_operasional, 0, ',', '.') . "</strong></td></tr>";

        // Hitung pendapatan bersih sebelum pajak (Laba Kotor - Total Biaya Operasional)
        $pendapatan_bersih_sebelum_pajak = $laba_kotor - $total_biaya_operasional;
        echo "<tr><td colspan='1'><strong>Laba Bersih Sebelum Pajak</strong></td><td style='text-align:right'><strong>" . number_format($pendapatan_bersih_sebelum_pajak, 0, ',', '.') . "</strong></td></tr>";

        // Hitung pajak penghasilan 25% (hanya jika laba)
        $pajak_penghasilan = ($pendapatan_bersih_sebelum_pajak > 0) ? $pendapatan_bersih_sebelum_pajak * 0.25 : 0;
        echo "<tr><td colspan='1'><strong>Beban Pajak Penghasilan (25%)</strong></td><td style='text-align:right'><strong>(" . number_format($pajak_penghasilan, 0, ',', '.') . ")</strong></td></tr>";

        // Hitung pendapatan bersih setelah pajak
        $pendapatan_bersih = $pendapatan_bersih_sebelum_pajak - $pajak_penghasilan;
        echo "<tr><td colspan='1'><strong>Laba Bersih Setelah Pajak</strong></td><td style='text-align:right'><strong>" . number_format($pendapatan_bersih, 0, ',', '.') . "</strong></td></tr>";
        echo "<tr><td colspan='1'><strong>Total Pendapatan Komprehensif Periode Ini</strong></td><td style='text-align:right'><strong>" . number_format($pendapatan_bersih, 0, ',', '.') . "</strong></td></tr>";

        // Tutup koneksi ke database
        mysqli_close($koneksi);
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



<!-- <div class="right-info">
    <p style="padding-right:55px;">Padang, <?php echo date('d F Y'); ?></p>
    <p>Pimpinan CV Bina Padi Sabatang  </p>
    <br>
    <p style="padding-right:125px;">Pimpinan</p>
</div> -->

<?php

// Get the buffered content
$html = ob_get_clean();

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Set PDF headers - Preview di browser dulu (I = Inline)
$mpdf->Output('Laporan_Laba_Rugi.pdf', 'I');

// Exit to prevent any additional output
exit;
?>