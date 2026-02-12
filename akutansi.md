# Panduan Perhitungan Laporan Keuangan

Dokumen ini menjelaskan cara mendapatkan nilai untuk **Neraca Saldo**, **Laba Rugi**, dan **Arus Kas** dalam sistem akuntansi CV Bina Padi Sabatang.

---

## 1. Neraca Saldo (Trial Balance)

### Definisi
Neraca Saldo adalah laporan yang menunjukkan saldo akhir dari semua akun Buku Besar pada suatu periode tertentu. Berfungsi untuk memastikan bahwa total debet sama dengan total kredit.

### Sumber Data
- **Tabel Transaksi:** `pemasukan`, `pengeluaran`, `jurnal_umum`, `arus_kas`
- **Tabel Master:** `coa` (Chart of Accounts)

### Cara Perhitungan

#### Langkah 1: Ambil Saldo Setiap Akun
```sql
-- Ambil semua akun dengan saldo debet/kredit
SELECT 
    coa.kode_akun,
    coa.nama_akun,
    coa.jenis,
    IFNULL(SUM(CASE WHEN jt.posisi = 'debet' THEN jt.nilai ELSE 0 END), 0) AS total_debet,
    IFNULL(SUM(CASE WHEN jt.posisi = 'kredit' THEN jt.nilai ELSE 0 END), 0) AS total_kredit
FROM coa
LEFT JOIN jurnal_umum ju ON coa.kode_akun = ju.kode_akun
LEFT JOIN jurnal_transaksi jt ON ju.id = jt.id_jurnal
WHERE coa.status = 'aktif'
GROUP BY coa.kode_akun
ORDER BY coa.kode_akun;
```

#### Langkah 2: Hitung Saldo Akhir
```
Saldo Akhir = Total Debet - Total Kredit

- Jika jenis akun = Asumsi/Kewajiban/Modal: Saldo = Kredit - Debet
- Jika jenis akun = Pendapatan/Biaya: Saldo = Debet - Kredit
```

#### Langkah 3: Kategorikan Akun
| Kode Awalan | Jenis Akun | Kategori di Neraca |
|-------------|------------|---------------------|
| 1xxx | Aset (Kas, Piutang, Persediaan) | Aktiva |
| 2xxx | Kewajiban (Hutang) | Pasiva |
| 3xxx | Modal | Ekuitas |
| 4xxx | Pendapatan | Laba Rugi |
| 5xxx | Biaya/Beban | Laba Rugi |

#### Contoh Perhitungan
```
Kas (1101):
- Total Debet: Rp 50.000.000
- Total Kredit: Rp 30.000.000
- Saldo Akhir: Rp 20.000.000 (Debet)

Pendapatan Jasa (4101):
- Total Debet: Rp 5.000.000
- Total Kredit: Rp 75.000.000
- Saldo Akhir: Rp 70.000.000 (Kredit)
```

### Validasi
```
TOTAL DEBET (Semua Akun) = TOTAL KREDIT (Semua Akun)
                           ✓ Harus Sama
```

---

## 2. Laba Rugi (Income Statement)

### Definisi
Laporan Laba Rugi menunjukkan pendapatan dan beban untuk mengetahui apakah perusahaan menghasilkan laba atau rugi pada periode tertentu.

### Rumus Dasar
```
LABA/RUGI BERSIH = PENDAPATAN TOTAL - BEBAN TOTAL

Jika Pendapatan > Beban = LABA
Jika Pendapatan < Beban = RUGI
```

### Sumber Data
- **Pendapatan:** Tabel `pemasukan`, akun COA 4xxx (Pendapatan)
- **Beban:** Tabel `pengeluaran`, akun COA 5xxx (Biaya/Beban)
- **Beban Pokok Penjualan:** Dari `jurnal_umum` akun 51xx

### Cara Perhitungan

#### A. Hitung Pendapatan
```sql
-- Pendapatan dari Transaksi
SELECT 
    SUM(CASE WHEN kode_akun LIKE '41%' THEN saldo ELSE 0 END) AS pendapatan_usaha,
    SUM(CASE WHEN kode_akun LIKE '42%' THEN saldo ELSE 0 END) AS pendapatan_non_usaha,
    SUM(CASE WHEN kode_akun LIKE '4%' THEN saldo ELSE 0 END) AS total_pendapatan
FROM coa
WHERE status = 'aktif';
```

#### B. Hitung Beban
```sql
-- Beban dari Transaksi
SELECT 
    SUM(CASE WHEN kode_akun LIKE '51%' THEN saldo ELSE 0 END) AS beban_pokok,
    SUM(CASE WHEN kode_akun LIKE '52%' THEN saldo ELSE 0 END) AS beban_operasional,
    SUM(CASE WHEN kode_akun LIKE '53%' THEN saldo ELSE 0 END) AS beban_non_operasional,
    SUM(CASE WHEN kode_akun LIKE '5%' THEN saldo ELSE 0 END) AS total_beban
FROM coa
WHERE status = 'aktif';
```

#### C. Struktur Laba Rugi
```
CV BINA PADI SABATANG
LAPORAN LABA RUGI
Periode: [Tanggal Awal] s/d [Tanggal Akhir]

===================================================
PENDAPATAN
  Pendapatan Usaha (Jasa/Penjualan)         Rp XXX
  Pendapatan Lainnya                        Rp XXX
  ---------------------------------------- ----------
  TOTAL PENDAPATAN                          Rp XXX

BEBAN
  Beban Pokok Penjualan/Jasa                Rp XXX
  Beban Operasional:
    - Beban Gaji                            Rp XXX
    - Beban Sewa                            Rp XXX
    - Beban Listrik/Air                     Rp XXX
    - Beban Umum & Administrasi             Rp XXX
  ---------------------------------------- ----------
  TOTAL BEBAN                               Rp XXX

LABA/RUGI BERSIH (Pendapatan - Beban)       Rp XXX
===================================================

Jika Positif = LABA
Jika Negatif = RUGI
```

### Contoh Perhitungan
```
PENDAPATAN:
- Pendapatan Jasa:     Rp 100.000.000
- Pendapatan Lain:     Rp   5.000.000
                      ----------------
TOTAL PENDAPATAN:     Rp 105.000.000

BEBAN:
- Beban Gaji:         Rp  40.000.000
- Beban Sewa:         Rp  12.000.000
- Beban Listrik:     Rp   5.000.000
- Beban Lainnya:     Rp   8.000.000
                      ----------------
TOTAL BEBAN:         Rp  65.000.000

LABA BERSIH:         Rp  40.000.000
```

### Perhitungan Laba Ditahan
```
LABA DITAHAN = Laba Bersih Periode Ini + Laba Ditahan Periode Lalu
```

---

## 3. Arus Kas (Cash Flow)

### Definisi
Laporan Arus Kas mencatat seluruh流入 (masuk) dan 流出品 (keluar) kas selama periode tertentu, dikategorikan menjadi tiga aktivitas.

### Struktur Arus Kas
```
ARUS KAS DARI AKTIVITAS OPERASI
  Kas Masuk dari Pelanggan              Rp XXX
  Kas Keluar untuk Supplier/Karyawan     (Rp XXX)
  Arus Kas Bersih Operasi               Rp XXX

ARUS KAS DARI AKTIVITAS INVESTASI
  Pembelian Aset                        (Rp XXX)
  Penjualan Aset                        Rp XXX
  Arus Kas Bersih Investasi            Rp XXX

ARUS KAS DARI AKTIVITAS PENDANAAN
  Pinjaman Diterima                     Rp XXX
  Pembayaran Cicilan                    (Rp XXX)
  Penarikan Modal                       (Rp XXX)
  Arus Kas Bersih Pendanaan            Rp XXX

SALDO KAS AWAL                          Rp XXX
  (+) Arus Kas Bersih Total             Rp XXX
SALDO KAS AKHIR                         Rp XXX
```

### Sumber Data
- **Aktivitas Operasi:** `pemasukan`, `pengeluaran`, `jurnal_umum`
- **Aktivitas Investasi:** Akun 1xxx (kecuali Kas/Bank)
- **Aktivitas Pendanaan:** Akun 2xxx, 3xxx

### Cara Perhitungan

#### A. Arus Kas Operasi (Indirect Method)
```sql
-- Dari Laba Bersih
SELECT 
    (SELECT SUM(jumlah) FROM pemasukan WHERE MONTH(tgl_pemasukan) = $bulan) 
    - 
    (SELECT SUM(jumlah) FROM pengeluaran WHERE MONTH(tgl_pengeluaran) = $bulan) 
    AS arus_kas_operasi;
```

#### B. Arus Kas dari Perubahan Kas/Bank
```sql
-- Perubahan Kas
SELECT 
    SUM(CASE WHEN kode_akun = '1101' AND posisi = 'debet' THEN nilai ELSE 0 END) 
    - 
    SUM(CASE WHEN kode_akun = '1101' AND posisi = 'kredit' THEN nilai ELSE 0 END) 
    AS saldo_kas
FROM jurnal_transaksi;
```

#### C. Kategori per Jenis Transaksi
```sql
-- Klasifikasikan arus kas
SELECT 
    coa.kode_akun,
    coa.nama_akun,
    coa.jenis,
    SUM(IF(jt.posisi = 'debet', jt.nilai, -jt.nilai)) AS arus_kas
FROM coa
JOIN jurnal_umum ju ON coa.kode_akun = ju.kode_akun
JOIN jurnal_transaksi jt ON ju.id = jt.id_jurnal
WHERE coa.kode_akun IN ('1101', '1102') -- Kas & Bank
GROUP BY coa.kode_akun;
```

### Contoh Perhitungan
```
ARUS KAS OPERASI (Tahun 2025):
  Kas dari Pelanggan:        Rp 150.000.000
  Kas untuk Supplier:        (Rp  45.000.000)
  Kas untuk Gaji:            (Rp  40.000.000)
  Kas untuk Operasional:     (Rp  15.000.000)
  -----------------------------------------
  Arus Kas Operasi:          Rp  50.000.000

ARUS KAS INVESTASI:
  Pembelian Kendaraan:       (Rp 100.000.000)
  Penjualan Laptop Lama:     Rp   5.000.000
  -----------------------------------------
  Arus Kas Investasi:        (Rp  95.000.000)

ARUS KAS PENDANAAN:
  Pinjaman Bank:             Rp 100.000.000
  Cicilan Bank:              (Rp  20.000.000)
  -----------------------------------------
  Arus Kas Pendanaan:        Rp  80.000.000

ARUS KAS BERSIH:             Rp  35.000.000
SALDO KAS AWAL:              Rp  25.000.000
SALDO KAS AKHIR:             Rp  60.000.000
```

### Rumus Cepat Arus Kas
```
Arus Kas Bersih = Arus Kas Operasi + Arus Kas Investasi + Arus Kas Pendanaan

Saldo Kas Akhir = Saldo Kas Awal + Arus Kas Bersih
```

---

## 4. Hubungan Antar Laporan

### Diagram Alur Data
```
JURNAL UMUM
     │
     ▼
NERACA SALDO
     │
     ├──▶ LABA RUGI (Akun 4xxx & 5xxx)
     │         │
     │         ▼
     │    LABA/RUGI BERSIH
     │
     ▼
LABA DITAHAN (Modal)
     │
     ▼
NERACA (Akun 1xxx, 2xxx, 3xxx)
     │
     ▼
ARUS KAS (Perubahan Kas)
```

### Konsistensi Data
```
1. LABA RUGI BERSIH harus = Perubahan Laba Ditahan
2. SALDO KAS di NERACA = SALDO KAS di ARUS KAS
3. TOTAL NERACA (Aktiva) = TOTAL NERACA (Pasiva + Ekuitas)
4. Debet di NERACA SALDO = Kredit di NERACA SALDO
```

---

## 5. Implementasi di Database

### Tabel Utama yang Digunakan

#### coa (Chart of Accounts)
| Field | Tipe | Deskripsi |
|-------|------|-----------|
| kode_akun | VARCHAR(10) | Kode akun unik |
| nama_akun | VARCHAR(100) | Nama akun |
| jenis | ENUM('debet','kredit') | Tipe akun |
| status | ENUM('aktif','nonaktif') | Status |

#### pemasukan (Pendapatan)
| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | INT | ID transaksi |
| tgl_pemasukan | DATE | Tanggal |
| jumlah | DECIMAL(15,2) | Nilai |
| kode_akun | VARCHAR(10) | Akun COA |
| keterangan | TEXT | Deskripsi |

#### pengeluaran (Beban)
| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | INT | ID transaksi |
| tgl_pengeluaran | DATE | Tanggal |
| jumlah | DECIMAL(15,2) | Nilai |
| kode_akun | VARCHAR(10) | Akun COA |
| keterangan | TEXT | Deskripsi |

#### jurnal_umum & jurnal_transaksi
| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | INT | ID jurnal |
| no_bukti | VARCHAR(50) | Nomor bukti |
| kode_akun | VARCHAR(10) | Akun COA |
| tanggal | DATE | Tanggal |
| posisi | ENUM('debet','kredit') | Posisi |
| nilai | DECIMAL(15,2) | Nilai transaksi |

---

## 6. Tips Penggunaan di Sistem

### Query Cepat
```php
// Neraca Saldo
$query = "SELECT * FROM v_neraca_saldo ORDER BY kode_akun";

// Laba Rugi
$query = "SELECT * FROM v_laba_rugi WHERE periode = '$periode'";

// Arus Kas
$query = "SELECT * FROM v_arus_kas WHERE YEAR(tanggal) = '$tahun'";
```

### Validasi Data
1. **Cek Neraca Saldo:** Total Debet = Total Kredit
2. **Cek Laba Rugi:** Pendapatan > Beban = Laba
3. **Cek Arus Kas:** Kas Akhir = Kas Awal + Arus Kas Bersih

### Error Handling
- Jika neraca tidak seimbang: Cek jurnal
- Jika arus kas negatif: Cek流入/流出口
- Jika laba negatif: Review beban

---

## 7. Referensi

- **Standar Akuntansi Keuangan (SAK)**
- **PSAK No. 1:** Penyajian Laporan Keuangan
- **PSAK No. 2:** Laporan Arus Kas
- **PSAK No. 3:** Laporan Keuangan Interim

---

*Document dibuat untuk CV Bina Padi Sabatang*
*Versi 1.0 - Februari 2026*
