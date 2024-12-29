-- phpMyAdmin SQL Dump
-- Host: localhost
-- Generation Time: [Tanggal dan waktu]
-- Server version: [Versi server]
-- PHP Version: [Versi PHP]

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Membuat database 
CREATE DATABASE IF NOT EXISTS fitness_db;

-- Gunakan database yang baru dibuat
USE fitness_db;

-- --------------------------------------------------------

-- Tabel: menu_paket
CREATE TABLE `menu_paket` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT,
  `harga` DECIMAL(10,2) NOT NULL,
  `durasi` INT NOT NULL COMMENT 'Durasi dalam hari',
  `dibuat_pada` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -- Dumping data untuk tabel `menu_paket`
-- INSERT INTO `menu_paket` (`nama`, `deskripsi`, `harga`, `durasi`) VALUES
-- ('Paket A', 'Paket fitness untuk pemula', 500000, 30),
-- ('Paket B', 'Paket fitness lanjutan', 750000, 60);

-- --------------------------------------------------------

-- CREATE TABLE IF NOT EXISTS `staff` (
--   `id` INT AUTO_INCREMENT PRIMARY KEY,
--   `nama` VARCHAR(100) NOT NULL,
--   `posisi` ENUM('Admin', 'Trainer', 'Staff') NOT NULL,
--   `email` VARCHAR(100) UNIQUE,
--   `telepon` VARCHAR(15),
--   `alamat` TEXT,
--   `tanggal_lahir` DATE,
--   `foto_profil` VARCHAR(255),
--   `gaji` DECIMAL(10,2) DEFAULT NULL,
--   `status` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
--   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--   `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -- Dumping data untuk tabel `menu_staff`
-- INSERT INTO `menu_staff` (`nama`, `posisi`) VALUES
-- ('Elang Terbang', 'Staff'),
-- ('Ikhsan Setiaji', 'Admin');
CREATE TABLE staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE staff_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Hadir', 'Tidak Hadir', 'Izin', 'Sakit') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE
) ENGINE=InnoDB;
-- --------------------------------------------------------

-- Tabel: login
CREATE TABLE `login` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -- Dumping data untuk tabel `login`
-- INSERT INTO `login` (`username`, `password`) VALUES
-- ('admin', MD5('123456')),
-- ('staff', MD5('staff123'));

-- --------------------------------------------------------
-- Tabel: member
CREATE TABLE `member` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `nomor_telepon` VARCHAR(20),
  `menu_paket_id` INT NOT NULL,
  `tanggal_pendaftaran` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `tanggal_berakhir` DATETIME NULL,
  `status` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  FOREIGN KEY (`menu_paket_id`) REFERENCES `menu_paket`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- --------------------------------------------------------
CREATE TABLE `menu_jadwal_latihan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `pelatih_id` INT NOT NULL,        -- Relasi ke tabel pelatih
    `member_id` INT NOT NULL,         -- Relasi ke tabel member
    `nama_sesi` VARCHAR(100) NOT NULL, -- Nama sesi latihan
    `deskripsi` TEXT,                  -- Deskripsi sesi latihan (opsional)
    `waktu_mulai` DATETIME NOT NULL,  -- Waktu mulai sesi
    `waktu_selesai` DATETIME NOT NULL, -- Waktu selesai sesi
    `lokasi` VARCHAR(255),            -- Lokasi tempat latihan
    `status` ENUM('Dijadwalkan', 'Selesai', 'Dibatalkan') DEFAULT 'Dijadwalkan', -- Status sesi
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_jadwal_pelatih` FOREIGN KEY (`pelatih_id`) REFERENCES `pelatih` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_jadwal_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE `menu_kehadiran_member` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,          -- Relasi ke tabel member
    `jadwal_id` INT NOT NULL,          -- Relasi ke tabel jadwal
    `status_kehadiran` ENUM('Hadir', 'Tidak Hadir', 'Izin') DEFAULT 'Tidak Hadir', -- Status kehadiran
    `catatan` TEXT,                    -- Catatan tambahan (opsional)
    `waktu_check_in` DATETIME DEFAULT NULL, -- Waktu member check-in
    `waktu_check_out` DATETIME DEFAULT NULL, -- Waktu member check-out
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_kehadiran_member` FOREIGN KEY (`member_id`) REFERENCES `member`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_kehadiran_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `menu_jadwal_latihan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- CREATE TABLE `kehadiran_staff` (
--     `id` INT AUTO_INCREMENT PRIMARY KEY,
--     `staff_id` INT NOT NULL,
--     `tanggal` DATE NOT NULL,
--     `status_kehadiran` ENUM('Hadir', 'Izin', 'Tidak Hadir') DEFAULT 'Tidak Hadir',
--     `waktu_check_in` DATETIME DEFAULT NULL,
--     `waktu_check_out` DATETIME DEFAULT NULL,
--     `catatan` TEXT,
--     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     CONSTRAINT `fk_kehadiran_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff`(`id`) ON DELETE CASCADE,
--     UNIQUE (`staff_id`, `tanggal`) -- Validasi unik
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembayaran_member` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,  -- Menghubungkan dengan tabel member
    `jumlah_pembayaran` DECIMAL(10, 2) NOT NULL,  -- Jumlah pembayaran
    `metode_pembayaran` ENUM('Tunai', 'Transfer', 'Kartu Kredit', 'E-Wallet') NOT NULL,  -- Metode pembayaran
    `tanggal_pembayaran` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Tanggal dan waktu pembayaran
    `status_pembayaran` ENUM('Lunas', 'Belum Lunas', 'Dibatalkan') DEFAULT 'Belum Lunas',  -- Status pembayaran
    `deskripsi` TEXT,  -- Keterangan tambahan, misalnya alasan pembatalan
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Waktu entri data
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Waktu pembaruan data
    FOREIGN KEY (`member_id`) REFERENCES `member`(`id`) ON DELETE CASCADE  -- Relasi dengan tabel member
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saran_dan_masukan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT,  -- Menghubungkan dengan tabel member (opsional, jika ingin menghubungkan dengan member)
    `nama_pengguna` VARCHAR(100) NOT NULL,  -- Nama pengguna yang memberikan saran
    `email_pengguna` VARCHAR(100),  -- Email pengguna (opsional)
    `isi_masukan` TEXT NOT NULL,  -- Isi saran atau masukan
    `status` ENUM('Diterima', 'Tindak Lanjut', 'Selesai') DEFAULT 'Diterima',  -- Status tindak lanjut masukan
    `tanggal_masukan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Waktu saran diberikan
    `tanggapan_admin` TEXT,  -- Tanggapan dari admin atau pengelola gym
    `tanggal_tanggapan` TIMESTAMP,  -- Waktu tanggapan diberikan
    FOREIGN KEY (`member_id`) REFERENCES `member`(`id`) ON DELETE SET NULL  -- Relasi opsional dengan tabel member
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) DEFAULT NULL,
    oauth_provider ENUM('google', 'facebook', 'apple', 'local') DEFAULT 'local',
    oauth_id VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ALTER TABLE users ADD CONSTRAINT unique_email UNIQUE (email);
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- AUTO_INCREMENT untuk tabel yang sudah ada
ALTER TABLE `login`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `member`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;
