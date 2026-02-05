# PANDUAN UPDATE SISTEM AKUNTANSI
## Minor Improvements - Neraca Saldo, Laba Rugi, Arus Kas
## CV BINA PADI SABATANG

Tanggal: 19 Januari 2026

---

## 📋 RINGKASAN PERUBAHAN

### 1. ✅ NERACA SALDO (Trial Balance) - SELESAI

**Perbaikan yang dilakukan:**
- ✅ Menambahkan kolom `nomor_akun` (Chart of Accounts - COA)
- ✅ Filter periode tanggal (tanggal awal & tanggal akhir)
- ✅ Mengganti hardcoded `id_user = 1` menjadi dynamic `$_SESSION['id_admin']`
- ✅ Format number dengan pemisah ribuan (titik)
- ✅ Urutan berdasarkan nomor akun

**File yang diperbarui:**
1. `neraca-saldo-lihat.php` - View dengan filter
2. `export-neraca-saldo.php` - Export PDF dengan filter

**Perubahan Database:**
```sql
ALTER TABLE `neraca_saldo`
ADD COLUMN `nomor_akun` VARCHAR(20) NULL AFTER `nama_akun`,
MODIFY COLUMN `status` INT(11) DEFAULT NULL;
```

---

### 2. ✅ LABA RUGI (Income Statement) - SELESAI

**Perbaikan yang dilakukan:**
- ✅ Menambahkan **Pajak Penghasilan 25%** (sesuai UU PPh Indonesia)
- ✅ Filter periode tanggal
- ✅ Mengganti hardcoded `id_user = 1` menjadi dynamic
- ✅ Format number dengan pemisah ribuan
- ✅ Struktur baru:
  - Pendapatan dari Penjualan
  - (-) Harga Pokok Penjualan
  - (=) Laba Kotor
  - (-) Biaya Operasional
  - (=) **Laba Bersih Sebelum Pajak** ← BARU
  - (-) **Beban Pajak Penghasilan (25%)** ← BARU
  - (=) **Laba Bersih Setelah Pajak** ← BARU
  - Total Pendapatan Komprehensif

**File yang diperbarui:**
1. `laba-rugi-lihat.php` - View dengan pajak & filter

**Perubahan Database:**
- Tidak perlu mengubah database untuk Laba Rugi

---

### 3. 🔄 ARUS KAS (Cash Flow) - DALAM PROSES

**Perbaikan yang diperlukan:**
- ⏳ Menambahkan kategori **Aktivitas Investasi** (status 3)
- ⏳ Menambahkan kolom `kas_awal` untuk mencatat saldo awal
- ⏳ Mengubah kategori "Keuangan" menjadi "Pendanaan"
- ⏳ Menambahkan perhitungan:
  - Kas Awal Periode
  - Kenaikan/penurunan kas (Net Change)
  - Kas Akhir Periode
- ⏳ Filter periode tanggal
- ⏳ Mengganti hardcoded `id_user = 1` menjadi dynamic

**Struktur Standar PSAK 2 / IAS 7:**
```
LAPORAN ARUS KAS
Periode: [Tanggal Awal] s/d [Tanggal Akhir]

A. Aktivitas Operasional
   - Arus kas masuk/keluar dari operasi
   Total Arus Kas Operasional: Rp XXX

B. Aktivitas Investasi  ← BARU
   - Pembelian/pengjualan aset tetap
   - Investasi jangka panjang
   Total Arus Kas Investasi: Rp XXX

C. Aktivitas Pendanaan (sebelumnya: Keuangan)
   - Pinjaman/modal
   - Pembayaran hutang
   Total Arus Kas Pendanaan: Rp XXX

D. Kas Awal Periode: Rp XXX  ← BARU
E. Kenaikan/(Penurunan) Kas: Rp XXX  ← BARU
F. Kas Akhir Periode: Rp XXX  ← BARU
```

**Perubahan Database yang Diperlukan:**
```sql
-- Tambah kolom kas_awal
ALTER TABLE `arus_kas`
ADD COLUMN `kas_awal` BIGINT(20) DEFAULT 0 AFTER `jumlah`;

-- Update nilai status:
-- Status 1: Operasional (tetap)
-- Status 2: Pendanaan (sebelumnya: Keuangan)
-- Status 3: Investasi (BARU)
```

**File yang perlu diperbarui:**
1. `arus-kas.php` - View utama
2. `export-arus-kas.php` - Export PDF
3. `arus-kas-tambah.php` - Form input (tambah field kas_awal)

---

## 🚀 LANGKAH-LANGKAH IMPLEMENTASI

### LANGKAH 1: JALANKAN UPDATE DATABASE

Buka phpMyAdmin atau terminal, jalankan perintah:

```bash
# Masuk ke MySQL
mysql -u root -p

# Pilih database
USE keuangan2;

# Jalankan file update
source C:/xampp/htdocs/akutansi/database/update_database.sql;
```

Atau import file `database/update_database.sql` melalui phpMyAdmin.

### LANGKAH 2: UPDATE DATA YANG SUDAH ADA

Setelah menjalankan SQL update, jalankan query ini untuk update data contoh:

```sql
-- Update nomor akun untuk data yang sudah ada
UPDATE `neraca_saldo` SET `nomor_akun` = '1-1001' WHERE `status` = 1 AND `nomor_akun` IS NULL;
UPDATE `neraca_saldo` SET `nomor_akun` = '2-1001' WHERE `status` = 2 AND `nomor_akun` IS NULL;
UPDATE `neraca_saldo` SET `nomor_akun` = '3-1001' WHERE `status` = 3 AND `nomor_akun` IS NULL;
UPDATE `neraca_saldo` SET `nomor_akun` = '4-1001' WHERE `status` = 4 AND `nomor_akun` IS NULL;
UPDATE `neraca_saldo` SET `nomor_akun` = '5-1001' WHERE `status` = 5 AND `nomor_akun` IS NULL;
```

### LANGKAH 3: VERIFIKASI PERUBAHAN

1. **Neraca Saldo:**
   - Buka `neraca-saldo-lihat.php`
   - Cek apakah filter tanggal muncul
   - Cek apakah kolom "No. Akun" muncul
   - Cek apakah data terfilter berdasarkan tanggal

2. **Laba Rugi:**
   - Buka `laba-rugi-lihat.php`
   - Cek apakah filter tanggal muncul
   - Cek apakah "Beban Pajak Penghasilan (25%)" muncul
   - Verifikasi perhitungan pajak benar (laba bersih sebelum pajak × 25%)

3. **Arus Kas:**
   - Masih dalam proses pengerjaan
   - Tunggu update selanjutnya

---

## 📊 CONTOH PERHITUNGAN PAJAK

### Contoh 1: Laba Bersih
```
Pendapatan Penjualan: Rp 100.000.000
(-) Harga Pokok: Rp (60.000.000)
(=) Laba Kotor: Rp 40.000.000

(-) Biaya Operasional: Rp (15.000.000)
(=) Laba Bersih Sebelum Pajak: Rp 25.000.000

(-) Pajak Penghasilan 25%: Rp (6.250.000)
(=) Laba Bersih Setelah Pajak: Rp 18.750.000
```

### Contoh 2: Rugi
```
Pendapatan Penjualan: Rp 50.000.000
(-) Harga Pokok: Rp (60.000.000)
(=) Laba Kotor: Rp (10.000.000)  ← RUGI

(-) Biaya Operasional: Rp (5.000.000)
(=) Laba Bersih Sebelum Pajak: Rp (15.000.000)

(-) Pajak Penghasilan 25%: Rp 0  ← Tidak ada pajak jika rugi
(=) Laba Bersih Setelah Pajak: Rp (15.000.000)
```

---

## 🔧 FITUR BARU YANG DITAMBAHKAN

### 1. Filter Periode Tanggal
- Semua laporan memiliki filter tanggal awal & akhir
- Default: Awal bulan sampai akhir bulan ini
- Bisa diubah sesuai kebutuhan

### 2. Dynamic User ID
- Menggunakan `$_SESSION['id_admin']` instead of hardcoded
- Setiap user hanya melihat data mereka sendiri
- Multi-user ready

### 3. Format Number Indonesia
- Menggunakan format Indonesia: 1.000.000 (titik sebagai pemisah ribuan)
- Konsisten di semua laporan

### 4. Chart of Accounts (COA)
- Nomor akun ditampilkan di Neraca Saldo
- Format: [Kategori]-[Nomor]
  - 1-XXX: Asset
  - 2-XXX: Kewajiban
  - 3-XXX: Ekuitas
  - 4-XXX: Pendapatan
  - 5-XXX: Beban

---

## ⚠️ CATATAN PENTING

### Security Warning:
⚠️ **SISTEM MASIH VULNERABLE TERHADAP SQL INJECTION!**

Masalah yang perlu diperbaiki:
1. Query masih menggunakan variabel langsung tanpa prepared statements
2. Seharusnya menggunakan PDO/MySQLi prepared statements

Contoh yang VULNERABLE (saat ini):
```php
$sql = "SELECT * FROM neraca_saldo WHERE status = 1
        AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
```

Contoh yang AMAN (seharusnya):
```php
$stmt = $koneksi->prepare("SELECT * FROM neraca_saldo WHERE status = 1
                          AND tanggal BETWEEN ? AND ?");
$stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt->execute();
```

**Rekomendasi:** Segera perbaiki dengan prepared statements setelah update ini selesai.

---

## 📝 TO-DO LIST

- [x] Buat file SQL update database
- [x] Perbaiki Neraca Saldo - View & Export
- [x] Perbaiki Laba Rugi - View dengan pajak
- [ ] Perbaiki Laba Rugi - Export PDF dengan pajak
- [ ] Perbaiki Arus Kas - Tambah kategori Investasi
- [ ] Perbaiki Arus Kas - Tambah kas awal & perhitungan
- [ ] Perbaiki Arus Kas - Export PDF
- [ ] Implementasi prepared statements (security)

---

## 🆘 TROUBLESHOOTING

### Error: "Unknown column 'nomor_akun'"
**Solusi:** Jalankan file `database/update_database.sql` terlebih dahulu

### Error: "Undefined index: id_admin"
**Solusi:** Pastikan user sudah login dan session aktif. Cek file `cek-sesi.php`

### Pajak tidak muncul
**Solusi:**
1. Pastikan ada data di tabel `laba_rugi`
2. Pastikan data memiliki status 1, 2, atau 3
3. Refresh halaman

### Filter tanggal tidak berfungsi
**Solusi:**
1. Pastikan format tanggal YYYY-MM-DD
2. Cek apakah data ada dalam range tanggal yang dipilih
3. Clear browser cache

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:
1. Cek file log error di: `C:\xampp\apache\logs\error.log`
2. Enable PHP error display di file php.ini
3. Cek dokumentasi PHP: https://www.php.net/docs.php

---

**Dibuat oleh:** Claude Code Assistant
**Tanggal:** 19 Januari 2026
**Versi:** 1.0
