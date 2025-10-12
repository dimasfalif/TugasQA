-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 08:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_spk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `alamat` varchar(250) NOT NULL,
  `telepon` varchar(13) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `alamat`, `telepon`, `email`, `username`, `password`) VALUES
(17, 'Alif', 'lebak', '0816178843', 'dimskuy@gmail.com', 'dimskuy', '$2y$10$48s1pQAyf0i4Ub5CWRGuGegnbCfUmcV5/BOkiY8TnXOxwyByE.iHS'),
(19, 'Dimas Faturahman Alif', 'KP.Sindangsono', '12345', 'SKAKLDLS@GMAIL.COM', 'alif', '$2y$10$LWNXWJKArzfzLd40536NQ.ArItxfiuJFTNW068FJyNKn5x2J6Wuom');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_spk`
--

CREATE TABLE `hasil_spk` (
  `id_spk` int(11) NOT NULL,
  `id_calon_kr` int(11) DEFAULT NULL,
  `hasil_spk` float(10,2) DEFAULT NULL,
  `minggu` varchar(2) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_tpa`
--

CREATE TABLE `hasil_tpa` (
  `id_test` int(11) NOT NULL,
  `id_calon_kr` int(11) DEFAULT NULL,
  `ABSENSI` float(10,2) DEFAULT NULL,
  `Atribut_Pakaian_Kerja_Lengkap` float(10,2) NOT NULL,
  `SOP_Packing` float(10,2) DEFAULT NULL,
  `Kerja_Sama` float(10,2) DEFAULT NULL,
  `Tanggung_jawab` float(10,2) DEFAULT NULL,
  `Inisiatif` float(10,2) DEFAULT NULL,
  `Kerapian` float(10,2) DEFAULT NULL,
  `Ketelitian` float(10,2) DEFAULT NULL,
  `Kecepatan` float(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_calon_kr` int(11) NOT NULL,
  `NIK` varchar(100) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jeniskelamin` varchar(10) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `telepon` varchar(13) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `ttl` date NOT NULL,
  `TempatLahir` varchar(200) NOT NULL,
  `PendidikanTerakhir` varchar(100) NOT NULL,
  `Jabatan` varchar(100) NOT NULL,
  `TglBergabung` date NOT NULL,
  `skill` varchar(200) DEFAULT NULL,
  `pengalaman` varchar(200) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_calon_kr`, `NIK`, `nama`, `jeniskelamin`, `alamat`, `telepon`, `foto`, `ttl`, `TempatLahir`, `PendidikanTerakhir`, `Jabatan`, `TglBergabung`, `skill`, `pengalaman`, `password`) VALUES
(5, '3B.02.15.SB', 'Dimas Faturahman Alif', 'Pria', 'jl.pandeglang km.12 warunggunung lebak-banten', '081617694097', 'IMG20190430073112.jpg', '2002-03-16', 'lebak', 'SMA', ' Produksi', '2019-08-18', 'design corel draw', 'operator laser cutting', ''),
(20, '1234567', 'wahyu', 'Pria', 'dasi', '087654345', '', '2025-09-01', 'leb', 'D3', 'Akutansi', '2025-10-30', 'Data Analysis, Network Administrator, Phyton', 'dang', '$2y$10$Ck4TYpiwNjV75zZtfg0Cqe7dwR2UqZnr2ajwgdY0he9aQySh3n.K6'),
(21, '123', 'SAMLONG', 'Pria', 'sanian', '08161756543', '', '2011-02-01', 'Banten', 'SMA', 'Staff IT', '2025-10-13', 'Data Analysis, Network Administrator, Phyton', 'dangdutan', '$2y$10$y.DF2E8KdV7oPM4ZzqxV2eZvB/.HDzTdQYj4caDUHHx/s6kr6YkPa');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kriteria` varchar(32) DEFAULT NULL,
  `bobot` float(5,2) DEFAULT NULL,
  `type` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kriteria`, `bobot`, `type`) VALUES
(13, 'ABSENSI', 20.00, 'Cost'),
(14, 'Atribut_Pakaian_Kerja_Lengkap', 20.00, 'Benefit'),
(15, 'SOP_Packing', 15.00, 'Benefit'),
(16, 'Kerja_Sama', 10.00, 'Benefit'),
(28, 'Tanggung_jawab', 10.00, 'Benefit'),
(29, 'Inisiatif', 10.00, 'Benefit'),
(30, 'Kerapian', 5.00, 'Benefit'),
(31, 'Ketelitian', 5.00, 'Benefit'),
(32, 'Kecepatan', 5.00, 'Benefit');

-- --------------------------------------------------------

--
-- Table structure for table `lg_karyawan`
--

CREATE TABLE `lg_karyawan` (
  `nik` varchar(100) NOT NULL,
  `nama` int(50) NOT NULL,
  `email` int(50) NOT NULL,
  `password` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_subkriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `subkriteria` varchar(255) NOT NULL,
  `nilai` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_subkriteria`, `id_kriteria`, `subkriteria`, `nilai`) VALUES
(11, 13, 'terlambat 0 kali', 10.00),
(12, 13, 'terlambat 1 kali', 9.00),
(13, 13, 'terlambat 2 kali', 8.00),
(14, 13, 'terlambat 3 kali', 7.00),
(15, 13, 'terlambat 4 kali', 6.00),
(16, 13, 'terlambat 5 kali', 5.00),
(17, 13, 'terlambat 6 kali', 4.00),
(18, 13, 'terlambat 7 kali', 3.00),
(19, 13, 'terlambat 8 kali', 2.00),
(20, 13, 'terlambat 9 kali', 1.00),
(21, 13, 'terlambat >10 kali', 0.00),
(22, 14, 'Baik Sekali', 5.00),
(23, 14, 'baik', 4.00),
(24, 14, 'cukup', 3.00),
(25, 14, 'kurang', 2.00),
(26, 14, 'kurang sekali', 1.00),
(27, 15, 'Baik Sekali', 5.00),
(28, 15, 'baik', 4.00),
(29, 15, 'cukup', 3.00),
(30, 15, 'kurang', 2.00),
(31, 15, 'kurang sekali', 1.00),
(32, 16, 'Baik Sekali', 5.00),
(33, 16, 'baik', 4.00),
(34, 16, 'cukup', 3.00),
(35, 16, 'kurang', 2.00),
(36, 16, 'kurang sekali', 1.00),
(37, 28, 'Baik Sekali', 5.00),
(38, 28, 'baik', 4.00),
(39, 28, 'cukup', 3.00),
(40, 28, 'kurang', 2.00),
(41, 28, 'kurang sekali', 1.00),
(42, 29, 'Baik Sekali', 5.00),
(43, 29, 'baik', 4.00),
(44, 29, 'cukup', 3.00),
(45, 29, 'kurang', 2.00),
(46, 29, 'kurang sekali', 1.00),
(47, 30, 'Baik Sekali', 5.00),
(48, 30, 'baik', 4.00),
(49, 30, 'cukup', 3.00),
(50, 30, 'kurang', 2.00),
(51, 30, 'kurang sekali', 1.00),
(52, 31, 'Baik Sekali', 5.00),
(53, 31, 'baik', 4.00),
(54, 31, 'cukup', 3.00),
(55, 31, 'kurang', 2.00),
(56, 31, 'kurang sekali', 1.00),
(57, 32, 'Baik Sekali', 5.00),
(58, 32, 'baik', 4.00),
(59, 32, 'cukup', 3.00),
(60, 32, 'kurang', 2.00),
(61, 32, 'kurang sekali', 1.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasil_spk`
--
ALTER TABLE `hasil_spk`
  ADD PRIMARY KEY (`id_spk`),
  ADD KEY `id_calon_kr` (`id_calon_kr`);

--
-- Indexes for table `hasil_tpa`
--
ALTER TABLE `hasil_tpa`
  ADD PRIMARY KEY (`id_test`),
  ADD KEY `id_calon_kr` (`id_calon_kr`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_calon_kr`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `lg_karyawan`
--
ALTER TABLE `lg_karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_subkriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `hasil_spk`
--
ALTER TABLE `hasil_spk`
  MODIFY `id_spk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `hasil_tpa`
--
ALTER TABLE `hasil_tpa`
  MODIFY `id_test` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_calon_kr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_spk`
--
ALTER TABLE `hasil_spk`
  ADD CONSTRAINT `hasil_spk_ibfk_1` FOREIGN KEY (`id_calon_kr`) REFERENCES `karyawan` (`id_calon_kr`);

--
-- Constraints for table `hasil_tpa`
--
ALTER TABLE `hasil_tpa`
  ADD CONSTRAINT `hasil_tpa_ibfk_1` FOREIGN KEY (`id_calon_kr`) REFERENCES `karyawan` (`id_calon_kr`);

--
-- Constraints for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
