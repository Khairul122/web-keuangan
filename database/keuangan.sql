-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 04:50 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `pass`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `arus_kas`
--

CREATE TABLE `arus_kas` (
  `id_arus_kas` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `sumber` varchar(150) DEFAULT NULL,
  `jumlah` int(30) DEFAULT NULL,
  `kas_awal` bigint(20) DEFAULT 0,
  `status` int(5) DEFAULT NULL,
  `id_user` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `arus_kas`
--

INSERT INTO `arus_kas` (`id_arus_kas`, `tanggal`, `sumber`, `jumlah`, `kas_awal`, `status`, `id_user`) VALUES
(16, '2026-01-28', 'Pendapatan 28 Januari 2026', 1000000, 0, 2, 1),
(17, '2026-01-28', 'Pembelian Rak Baru', 100000, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id_akun` int(11) NOT NULL,
  `nomor_akun` varchar(20) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `jenis_akun` enum('Asset','Kewajiban','Ekuitas','Pendapatan','Beban') NOT NULL,
  `saldo_normal` enum('Debit','Kredit') NOT NULL,
  `kategori_arus_kas` varchar(20) DEFAULT NULL COMMENT 'Operasional/Investasi/Pendanaan',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id_akun`, `nomor_akun`, `nama_akun`, `jenis_akun`, `saldo_normal`, `kategori_arus_kas`, `is_active`, `created_at`) VALUES
(34, '1-1001', 'Kas', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(35, '1-1002', 'Bank BCA', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(36, '1-1003', 'Bank Mandiri', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(37, '1-1004', 'Piutang Usaha', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(38, '1-1005', 'Persediaan Pupuk & Pupuk Organik', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(39, '1-1006', 'Persediaan Benih & Bibit', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(40, '1-1007', 'Persediaan Alat Pertanian', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(41, '1-1008', 'Persediaan Sarana Produksi Pertanian', 'Asset', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(42, '1-1009', 'Peralatan Pertanian', 'Asset', 'Debit', 'Investasi', 1, '2026-02-04 05:23:44'),
(43, '1-1010', 'Kendaraan Operasional', 'Asset', 'Debit', 'Investasi', 1, '2026-02-04 05:23:44'),
(44, '1-1011', 'Tanah', 'Asset', 'Debit', 'Investasi', 1, '2026-02-04 05:23:44'),
(45, '1-1012', 'Bangun Gudang', 'Asset', 'Debit', 'Investasi', 1, '2026-02-04 05:23:44'),
(46, '1-1013', 'Akumulasi Penyusutan Peralatan', 'Asset', 'Kredit', NULL, 1, '2026-02-04 05:23:44'),
(47, '1-1014', 'Akumulasi Penyusutan Kendaraan', 'Asset', 'Kredit', NULL, 1, '2026-02-04 05:23:44'),
(48, '1-1015', 'Akumulasi Penyusutan Bangunan', 'Asset', 'Kredit', NULL, 1, '2026-02-04 05:23:44'),
(49, '2-1001', 'Utang Usaha', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(50, '2-1002', 'Utang Gaji Karyawan', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(51, '2-1003', 'Utang Listrik & Air', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(52, '2-1004', 'Utang Pajak', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(53, '3-1001', 'Modal Pemilik', 'Ekuitas', 'Kredit', 'Pendanaan', 1, '2026-02-04 05:23:44'),
(54, '3-1002', 'Laba Ditahan', 'Ekuitas', 'Kredit', 'Pendanaan', 1, '2026-02-04 05:23:44'),
(55, '3-1003', 'Laba Berjalan', 'Ekuitas', 'Kredit', 'Pendanaan', 1, '2026-02-04 05:23:44'),
(56, '4-1001', 'Pendapatan Penjualan Pupuk', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(57, '4-1002', 'Pendapatan Penjualan Benih & Bibit', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(58, '4-1003', 'Pendapatan Penjualan Alat Pertanian', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(59, '4-1004', 'Pendapatan Jasa Konsultasi Pertanian', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(60, '4-1005', 'Pendapatan Jasa Pengolahan Lahan', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(61, '4-1006', 'Pendapatan Sewa Alat Pertanian', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(62, '4-1007', 'Pendapatan Lain-lain', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-04 05:23:44'),
(63, '5-1001', 'Harga Pokok Penjualan Pupuk', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(64, '5-1002', 'Harga Pokok Penjualan Benih', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(65, '5-1003', 'Harga Pokok Penjualan Alat Pertanian', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(66, '5-1004', 'Beban Gaji Karyawan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(67, '5-1005', 'Beban Listrik & Air', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(68, '5-1006', 'Beban Sewa Tempat Usaha', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(69, '5-1007', 'Beban Penyusutan Peralatan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(70, '5-1008', 'Beban Penyusutan Kendaraan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(71, '5-1009', 'Beban Penyusutan Bangunan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(72, '5-1010', 'Beban Bensin & Transportasi', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(73, '5-1011', 'Beban ATK & Operasional Kantor', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(74, '5-1012', 'Beban Maintenance Alat Pertanian', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(75, '5-1013', 'Beban Pajak Penghasilan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(76, '5-1014', 'Beban Promosi & Marketing', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44'),
(77, '5-1015', 'Beban Lain-lain', 'Beban', 'Debit', 'Operasional', 1, '2026-02-04 05:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id_jurnal` int(11) NOT NULL,
  `nomor_jurnal` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_ref_transaksi` int(11) DEFAULT NULL,
  `tipe_ref_transaksi` enum('pemasukan','pengeluaran','manual') DEFAULT 'manual',
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `id_line` int(11) NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `debit` decimal(20,2) DEFAULT 0.00,
  `kredit` decimal(20,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `posisi` varchar(40) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `umur` int(11) NOT NULL,
  `kontak` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `laba_rugi`
--

CREATE TABLE `laba_rugi` (
  `id_laba_rugi` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `sumber` varchar(100) DEFAULT NULL,
  `jumlah` int(30) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laba_rugi`
--

INSERT INTO `laba_rugi` (`id_laba_rugi`, `tanggal`, `sumber`, `jumlah`, `status`, `id_user`) VALUES
(16, '2026-01-28', 'Pembayaran Denda', 200000, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `neraca_saldo`
--

CREATE TABLE `neraca_saldo` (
  `id_neraca_saldo` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_akun` varchar(100) DEFAULT NULL,
  `nomor_akun` varchar(20) DEFAULT NULL,
  `saldo_awal_debit` int(30) DEFAULT NULL,
  `saldo_awal_kredit` int(30) DEFAULT NULL,
  `pergerakan_debit` int(30) DEFAULT NULL,
  `pergerakan_kredit` int(30) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `neraca_saldo`
--

INSERT INTO `neraca_saldo` (`id_neraca_saldo`, `tanggal`, `nama_akun`, `nomor_akun`, `saldo_awal_debit`, `saldo_awal_kredit`, `pergerakan_debit`, `pergerakan_kredit`, `status`, `id_user`) VALUES
(16, '2026-01-28', 'Pembayaran Hutang Karyawan', NULL, 200000, 100000, 50000, 10000, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id_pemasukan` int(11) NOT NULL,
  `tgl_pemasukan` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `sumber` text NOT NULL,
  `id_akun_pendapatan` int(11) DEFAULT NULL COMMENT 'Akun Pendapatan (Kredit)',
  `id_akun_kas` int(11) DEFAULT NULL COMMENT 'Akun Kas/Bank (Debit)',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `tgl_pemasukan`, `jumlah`, `sumber`, `id_akun_pendapatan`, `id_akun_kas`, `status`) VALUES
(13, '2026-01-28', 100000, 'Penjualan Padi', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `tgl_pengeluaran` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `sumber` text NOT NULL,
  `id_akun_beban` int(11) DEFAULT NULL COMMENT 'Akun Beban (Debit)',
  `id_akun_kas` int(11) DEFAULT NULL COMMENT 'Akun Kas/Bank (Kredit)',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tgl_pengeluaran`, `jumlah`, `sumber`, `id_akun_beban`, `id_akun_kas`, `status`) VALUES
(16, '2026-01-28', 50000, 'Gaji Pak Ahmad (Supir)', NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `arus_kas`
--
ALTER TABLE `arus_kas`
  ADD PRIMARY KEY (`id_arus_kas`),
  ADD KEY `idx_tanggal_user` (`tanggal`,`id_user`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `nomor_akun` (`nomor_akun`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id_jurnal`),
  ADD UNIQUE KEY `nomor_jurnal` (`nomor_jurnal`),
  ADD KEY `tanggal` (`tanggal`),
  ADD KEY `id_ref_transaksi` (`id_ref_transaksi`,`tipe_ref_transaksi`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`id_line`),
  ADD KEY `id_jurnal` (`id_jurnal`),
  ADD KEY `id_akun` (`id_akun`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  ADD PRIMARY KEY (`id_laba_rugi`),
  ADD KEY `idx_tanggal_user` (`tanggal`,`id_user`);

--
-- Indexes for table `neraca_saldo`
--
ALTER TABLE `neraca_saldo`
  ADD PRIMARY KEY (`id_neraca_saldo`),
  ADD KEY `idx_tanggal_user` (`tanggal`,`id_user`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id_pemasukan`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `arus_kas`
--
ALTER TABLE `arus_kas`
  MODIFY `id_arus_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id_line` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  MODIFY `id_laba_rugi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `neraca_saldo`
--
ALTER TABLE `neraca_saldo`
  MODIFY `id_neraca_saldo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `fk_journal_lines_akun` FOREIGN KEY (`id_akun`) REFERENCES `chart_of_accounts` (`id_akun`),
  ADD CONSTRAINT `fk_journal_lines_jurnal` FOREIGN KEY (`id_jurnal`) REFERENCES `journal_entries` (`id_jurnal`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
