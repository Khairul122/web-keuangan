# Aplikasi Sistem Akuntansi CV Bina Padi Sabatang

Aplikasi sistem akuntansi berbasis web yang dirancang khusus untuk mengelola keuangan CV Bina Padi Sabatang, sebuah perusahaan pertanian yang berlokasi di Padang, Sumatera Barat. Sistem ini membantu dalam pencatatan transaksi keuangan, pembuatan laporan keuangan, dan pengelolaan data keuangan secara digital dan terintegrasi.

## Fitur Utama

- **Manajemen Transaksi Keuangan**: Pencatatan pemasukan dan pengeluaran secara rinci dan terorganisir
- **Manajemen Hutang**: Monitoring kewajiban keuangan perusahaan secara efektif
- **Manajemen Arus Kas**: Pelacakan pergerakan uang masuk dan keluar secara real-time
- **Manajemen Neraca Saldo**: Pengelolaan posisi keuangan perusahaan berdasarkan akun-akun buku besar
- **Manajemen Laba Rugi**: Perhitungan keuntungan dan kerugian secara otomatis
- **Manajemen Karyawan**: Penyimpanan informasi karyawan perusahaan
- **Laporan Keuangan**: Pembuatan laporan keuangan dalam format PDF yang profesional
- **Sistem Jurnal Otomatis**: Implementasi prinsip akuntansi double-entry dengan pembuatan jurnal otomatis
- **Dashboard Analitik**: Visualisasi data keuangan untuk pengambilan keputusan
- **Multi-Level User**: Sistem akses berjenjang untuk admin dan pemilik dengan hak akses berbeda
- **Antarmuka Modern**: Tampilan yang modern dan user-friendly untuk pengalaman pengguna optimal

## Teknologi yang Digunakan

- **Backend**: PHP 7.4
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Framework UI**: Bootstrap 4 (SB Admin 2 Template)
- **Library Grafik**: Chart.js
- **Library PDF**: mPDF
- **Library Tabel**: DataTables
- **Animasi**: Anime.js

## Instalasi

1. Clone atau download repository ini ke direktori web server Anda (misalnya `htdocs` untuk XAMPP)
2. Ubah nama folder menjadi `keuangan`
3. Pastikan PHP versi 7.4 atau lebih tinggi digunakan
4. Buat database baru di MySQL dengan nama `keuangan`
5. Import file database `keuangan.sql` ke dalam database yang telah dibuat
6. Konfigurasikan koneksi database pada file `koneksi.php`
7. Jalankan aplikasi melalui browser

## Struktur Database

Database `keuangan` terdiri dari beberapa tabel penting yang saling terintegrasi melalui relasi Foreign Key untuk menjaga integritas data dan konsistensi informasi keuangan:

- `admin`: Tabel master pengguna dengan informasi login dan level akses (admin/pemilik)
- `chart_of_accounts`: Daftar akun akuntansi yang digunakan dalam sistem
- `pemasukan`: Data transaksi pemasukan dengan referensi ke akun pendapatan dan akun kas
- `pengeluaran`: Data transaksi pengeluaran dengan referensi ke akun beban dan akun kas
- `hutang`: Data kewajiban hutang dengan referensi opsional ke akun debet dan kredit
- `arus_kas`: Data arus kas perusahaan dengan referensi opsional ke akun yang terkait
- `laba_rugi`: Data laba rugi dengan referensi opsional ke akun yang terkait
- `neraca_saldo`: Data neraca saldo dengan referensi opsional ke akun yang terkait
- `journal_entries`: Entri jurnal otomatis yang dibuat berdasarkan transaksi
- `journal_lines`: Detail baris jurnal yang terkait dengan entri jurnal dan akun
- `karyawan`: Data karyawan perusahaan

### Integrasi Data

Struktur database dirancang untuk mendukung prinsip akuntansi double-entry dengan sistem jurnal otomatis yang terintegrasi dengan transaksi pemasukan, pengeluaran, dan hutang.

## Tampilan Aplikasi

Berikut adalah beberapa tangkapan layar dari sistem:

### 1. Halaman Login
![Login Page](screenshot/login-page.png)
*Halaman autentikasi dengan desain Web 3.0*

### 2. Dashboard Admin
![Dashboard](screenshot/dashboard.png)
*Tampilan utama dengan ringkasan keuangan*

### 3. Dashboard Pemilik
![Dashboard](screenshot/dashboard-admin.png)
*Tampilan utama dengan ringkasan keuangan*

### 4. Manajemen Pemasukan
![Income Management](screenshot/income-management.png)
*Form untuk mencatat transaksi pemasukan*

### 5. Manajemen Pengeluaran
![Expense Management](screenshot/expense-management.png)
*Form untuk mencatat transaksi pengeluaran*

### 6. Laporan Keuangan
![Financial Reports](screenshot/financial-reports.png)
*Preview laporan keuangan dalam format PDF*

## Hak Akses Multi-Level

Sistem ini dirancang dengan sistem akses berjenjang untuk memastikan kontrol dan keamanan data keuangan perusahaan:

### Admin
- Akses penuh ke semua fitur sistem
- Pengelolaan transaksi keuangan (pemasukan, pengeluaran, hutang, dll.)
- Pengelolaan data karyawan
- Pembuatan dan pengelolaan laporan keuangan
- Pengaturan pengguna sistem

### Pemilik
- Akses ke dashboard utama untuk monitoring
- Pembuatan laporan keuangan
- Tidak memiliki akses ke fitur manajemen transaksi harian
- Dapat mengelola hak akses pengguna lain

## Sistem Jurnal Otomatis

Sistem ini menerapkan prinsip akuntansi double-entry dengan sistem jurnal otomatis yang terintegrasi. Setiap transaksi keuangan yang dicatat akan otomatis menghasilkan entri jurnal yang sesuai dengan prinsip debit-kredit, memastikan keseimbangan dan akurasi data keuangan.

Fitur-fitur utama sistem jurnal:
- Pembuatan jurnal otomatis berdasarkan transaksi pemasukan/pengeluaran
- Validasi keseimbangan debit dan kredit
- Integrasi dengan chart of accounts
- Penyimpanan histori transaksi secara terstruktur

## Desain Antarmuka

Sistem menggunakan desain modern dan responsif dengan:
- Tampilan yang intuitif dan user-friendly
- Animasi halus menggunakan Anime.js untuk pengalaman pengguna yang lebih baik
- Layout responsif yang menyesuaikan ukuran layar perangkat
- Warna formal dan profesional tanpa efek berlebihan
- Navigasi yang mudah dan terstruktur

## Konfigurasi Sistem

Pastikan konfigurasi database telah disesuaikan pada file `koneksi.php`:

```php
$host = 'localhost';
$nama = 'root';
$pass = '';  // Sesuaikan dengan password MySQL Anda
$db = 'keuangan';
```

## Panduan Penggunaan

1. Akses halaman login dengan kredensial default:
   - Admin: email `admin@gmail.com` dan password `admin`
   - Pemilik: email `pemilik@gmail.com` dan password `pemilik`
2. Sistem akan otomatis menyesuaikan tampilan dan menu berdasarkan level pengguna
3. Gunakan menu navigasi untuk mengakses berbagai fitur sistem
4. Tambahkan transaksi keuangan melalui menu yang tersedia sesuai dengan hak akses
5. Generate laporan keuangan dari menu Laporan sesuai kebutuhan

## Manfaat Sistem

- **Efisiensi**: Otomatisasi proses pencatatan transaksi dan pembuatan jurnal
- **Akurasi**: Validasi data dan prinsip double-entry untuk menghindari kesalahan
- **Integrasi**: Data terhubung secara langsung antar modul
- **Keamanan**: Sistem akses berjenjang untuk perlindungan data
- **Kemudahan**: Antarmuka yang intuitif untuk penggunaan sehari-hari

## Kontribusi dan Pengembangan

Sistem ini dirancang untuk dapat dikembangkan lebih lanjut sesuai kebutuhan bisnis. Silakan fork repository ini dan submit pull request untuk kontribusi pengembangan sistem.

## Lisensi dan Hak Cipta

Proyek ini merupakan solusi internal untuk CV Bina Padi Sabatang dan tidak dilindungi oleh lisensi publik tertentu.

## Tim Pengembangan

Sistem ini dikembangkan sebagai solusi digital untuk kebutuhan akuntansi dan manajemen keuangan CV Bina Padi Sabatang.