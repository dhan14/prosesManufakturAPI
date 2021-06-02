-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2021 at 07:24 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prosmandb`
--

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(10) NOT NULL,
  `nip` int(10) NOT NULL,
  `nama_karyawan` varchar(75) NOT NULL,
  `operator` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nip`, `nama_karyawan`, `operator`) VALUES
(1, 12001, 'Asep Wijaya', 'Milling'),
(2, 12002, 'Fauzan', 'Milling'),
(3, 11001, 'Made Irawan', 'Laser Cut'),
(4, 10001, 'Bachrudin Lesmana', 'CNC'),
(5, 13001, 'Hadi Roesli', 'Boiler'),
(8, 14003, 'Hadi Setiyadi', 'Kelistrikan');

-- --------------------------------------------------------

--
-- Table structure for table `mesin`
--

CREATE TABLE `mesin` (
  `id_mesin` int(10) NOT NULL,
  `nama_mesin` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mesin`
--

INSERT INTO `mesin` (`id_mesin`, `nama_mesin`, `status`) VALUES
(1, 'Milling', 'Beroperasi'),
(2, 'CNC', 'Beroperasi'),
(3, 'Water Jet Cutter', 'Maintenance'),
(4, 'Boiler', 'Perawatan'),
(5, 'Laser Cutting', 'Beroperasi'),
(6, 'Mesin Bubut', 'Beroperasi'),
(9, 'Hydraulic Press:', 'Beroperasi');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(10) NOT NULL,
  `id_rm` int(10) NOT NULL,
  `id_prosedur` int(10) NOT NULL,
  `id_mesin` int(10) NOT NULL,
  `id_karyawan` int(10) NOT NULL,
  `nama_produk` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_rm`, `id_prosedur`, `id_mesin`, `id_karyawan`, `nama_produk`) VALUES
(1, 2, 1, 2, 4, 'Poros'),
(2, 1, 2, 5, 3, 'Body Mentah'),
(7, 2, 2, 4, 4, 'Body Mentah Laser Cut');

-- --------------------------------------------------------

--
-- Table structure for table `prosedur`
--

CREATE TABLE `prosedur` (
  `id_prosedur` int(10) NOT NULL,
  `nama_prosedur` varchar(50) NOT NULL,
  `deskripsi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prosedur`
--

INSERT INTO `prosedur` (`id_prosedur`, `nama_prosedur`, `deskripsi`) VALUES
(1, 'Pembubutan Poros', 'Membuat poros dengan toleransi diameter -/+ 25cm'),
(2, 'Pemotongan Sheetmetal', 'Lembar metal akan dipotong dengan menggunakan alat seperti laser cut / water jet'),
(5, 'Maintenance Produksi', 'Memperbaiki / melakukan prosedur perawatan keseluruhan jenis alat');

-- --------------------------------------------------------

--
-- Table structure for table `rawmaterial`
--

CREATE TABLE `rawmaterial` (
  `id_rm` int(10) NOT NULL,
  `nama_mtrl` varchar(50) NOT NULL,
  `stok` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rawmaterial`
--

INSERT INTO `rawmaterial` (`id_rm`, `nama_mtrl`, `stok`) VALUES
(1, 'SheetMetal 5mx5m', 100),
(2, 'Batang Besi Tebal 50cm', 50),
(5, 'SheetMetal 2.5mx2.5m ketebalan 0,74cm', 75);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id_wh` int(10) NOT NULL,
  `id_produk` int(10) NOT NULL,
  `stok` int(50) NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `nama_warehouse` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id_wh`, `id_produk`, `stok`, `deskripsi`, `nama_warehouse`) VALUES
(1, 2, 20, 'Body untuk pembuatan kabinet', 'Barang Jadi'),
(2, 1, 25, 'Poros mentah', 'Barang proses'),
(3, 2, 234, 'Batang Poros', 'Barang setengah jadi'),
(5, 2, 20, 'Body untuk pembuatan kabinet', 'Barang Setengah jadi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `mesin`
--
ALTER TABLE `mesin`
  ADD PRIMARY KEY (`id_mesin`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_rm` (`id_rm`,`id_prosedur`,`id_mesin`,`id_karyawan`),
  ADD KEY `id_prosedur` (`id_prosedur`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_mesin` (`id_mesin`);

--
-- Indexes for table `prosedur`
--
ALTER TABLE `prosedur`
  ADD PRIMARY KEY (`id_prosedur`);

--
-- Indexes for table `rawmaterial`
--
ALTER TABLE `rawmaterial`
  ADD PRIMARY KEY (`id_rm`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id_wh`),
  ADD KEY `id_produk` (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mesin`
--
ALTER TABLE `mesin`
  MODIFY `id_mesin` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prosedur`
--
ALTER TABLE `prosedur`
  MODIFY `id_prosedur` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rawmaterial`
--
ALTER TABLE `rawmaterial`
  MODIFY `id_rm` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id_wh` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_rm`) REFERENCES `rawmaterial` (`id_rm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_prosedur`) REFERENCES `prosedur` (`id_prosedur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_3` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_4` FOREIGN KEY (`id_mesin`) REFERENCES `mesin` (`id_mesin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD CONSTRAINT `warehouse_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
