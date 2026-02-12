-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 09:46 AM
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
  `pass` varchar(40) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `pass`, `level`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 'admin'),
(3, 'Pemilik', 'pemilik@gmail.com', 'pemilik', 'pemilik');

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
  `id_akun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `arus_kas`
--

INSERT INTO `arus_kas` (`id_arus_kas`, `tanggal`, `sumber`, `jumlah`, `kas_awal`, `status`, `id_akun`) VALUES
(28, '2026-01-01', 'Penerimaan dari Penjualan Pupuk', 2390091, 52390091, 1, 78),
(29, '2026-01-02', 'Penerimaan dari Penjualan Benih', 2242657, 50147434, 2, 78),
(30, '2026-01-03', 'Penerimaan dari Jasa Pengolahan Lahan', 1054259, 51201693, 1, 78),
(31, '2026-01-04', 'Pembayaran Gaji Karyawan', 1972601, 49229092, 2, 78),
(32, '2026-01-05', 'Pembayaran Biaya Operasional', 1626699, 50855791, 1, 78),
(33, '2026-01-06', 'Penerimaan dari Jasa Konsultasi', 2474755, 48381036, 2, 78),
(34, '2026-01-07', 'Pembayaran Pembelian Stok Barang', 2319259, 50700295, 1, 78),
(35, '2026-01-08', 'Penerimaan dari Jasa Pengendalian Hama', 2072044, 48628251, 2, 78),
(36, '2026-01-09', 'Pembayaran Biaya Transportasi', 1806492, 50434743, 1, 78),
(37, '2026-01-10', 'Penerimaan dari Penjualan Alat', 813294, 49621449, 2, 78),
(38, '2026-01-11', 'Pembayaran Biaya Sewa Gudang', 1452083, 51073532, 1, 78),
(39, '2026-01-12', 'Penerimaan dari Jasa Penyemaian', 1107598, 49965934, 2, 78),
(40, '2026-01-13', 'Pembayaran Biaya Perawatan', 1034892, 51000826, 1, 78),
(41, '2026-01-14', 'Penerimaan dari Penjualan Perlengkapan', 1890451, 49110375, 2, 78),
(42, '2026-01-15', 'Pembayaran Biaya Promosi', 1074647, 50185022, 1, 78),
(43, '2026-01-16', 'Penerimaan dari Jasa Analisis Tanah', 2203793, 47981229, 2, 78),
(44, '2026-01-17', 'Pembayaran Biaya Pajak', 808432, 48789661, 1, 78),
(45, '2026-01-18', 'Penerimaan dari Jasa Pemeliharaan', 1772610, 47017051, 2, 78),
(46, '2026-01-19', 'Pembayaran Biaya Asuransi', 1129280, 48146331, 1, 78),
(47, '2026-01-20', 'Penerimaan dari Jasa Pengeringan', 1323711, 46822620, 2, 78);

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
(78, '1-1001', 'Kas', 'Asset', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42'),
(79, '1-1002', 'Bank BCA', 'Asset', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42'),
(80, '1-1003', 'Bank Mandiri', 'Asset', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42'),
(81, '1-1004', 'Piutang Usaha', 'Asset', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42'),
(82, '1-1005', 'Persediaan Pupuk & Pupuk Organik', 'Asset', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42'),
(83, '2-1001', 'Utang Usaha', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-05 03:53:42'),
(84, '2-1002', 'Utang Gaji Karyawan', 'Kewajiban', 'Kredit', 'Operasional', 1, '2026-02-05 03:53:42'),
(85, '3-1001', 'Modal Pemilik', 'Ekuitas', 'Kredit', 'Pendanaan', 1, '2026-02-05 03:53:42'),
(86, '4-1001', 'Pendapatan Penjualan Pupuk', 'Pendapatan', 'Kredit', 'Operasional', 1, '2026-02-05 03:53:42'),
(87, '5-1001', 'Beban Gaji Karyawan', 'Beban', 'Debit', 'Operasional', 1, '2026-02-05 03:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `hutang`
--

CREATE TABLE `hutang` (
  `id_hutang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_hutang` date NOT NULL,
  `alasan` text NOT NULL,
  `penghutang` varchar(100) NOT NULL,
  `status` int(11) DEFAULT 1,
  `id_akun_debet` int(11) DEFAULT NULL,
  `id_akun_kredit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hutang`
--

INSERT INTO `hutang` (`id_hutang`, `jumlah`, `tgl_hutang`, `alasan`, `penghutang`, `status`, `id_akun_debet`, `id_akun_kredit`) VALUES
(11, 9300167, '2026-01-01', 'Pinjaman Modal Kerja dari Bank', 'Bank Mandiri', 1, 82, 78),
(12, 7558363, '2026-01-02', 'Utang Pembelian Pupuk', 'PT. Tani Sejahtera', 1, 82, 81),
(13, 2522799, '2026-01-03', 'Utang Pembelian Benih', 'CV. Mitra Agroindo', 1, 82, 81),
(14, 8829164, '2026-01-04', 'Utang Pembelian Alat Pertanian', 'PT. Alat Tani Jaya', 1, 82, 78),
(15, 8070601, '2026-01-05', 'Utang Jasa Pengolahan Lahan', 'CV. Jasa Pengolahan Lahan', 1, 82, 81),
(16, 5434728, '2026-01-06', 'Utang Pembelian Perlengkapan', 'PT. Perlengkapan Tani', 1, 82, 81),
(17, 6301014, '2026-01-07', 'Utang Biaya Renovasi Gudang', 'Kontraktor Bangunan', 1, 82, 78),
(18, 7250798, '2026-01-08', 'Utang Pembelian Kendaraan Operasional', 'Dealer Kendaraan', 1, 82, 78),
(19, 6401093, '2026-01-09', 'Utang Pembelian Peralatan Irigasi', 'CV. Sistem Irigasi', 1, 82, 78),
(20, 8120150, '2026-01-10', 'Utang Biaya Pemasaran', 'PT. Digital Marketing', 1, 82, 78),
(21, 8489231, '2026-01-11', 'Utang Pembelian Perlengkapan Ternak', 'CV. Peternakan Sehat', 1, 82, 78),
(22, 7180979, '2026-01-12', 'Utang Biaya Konstruksi Fasilitas', 'PT. Konstruksi Hijau', 1, 82, 78),
(23, 8771666, '2026-01-13', 'Utang Pembelian Media Tanam', 'CV. Media Tanam Organik', 1, 82, 78),
(24, 9335180, '2026-01-14', 'Utang Biaya Konsultan', 'Konsultan Bisnis', 1, 82, 78),
(25, 5515706, '2026-01-15', 'Utang Pembelian Peralatan Hidroponik', 'PT. Teknologi Hidroponik', 1, 82, 78),
(26, 3901951, '2026-01-16', 'Utang Biaya Lisensi Perangkat Lunak', 'Perusahaan Software', 1, 82, 78),
(27, 9195464, '2026-01-17', 'Utang Pembelian Peralatan Laboratorium', 'CV. Laboratorium Tanah', 1, 82, 78),
(28, 6909957, '2026-01-18', 'Utang Biaya Sertifikasi Produk', 'Lembaga Sertifikasi', 1, 82, 78),
(29, 5389035, '2026-01-19', 'Utang Pembelian Sistem Pengolahan Air', 'PT. Teknik Pengolahan Air', 1, 82, 78),
(30, 9312388, '2026-01-20', 'Utang Biaya Pengembangan Website', 'Web Developer Freelance', 1, 82, 78);

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
  `tipe_ref_transaksi` enum('pemasukan','pengeluaran','manual','hutang') DEFAULT 'manual',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id_jurnal`, `nomor_jurnal`, `tanggal`, `keterangan`, `id_ref_transaksi`, `tipe_ref_transaksi`, `created_at`, `updated_at`) VALUES
(142, 'JV-20260101-0001', '2026-01-01', 'Pemasukan: Penjualan Pupuk Organik Premium', 44, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(143, 'JV-20260102-0001', '2026-01-02', 'Pemasukan: Penjualan Benih Padi Unggul', 45, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(144, 'JV-20260103-0001', '2026-01-03', 'Pemasukan: Penjualan Alat Pertanian Modern', 46, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(145, 'JV-20260104-0001', '2026-01-04', 'Pemasukan: Jasa Pengolahan Lahan', 47, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(146, 'JV-20260105-0001', '2026-01-05', 'Pemasukan: Jasa Konsultasi Pertanian', 48, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(147, 'JV-20260106-0001', '2026-01-06', 'Pemasukan: Penjualan Peralatan Irigasi', 49, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(148, 'JV-20260107-0001', '2026-01-07', 'Pemasukan: Penjualan Bibit Sayuran', 50, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(149, 'JV-20260108-0001', '2026-01-08', 'Pemasukan: Jasa Pemupukan Lahan', 51, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(150, 'JV-20260109-0001', '2026-01-09', 'Pemasukan: Penjualan Pupuk Cair Nutrisi', 52, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(151, 'JV-20260110-0001', '2026-01-10', 'Pemasukan: Jasa Pengendalian Hama', 53, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(152, 'JV-20260111-0001', '2026-01-11', 'Pemasukan: Penjualan Alat Panen Modern', 54, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(153, 'JV-20260112-0001', '2026-01-12', 'Pemasukan: Jasa Penyemaian Benih', 55, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(154, 'JV-20260113-0001', '2026-01-13', 'Pemasukan: Penjualan Perlengkapan Ternak', 56, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(155, 'JV-20260114-0001', '2026-01-14', 'Pemasukan: Jasa Pengeringan Hasil Panen', 57, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(156, 'JV-20260115-0001', '2026-01-15', 'Pemasukan: Penjualan Peralatan Pengolahan', 58, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(157, 'JV-20260116-0001', '2026-01-16', 'Pemasukan: Jasa Desinfeksi Lahan', 59, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(158, 'JV-20260117-0001', '2026-01-17', 'Pemasukan: Penjualan Media Tanam', 60, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(159, 'JV-20260118-0001', '2026-01-18', 'Pemasukan: Jasa Pemeliharaan Tanaman', 61, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(160, 'JV-20260119-0001', '2026-01-19', 'Pemasukan: Penjualan Peralatan Hidroponik', 62, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(161, 'JV-20260120-0001', '2026-01-20', 'Pemasukan: Jasa Analisis Tanah', 63, 'pemasukan', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(162, 'JV-20260101-0002', '2026-01-01', 'Pengeluaran: Gaji Karyawan Bulan Januari', 48, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(163, 'JV-20260102-0002', '2026-01-02', 'Pengeluaran: Biaya Listrik Kantor', 49, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(164, 'JV-20260103-0002', '2026-01-03', 'Pengeluaran: Biaya Air Bersih', 50, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(165, 'JV-20260104-0002', '2026-01-04', 'Pengeluaran: Pembelian Pupuk untuk Stok', 51, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(166, 'JV-20260105-0002', '2026-01-05', 'Pengeluaran: Pembelian Benih untuk Stok', 52, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(167, 'JV-20260106-0002', '2026-01-06', 'Pengeluaran: Biaya Transportasi Operasional', 53, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(168, 'JV-20260107-0002', '2026-01-07', 'Pengeluaran: Biaya Bensin Kendaraan', 54, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(169, 'JV-20260108-0002', '2026-01-08', 'Pengeluaran: Biaya Perawatan Alat', 55, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(170, 'JV-20260109-0002', '2026-01-09', 'Pengeluaran: Biaya Sewa Gudang', 56, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(171, 'JV-20260110-0002', '2026-01-10', 'Pengeluaran: Biaya Promosi dan Iklan', 57, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(172, 'JV-20260111-0002', '2026-01-11', 'Pengeluaran: Biaya ATK Kantor', 58, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(173, 'JV-20260112-0002', '2026-01-12', 'Pengeluaran: Biaya Internet dan Telepon', 59, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(174, 'JV-20260113-0002', '2026-01-13', 'Pengeluaran: Biaya Pajak Bulanan', 60, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(175, 'JV-20260114-0002', '2026-01-14', 'Pengeluaran: Biaya Asuransi Karyawan', 61, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(176, 'JV-20260115-0002', '2026-01-15', 'Pengeluaran: Biaya Pelatihan Karyawan', 62, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(177, 'JV-20260116-0002', '2026-01-16', 'Pengeluaran: Biaya Perlengkapan Kebersihan', 63, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(178, 'JV-20260117-0002', '2026-01-17', 'Pengeluaran: Biaya Perbaikan Kendaraan', 64, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(179, 'JV-20260118-0002', '2026-01-18', 'Pengeluaran: Biaya Pengiriman Barang', 65, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(180, 'JV-20260119-0002', '2026-01-19', 'Pengeluaran: Biaya Maintenance Website', 66, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51'),
(181, 'JV-20260120-0002', '2026-01-20', 'Pengeluaran: Biaya Sewa Lahan Percobaan', 67, 'pengeluaran', '2026-02-05 05:12:51', '2026-02-05 05:12:51');

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

--
-- Dumping data for table `journal_lines`
--

INSERT INTO `journal_lines` (`id_line`, `id_jurnal`, `id_akun`, `debit`, `kredit`, `created_at`) VALUES
(253, 142, 78, '3460058.00', '0.00', '2026-02-05 05:12:51'),
(254, 142, 86, '0.00', '3460058.00', '2026-02-05 05:12:51'),
(255, 143, 79, '1384820.00', '0.00', '2026-02-05 05:12:51'),
(256, 143, 86, '0.00', '1384820.00', '2026-02-05 05:12:51'),
(257, 144, 80, '1864584.00', '0.00', '2026-02-05 05:12:51'),
(258, 144, 86, '0.00', '1864584.00', '2026-02-05 05:12:51'),
(259, 145, 81, '4521454.00', '0.00', '2026-02-05 05:12:51'),
(260, 145, 86, '0.00', '4521454.00', '2026-02-05 05:12:51'),
(261, 146, 82, '1836967.00', '0.00', '2026-02-05 05:12:51'),
(262, 146, 86, '0.00', '1836967.00', '2026-02-05 05:12:51'),
(263, 147, 78, '4702739.00', '0.00', '2026-02-05 05:12:51'),
(264, 147, 86, '0.00', '4702739.00', '2026-02-05 05:12:51'),
(265, 148, 79, '3259931.00', '0.00', '2026-02-05 05:12:51'),
(266, 148, 86, '0.00', '3259931.00', '2026-02-05 05:12:51'),
(267, 149, 80, '4368726.00', '0.00', '2026-02-05 05:12:51'),
(268, 149, 86, '0.00', '4368726.00', '2026-02-05 05:12:51'),
(269, 150, 81, '2657663.00', '0.00', '2026-02-05 05:12:51'),
(270, 150, 86, '0.00', '2657663.00', '2026-02-05 05:12:51'),
(271, 151, 82, '1660203.00', '0.00', '2026-02-05 05:12:51'),
(272, 151, 86, '0.00', '1660203.00', '2026-02-05 05:12:51'),
(273, 152, 78, '1975759.00', '0.00', '2026-02-05 05:12:51'),
(274, 152, 86, '0.00', '1975759.00', '2026-02-05 05:12:51'),
(275, 153, 79, '1675362.00', '0.00', '2026-02-05 05:12:51'),
(276, 153, 86, '0.00', '1675362.00', '2026-02-05 05:12:51'),
(277, 154, 80, '4345500.00', '0.00', '2026-02-05 05:12:51'),
(278, 154, 86, '0.00', '4345500.00', '2026-02-05 05:12:51'),
(279, 155, 81, '4946059.00', '0.00', '2026-02-05 05:12:51'),
(280, 155, 86, '0.00', '4946059.00', '2026-02-05 05:12:51'),
(281, 156, 82, '3768762.00', '0.00', '2026-02-05 05:12:51'),
(282, 156, 86, '0.00', '3768762.00', '2026-02-05 05:12:51'),
(283, 157, 78, '3687617.00', '0.00', '2026-02-05 05:12:51'),
(284, 157, 86, '0.00', '3687617.00', '2026-02-05 05:12:51'),
(285, 158, 79, '4691625.00', '0.00', '2026-02-05 05:12:51'),
(286, 158, 86, '0.00', '4691625.00', '2026-02-05 05:12:51'),
(287, 159, 80, '4792209.00', '0.00', '2026-02-05 05:12:51'),
(288, 159, 86, '0.00', '4792209.00', '2026-02-05 05:12:51'),
(289, 160, 81, '3247805.00', '0.00', '2026-02-05 05:12:51'),
(290, 160, 86, '0.00', '3247805.00', '2026-02-05 05:12:51'),
(291, 161, 82, '1664683.00', '0.00', '2026-02-05 05:12:51'),
(292, 161, 86, '0.00', '1664683.00', '2026-02-05 05:12:51'),
(293, 162, 87, '1582356.00', '0.00', '2026-02-05 05:12:51'),
(294, 162, 78, '0.00', '1582356.00', '2026-02-05 05:12:51'),
(295, 163, 87, '1128929.00', '0.00', '2026-02-05 05:12:51'),
(296, 163, 79, '0.00', '1128929.00', '2026-02-05 05:12:51'),
(297, 164, 87, '1924761.00', '0.00', '2026-02-05 05:12:51'),
(298, 164, 80, '0.00', '1924761.00', '2026-02-05 05:12:51'),
(299, 165, 87, '832140.00', '0.00', '2026-02-05 05:12:51'),
(300, 165, 81, '0.00', '832140.00', '2026-02-05 05:12:51'),
(301, 166, 87, '1383300.00', '0.00', '2026-02-05 05:12:51'),
(302, 166, 82, '0.00', '1383300.00', '2026-02-05 05:12:51'),
(303, 167, 87, '2874446.00', '0.00', '2026-02-05 05:12:51'),
(304, 167, 78, '0.00', '2874446.00', '2026-02-05 05:12:51'),
(305, 168, 87, '557252.00', '0.00', '2026-02-05 05:12:51'),
(306, 168, 79, '0.00', '557252.00', '2026-02-05 05:12:51'),
(307, 169, 87, '2211290.00', '0.00', '2026-02-05 05:12:51'),
(308, 169, 80, '0.00', '2211290.00', '2026-02-05 05:12:51'),
(309, 170, 87, '2371593.00', '0.00', '2026-02-05 05:12:51'),
(310, 170, 81, '0.00', '2371593.00', '2026-02-05 05:12:51'),
(311, 171, 87, '2283740.00', '0.00', '2026-02-05 05:12:51'),
(312, 171, 82, '0.00', '2283740.00', '2026-02-05 05:12:51'),
(313, 172, 87, '2974242.00', '0.00', '2026-02-05 05:12:51'),
(314, 172, 78, '0.00', '2974242.00', '2026-02-05 05:12:51'),
(315, 173, 87, '1893112.00', '0.00', '2026-02-05 05:12:51'),
(316, 173, 79, '0.00', '1893112.00', '2026-02-05 05:12:51'),
(317, 174, 87, '522258.00', '0.00', '2026-02-05 05:12:51'),
(318, 174, 80, '0.00', '522258.00', '2026-02-05 05:12:51'),
(319, 175, 87, '1654791.00', '0.00', '2026-02-05 05:12:51'),
(320, 175, 81, '0.00', '1654791.00', '2026-02-05 05:12:51'),
(321, 176, 87, '2805180.00', '0.00', '2026-02-05 05:12:51'),
(322, 176, 82, '0.00', '2805180.00', '2026-02-05 05:12:51'),
(323, 177, 87, '2602934.00', '0.00', '2026-02-05 05:12:51'),
(324, 177, 78, '0.00', '2602934.00', '2026-02-05 05:12:51'),
(325, 178, 87, '1177399.00', '0.00', '2026-02-05 05:12:51'),
(326, 178, 79, '0.00', '1177399.00', '2026-02-05 05:12:51'),
(327, 179, 87, '1319209.00', '0.00', '2026-02-05 05:12:51'),
(328, 179, 80, '0.00', '1319209.00', '2026-02-05 05:12:51'),
(329, 180, 87, '1905986.00', '0.00', '2026-02-05 05:12:51'),
(330, 180, 81, '0.00', '1905986.00', '2026-02-05 05:12:51'),
(331, 181, 87, '2153084.00', '0.00', '2026-02-05 05:12:51'),
(332, 181, 82, '0.00', '2153084.00', '2026-02-05 05:12:51');

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

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama`, `posisi`, `alamat`, `umur`, `kontak`) VALUES
(23, 'Ahmad Rizki', 'Kepala Mekanik', 'Jl. Padang Selatan No. 10', 28, '081234567890'),
(24, 'Budi Santoso', 'Mekanik Senior', 'Jl. Air Tawar No. 15', 30, '081234567891'),
(25, 'Doni Pratama', 'Mekanik Junior', 'Jl. Belanti No. 20', 24, '081234567892'),
(26, 'Eko Wahyudi', 'Mekanik Junior', 'Jl. Andalas No. 25', 23, '081234567893'),
(27, 'Feri Irawan', 'Kasir', 'Jl. Dobi No. 5', 26, '081234567894'),
(28, 'Gunawan', 'Admin Sparepart', 'Jl. Pasar Raya No. 30', 25, '081234567895'),
(29, 'Hendra Wijaya', 'Sales Marketing', 'Jl. Bundo Kanduang No. 12', 27, '081234567896'),
(30, 'I Made Sukma', 'Cleaning Service', 'Jl. Sawahan No. 8', 35, '081234567897'),
(31, 'Joko Susilo', 'Security', 'Jl. Siteba No. 18', 40, '081234567898'),
(32, 'Kelvin Saputra', 'Kurir', 'Jl. Ulak Karang No. 22', 22, '081234567899'),
(33, 'Lukman Hakim', 'Mekanik Senior', 'Jl. Gurun Laweh No. 35', 32, '081234567800'),
(34, 'Muhammad Raffi', 'Helper Mekanik', 'Jl. Parak Gadang No. 40', 20, '081234567801'),
(35, 'Nina Safitri', 'Administrasi', 'Jl. Koto Panjang No. 5', 25, '081234567802'),
(36, 'Oscar Pratama', 'Teknisi Alat', 'Jl. Lubang Gandang No. 12', 29, '081234567803'),
(37, 'Putri Andini', 'Keuangan', 'Jl. Tarandam No. 8', 26, '081234567804'),
(38, 'Qori Maulana', 'Logistik', 'Jl. VIII Koto Mudiek No. 15', 28, '081234567805'),
(39, 'Rina Kurnia', 'HRD', 'Jl. Kuranji Hulu No. 20', 27, '081234567806'),
(40, 'Sigit Prabowo', 'Quality Control', 'Jl. Koto Tangah No. 25', 31, '081234567807'),
(41, 'Tina Sari', 'Customer Service', 'Jl. Sungai Sapih No. 30', 24, '081234567808'),
(42, 'Umar Faruq', 'Supervisor', 'Jl. Koto Baru No. 35', 33, '081234567809');

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
  `id_akun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laba_rugi`
--

INSERT INTO `laba_rugi` (`id_laba_rugi`, `tanggal`, `sumber`, `jumlah`, `status`, `id_akun`) VALUES
(27, '2026-01-01', 'Pendapatan Penjualan Pupuk', 2500000, 1, 86),
(28, '2026-01-02', 'Pendapatan Penjualan Benih', 3000000, 1, 86),
(29, '2026-01-03', 'Pendapatan Jasa Pengolahan Lahan', 1800000, 1, 86),
(30, '2026-01-04', 'Pendapatan Jasa Konsultasi', 1200000, 1, 86),
(31, '2026-01-05', 'Pendapatan Penjualan Alat', 4500000, 1, 86),
(32, '2026-01-06', 'HPP Pupuk yang Terjual', 1500000, 2, 87),
(33, '2026-01-07', 'HPP Benih yang Terjual', 1800000, 2, 87),
(34, '2026-01-08', 'HPP Alat yang Terjual', 3000000, 2, 87),
(35, '2026-01-09', 'Beban Gaji Karyawan', 8000000, 3, 87),
(36, '2026-01-10', 'Beban Operasional', 2500000, 3, 87),
(37, '2026-01-11', 'Beban Transportasi', 1200000, 3, 87),
(38, '2026-01-12', 'Beban Sewa Gudang', 2000000, 3, 87),
(39, '2026-01-13', 'Beban Listrik & Air', 800000, 3, 87),
(40, '2026-01-14', 'Beban Perawatan', 1000000, 3, 87),
(41, '2026-01-15', 'Beban Promosi', 1500000, 3, 87),
(42, '2026-01-16', 'Beban Administrasi', 750000, 3, 87),
(43, '2026-01-17', 'Beban Pajak', 1000000, 3, 87),
(44, '2026-01-18', 'Beban Asuransi', 1200000, 3, 87),
(45, '2026-01-19', 'Beban Pelatihan', 900000, 3, 87),
(46, '2026-01-20', 'Laba Bersih Bulanan', 5000000, 4, 86);

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
  `id_akun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `neraca_saldo`
--

INSERT INTO `neraca_saldo` (`id_neraca_saldo`, `tanggal`, `nama_akun`, `nomor_akun`, `saldo_awal_debit`, `saldo_awal_kredit`, `pergerakan_debit`, `pergerakan_kredit`, `status`, `id_akun`) VALUES
(27, '2026-01-01', 'Kas', '1-1001', 50000000, 0, 25000000, 15000000, 1, 78),
(28, '2026-01-02', 'Bank BCA', '1-1002', 100000000, 0, 50000000, 30000000, 1, 79),
(29, '2026-01-03', 'Piutang Usaha', '1-1003', 25000000, 0, 15000000, 5000000, 1, 81),
(30, '2026-01-04', 'Persediaan Pupuk', '1-1004', 75000000, 0, 35000000, 20000000, 1, 82),
(31, '2026-01-05', 'Persediaan Benih', '1-1005', 30000000, 0, 5000000, 0, 1, 82),
(32, '2026-01-06', 'Peralatan Pertanian', '1-1006', 45000000, 0, 15000000, 5000000, 1, 78),
(33, '2026-01-07', 'Utang Usaha', '2-1001', 0, 35000000, 15000000, 25000000, 2, 83),
(34, '2026-01-08', 'Utang Gaji', '2-1002', 0, 5000000, 3000000, 0, 2, 84),
(35, '2026-01-09', 'Utang Bank', '2-1003', 0, 20000000, 10000000, 15000000, 3, 83),
(36, '2026-01-10', 'Modal Awal', '3-1001', 0, 200000000, 0, 0, 3, 85),
(37, '2026-01-11', 'Laba Ditahan', '3-1002', 0, 15000000, 0, 5000000, 4, 85),
(38, '2026-01-12', 'Pendapatan Penjualan Pupuk', '4-1001', 0, 85000000, 0, 120000000, 4, 86),
(39, '2026-01-13', 'Pendapatan Penjualan Benih', '4-1002', 0, 65000000, 0, 95000000, 4, 86),
(40, '2026-01-14', 'Pendapatan Jasa', '4-1003', 0, 5000000, 0, 8000000, 5, 86),
(41, '2026-01-15', 'Beban Gaji', '5-1001', 15000000, 0, 25000000, 0, 5, 87),
(42, '2026-01-16', 'Beban Operasional', '5-1002', 3000000, 0, 5000000, 0, 5, 87),
(43, '2026-01-17', 'Beban Peralatan', '5-1003', 8000000, 0, 12000000, 0, 5, 87),
(44, '2026-01-18', 'Beban Transportasi', '5-1004', 2500000, 0, 5000000, 0, 5, 87),
(45, '2026-01-19', 'Beban Pajak', '5-1005', 1500000, 0, 2500000, 0, 5, 87),
(46, '2026-01-20', 'Beban Lain-lain', '5-1006', 2000000, 0, 3000000, 0, 5, 87);

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
(44, '2026-01-01', 3460058, 'Penjualan Pupuk Organik Premium', 86, 78, 1),
(45, '2026-01-02', 1384820, 'Penjualan Benih Padi Unggul', 86, 79, 1),
(46, '2026-01-03', 1864584, 'Penjualan Alat Pertanian Modern', 86, 80, 1),
(47, '2026-01-04', 4521454, 'Jasa Pengolahan Lahan', 86, 81, 1),
(48, '2026-01-05', 1836967, 'Jasa Konsultasi Pertanian', 86, 82, 1),
(49, '2026-01-06', 4702739, 'Penjualan Peralatan Irigasi', 86, 78, 1),
(50, '2026-01-07', 3259931, 'Penjualan Bibit Sayuran', 86, 79, 1),
(51, '2026-01-08', 4368726, 'Jasa Pemupukan Lahan', 86, 80, 1),
(52, '2026-01-09', 2657663, 'Penjualan Pupuk Cair Nutrisi', 86, 81, 1),
(53, '2026-01-10', 1660203, 'Jasa Pengendalian Hama', 86, 82, 1),
(54, '2026-01-11', 1975759, 'Penjualan Alat Panen Modern', 86, 78, 1),
(55, '2026-01-12', 1675362, 'Jasa Penyemaian Benih', 86, 79, 1),
(56, '2026-01-13', 4345500, 'Penjualan Perlengkapan Ternak', 86, 80, 1),
(57, '2026-01-14', 4946059, 'Jasa Pengeringan Hasil Panen', 86, 81, 1),
(58, '2026-01-15', 3768762, 'Penjualan Peralatan Pengolahan', 86, 82, 1),
(59, '2026-01-16', 3687617, 'Jasa Desinfeksi Lahan', 86, 78, 1),
(60, '2026-01-17', 4691625, 'Penjualan Media Tanam', 86, 79, 1),
(61, '2026-01-18', 4792209, 'Jasa Pemeliharaan Tanaman', 86, 80, 1),
(62, '2026-01-19', 3247805, 'Penjualan Peralatan Hidroponik', 86, 81, 1),
(63, '2026-01-20', 1664683, 'Jasa Analisis Tanah', 86, 82, 1);

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
(48, '2026-01-01', 1582356, 'Gaji Karyawan Bulan Januari', 87, 78, 2),
(49, '2026-01-02', 1128929, 'Biaya Listrik Kantor', 87, 79, 2),
(50, '2026-01-03', 1924761, 'Biaya Air Bersih', 87, 80, 2),
(51, '2026-01-04', 832140, 'Pembelian Pupuk untuk Stok', 87, 81, 2),
(52, '2026-01-05', 1383300, 'Pembelian Benih untuk Stok', 87, 82, 2),
(53, '2026-01-06', 2874446, 'Biaya Transportasi Operasional', 87, 78, 2),
(54, '2026-01-07', 557252, 'Biaya Bensin Kendaraan', 87, 79, 2),
(55, '2026-01-08', 2211290, 'Biaya Perawatan Alat', 87, 80, 2),
(56, '2026-01-09', 2371593, 'Biaya Sewa Gudang', 87, 81, 2),
(57, '2026-01-10', 2283740, 'Biaya Promosi dan Iklan', 87, 82, 2),
(58, '2026-01-11', 2974242, 'Biaya ATK Kantor', 87, 78, 2),
(59, '2026-01-12', 1893112, 'Biaya Internet dan Telepon', 87, 79, 2),
(60, '2026-01-13', 522258, 'Biaya Pajak Bulanan', 87, 80, 2),
(61, '2026-01-14', 1654791, 'Biaya Asuransi Karyawan', 87, 81, 2),
(62, '2026-01-15', 2805180, 'Biaya Pelatihan Karyawan', 87, 82, 2),
(63, '2026-01-16', 2602934, 'Biaya Perlengkapan Kebersihan', 87, 78, 2),
(64, '2026-01-17', 1177399, 'Biaya Perbaikan Kendaraan', 87, 79, 2),
(65, '2026-01-18', 1319209, 'Biaya Pengiriman Barang', 87, 80, 2),
(66, '2026-01-19', 1905986, 'Biaya Maintenance Website', 87, 81, 2),
(67, '2026-01-20', 2153084, 'Biaya Sewa Lahan Percobaan', 87, 82, 2);

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
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `fk_arus_kas_akun` (`id_akun`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `nomor_akun` (`nomor_akun`);

--
-- Indexes for table `hutang`
--
ALTER TABLE `hutang`
  ADD PRIMARY KEY (`id_hutang`),
  ADD KEY `fk_hutang_akun_debet` (`id_akun_debet`),
  ADD KEY `fk_hutang_akun_kredit` (`id_akun_kredit`);

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
  ADD KEY `fk_laba_rugi_akun` (`id_akun`);

--
-- Indexes for table `neraca_saldo`
--
ALTER TABLE `neraca_saldo`
  ADD PRIMARY KEY (`id_neraca_saldo`),
  ADD KEY `fk_neraca_saldo_akun` (`id_akun`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id_pemasukan`),
  ADD KEY `fk_pemasukan_akun_pendapatan` (`id_akun_pendapatan`),
  ADD KEY `fk_pemasukan_akun_kas` (`id_akun_kas`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `fk_pengeluaran_akun_beban` (`id_akun_beban`),
  ADD KEY `fk_pengeluaran_akun_kas` (`id_akun_kas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `arus_kas`
--
ALTER TABLE `arus_kas`
  MODIFY `id_arus_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `hutang`
--
ALTER TABLE `hutang`
  MODIFY `id_hutang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id_line` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=333;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  MODIFY `id_laba_rugi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `neraca_saldo`
--
ALTER TABLE `neraca_saldo`
  MODIFY `id_neraca_saldo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arus_kas`
--
ALTER TABLE `arus_kas`
  ADD CONSTRAINT `fk_arus_kas_akun` FOREIGN KEY (`id_akun`) REFERENCES `chart_of_accounts` (`id_akun`);

--
-- Constraints for table `hutang`
--
ALTER TABLE `hutang`
  ADD CONSTRAINT `fk_hutang_akun_debet` FOREIGN KEY (`id_akun_debet`) REFERENCES `chart_of_accounts` (`id_akun`),
  ADD CONSTRAINT `fk_hutang_akun_kredit` FOREIGN KEY (`id_akun_kredit`) REFERENCES `chart_of_accounts` (`id_akun`);

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `fk_journal_lines_akun` FOREIGN KEY (`id_akun`) REFERENCES `chart_of_accounts` (`id_akun`),
  ADD CONSTRAINT `fk_journal_lines_jurnal` FOREIGN KEY (`id_jurnal`) REFERENCES `journal_entries` (`id_jurnal`) ON DELETE CASCADE;

--
-- Constraints for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  ADD CONSTRAINT `fk_laba_rugi_akun` FOREIGN KEY (`id_akun`) REFERENCES `chart_of_accounts` (`id_akun`);

--
-- Constraints for table `neraca_saldo`
--
ALTER TABLE `neraca_saldo`
  ADD CONSTRAINT `fk_neraca_saldo_akun` FOREIGN KEY (`id_akun`) REFERENCES `chart_of_accounts` (`id_akun`);

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `fk_pemasukan_akun_kas` FOREIGN KEY (`id_akun_kas`) REFERENCES `chart_of_accounts` (`id_akun`),
  ADD CONSTRAINT `fk_pemasukan_akun_pendapatan` FOREIGN KEY (`id_akun_pendapatan`) REFERENCES `chart_of_accounts` (`id_akun`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_akun_beban` FOREIGN KEY (`id_akun_beban`) REFERENCES `chart_of_accounts` (`id_akun`),
  ADD CONSTRAINT `fk_pengeluaran_akun_kas` FOREIGN KEY (`id_akun_kas`) REFERENCES `chart_of_accounts` (`id_akun`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;