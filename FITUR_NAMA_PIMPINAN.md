# FITUR NAMA PIMPINAN - SESSION BASED
## CV BINA PADI SABATANG

Tanggal: 19 Januari 2026

---

## 📋 DESKRIPSI FITUR

Fitur ini memungkinkan user untuk memasukkan nama pimpinan (direktur/manajer) yang akan ditampilkan di bagian tanda tangan pada semua laporan PDF.

**Keunggulan:**
- ✅ Disimpan dalam **SESSION** (bukan database)
- ✅ Tidak perlu mengubah struktur database
- ✅ Lebih sederhana dan cepat
- ✅ Nama pimpinan ditampilkan di semua 5 laporan PDF

---

## 🎯 CARA KERJA

### **1. Form Input di Laporan.php**

Buka halaman `laporan.php`, user akan melihat form di bagian atas:

```
┌─────────────────────────────────────────┐
│ Pengaturan Nama Pimpinan                │
├─────────────────────────────────────────┤
│ Nama Pimpinan:                          │
│ [____________________________]          │
│ Masukkan nama pimpinan (misal: Budi...  │
│                                         │
│ [Simpan Nama Pimpinan]                  │
└─────────────────────────────────────────┘
```

### **2. Simpan ke Session**

Ketika tombol "Simpan Nama Pimpinan" diklik:
- Form akan submit ke `save-pimpinan.php`
- Nama pimpinan disimpan ke `$_SESSION['pimpinan']`
- User di-redirect kembali ke `laporan.php`
- Muncul pesan sukses: "Nama pimpinan berhasil disimpan!"

### **3. Ditampilkan di Semua Laporan PDF**

Nama pimpinan yang disimpan di session akan otomatis ditampilkan di:

1. **Laporan Neraca Saldo** (export-neraca-saldo.php:305,310)
2. **Laporan Laba Rugi** (export-laba-rugi.php:214,219)
3. **Laporan Arus Kas** (export-arus-kas.php:140,145)
4. **Laporan Pemasukan** (export-pemasukan.php:108,113)
5. **Laporan Pengeluaran** (export-pengeluaran.php:108,113)

Format tanda tangan di PDF:

```
                    Padang, 19 Januari 2026
                    [Nama Pimpinan]



                    ([Nama Pimpinan])
```

---

## 📁 FILE YANG DIPERBARUI

### **1. laporan.php** (Form Input)
```php
<!-- Form Input Nama Pimpinan -->
<form action="save-pimpinan.php" method="POST">
    <input type="text" name="pimpinan"
           value="<?php echo isset($_SESSION['pimpinan'])
                      ? htmlspecialchars($_SESSION['pimpinan'])
                      : 'Pimpinan'; ?>">
    <button type="submit">Simpan Nama Pimpinan</button>
</form>
```

**Lokasi:** `C:\xampp\htdocs\akutansi\laporan.php` (lines 48-71)

### **2. save-pimpinan.php** (Simpan ke Session)
```php
session_start();
require 'cek-sesi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pimpinan = isset($_POST['pimpinan']) ? trim($_POST['pimpinan']) : '';

    if (empty($pimpinan)) {
        $pimpinan = 'Pimpinan';
    }

    // Simpan ke session (bukan database!)
    $_SESSION['pimpinan'] = $pimpinan;

    $_SESSION['success_message'] = "Nama pimpinan berhasil disimpan!";
}

header("Location: laporan.php");
exit;
```

**Lokasi:** `C:\xampp\htdocs\akutansi\save-pimpinan.php`

### **3. Export PDF Files** (Ambil dari Session)

Semua file export mengambil nama pimpinan dari session:

```php
// Start session
session_start();

// Get pimpinan name from session
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';
```

**Files:**
- `export-neraca-saldo.php` (line 18)
- `export-laba-rugi.php` (line 18)
- `export-arus-kas.php` (line 12)
- `export-pemasukan.php` (line 12)
- `export-pengeluaran.php` (line 12)

**Display di PDF:**
```html
<div style="float: right;">
    Padang, <?php echo date('j F Y'); ?><br>
    <?php echo htmlspecialchars($nama_pimpinan); ?>
    <br><br><br><br>
    (<?php echo htmlspecialchars($nama_pimpinan); ?>)
</div>
```

---

## 🔧 TEKNIS IMPLEMENTASI

### **Kenapa Session, Bukan Database?**

| Pendekatan | Kelebihan | Kekurangan |
|------------|-----------|------------|
| **SESSION** | ✅ Tidak perlu ubah database<br>✅ Lebih simpel<br>✅ Cepat diimplementasi | ❌ Hilang jika session destroy<br>❌ Tidak persisten |
| **DATABASE** | ✅ Persisten<br>✅ Tidak hilang | ❌ Perlu ALTER TABLE<br>❌ Perlu query tambahan<br>❌ Lebih kompleks |

**Pilihan saat ini:** SESSION (sesuai permintaan user)

---

## 💡 CONTOH PENGGUNAAN

### **Skenario 1: Input Nama Pimpinan Baru**

1. User login ke sistem
2. Buka menu **Laporan**
3. Di form "Pengaturan Nama Pimpinan", isi: **"Ahmad Zulkarnain"**
4. Klik tombol **"Simpan Nama Pimpinan"**
5. Muncul pesan: ✅ "Nama pimpinan berhasil disimpan!"
6. Coba download salah satu laporan (misal: Neraca Saldo)
7. Di PDF akan muncul:

```
                    Padang, 19 Januari 2026
                    Ahmad Zulkarnain



                    (Ahmad Zulkarnain)
```

### **Skenario 2: Mengubah Nama Pimpinan**

1. User buka menu **Laporan**
2. Di form input, nama lama sudah terisi otomatis (dari session)
3. User edit nama: **"Ahmad Zulkarnain, SE"**
4. Klik **"Simpan Nama Pimpinan"**
5. Session diperbarui
6. Semua laporan PDF yang didownload setelah ini akan menggunakan nama baru

### **Skenario 3: Default Value**

Jika session belum diset atau kosong:
- Input form akan menampilkan: **"Pimpinan"** (default)
- Di PDF juga akan muncul: **"Pimpinan"**

---

## ⚠️ CATATAN PENTING

### **1. Session Lifetime**

- Nama pimpinan disimpan di `$_SESSION['pimpinan']`
- Session akan hilang jika:
  - User logout
  - Browser ditutup (tergantung config PHP)
  - Session timeout (default 24 menit di XAMPP)

**Solusi:** User perlu memasukkan ulang nama pimpinan setelah login kembali.

### **2. Security**

- Menggunakan `htmlspecialchars()` untuk mencegah XSS
- Input di-trim untuk menghapus spasi berlebih
- Validasi input tidak kosong

### **3. Default Value**

```php
$nama_pimpinan = isset($_SESSION['pimpinan']) ? $_SESSION['pimpinan'] : 'Pimpinan';
```

Jika session belum diset, default value adalah **"Pimpinan"**.

---

## 🐛 TROUBLESHOOTING

### **Masalah 1: Nama pimpinan tidak muncul di PDF**

**Penyebab:** Session belum diset atau session expired

**Solusi:**
1. Buka `laporan.php`
2. Isi form nama pimpinan
3. Klik "Simpan Nama Pimpinan"
4. Coba download PDF lagi

### **Masalah 2: Setelah login, nama pimpinan hilang**

**Penyebab:** Session destroyed saat logout

**Solusi:**
- Ini adalah perilaku normal (session-based)
- User perlu memasukkan ulang nama pimpinan setelah login

### **Masalah 3: Form menampilkan nilai kosong**

**Penyebab:** Session tidak ter-set dengan benar

**Solusi:**
1. Cek apakah `session_start()` dipanggil di laporan.php
2. Cek apakah ada error di save-pimpinan.php
3. Debug dengan: `var_dump($_SESSION);`

---

## 📊 ALUR KERJA LENGKAP

```
USER
  │
  ├─→ Buka laporan.php
  │   └─→ Cek $_SESSION['pimpinan']
  │       └─→ Jika ada: tampilkan di form
  │       └─→ Jika tidak: tampilkan "Pimpinan"
  │
  ├─→ Input nama: "Budi Santoso"
  │
  ├─→ Klik tombol "Simpan"
  │   │
  │   ▼
  │ FORM SUBMIT → save-pimpinan.php
  │   ├─→ Terima POST data
  │   ├─→ Validasi input
  │   ├─→ Simpan ke $_SESSION['pimpinan']
  │   ├─→ Set success message
  │   └─→ Redirect ke laporan.php
  │
  ├─→ Klik tombol download (misal: Neraca Saldo)
  │   │
  │   ▼
  │ export-neraca-saldo.php
  │   ├─→ Start session
  │   ├─→ Ambil $_SESSION['pimpinan']
  │   ├─→ Generate PDF
  │   └─→ Tampilkan di browser (preview mode)
  │
  └─→ Lihat PDF
      └─→ Di bagian tanda tangan muncul:
          "Budi Santoso" (bukan "Pimpinan")
```

---

## 🎯 TESTING

1. **Test Input:**
   - Buka `http://localhost/akutansi/laporan.php`
   - Masukkan nama pimpinan: "Test User"
   - Klik simpan
   - Verifikasi muncul pesan sukses

2. **Test PDF Output:**
   - Download semua 5 laporan
   - Cek bagian tanda tangan di setiap PDF
   - Pastikan nama "Test User" muncul di semua laporan

3. **Test Session Persist:**
   - Refresh halaman laporan.php
   - Pastikan form masih menampilkan "Test User"
   - Buka menu lain, lalu kembali ke laporan.php
   - Pastikan nama masih ada

4. **Test Default Value:**
   - Logout
   - Login kembali
   - Buka laporan.php
   - Pastikan form menampilkan "Pimpinan" (default)

---

## 📝 SUMMARY

✅ **Fitur:** Input nama pimpinan untuk laporan PDF
✅ **Storage:** SESSION (bukan database)
✅ **Files Diupdate:** 7 file (1 form, 1 save script, 5 export PDF)
✅ **Default Value:** "Pimpinan"
✅ **Security:** Menggunakan htmlspecialchars()
✅ **User Experience:** Form input di halaman laporan.php

---

**Dibuat oleh:** Claude Code Assistant
**Tanggal:** 19 Januari 2026
**Versi:** 1.0 - Session Based
