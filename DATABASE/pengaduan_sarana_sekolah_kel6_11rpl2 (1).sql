-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260130.4d5432a85e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 09, 2026 at 02:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan_sarana_sekolah_kel6_11rpl2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(112, 'Rezka', '$2y$12$UYk9H4C6kMHdZwhMJQj7IO7jGCr./6dRIrjC20V17HwNF5KRUMbMe', 'Rezka Parsha', 'rezkapasha@gmail.com', '2026-02-28 17:23:10'),
(113, 'parsha', '$2y$12$ojx7YKHBc.nJdHdI53TR/OEWjPsimbyKZt8d/baM0PoavkgsuGYQu', 'parsha aqeela', 'parsha@gmail.com', '2026-02-28 17:39:18'),
(114, 'ADMIN 1', '$2y$12$YYSXHWu9o5jg.5o9OOFN2upRrm6DhDthYStPrSTgaQX9f5elyPIxG', 'ADMIN 1', 'ADMIN1@gmail.com', '2026-03-25 01:33:51'),
(115, 'ADMIN 2', '$2y$12$3E81tN7ibVmxQ216lc32cOBJd.ZZBwN9ziStu5jWHRQnClzgWsw82', 'ADMIN 2', 'ADMIN2@gmail.com', '2026-03-25 01:34:25'),
(116, 'ADMIN 3', '$2y$12$7Tpo5ZWBN3MjZJpzy4vRSuTrnFPLK5dGY41AHxaKJw3uJNqn1DinO', 'ADMIN 3', 'ADMIN3@gmail.com', '2026-03-25 01:34:43'),
(117, 'ADMIN 4', '$2y$12$smDrI5Q2q0RV1jTCQdRe/..ZzR/JOWvB8QsOQtiKH3RZKFDZ4duFm', 'ADMIN 4', 'ADMIN4@gmail.com', '2026-03-25 01:35:07'),
(118, 'ADMIN 5', '$2y$12$bLnjtmS/VCd5DVwt2oaraemafTOXztcho09kxhfDG5yt92zBMklBm', 'ADMIN 5', 'ADMIN5@gmail.com', '2026-03-25 01:35:31'),
(119, 'ADMIN 6', '$2y$12$UssYBvsDeGhZYKV8dxqj4e6pVhpULPLXRbneTKZFmqZ0stTlvJtxq', 'ADMIN 6', 'ADMIN6@gmail.com', '2026-03-25 01:35:52'),
(120, 'ADMIN 7', '$2y$12$MpvcjeJ/rBkKLtAqVxsXJu7K4jTO6P0LT6sFEJLkIL7iq9fW2L.vm', 'ADMIN 7', 'ADMIN7@gmail.com', '2026-03-25 01:36:11'),
(121, 'ADMIN 8', '$2y$12$LahvRJQrcTh2JKIVmJPnouT3GRLitgEsMawYydhO8wYHU0AeaiNVu', 'ADMIN 8', 'ADMIN8@gmail.com', '2026-03-25 01:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `nis` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `id_histori` int DEFAULT NULL,
  `judul_laporan` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `kategori_prioritas` enum('high priority','medium priority','low priority') DEFAULT 'medium priority',
  `lokasi` enum('kelas','toilet','mushola','lapangan','koridor','lab') NOT NULL,
  `foto_gambar` varchar(100) DEFAULT NULL,
  `tanggal_dikirim` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('menunggu','diproses','selesai') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `nis`, `id_admin`, `id_histori`, `judul_laporan`, `keterangan`, `kategori_prioritas`, `lokasi`, `foto_gambar`, `tanggal_dikirim`, `status`) VALUES
(1, 123, NULL, NULL, 'wifi lemot', 'wifi di kelas sangat lemot susah untuk belajar', 'low priority', 'kelas', NULL, '2026-02-28 17:00:00', 'selesai'),
(2, 999, 112, NULL, 'bangku di kelas 11 rpl 1 rusak', 'bangku atau kursi di kelas 11 rpl 1 sudah rusak dan menggagu kenyamanan belajar ', 'medium priority', 'kelas', NULL, '2026-02-28 17:00:00', 'diproses'),
(4, 101, NULL, NULL, 'plafon rusak', 'adanya bagian plafon yang kosong di bagian belakang kelas XI RPL 2  , sehingga membuka celah ventilasi dan dapat menyebabkan kebocoran atau rembesan air', 'medium priority', 'kelas', '1774540442_plafon.jpeg', '2026-03-25 17:00:00', 'menunggu'),
(6, 2008, NULL, NULL, 'kunci kamar mandi rusak', 'terdapat kunci pintu kamar mandi sekolah yang rusak dan tidak berfungsi dengan baik, sehingga pintu tidak dapat dikunci dengan aman dan berpotensi mengganggu kenyamanan serta privasi pengguna', 'medium priority', 'kelas', '1774541385_kunci kamar mandi.jpeg', '2026-03-25 17:00:00', 'menunggu'),
(8, 888, NULL, NULL, 'Tanaman yang tidak terawat', 'Terdapat tanaman di lingkungan sekolah yang sudah tidak terawat, dengan kondisi batang kering serta pertumbuhan tidak terkontrol sehingga mengganggu keindahan lingkungan sekolah', 'medium priority', 'koridor', '1774759301_tanaman.jpeg', '2026-03-28 17:00:00', 'menunggu'),
(9, 2008, NULL, NULL, 'Ring basket rusak', 'terdapat satu ring basket di lapangan sekolah yang mengalami kerusakan , terlihat pada bagian ring dan jaring yang tidak layak pakai , sehingga tidak dapat digunakan secara optimal', 'medium priority', 'lapangan', '1774759550_ring basket.jpeg', '2026-03-28 17:00:00', 'menunggu'),
(10, 888, NULL, NULL, 'Lampu Kelas Rusak ', 'Lampu penerangan di ruang kelas dalam kondisi rusak dan tidak berfungsi dengan baik, sehingga pencahayaan kelas menjadi kurang dan mengganggu proses kegitan belajar dan mengajar', 'medium priority', 'kelas', '1774759783_lampu kelas.jpeg', '2026-03-28 17:00:00', 'menunggu'),
(11, 1000, NULL, NULL, 'CCTV kelas rusak', 'CCTV yang terpasang di sudut ruang kelas XI RPL 2 dalam kondisi rusak dan tidak berfungsi sehingga pengawasan serta keamanan di lingkungan kelas menjadi tidak optimal ', 'high priority', 'kelas', '1774760278_CCTV.jpeg', '2026-03-28 17:00:00', 'menunggu'),
(12, 1000, NULL, NULL, 'ventilasi kelas rusak', 'terdapat ventilasi udara di kelas XI RPL 2 yang rusak', 'medium priority', 'kelas', '1774760431_pentilasi kelas.jpeg', '2026-03-28 17:00:00', 'menunggu'),
(13, 60407, 112, NULL, 'tiang volly rusak', 'Terdapat tiang volly yang di lapangan sekolah yang sudah miring ,berkarat, dan tidak layak pakai', 'medium priority', 'lapangan', '1774760574_tiang volly.jpeg', '2026-03-28 17:00:00', 'diproses'),
(15, 456, NULL, NULL, 'kipas rusak', 'kipas di kelas xi rpl 2 rusak dan tidak bisa digunakan', 'medium priority', 'kelas', '1775008086_LOGO_MySarPrass.jpeg', '2026-03-31 17:00:00', 'menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `histori_aspirasi`
--

CREATE TABLE `histori_aspirasi` (
  `id_histori` int NOT NULL,
  `id_aspirasi` int NOT NULL,
  `status_sebelum` enum('belum ditangani','dalam proses','selesai') NOT NULL,
  `status_sesudah` enum('belum ditangani','dalam proses','selesai') NOT NULL,
  `tanggal_perubahan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `histori_aspirasi`
--

INSERT INTO `histori_aspirasi` (`id_histori`, `id_aspirasi`, `status_sebelum`, `status_sesudah`, `tanggal_perubahan`) VALUES
(1, 1, 'belum ditangani', 'dalam proses', '2026-02-28 17:00:00'),
(2, 1, 'dalam proses', 'dalam proses', '2026-02-28 17:00:00'),
(3, 1, 'dalam proses', 'selesai', '2026-02-28 17:00:00'),
(4, 2, 'belum ditangani', 'dalam proses', '2026-02-28 17:00:00'),
(5, 13, 'belum ditangani', 'dalam proses', '2026-03-29 17:00:00'),
(6, 13, 'dalam proses', 'dalam proses', '2026-03-29 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `progres_laporanaspirasi`
--

CREATE TABLE `progres_laporanaspirasi` (
  `id_progres` int NOT NULL,
  `id_aspirasi` int NOT NULL,
  `deskripsi_progres` text NOT NULL,
  `foto_bukti` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `progres_laporanaspirasi`
--

INSERT INTO `progres_laporanaspirasi` (`id_progres`, `id_aspirasi`, `deskripsi_progres`, `foto_bukti`, `tanggal`) VALUES
(1, 2, 'laporan anda sedang di proses', NULL, '2026-02-28 17:00:00'),
(2, 2, 'laporan anda sedang di proses ', NULL, '2026-02-28 17:00:00'),
(3, 2, 'contoh laporan anda sedang di proses terimakasih ', 'progres_1773594174_WhatsApp Image 2025-11-09 at 11.21.32_d078dc30.jpg', '2026-03-15 17:00:00'),
(4, 13, 'laporan anda sedang di proses oleh petugas kami  ', NULL, '2026-03-29 17:00:00'),
(5, 13, 'terima kasih sudah mengirim laporan , laporan anda sedang di proses untuk pengerjaan oleh petugas kami', NULL, '2026-03-29 17:00:00'),
(6, 1, 'siap malvien', NULL, '2026-03-29 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` int NOT NULL,
  `nama` varchar(35) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama`, `kelas`, `email`, `password`, `created_at`) VALUES
(101, 'ALFI ALFIANSYAH', '11 rpl 2', 'alfi@gmail.com', '$2y$12$.NBKIwvvy4gxV/iQWd1DY.uswubl4eUzZOSud.jNn8xSVGmpuyJPW', '2026-03-25 01:22:04'),
(123, 'M. MALVIEN RAMADHAN SULEIMAN', '11 rpl 2', 'malvien@gmail.com', '$2y$12$n/EkamUsX/YavkSl6ut16.pbbXHPHDJ.JQ6HfgTIVlOBchEh7mSu2', '2026-02-28 16:55:33'),
(159, 'hendra', 'XII RPL 3', 'kei@gmail.com', '$2y$12$TnOWWa1maitBgJuOvwBSj.GSdDMOXGCd8q3ydPvFnf0pfsmwrktyO', '2026-03-16 14:24:52'),
(456, 'ARIFKHY CAHAYA S', 'XI RPL 2', 'arif@gmail.com', '$2y$12$fPiwJEoAnkvOB3V48SLfSerDdsZRDZqlH5Aw3wm1HgyXWokRr9Mb.', '2026-04-01 01:43:48'),
(888, 'NOVA MAULANA', '11 rpl 2', 'nova@gmail.com', '$2y$12$iPF.hZylMQrp2I82sAHMTuun/GRNIl.zUhD0w7.CCLwwwEPZjo12u', '2026-02-28 17:35:34'),
(999, 'agus suhendar', '12 rpl 1', 'agus@gmail.com', '$2y$12$1N5pCQIzCqSvmfngeMPgWu84onYJW1nC9KZ2u0Tqg1qOjWe4ye/gy', '2026-02-28 17:29:53'),
(1000, 'REHAN NUR IKHSAN', 'XII RPL 2', 'rehannur@gmail.com', '$2y$12$3sfRloRwKY8jchqSHUU7Tee8hXeiEOAqi3kbiSUeg2be7Vx7HxT8e', '2026-03-25 01:27:29'),
(2008, 'MUHAMMAD EKA SANTIKA', '11 rpl 2', 'eka@gmail.com', '$2y$12$Tqwv9gjbV8KVaqPBOnxo0uAZY1rT6zmHk3rmJpfW4Hr/KluLEm9ie', '2026-03-25 01:25:06'),
(12345, 'MUHAMMAD RESKY ADITYA', 'XII RPL 1', 'titiw@email.com', '$2y$12$1xfHoumdjwBop9DA85YzS.P0vxrqKxqVgbb2teDRytQ4oLWOAj7QS', '2026-02-28 16:50:30'),
(12346, 'Ani Wulandari', 'XII RPL 2', 'ani@email.com', '$2y$12$yPR7w7lC7DS8XZmZ7MIzgeFbJyus89ORpIqKCJGBrS6kdb/PjpCNW', '2026-02-28 16:50:30'),
(60407, 'REZKA PARSHA AQEELA', '11 rpl 2', 'rezkapashaaqeela@gmail.com', '$2y$12$mJILCmowas1QT/Ma8DOo8Ozv7czv29hjrZujCHToFtzGhnQq/P3Qi', '2026-03-25 01:23:13'),
(93393768, 'HILMAN NUR MAULANA', 'XI RPL 2', 'maul@gmail.com', '$2y$12$BOTFRdxs9KZQ9fzpTceGy.H9bCCyrqplp3GDzX4lPjgnFhDfLS3be', '2026-04-01 01:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `umpan_balik`
--

CREATE TABLE `umpan_balik` (
  `id_UmpanBalik` int NOT NULL,
  `id_aspirasi` int NOT NULL,
  `id_admin` int NOT NULL,
  `isi_UmpanBalik` text NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `umpan_balik`
--

INSERT INTO `umpan_balik` (`id_UmpanBalik`, `id_aspirasi`, `id_admin`, `isi_UmpanBalik`, `tanggal`) VALUES
(2, 2, 112, 'terimkasih', '2026-02-28 17:00:00'),
(4, 13, 112, 'TERIMA KASIH SUDAH MENGIRIMKAN ASPIRASI/LAPORAN ANDA MOHON TUNGGU PETUGAS UNTUK MENGATASI LAPORAN ANDA \r\n\r\n🙏-\"REZKA ADMIN\"', '2026-03-29 17:00:00'),
(5, 1, 112, 'laporan anda sudah diselesaikan, terimakasih sudah melaporkan ', '2026-03-31 17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `fk_histori` (`id_histori`);

--
-- Indexes for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  ADD PRIMARY KEY (`id_histori`),
  ADD KEY `id_aspirasi` (`id_aspirasi`);

--
-- Indexes for table `progres_laporanaspirasi`
--
ALTER TABLE `progres_laporanaspirasi`
  ADD PRIMARY KEY (`id_progres`),
  ADD KEY `id_aspirasi` (`id_aspirasi`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  ADD PRIMARY KEY (`id_UmpanBalik`),
  ADD KEY `id_aspirasi` (`id_aspirasi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  MODIFY `id_histori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `progres_laporanaspirasi`
--
ALTER TABLE `progres_laporanaspirasi`
  MODIFY `id_progres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  MODIFY `id_UmpanBalik` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_histori` FOREIGN KEY (`id_histori`) REFERENCES `histori_aspirasi` (`id_histori`) ON DELETE SET NULL;

--
-- Constraints for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  ADD CONSTRAINT `histori_aspirasi_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE CASCADE;

--
-- Constraints for table `progres_laporanaspirasi`
--
ALTER TABLE `progres_laporanaspirasi`
  ADD CONSTRAINT `progres_laporanaspirasi_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE CASCADE;

--
-- Constraints for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  ADD CONSTRAINT `umpan_balik_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `umpan_balik_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
