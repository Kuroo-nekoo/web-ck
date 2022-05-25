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
  `BALANCE` double DEFAULT 0,
  `USERNAME` varchar(10) DEFAULT NULL,
  `PASSWORD` varchar(10) DEFAULT NULL,
  `IS_NEW_USER` bit(1) DEFAULT b'0',
  `ACTIVATED_STATE` varchar(50),
  `FAIL_LOGIN_COUNT` int(11) DEFAULT 0,
  `ABNORMAL_LOGIN_COUNT` int(11) DEFAULT 0,
  `IS_LOCKED` bit(1) DEFAULT b'1',
  `DATE_CREATED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `history` (
        `ID` int(11) not null,
        `RECEIVER_USER` varchar(10),
        `RECEIVER_PHONE` varchar(20),
        `AMOUNT`  float ,
        `TIME` varchar(20) NOT NULL
      )
--

--
ALTER TABLE `account`
  ADD KEY `USER_ID` (`USER_ID`);

ALTER TABLE `history`
  ADD KEY `ID` (`ID`);
--
ALTER TABLE `account`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

ALTER TABLE `history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
