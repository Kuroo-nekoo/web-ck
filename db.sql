      DROP DATABASE IF EXISTS QUANLYVIDIENTU;

      CREATE DATABASE QUANLYVIDIENTU;

      USE QUANLYVIDIENTU;

      -- phpMyAdmin SQL Dump
      -- version 5.0.4
      -- https://www.phpmyadmin.net/
      --
      -- Host: 127.0.0.1
      -- Generation Time: May 24, 2022 at 07:21 AM
      -- Server version: 10.4.17-MariaDB
      -- PHP Version: 8.0.1

      SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
      START TRANSACTION;
      SET time_zone = "+00:00";


      /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
      /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
      /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
      /*!40101 SET NAMES utf8mb4 */;

      --
      -- Database: `quanlyvidientu`
      --

      -- --------------------------------------------------------

      --
      -- Table structure for table `account`
      --

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `USER_ID` int(11) NOT NULL,
  `PHONE_NUMBER` varchar(20) NOT NULL,
  `EMAIL` varchar(50) NOT NULL,
  `FULL_NAME` varchar(50) NOT NULL,
  `DATE_OF_BIRTH` date NOT NULL,
  `ADDRESS` varchar(50) NOT NULL,
  `USERNAME` varchar(10) DEFAULT NULL,
  `PASSWORD` varchar(10) DEFAULT NULL,
  `IS_NEW_USER` bit(1) DEFAULT b'1',
  `ACTIVATED_STATE` varchar(50) DEFAULT NULL,
  `FAIL_LOGIN_COUNT` int(11) DEFAULT 0,
  `ABNORMAL_LOGIN_COUNT` int(11) DEFAULT 0,
  `IS_LOCKED` bit(1) DEFAULT b'0',
  `DATE_LOCKED` datetime DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL,
  `DATE_UPDATE` datetime DEFAULT NULL,
  `BALANCE` int(11) NOT NULL,
  `FRONT_ID_IMAGE_DIR` varchar(50) DEFAULT NULL,
  `BACK_ID_IMAGE_DIR` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`USER_ID`, `PHONE_NUMBER`, `EMAIL`, `FULL_NAME`, `DATE_OF_BIRTH`, `ADDRESS`, `USERNAME`, `PASSWORD`, `IS_NEW_USER`, `ACTIVATED_STATE`, `FAIL_LOGIN_COUNT`, `ABNORMAL_LOGIN_COUNT`, `IS_LOCKED`, `DATE_LOCKED`, `DATE_CREATED`, `DATE_UPDATE`, `BALANCE`, `FRONT_ID_IMAGE_DIR`, `BACK_ID_IMAGE_DIR`) VALUES
(1, '0907718480', 'lygiabaokg2002@gmail.com', 'Lý Gia Bảo', '2022-05-02', 'Tp. Rạch Giá', '4564654655', '123456', b'0', 'đã xác minh', 0, 0, b'0', NULL, '2025-05-22 01:26:35', NULL, 0, NULL, NULL),
(2, '25156456', 'phihung@gmail.com', 'Phi Hùng', '2022-05-11', 'TP HCM', '9766924239', '123456', b'0', 'đã xác minh', 0, 0, b'0', NULL, '2022-05-25 13:43:56', NULL, 0, NULL, NULL),
(3, '123132123', 'phanhien@gmail.com', 'Phan Hiền', '2022-05-11', 'TP HCM', '4422926261', '123456', b'0', 'chờ cập nhật', 0, 0, b'0', NULL, '2022-05-29 22:45:14', NULL, 0, './uploads/kay-vogelgesang.jpg', './uploads/paul-hinz.jpg'),
(5, '4564654654', 'abc@gmail.com', 'Hải Nam', '2022-05-04', 'Kiên Giang', '5862746239', '123456', b'0', 'đã xác minh', 0, 0, b'0', NULL, '2022-05-30 02:15:51', NULL, 6000000, './uploads/kai-seidler.jpg', './uploads/daniel-lopez.png');

-- --------------------------------------------------------

--
-- Table structure for table `credit`
--

CREATE TABLE `credit` (
  `credit_id` int(6) NOT NULL,
  `expiration_date` date NOT NULL,
  `CVV` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `credit`
--

INSERT INTO `credit` (`credit_id`, `expiration_date`, `CVV`) VALUES
(111111, '2022-10-10', 411),
(222222, '2022-11-11', 443),
(333333, '2022-12-12', 577);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `RECEIVER_PHONE` varchar(20) DEFAULT NULL,
  `AMOUNT` int(11) DEFAULT NULL,
  `TIME` datetime NOT NULL,
  `IS_ALLOW` bit(1) DEFAULT b'1',
  `CONTENT` varchar(50) DEFAULT NULL,
  `TYPE` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`ID`, `USER_ID`, `RECEIVER_PHONE`, `AMOUNT`, `TIME`, `IS_ALLOW`, `CONTENT`, `TYPE`) VALUES
(1, 2, '25156456', 200000, '2022-05-30 03:11:51', b'1', 'khong có', 'transaction'),
(2, 2, '4564654654', 5000000, '2022-05-31 10:16:30', b'1', NULL, NULL),
(3, 2, '4564654654', 6000000, '2022-05-31 11:16:30', b'1', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`USER_ID`),
  ADD UNIQUE KEY `PHONE_NUMBER` (`PHONE_NUMBER`);

--
-- Indexes for table `credit`
--
ALTER TABLE `credit`
  ADD PRIMARY KEY (`credit_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_history` (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history` FOREIGN KEY (`USER_ID`) REFERENCES `account` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;