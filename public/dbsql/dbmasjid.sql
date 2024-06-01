-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for masjid
CREATE DATABASE IF NOT EXISTS `masjid` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `masjid`;

-- Dumping structure for table masjid.mm_berita_semasa
CREATE TABLE IF NOT EXISTS `mm_berita_semasa` (
  `beritaID` int unsigned NOT NULL AUTO_INCREMENT,
  `berita_titleMS` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `berita_titleEN` varchar(100) DEFAULT NULL,
  `berita_fileName` varchar(100) DEFAULT NULL,
  `berita_descriptionMS` varchar(255) DEFAULT NULL,
  `berita_descriptionEN` varchar(255) DEFAULT NULL,
  `berita_visitCount` int DEFAULT NULL,
  `berita_status` tinyint DEFAULT NULL,
  `berita_createdAt` datetime DEFAULT NULL,
  `berita_updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`beritaID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table is used to store the contents of the Berita Semasa section';

-- Dumping data for table masjid.mm_berita_semasa: ~4 rows (approximately)
INSERT IGNORE INTO `mm_berita_semasa` (`beritaID`, `berita_titleMS`, `berita_titleEN`, `berita_fileName`, `berita_descriptionMS`, `berita_descriptionEN`, `berita_visitCount`, `berita_status`, `berita_createdAt`, `berita_updatedAt`) VALUES
	(1, 'Majlis Selawat Al-Mustaghfirin', 'Al-Mustaghfirin Greetings Ceremony', 'berita_1', 'Untuk pengetahuan umum, orang ramai dijemput hadir ke \r\nProgram Selawat Al Mustaghfirin akan bermula \r\npada 31 Disember 2023 hari Ahad, \r\njam 8 hingga 11 malam.', 'For general information, the public is invited to attend\r\nThe Selawat Al Mustaghfirin program will begin\r\non 31 December 2023 Sunday,\r\n8 to 11 pm.', 98, 0, '2024-05-19 14:22:26', '2024-05-19 14:22:27'),
	(2, 'Hari Riadah Keluarga Al-Mustaghfirin', 'Al-Mustaghfirin Family Leisure Day', 'berita_2', 'Orang ramai dijemput hadir ke \r\nHari Riadah Keluarga Al-Mustaghfirin yang akan bermula \r\npada 31 Disember 2023 hari Ahad, \r\njam 8 hingga 10 pagi.', 'The public is invited to attend\r\nAl-Mustaghfirin Family Leisure Day which will start\r\non 31 December 2023 Sunday,\r\n8 to 10 am.', 10, 0, '2024-05-19 14:22:26', '2024-05-19 14:22:27'),
	(3, 'Rehlah Dakwah', 'Rehlah Da\'wah', 'berita_3', 'Orang ramai dijemput hadir ke \r\nKuliah Subuh oleh Ustaz Bashir Mohamed Al Azhari\r\npada Sabtu ini.', 'The public is invited to attend\r\nMorning lecture by Ustaz Bashir Mohamed Al Azhari\r\non this Saturday.', 10, 0, '2024-05-19 14:22:26', '2024-05-19 14:22:27'),
	(4, 'Ceramah Perdana Ustaz Kazim Elias', 'Ustaz Kazim Elias\'s Prime Ministerial Lecture', 'berita_4', 'Muslimin dan muslimat dijemput hadir ke \r\nCeramah Perdana oleh Dato\' Ustaz Kazim Elias\r\npada 7 Jun 2023 @ Rabu di Masjid Al Mustaghfirin\r\nselepas solat waktu Maghrib.', 'Muslim men and women are invited to attend\r\nKeynote speech by Dato\' Ustaz Kazim Elias\r\non June 7, 2023 @ Wednesday at Al Mustaghfirin Mosque\r\nafter Maghrib prayer.', 7, 0, '2024-05-19 14:22:26', '2024-05-19 14:22:27');

-- Dumping structure for table masjid.mm_kutipan
CREATE TABLE IF NOT EXISTS `mm_kutipan` (
  `kutipanID` int unsigned NOT NULL AUTO_INCREMENT,
  `kutipanDay` int DEFAULT NULL,
  `kutipanWeek` int DEFAULT NULL,
  `kutipanMonth` int DEFAULT NULL,
  `kutipanYear` int DEFAULT NULL,
  `kutipanDayTotal` double DEFAULT NULL,
  `kutipanWeekTotal` double DEFAULT NULL,
  `kutipanMonthTotal` double DEFAULT NULL,
  `kutipanTotal` double DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `createdBy` tinyint(1) DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `updatedBy` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`kutipanID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table summarise the total collection of donation for the masjid';

-- Dumping data for table masjid.mm_kutipan: ~20 rows (approximately)
INSERT IGNORE INTO `mm_kutipan` (`kutipanID`, `kutipanDay`, `kutipanWeek`, `kutipanMonth`, `kutipanYear`, `kutipanDayTotal`, `kutipanWeekTotal`, `kutipanMonthTotal`, `kutipanTotal`, `createdAt`, `createdBy`, `updatedAt`, `updatedBy`) VALUES
	(1, 1, 1, 1, 2024, 883, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(2, 2, 1, 1, 2024, 465, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(3, 3, 1, 1, 2024, 714, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(4, 4, 1, 1, 2024, 499, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(5, 5, 1, 1, 2024, 912, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(6, 6, 1, 1, 2024, 187, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(7, 7, 1, 1, 2024, 594, 4554, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(8, 1, 2, 1, 2024, 724, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(9, 2, 2, 1, 2024, 841, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(10, 3, 2, 1, 2024, 240, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(11, 4, 2, 1, 2024, 208, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(12, 5, 2, 1, 2024, 633, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(13, 6, 2, 1, 2024, 115, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(14, 7, 2, 1, 2024, 544, 3305, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(15, 1, 3, 1, 2024, 865, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(16, 2, 3, 1, 2024, 452, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(17, 3, 3, 1, 2024, 591, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(18, 4, 3, 1, 2024, 915, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(19, 5, 3, 1, 2024, 217, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1),
	(20, 6, 3, 1, 2024, 233, 3273, 11132, 11132, '2024-05-25 18:23:52', 1, '2024-05-25 18:23:52', 1);

-- Dumping structure for table masjid.mm_pengumuman
CREATE TABLE IF NOT EXISTS `mm_pengumuman` (
  `pengumumanID` int unsigned NOT NULL AUTO_INCREMENT,
  `pengumuman_titleMS` varchar(100) DEFAULT NULL,
  `pengumuman_titleEN` varchar(100) DEFAULT NULL,
  `pengumuman_fileName` varchar(100) DEFAULT NULL,
  `pengumuman_descriptionMS` varchar(255) DEFAULT NULL,
  `pengumuman_descriptionEN` varchar(255) DEFAULT NULL,
  `pengumuman_status` tinyint DEFAULT NULL,
  `pengumuman_createdAt` datetime DEFAULT NULL,
  `pengumuman_updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`pengumumanID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table is used to store the contents of the Pengumuman section';

-- Dumping data for table masjid.mm_pengumuman: ~4 rows (approximately)
INSERT IGNORE INTO `mm_pengumuman` (`pengumumanID`, `pengumuman_titleMS`, `pengumuman_titleEN`, `pengumuman_fileName`, `pengumuman_descriptionMS`, `pengumuman_descriptionEN`, `pengumuman_status`, `pengumuman_createdAt`, `pengumuman_updatedAt`) VALUES
	(1, 'Wakaf Pembelian Van Jenazah', 'Endowment for the Purchase of Hearse Vans', 'pengumuman_1', 'Bagi yang ingin memberikan sumbangan, boleh hubungi nombor telefon yang tertera di bawah atau bayar secara tunai di masjid.', 'For those who want to make a donation, you can call the phone number listed below or pay in cash at the mosque.', 0, '2024-05-22 22:49:03', '2024-05-22 22:49:03'),
	(2, 'Kuliah Bulanan - Pengajian Kitab  " AYYUHAL WALAD (Wahai anakku yang tercinta)', 'Monthly Lecture - Book Study " AYYUHAL WALAD (O my beloved son)', 'pengumuman_2', 'Jemput hadir ke kuliah bulanan (2 Mac 2024) Masjid Al Mustaghfirin dalam pengajian Kitab AYYUHAL WALAD yang dibawakan oleh Sheikh Mohd Nazrul', 'Invited to attend the monthly lecture (March 2, 2024) of Al Mustaghfirin Mosque in the study of the Book of AYYUHAL WALAD delivered by Sheikh Mohd Nazrul', 0, '2024-05-22 22:50:03', '2024-05-22 22:50:04'),
	(3, 'Kuliah Bulanan - Pengajian Kitab Himpunan 2 Risalah (Tasawwuf)', 'Monthly Lecture - Book Study Collection of 2 Treatises (Tasawwuf)', 'pengumuman_3', 'Jemput hadir ke kuliah bulanan ( 6 Jan 2024) Masjid Al Mustaghfirin dalam pengajian Kitab Himpunan 2 Risalah (Tasawwuf) yang dibawakan oleh Sheikh Mohd Nazrul', 'Invited to attend the monthly lecture (Jan 6, 2024) of Al Mustaghfirin Mosque in the study of Kitab Himpunan 2 Risalah (Tasawwuf) delivered by Sheikh Mohd Nazru', 0, '2024-05-22 22:50:07', '2024-05-22 22:50:08'),
	(4, 'Kuliah Asar - Ustaz Sidek Nor', 'Afternoon Lecture - Ustaz Sidek Nor', 'pengumuman_4', 'Jemput hadir ke kuliah asar (6 Jan 2024) yang dibawakan oleh Ustaz Sidek Nor', 'Invited to attend the afternoon lecture (6 Jan 2024) delivered by Ustaz Sidek Nor', 0, '2024-05-22 22:50:12', '2024-05-22 22:50:12');

-- Dumping structure for table masjid.mm_visitor
CREATE TABLE IF NOT EXISTS `mm_visitor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `visitorIP` varchar(50) DEFAULT NULL,
  `visitorCount` int DEFAULT NULL,
  `visitorDuration` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='This table count the web page visitor';

-- Dumping data for table masjid.mm_visitor: ~11 rows (approximately)
INSERT IGNORE INTO `mm_visitor` (`id`, `visitorIP`, `visitorCount`, `visitorDuration`, `created_at`, `updated_at`) VALUES
	(1, NULL, 1, 1716811561, '2024-05-26 20:06:01', '2024-05-26 20:06:01'),
	(2, NULL, 1, 1716811561, '2024-05-26 20:11:38', '2024-05-26 20:11:39'),
	(3, NULL, 1, 1716811561, '2024-05-26 20:11:38', '2024-05-26 20:29:53'),
	(4, '127.0.0.1', 1, 1716816368, '2024-05-26 21:26:08', '2024-05-26 21:26:08'),
	(5, NULL, 1, NULL, '2024-06-01 02:51:20', '2024-06-01 02:51:20'),
	(6, NULL, 1, NULL, '2024-06-01 02:51:22', '2024-06-01 02:51:22'),
	(7, NULL, 1, NULL, '2024-06-01 02:51:25', '2024-06-01 02:51:25'),
	(8, NULL, 1, NULL, '2024-06-01 02:51:25', '2024-06-01 02:51:25'),
	(9, NULL, 1, NULL, '2024-06-01 02:51:35', '2024-06-01 02:51:35'),
	(10, NULL, 1, NULL, '2024-06-01 02:51:36', '2024-06-01 02:51:36'),
	(11, NULL, 1, NULL, '2024-06-01 02:51:36', '2024-06-01 02:51:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
