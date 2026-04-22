-- ============================================================
-- Darurat Bencana - Database Schema
-- Disaster Reporting Platform
-- ============================================================

CREATE DATABASE IF NOT EXISTS `darurat_bencana` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;

USE `darurat_bencana`;

-- ------------------------------------------------------------
-- Table: users (Admin accounts)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin') NOT NULL DEFAULT 'admin',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: laporan (Disaster reports)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `laporan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_pelapor` VARCHAR(255) NOT NULL,
  `kategori` VARCHAR(100) NOT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_urgent` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  `wa_notified` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_kategori` (`kategori`),
  KEY `idx_status` (`status`),
  KEY `idx_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: kontak (WhatsApp contacts)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kontak` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(255) NOT NULL,
  `nomor_wa` VARCHAR(20) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Seed: Default admin account
-- Password: admin123 (hashed with password_hash)
-- ------------------------------------------------------------
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@darurat-bencana.com', '$2y$10$Q8ZzozZeA1jl/PGJD.pbBOsOHd1R3wJcEaZqnEmwuNQ0zif5qdC9e', 'admin');

-- ------------------------------------------------------------
-- Seed: Sample contacts
-- ------------------------------------------------------------
INSERT INTO `kontak` (`nama`, `nomor_wa`, `is_active`) VALUES
('Admin Utama', '081234567890', 1),
('Petugas BPBD', '082345678901', 1);

-- ------------------------------------------------------------
-- Seed: Sample reports for demo
-- ------------------------------------------------------------
INSERT INTO `laporan` (`nama_pelapor`, `kategori`, `lokasi`, `deskripsi`, `tanggal`, `is_urgent`, `status`) VALUES
('Ahmad Sudirman', 'Banjir', 'Jl. Merdeka No. 10, Jakarta Selatan', 'Banjir setinggi 1 meter merendam pemukiman warga. Dibutuhkan bantuan evakuasi segera.', NOW() - INTERVAL 2 DAY, 1, 'baru'),
('Siti Nurhaliza', 'Kebakaran', 'Pasar Baru, Bandung', 'Kebakaran terjadi di area pasar, api sudah menjalar ke 3 kios. Pemadam sedang menuju lokasi.', NOW() - INTERVAL 1 DAY, 1, 'diproses'),
('Budi Santoso', 'Longsor', 'Desa Sukamaju, Cianjur', 'Longsor kecil terjadi akibat hujan deras semalaman. Belum ada korban jiwa.', NOW() - INTERVAL 3 HOUR, 0, 'baru'),
('Rina Wati', 'Gempa', 'Kota Palu, Sulawesi Tengah', 'Gempa berkekuatan 5.2 SR terasa cukup kuat. Warga berhamburan keluar rumah.', NOW(), 1, 'baru'),
('Joko Widodo', 'Banjir', 'Kelurahan Kampung Melayu, Jakarta Timur', 'Air sungai Ciliwung meluap, ketinggian air 50cm di jalan utama.', NOW() - INTERVAL 5 DAY, 0, 'selesai');
