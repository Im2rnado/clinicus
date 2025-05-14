-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 07:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinicus`
--

-- --------------------------------------------------------
-- User type table
CREATE TABLE `usertype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserType` varchar(255) NOT NULL,
  `RoleId` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Updated users table
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL UNIQUE,
  `Password` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `DOB` date NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Role` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`Role`) REFERENCES `usertype`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Patients table
CREATE TABLE `patients` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `HasHistory` BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`UserId`) REFERENCES `users`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Doctors table
CREATE TABLE `doctors` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `Specialization` varchar(255) NOT NULL,
  `YearsOfExperience` int NOT NULL,
  `Rating` int,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`UserId`) REFERENCES `users`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Staff table
CREATE TABLE `staff` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `RoleDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`UserId`) REFERENCES `users`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Appointments table
CREATE TABLE `appointments` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `PatientId` int(11) NOT NULL,
  `DoctorId` int(11) NOT NULL,
  `AppointmentDate` datetime NOT NULL,
  `Status` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`PatientId`) REFERENCES `patients`(`Id`) ON DELETE CASCADE,
  FOREIGN KEY (`DoctorId`) REFERENCES `doctors`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Medical History table
CREATE TABLE `medical_history` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `PatientId` int(11) NOT NULL,
  `Diagnosis` varchar(255) NOT NULL,
  `Treatment` varchar(255) NOT NULL,
  `RecordDate` date NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`PatientId`) REFERENCES `patients`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Medications table
CREATE TABLE `medications` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `SideEffects` varchar(255),
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Prescription table
CREATE TABLE `prescriptions` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `PatientId` int(11) NOT NULL,
  `DoctorId` int(11) NOT NULL,
  `MedicationId` int(11) NOT NULL,
  `Dosage` varchar(255) NOT NULL,
  `Instructions` varchar(255) NOT NULL,
  `DatePrescribed` date NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`PatientId`) REFERENCES `patients`(`Id`) ON DELETE CASCADE,
  FOREIGN KEY (`DoctorId`) REFERENCES `doctors`(`Id`) ON DELETE CASCADE,
  FOREIGN KEY (`MedicationId`) REFERENCES `medications`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Storage table
CREATE TABLE `storage` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Capacity` int NOT NULL,
  `CurrentStock` int NOT NULL,
  `LastRefilledDate` date,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Audit Logs table
CREATE TABLE `audit_logs` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `Action` varchar(255) NOT NULL,
  `Timestamp` datetime NOT NULL,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`UserId`) REFERENCES `users`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;