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

CREATE TABLE `account` (
  `USER_ID` int(11) NOT NULL,
  `PHONE_NUMBER` varchar(20) NOT NULL UNIQUE,
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
  `BALANCE` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--

-- test user
INSERT INTO `account` (`USER_ID`, `PHONE_NUMBER`, `EMAIL`, `FULL_NAME`, `DATE_OF_BIRTH`, `ADDRESS`, `USERNAME`, `PASSWORD`, `IS_NEW_USER`, `ACTIVATED_STATE`, `FAIL_LOGIN_COUNT`, `ABNORMAL_LOGIN_COUNT`, `IS_LOCKED`, `DATE_LOCKED`, `DATE_CREATED`, `BALANCE`) VALUES
(1, '0907718480', 'bao@gmail.com', 'Lý Gia Bảo', '2022-05-02', 'Tp. Rạch Giá', '5472576450', '123456', b'0', 'chờ xác minh', 0, 0, b'0', NULL, '2025-05-22 01:26:35', 0),
(2, '25156456', 'phihung@gmail.com', 'Phi Hùng', '2022-05-11', 'TP HCM', '9766924239', 'ormowc', b'1', 'chờ xác minh', 0, 0, b'0', NULL, '2022-05-25 13:43:56', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`USER_ID`);


--
ALTER TABLE `account`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE `history` (
        `ID` int(11) not null PRIMARY KEY,
        `USER_ID` int(11) not null,
        `RECEIVER_USER_ID` int(11),
        `RECEIVER_PHONE` varchar(20),
        `AMOUNT`  float ,
        `TIME` varchar(20) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

alter table `history`
add CONSTRAINT `fk_history` FOREIGN key (`USER_ID`) REFERENCES `account`(`USER_ID`)