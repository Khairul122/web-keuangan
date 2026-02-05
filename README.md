# Aplikasi Sistem Akuntansi CV Bina Padi Sabatang

Sistem akuntansi berbasis web untuk mengelola keuangan CV Bina Padi Sabatang, sebuah perusahaan pertanian yang berlokasi di Padang, Sumatera Barat.

## Fitur Utama

- **Manajemen Transaksi Keuangan**: Catat pemasukan dan pengeluaran secara rinci
- **Manajemen Hutang**: Pantau kewajiban keuangan perusahaan
- **Manajemen Arus Kas**: Lacak pergerakan uang masuk dan keluar
- **Manajemen Neraca Saldo**: Kelola posisi keuangan perusahaan
- **Manajemen Laba Rugi**: Hitung keuntungan dan kerugian
- **Manajemen Karyawan**: Simpan informasi karyawan
- **Laporan Keuangan**: Generate laporan dalam format PDF
- **Sistem Jurnal Otomatis**: Implementasi prinsip akuntansi double-entry
- **Dashboard Analitik**: Visualisasi data keuangan

## Teknologi yang Digunakan

- **Backend**: PHP 7.4
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Framework UI**: Bootstrap 4 (SB Admin 2 Template)
- **Library Grafik**: Chart.js
- **Library PDF**: mPDF
- **Library Tabel**: DataTables

## Instalasi

1. Clone atau download repository ini ke direktori web server Anda (misalnya `htdocs` untuk XAMPP)
2. Ubah nama folder menjadi `keuangan`
3. Pastikan PHP versi 7.4 digunakan
4. Import database ke MySQL dan beri nama `keuangan`
5. Jalankan aplikasi melalui browser

## Struktur Database

Database `keuangan` terdiri dari beberapa tabel penting:

- `admin`: Informasi login administrator
- `pemasukan`: Data transaksi pemasukan
- `pengeluaran`: Data transaksi pengeluaran
- `hutang`: Data kewajiban hutang
- `arus_kas`: Data arus kas perusahaan
- `laba_rugi`: Data laba rugi
- `neraca_saldo`: Data neraca saldo
- `karyawan`: Data karyawan perusahaan
- `chart_of_accounts`: Daftar akun akuntansi
- `journal_entries`: Entri jurnal otomatis
- `journal_lines`: Detail baris jurnal

## Tampilan Aplikasi

Berikut adalah beberapa tangkapan layar dari sistem:

### 1. Halaman Login
![Login Page](screenshot/login-page.png)
*Halaman autentikasi untuk mengakses sistem*

### 2. Dashboard Utama
![Dashboard](screenshot/dashboard.png)
*Tampilan utama dengan ringkasan keuangan*

### 3. Manajemen Pemasukan
![Income Management](screenshot/income-management.png)
*Form untuk mencatat transaksi pemasukan*

### 4. Manajemen Pengeluaran
![Expense Management](screenshot/expense-management.png)
*Form untuk mencatat transaksi pengeluaran*

### 5. Laporan Keuangan
![Financial Reports](screenshot/financial-reports.png)
*Preview laporan keuangan dalam format PDF*

## Konfigurasi

Pastikan konfigurasi database benar pada file `koneksi.php`:

```php
$host = 'localhost';
$nama = 'root';
$pass = '';  // Sesuaikan dengan password MySQL Anda
$db = 'keuangan';
```

## Penggunaan

1. Akses halaman login dengan email `admin@gmail.com` dan password `admin`
2. Gunakan menu navigasi untuk mengakses fitur-fitur sistem
3. Tambahkan transaksi keuangan melalui menu Pendapatan dan Pengeluaran
4. Generate laporan keuangan dari menu Laporan

## Sistem Jurnal Otomatis

Sistem ini menerapkan prinsip akuntansi double-entry dengan membuat jurnal otomatis ketika transaksi dicatat. Setiap transaksi akan menghasilkan entri jurnal yang sesuai dengan prinsip debit-kredit.

## Kontribusi

Silakan fork repository ini dan submit pull request untuk kontribusi pengembangan sistem.

## Lisensi

Proyek ini merupakan proyek internal untuk CV Bina Padi Sabatang dan tidak dilindungi oleh lisensi publik tertentu.

## Tim Pengembang

Sistem ini dikembangkan sebagai solusi internal untuk kebutuhan akuntansi CV Bina Padi Sabatang.