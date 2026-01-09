-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2024 at 07:50 AM
-- Server version: 8.0.39-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.23

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konsuler2`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_message`
--

DROP TABLE IF EXISTS `chat_message`;
CREATE TABLE `chat_message` (
  `id` bigint NOT NULL,
  `dari` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ke` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_user_online`
--

DROP TABLE IF EXISTS `chat_user_online`;
CREATE TABLE `chat_user_online` (
  `id` bigint NOT NULL,
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_online` int DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fungsi`
--

DROP TABLE IF EXISTS `fungsi`;
CREATE TABLE `fungsi` (
  `id` bigint NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indonesia_desa`
--

DROP TABLE IF EXISTS `indonesia_desa`;
CREATE TABLE `indonesia_desa` (
  `id` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `district_id` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indonesia_kecamatan`
--

DROP TABLE IF EXISTS `indonesia_kecamatan`;
CREATE TABLE `indonesia_kecamatan` (
  `id` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city_id` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indonesia_kota`
--

DROP TABLE IF EXISTS `indonesia_kota`;
CREATE TABLE `indonesia_kota` (
  `id` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province_id` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indonesia_provinsi`
--

DROP TABLE IF EXISTS `indonesia_provinsi`;
CREATE TABLE `indonesia_provinsi` (
  `id` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jml_pelayanan`
--

DROP TABLE IF EXISTS `jml_pelayanan`;
CREATE TABLE `jml_pelayanan` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `pelayanan` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus`
--

DROP TABLE IF EXISTS `kasus`;
CREATE TABLE `kasus` (
  `id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_kejadian` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `polis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kategori_kasus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_by` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `ket_status` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `req_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `req_sts_by` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `closed_date` timestamp NULL DEFAULT NULL,
  `documented` int DEFAULT NULL,
  `gaji` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shelter` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus_file`
--

DROP TABLE IF EXISTS `kasus_file`;
CREATE TABLE `kasus_file` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ref` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ref_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus_identitas`
--

DROP TABLE IF EXISTS `kasus_identitas`;
CREATE TABLE `kasus_identitas` (
  `kasus_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `identitas_id` bigint NOT NULL,
  `sebagai` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus_kategori`
--

DROP TABLE IF EXISTS `kasus_kategori`;
CREATE TABLE `kasus_kategori` (
  `id` bigint NOT NULL,
  `kategori` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent` bigint DEFAULT NULL,
  `jenis_kasus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus_petugas`
--

DROP TABLE IF EXISTS `kasus_petugas`;
CREATE TABLE `kasus_petugas` (
  `kasus_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasus_progress`
--

DROP TABLE IF EXISTS `kasus_progress`;
CREATE TABLE `kasus_progress` (
  `id` bigint NOT NULL,
  `kasus_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `petugas` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `layanan_identitas`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `layanan_identitas`;
CREATE TABLE `layanan_identitas` (
`id` bigint
,`jenis_identitas` varchar(255)
,`nama` varchar(100)
,`no_identitas` varchar(100)
,`riwayat_layanan` text
,`tempat_lahir` varchar(100)
,`tgl_lahir` date
);

-- --------------------------------------------------------

--
-- Table structure for table `master_daerah`
--

DROP TABLE IF EXISTS `master_daerah`;
CREATE TABLE `master_daerah` (
  `id` bigint UNSIGNED NOT NULL,
  `negeri_id` bigint NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_distrik`
--

DROP TABLE IF EXISTS `master_distrik`;
CREATE TABLE `master_distrik` (
  `id` bigint UNSIGNED NOT NULL,
  `daerah_id` bigint NOT NULL,
  `nama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_identitas`
--

DROP TABLE IF EXISTS `master_identitas`;
CREATE TABLE `master_identitas` (
  `id` bigint NOT NULL,
  `no_identitas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_identitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ibu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kewarganegaraan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_idn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `negeri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `distrik` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `daerah` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_mys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_identitas_copy1`
--

DROP TABLE IF EXISTS `master_identitas_copy1`;
CREATE TABLE `master_identitas_copy1` (
  `id` bigint NOT NULL,
  `no_identitas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_identitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ibu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kewarganegaraan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_idn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `negeri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `distrik` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `daerah` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_mys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_identitas_log`
--

DROP TABLE IF EXISTS `master_identitas_log`;
CREATE TABLE `master_identitas_log` (
  `id` bigint NOT NULL,
  `no_identitas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_identitas` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ibu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kewarganegaraan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_idn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `negeri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `distrik` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `daerah` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_mys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_jenis_identitas`
--

DROP TABLE IF EXISTS `master_jenis_identitas`;
CREATE TABLE `master_jenis_identitas` (
  `id` bigint NOT NULL,
  `jenis` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_negeri`
--

DROP TABLE IF EXISTS `master_negeri`;
CREATE TABLE `master_negeri` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_pelayanan`
--

DROP TABLE IF EXISTS `master_pelayanan`;
CREATE TABLE `master_pelayanan` (
  `pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode_layanan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pelayanan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `biaya` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `template_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `show_jml` int DEFAULT NULL,
  `template_kwitansi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `template_bukti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fungsi` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_pelayanan_field`
--

DROP TABLE IF EXISTS `master_pelayanan_field`;
CREATE TABLE `master_pelayanan_field` (
  `id` bigint NOT NULL,
  `pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `field_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `field_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `required` int NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `output` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `urut` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE `notifikasi` (
  `id` bigint NOT NULL,
  `ke` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `href` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `has_read` int DEFAULT '0',
  `waktu` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scan_log`
--

DROP TABLE IF EXISTS `scan_log`;
CREATE TABLE `scan_log` (
  `id` bigint NOT NULL,
  `agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_addr` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `viewer` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `singkronisasi`
--

DROP TABLE IF EXISTS `singkronisasi`;
CREATE TABLE `singkronisasi` (
  `id` bigint NOT NULL,
  `file` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `sending` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_pelayanan`
--

DROP TABLE IF EXISTS `tr_pelayanan`;
CREATE TABLE `tr_pelayanan` (
  `id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_dokumen` bigint DEFAULT NULL,
  `pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pelapor` bigint NOT NULL,
  `biaya` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `petugas_loket` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `petugas_verifikasi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verif_edit` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hs` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kasir` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int DEFAULT '0',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `qrcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_berkas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_berkas_kasir` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tgl_ambil` datetime DEFAULT NULL,
  `jml_berkas` bigint DEFAULT '1',
  `file_esign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fungsi` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_pelayanan_file`
--

DROP TABLE IF EXISTS `tr_pelayanan_file`;
CREATE TABLE `tr_pelayanan_file` (
  `tr_pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_pelayanan_item`
--

DROP TABLE IF EXISTS `tr_pelayanan_item`;
CREATE TABLE `tr_pelayanan_item` (
  `tr_pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `field_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `field_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `field_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `field_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `field_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_pelayanan_no`
--

DROP TABLE IF EXISTS `tr_pelayanan_no`;
CREATE TABLE `tr_pelayanan_no` (
  `tr_pelayanan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_dokumen` int DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `kode_pelayanan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_detail`
--

DROP TABLE IF EXISTS `tugas_detail`;
CREATE TABLE `tugas_detail` (
  `tugas_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_pekerjaan`
--

DROP TABLE IF EXISTS `tugas_pekerjaan`;
CREATE TABLE `tugas_pekerjaan` (
  `id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `closed_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_progress`
--

DROP TABLE IF EXISTS `tugas_progress`;
CREATE TABLE `tugas_progress` (
  `id` bigint NOT NULL,
  `tugas_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `petugas` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan_en` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tte_nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tte_pwd` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `akses` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `aktif` int DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `akses_pelayanan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `kode_report` text COLLATE utf8mb4_general_ci NOT NULL,
  `fungsi` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_old`
--

DROP TABLE IF EXISTS `users_old`;
CREATE TABLE `users_old` (
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan_en` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `akses` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `aktif` int DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `akses_pelayanan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `layanan_identitas`
--
DROP TABLE IF EXISTS `layanan_identitas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `layanan_identitas`  AS  select `m`.`id` AS `id`,`m`.`no_identitas` AS `no_identitas`,`m`.`jenis_identitas` AS `jenis_identitas`,`m`.`nama` AS `nama`,`m`.`tempat_lahir` AS `tempat_lahir`,`m`.`tgl_lahir` AS `tgl_lahir`,substring_index(group_concat(`tr`.`tr_pelayanan_id` order by `tr`.`created_at` ASC separator ';'),';',3) AS `riwayat_layanan` from (`master_identitas` `m` left join (select `tr_pelayanan_item`.`tr_pelayanan_id` AS `tr_pelayanan_id`,`tr_pelayanan`.`created_at` AS `created_at`,rank() OVER (PARTITION BY `tr_pelayanan_item`.`tr_pelayanan_id` ORDER BY `tr_pelayanan`.`created_at` desc )  AS `rnk`,json_unquote(json_extract(`tr_pelayanan_item`.`field_data`,'$.id')) AS `id` from (`tr_pelayanan_item` join `tr_pelayanan` on((`tr_pelayanan`.`id` = `tr_pelayanan_item`.`tr_pelayanan_id`))) where (substr(`tr_pelayanan_item`.`field_data`,1,6) = '{"id":')) `tr` on((`m`.`id` = `tr`.`id`))) group by `m`.`id`,`m`.`no_identitas`,`m`.`jenis_identitas`,`m`.`nama`,`m`.`tempat_lahir`,`m`.`tgl_lahir` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_user_online`
--
ALTER TABLE `chat_user_online`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `fungsi`
--
ALTER TABLE `fungsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indonesia_desa`
--
ALTER TABLE `indonesia_desa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indonesia_villages_district_id_foreign` (`district_id`);

--
-- Indexes for table `indonesia_kecamatan`
--
ALTER TABLE `indonesia_kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indonesia_districts_city_id_foreign` (`city_id`);

--
-- Indexes for table `indonesia_kota`
--
ALTER TABLE `indonesia_kota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indonesia_cities_province_id_foreign` (`province_id`);

--
-- Indexes for table `indonesia_provinsi`
--
ALTER TABLE `indonesia_provinsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jml_pelayanan`
--
ALTER TABLE `jml_pelayanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kasus`
--
ALTER TABLE `kasus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kasus_file`
--
ALTER TABLE `kasus_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kasus_kategori`
--
ALTER TABLE `kasus_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kasus_progress`
--
ALTER TABLE `kasus_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_daerah`
--
ALTER TABLE `master_daerah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_daerah_id` (`id`);

--
-- Indexes for table `master_distrik`
--
ALTER TABLE `master_distrik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_distrik_id` (`id`);

--
-- Indexes for table `master_identitas`
--
ALTER TABLE `master_identitas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `master_identitas_copy1`
--
ALTER TABLE `master_identitas_copy1`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `no_identitas` (`no_identitas`,`nama`) USING BTREE;

--
-- Indexes for table `master_identitas_log`
--
ALTER TABLE `master_identitas_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `master_jenis_identitas`
--
ALTER TABLE `master_jenis_identitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_negeri`
--
ALTER TABLE `master_negeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_negeri_id` (`id`);

--
-- Indexes for table `master_pelayanan`
--
ALTER TABLE `master_pelayanan`
  ADD PRIMARY KEY (`pelayanan_id`);

--
-- Indexes for table `master_pelayanan_field`
--
ALTER TABLE `master_pelayanan_field`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scan_log`
--
ALTER TABLE `scan_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `singkronisasi`
--
ALTER TABLE `singkronisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_pelayanan`
--
ALTER TABLE `tr_pelayanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas_pekerjaan`
--
ALTER TABLE `tugas_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas_progress`
--
ALTER TABLE `tugas_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_old`
--
ALTER TABLE `users_old`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_user_online`
--
ALTER TABLE `chat_user_online`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fungsi`
--
ALTER TABLE `fungsi`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kasus_kategori`
--
ALTER TABLE `kasus_kategori`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kasus_progress`
--
ALTER TABLE `kasus_progress`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_daerah`
--
ALTER TABLE `master_daerah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_distrik`
--
ALTER TABLE `master_distrik`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_identitas`
--
ALTER TABLE `master_identitas`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_identitas_copy1`
--
ALTER TABLE `master_identitas_copy1`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_identitas_log`
--
ALTER TABLE `master_identitas_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_jenis_identitas`
--
ALTER TABLE `master_jenis_identitas`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_negeri`
--
ALTER TABLE `master_negeri`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_pelayanan_field`
--
ALTER TABLE `master_pelayanan_field`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scan_log`
--
ALTER TABLE `scan_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `singkronisasi`
--
ALTER TABLE `singkronisasi`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_progress`
--
ALTER TABLE `tugas_progress`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
