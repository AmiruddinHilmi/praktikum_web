-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Nov 2026 pada 18.32
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_alat_elektromedis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat_elektromedis`
--

CREATE TABLE `alat_elektromedis` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `tahun` year(4) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat_elektromedis`
--

INSERT INTO `alat_elektromedis` (`id`, `nama_alat`, `tahun`, `merek`, `lokasi`) VALUES
(2, 'Patient Monitor', '2024', 'Mindray', 'Ruang ICU'),
(3, 'Electrocardiograph (ECG)', '2023', 'GE Healthcare', 'Ruang Jantung'),
(4, 'Infusion Pump', '2022', 'B. Braun', 'Ruang Rawat Inap'),
(5, 'Defibrillator', '2024', 'ZOLL', 'Ruang IGD'),
(6, 'Suction Pump', '2023', 'Yuwell', 'Ruang Operasi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat_elektromedis`
--
ALTER TABLE `alat_elektromedis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat_elektromedis`
--
ALTER TABLE `alat_elektromedis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
