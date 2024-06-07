-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2024 at 07:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutortap`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminId` int(16) NOT NULL,
  `namaAdmin` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chatId` int(16) NOT NULL,
  `senderId` int(16) NOT NULL,
  `receiverId` int(16) NOT NULL,
  `pesan` varchar(1024) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `chatRoomId` int(16) NOT NULL,
  `userId` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_rating`
--

CREATE TABLE `class_rating` (
  `ratingId` int(16) NOT NULL,
  `userId` int(16) NOT NULL,
  `classRatingResultId` int(16) NOT NULL,
  `rating` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_rating_result`
--

CREATE TABLE `class_rating_result` (
  `classRatingResultId` int(16) NOT NULL,
  `totalRatingCount` int(11) NOT NULL,
  `totalRatingSum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complain`
--

CREATE TABLE `complain` (
  `complainId` int(16) NOT NULL,
  `complainMessage` varchar(1024) NOT NULL,
  `complainPicture` varchar(1024) NOT NULL,
  `adminId` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `idKelas` int(16) NOT NULL,
  `namaKelas` varchar(200) NOT NULL,
  `hargaKelas` int(64) NOT NULL,
  `durasiKelas` varchar(64) NOT NULL,
  `statusKelas` int(5) NOT NULL,
  `deskripsiKelas` varchar(1024) NOT NULL,
  `fotoKelas` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `idOrder` int(16) NOT NULL,
  `idUser` int(16) NOT NULL,
  `idClass` int(16) NOT NULL,
  `tanggalOrder` date NOT NULL,
  `jumlahDurasi` int(255) NOT NULL,
  `catatanOrder` varchar(1024) NOT NULL,
  `statusOrder` int(5) NOT NULL,
  `subtotalOrder` int(64) NOT NULL,
  `vaOrder` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(16) NOT NULL,
  `nama` varchar(1024) NOT NULL,
  `tanggalLahir` date NOT NULL,
  `biografi` varchar(2048) NOT NULL,
  `profilePicture` varchar(1024) NOT NULL,
  `lokasi` varchar(1024) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `profesi` varchar(1024) NOT NULL,
  `noRek` varchar(64) NOT NULL,
  `saldo` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rating`
--

CREATE TABLE `user_rating` (
  `ratingId` int(16) NOT NULL,
  `pemberiId` int(16) NOT NULL,
  `penerimaId` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rating_result`
--

CREATE TABLE `user_rating_result` (
  `userRatingResultId` int(16) NOT NULL,
  `totalRatingValue` int(4) NOT NULL,
  `totalRatingCount` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chatId`),
  ADD KEY `receiverId` (`receiverId`),
  ADD KEY `senderId` (`senderId`);

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`chatRoomId`),
  ADD KEY `check` (`userId`);

--
-- Indexes for table `class_rating`
--
ALTER TABLE `class_rating`
  ADD PRIMARY KEY (`ratingId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `classRatingResultId` (`classRatingResultId`);

--
-- Indexes for table `class_rating_result`
--
ALTER TABLE `class_rating_result`
  ADD PRIMARY KEY (`classRatingResultId`);

--
-- Indexes for table `complain`
--
ALTER TABLE `complain`
  ADD PRIMARY KEY (`complainId`),
  ADD KEY `adminId` (`adminId`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`idKelas`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idClass` (`idClass`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `user_rating`
--
ALTER TABLE `user_rating`
  ADD PRIMARY KEY (`ratingId`),
  ADD KEY `pemberiId` (`pemberiId`),
  ADD KEY `penerimaId` (`penerimaId`);

--
-- Indexes for table `user_rating_result`
--
ALTER TABLE `user_rating_result`
  ADD PRIMARY KEY (`userRatingResultId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chatId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `chatRoomId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_rating`
--
ALTER TABLE `class_rating`
  MODIFY `ratingId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_rating_result`
--
ALTER TABLE `class_rating_result`
  MODIFY `classRatingResultId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complain`
--
ALTER TABLE `complain`
  MODIFY `complainId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `idKelas` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `idOrder` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_rating`
--
ALTER TABLE `user_rating`
  MODIFY `ratingId` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_rating_result`
--
ALTER TABLE `user_rating_result`
  MODIFY `userRatingResultId` int(16) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `receiverId` FOREIGN KEY (`receiverId`) REFERENCES `chat_room` (`chatRoomId`),
  ADD CONSTRAINT `senderId` FOREIGN KEY (`senderId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `check` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `class_rating`
--
ALTER TABLE `class_rating`
  ADD CONSTRAINT `classRatingResultId` FOREIGN KEY (`classRatingResultId`) REFERENCES `class_rating_result` (`classRatingResultId`),
  ADD CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `complain`
--
ALTER TABLE `complain`
  ADD CONSTRAINT `adminId` FOREIGN KEY (`adminId`) REFERENCES `admin` (`adminId`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `idClass` FOREIGN KEY (`idClass`) REFERENCES `kelas` (`idKelas`),
  ADD CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `user` (`userId`);

--
-- Constraints for table `user_rating`
--
ALTER TABLE `user_rating`
  ADD CONSTRAINT `pemberiId` FOREIGN KEY (`pemberiId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `penerimaId` FOREIGN KEY (`penerimaId`) REFERENCES `user_rating_result` (`userRatingResultId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
