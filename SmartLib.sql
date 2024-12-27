-- Menghapus database jika sudah ada
DROP DATABASE IF EXISTS SmartLib_ITH;

-- Membuat database baru
CREATE DATABASE SmartLib_ITH;

-- Menggunakan database yang baru dibuat
USE SmartLib_ITH;

-- Tabel untuk data pengunjung
CREATE TABLE IF NOT EXISTS pengunjung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    prodi VARCHAR(100) NOT NULL
);

-- Tabel untuk data peminjaman buku
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    judul_buku VARCHAR(255) NOT NULL,
    kode_buku VARCHAR(50) NOT NULL,
    tgl_peminjaman TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk data pengembalian buku
CREATE TABLE IF NOT EXISTS pengembalian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    judul_buku VARCHAR(255) NOT NULL,
    kode_buku VARCHAR(50) NOT NULL,
    tgl_pengembalian TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk data anggota
CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    prodi VARCHAR(100) NOT NULL,
    email VARCHAR(255) DEFAULT NULL
);
