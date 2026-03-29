-- Database: pengaduan_sarana_sekolah_kel6
-- Dibuat untuk Uji Kompetensi Keahlian RPL 2025/2026

CREATE DATABASE IF NOT EXISTS pengaduan_sarana_sekolah_kel6_11rpl2;
USE pengaduan_sarana_sekolah_kel6_11rpl2;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin ( 
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    nama_lengkap VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Siswa
CREATE TABLE siswa (
    nis INT PRIMARY KEY,
    nama VARCHAR(35) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    email VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Aspirasi
CREATE TABLE aspirasi (
    id_aspirasi INT AUTO_INCREMENT PRIMARY KEY,
    nis INT NOT NULL,
    id_admin INT DEFAULT NULL,
    id_histori INT DEFAULT NULL,
    judul_laporan VARCHAR(100) NOT NULL,
    keterangan TEXT NOT NULL,
    kategori_prioritas ENUM('high priority', 'medium priority', 'low priority') DEFAULT 'medium priority',
    lokasi ENUM('kelas', 'toilet', 'mushola', 'lapangan', 'koridor', 'lab') NOT NULL,
    foto_gambar VARCHAR(100) DEFAULT NULL,
    tanggal_dikirim DATE NOT NULL DEFAULT CURRENT_DATE,
    status ENUM('menunggu', 'diproses', 'selesai') DEFAULT 'menunggu',
    FOREIGN KEY (nis) REFERENCES siswa(nis) ON DELETE CASCADE,
    FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Histori Aspirasi
CREATE TABLE histori_aspirasi (
    id_histori INT AUTO_INCREMENT PRIMARY KEY,
    id_aspirasi INT NOT NULL,
    status_sebelum ENUM('belum ditangani', 'dalam proses', 'selesai') NOT NULL,
    status_sesudah ENUM('belum ditangani', 'dalam proses', 'selesai') NOT NULL,
    tanggal_perubahan DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Umpan Balik
CREATE TABLE umpan_balik (
    id_UmpanBalik INT AUTO_INCREMENT PRIMARY KEY,
    id_aspirasi INT NOT NULL,
    id_admin INT NOT NULL,
    isi_UmpanBalik TEXT NOT NULL,
    tanggal DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi) ON DELETE CASCADE,
    FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Progres Laporan Aspirasi
CREATE TABLE progres_laporanaspirasi (
    id_progres INT AUTO_INCREMENT PRIMARY KEY,
    id_aspirasi INT NOT NULL,
    deskripsi_progres TEXT NOT NULL,
    foto_bukti VARCHAR(100) DEFAULT NULL,
    tanggal DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Admin Default
INSERT INTO admin (username, password, nama_lengkap, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@sekolah.com');
-- Password default: password

-- Data Siswa Contoh
INSERT INTO siswa (nis, nama, kelas, email, password) VALUES 
(12345, 'Budi Santoso', 'XII RPL 1', 'budi@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(12346, 'Ani Wulandari', 'XII RPL 2', 'ani@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Password default: password
