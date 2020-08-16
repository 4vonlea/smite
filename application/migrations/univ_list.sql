-- --------------------------------------------------------
-- Host:                         10.1.10.202
-- Server version:               5.7.29 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table simari.bk_detail_kuisioner
CREATE TABLE IF NOT EXISTS `fkg_bk_detail_kuisioner` (
  `id_formulir` varchar(16) DEFAULT NULL,
  `id_kuisioner` varchar(6) DEFAULT NULL,
  `isi` text,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id_kuisionerfkg` (`id_kuisioner`),
  KEY `bk_detail_kuisioner_ibfk_3fkg` (`id_formulir`),
  CONSTRAINT `bk_detail_kuisioner_ibfk_2fkg` FOREIGN KEY (`id_kuisioner`) REFERENCES `fkg_bk_kuisioner_formulir` (`id_kuisioner`),
  CONSTRAINT `bk_detail_kuisioner_ibfk_3fkg` FOREIGN KEY (`id_formulir`) REFERENCES `fkg_bk_rekam_konseling` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table simari.bk_kuisioner_formulir
CREATE TABLE IF NOT EXISTS `fkg_bk_kuisioner_formulir` (
  `id_kuisioner` varchar(6) NOT NULL,
  `kuisioner` varchar(300) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kuisioner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table simari.bk_pertemuan
CREATE TABLE IF NOT EXISTS `fkg_bk_pertemuan` (
  `id_pertemuan` int(11) NOT NULL AUTO_INCREMENT,
  `pertemuan` varchar(3) DEFAULT NULL,
  `id_formulir` varchar(16) DEFAULT NULL,
  `tgl_konseling` date DEFAULT NULL,
  `waktu_konseling` time DEFAULT NULL,
  `tahun_konsul` year(4) DEFAULT NULL,
  `kasus` varchar(400) DEFAULT NULL,
  `evaluasi` varchar(400) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pertemuan`),
  KEY `bk_pertemuan_ibfk_1fkg` (`id_formulir`),
  CONSTRAINT `bk_pertemuan_ibfk_1fkg` FOREIGN KEY (`id_formulir`) REFERENCES `fkg_bk_rekam_konseling` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table simari.bk_rekam_konseling
CREATE TABLE IF NOT EXISTS `bk_rekam_konseling` (
  `no_formulir` varchar(16) NOT NULL,
  `tgl_formulir` date DEFAULT NULL,
  `id_user` varchar(40) DEFAULT NULL,
  `id_admin` varchar(40) DEFAULT '-',
  `id_konselor` varchar(50) DEFAULT '-',
  `status` enum('Disetujui','Menunggu Konfirmasi','Atur Ulang Jadwal') DEFAULT 'Menunggu Konfirmasi',
  `keterangan` text,
  `batas_keterangan` varchar(1) DEFAULT NULL,
  `status_konseling` enum('Selesai','Berlanjut','Alih Tangan') DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_formulir`),
  KEY `bk_rekam_konseling_ibfk_2fkg` (`id_user`),
  KEY `id_konselorfkg` (`id_konselor`),
  CONSTRAINT `bk_rekam_konseling_ibfk_2fkg` FOREIGN KEY (`id_user`) REFERENCES `sia_m_mahasiswa` (`mhsNiu`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
