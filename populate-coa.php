<?php
require 'koneksi.php';

$coa_data = [
    [
        'nomor_akun' => '1-1001',
        'nama_akun' => 'Kas',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1002',
        'nama_akun' => 'Bank BCA',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1003',
        'nama_akun' => 'Bank Mandiri',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1004',
        'nama_akun' => 'Piutang Usaha',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1005',
        'nama_akun' => 'Persediaan Pupuk & Pupuk Organik',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1006',
        'nama_akun' => 'Persediaan Benih & Bibit',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1007',
        'nama_akun' => 'Persediaan Alat Pertanian',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1008',
        'nama_akun' => 'Persediaan Sarana Produksi Pertanian',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '1-1009',
        'nama_akun' => 'Peralatan Pertanian',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Investasi'
    ],
    [
        'nomor_akun' => '1-1010',
        'nama_akun' => 'Kendaraan Operasional',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Investasi'
    ],
    [
        'nomor_akun' => '1-1011',
        'nama_akun' => 'Tanah',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Investasi'
    ],
    [
        'nomor_akun' => '1-1012',
        'nama_akun' => 'Bangun Gudang',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Investasi'
    ],
    [
        'nomor_akun' => '1-1013',
        'nama_akun' => 'Akumulasi Penyusutan Peralatan',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => NULL
    ],
    [
        'nomor_akun' => '1-1014',
        'nama_akun' => 'Akumulasi Penyusutan Kendaraan',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => NULL
    ],
    [
        'nomor_akun' => '1-1015',
        'nama_akun' => 'Akumulasi Penyusutan Bangunan',
        'jenis_akun' => 'Asset',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => NULL
    ],

    [
        'nomor_akun' => '2-1001',
        'nama_akun' => 'Utang Usaha',
        'jenis_akun' => 'Kewajiban',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '2-1002',
        'nama_akun' => 'Utang Gaji Karyawan',
        'jenis_akun' => 'Kewajiban',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '2-1003',
        'nama_akun' => 'Utang Listrik & Air',
        'jenis_akun' => 'Kewajiban',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '2-1004',
        'nama_akun' => 'Utang Pajak',
        'jenis_akun' => 'Kewajiban',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],

    [
        'nomor_akun' => '3-1001',
        'nama_akun' => 'Modal Pemilik',
        'jenis_akun' => 'Ekuitas',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Pendanaan'
    ],
    [
        'nomor_akun' => '3-1002',
        'nama_akun' => 'Laba Ditahan',
        'jenis_akun' => 'Ekuitas',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Pendanaan'
    ],
    [
        'nomor_akun' => '3-1003',
        'nama_akun' => 'Laba Berjalan',
        'jenis_akun' => 'Ekuitas',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Pendanaan'
    ],

    [
        'nomor_akun' => '4-1001',
        'nama_akun' => 'Pendapatan Penjualan Pupuk',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1002',
        'nama_akun' => 'Pendapatan Penjualan Benih & Bibit',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1003',
        'nama_akun' => 'Pendapatan Penjualan Alat Pertanian',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1004',
        'nama_akun' => 'Pendapatan Jasa Konsultasi Pertanian',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1005',
        'nama_akun' => 'Pendapatan Jasa Pengolahan Lahan',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1006',
        'nama_akun' => 'Pendapatan Sewa Alat Pertanian',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '4-1007',
        'nama_akun' => 'Pendapatan Lain-lain',
        'jenis_akun' => 'Pendapatan',
        'saldo_normal' => 'Kredit',
        'kategori_arus_kas' => 'Operasional'
    ],

    [
        'nomor_akun' => '5-1001',
        'nama_akun' => 'Harga Pokok Penjualan Pupuk',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1002',
        'nama_akun' => 'Harga Pokok Penjualan Benih',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1003',
        'nama_akun' => 'Harga Pokok Penjualan Alat Pertanian',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1004',
        'nama_akun' => 'Beban Gaji Karyawan',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1005',
        'nama_akun' => 'Beban Listrik & Air',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1006',
        'nama_akun' => 'Beban Sewa Tempat Usaha',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1007',
        'nama_akun' => 'Beban Penyusutan Peralatan',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1008',
        'nama_akun' => 'Beban Penyusutan Kendaraan',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1009',
        'nama_akun' => 'Beban Penyusutan Bangunan',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1010',
        'nama_akun' => 'Beban Bensin & Transportasi',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1011',
        'nama_akun' => 'Beban ATK & Operasional Kantor',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1012',
        'nama_akun' => 'Beban Maintenance Alat Pertanian',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1013',
        'nama_akun' => 'Beban Pajak Penghasilan',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1014',
        'nama_akun' => 'Beban Promosi & Marketing',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ],
    [
        'nomor_akun' => '5-1015',
        'nama_akun' => 'Beban Lain-lain',
        'jenis_akun' => 'Beban',
        'saldo_normal' => 'Debit',
        'kategori_arus_kas' => 'Operasional'
    ]
];

$success_count = 0;
$error_count = 0;
$errors = [];

$cek_data = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM chart_of_accounts");
$total_existing = mysqli_fetch_assoc($cek_data)['total'];

if ($total_existing > 0) {
    $message = "<div class='warning'>
        <strong>⚠️ Perhatian!</strong> Tabel chart_of_accounts sudah berisi $total_existing data.
        <br>Data tidak akan ditambahkan duplikat.
    </div>";
} else {
    foreach ($coa_data as $akun) {
        $nomor_akun = mysqli_real_escape_string($koneksi, $akun['nomor_akun']);
        $nama_akun = mysqli_real_escape_string($koneksi, $akun['nama_akun']);
        $jenis_akun = $akun['jenis_akun'];
        $saldo_normal = $akun['saldo_normal'];
        $kategori_arus_kas = $akun['kategori_arus_kas'] ? "'{$akun['kategori_arus_kas']}'" : 'NULL';

        $sql = "INSERT INTO chart_of_accounts
                (nomor_akun, nama_akun, jenis_akun, saldo_normal, kategori_arus_kas)
                VALUES ('$nomor_akun', '$nama_akun', '$jenis_akun', '$saldo_normal', $kategori_arus_kas)";

        if (mysqli_query($koneksi, $sql)) {
            $success_count++;
        } else {
            $error_count++;
            $errors[] = mysqli_error($koneksi);
        }
    }

    $message = "<div class='success'>
        <strong>✅ Berhasil!</strong> $success_count dari " . count($coa_data) . " akun berhasil ditambahkan.
    </div>";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Populate COA - Sistem Akuntansi Otomatis</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 10px 0; color: #155724; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 10px 0; color: #856404; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 10px 0; color: #721c24; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 10px 0; color: #0c5460; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; margin-right: 10px; }
        .btn:hover { background: #45a049; }
        .btn-blue { background: #17a2b8; }
        .btn-blue:hover { background: #138496; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #4CAF50; color: white; }
        tr:hover { background: #f5f5f5; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-asset { background: #007bff; color: white; }
        .badge-liability { background: #dc3545; color: white; }
        .badge-equity { background: #28a745; color: white; }
        .badge-revenue { background: #ffc107; color: #000; }
        .badge-expense { background: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Populate Chart of Accounts (COA)</h1>

        <?php echo $message; ?>

        <?php if ($error_count > 0): ?>
            <div class="error">
                <strong>❌ Error:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="info">
            <strong>📋 Ringkasan COA untuk CV Bina Padi Sabatang - Prasarana Pendukung Produksi Pangan:</strong>
            <br><br>
            <strong>Jumlah Total Akun:</strong> <?php echo count($coa_data); ?> akun<br>
            <strong>Terdiri dari:</strong>
            <ul style="margin-top: 10px;">
                <li>📘 <strong>Asset</strong>: 15 akun (Kas, Bank, Persediaan Pupuk/Benih/Alat Pertanian, Peralatan, Tanah, Bangunan Gudang, dll)</li>
                <li>📕 <strong>Kewajiban</strong>: 4 akun (Utang Usaha, Utang Gaji, dll)</li>
                <li>📗 <strong>Ekuitas</strong>: 3 akun (Modal, Laba Ditahan, dll)</li>
                <li>📙 <strong>Pendapatan</strong>: 7 akun (Penjualan Pupuk, Benih, Alat Pertanian, Jasa Konsultasi, Sewa Alat, dll)</li>
                <li>📓 <strong>Beban</strong>: 15 akun (HPP, Gaji, Listrik, Sewa, Maintenance Alat, dll)</li>
            </ul>
        </div>

        <h2 style="margin-top: 30px; color: #333;">📋 Daftar Chart of Accounts</h2>

        <table>
            <thead>
                <tr>
                    <th>No. Akun</th>
                    <th>Nama Akun</th>
                    <th>Jenis</th>
                    <th>Saldo Normal</th>
                    <th>Kategori Arus Kas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coa_data as $akun): ?>
                    <tr>
                        <td><strong><?php echo $akun['nomor_akun']; ?></strong></td>
                        <td><?php echo $akun['nama_akun']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($akun['jenis_akun']); ?>">
                                <?php echo $akun['jenis_akun']; ?>
                            </span>
                        </td>
                        <td><?php echo $akun['saldo_normal']; ?></td>
                        <td><?php echo $akun['kategori_arus_kas'] ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="info" style="margin-top: 30px;">
            <strong>🎯 Langkah Selanjutnya:</strong><br>
            Sekarang sistem sudah siap! Anda dapat melanjutkan dengan:<br>
            <ol style="margin-top: 10px; padding-left: 20px;">
                <li>Menggunakan form input pemasukan/pengeluaran baru (dengan pilihan akun)</li>
                <li>Transaksi akan otomatis membuat jurnal</li>
                <li>Laporan keuangan di-generate otomatis dari jurnal</li>
            </ol>
        </div>

        <a href="index.php" class="btn">← Kembali ke Dashboard</a>
        <a href="lihat-coa.php" class="btn btn-blue">Lihat COA di Database →</a>
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>
