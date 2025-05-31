-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 31, 2025 at 01:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinicus_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `Id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`Id`, `name`, `createdAt`, `updatedAt`) VALUES
(1, '15 Tahrir Square, Downtown Cairo', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, '28 Zamalek Street, Zamalek, Cairo', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, '45 Nasr City, New Cairo', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, '12 Alexandria Road, Giza', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, '33 Heliopolis, Cairo', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, '67 Maadi, Cairo', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, '89 Dokki, Giza', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, '23 6th October City', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(9, 'Zahraa El Maadi, Cairo', '2025-05-27 23:42:34', '2025-05-27 23:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `ID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `appointmentDate` datetime NOT NULL,
  `status` int(11) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`ID`, `DoctorID`, `userID`, `appointmentDate`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 1, 2, '2025-05-26 10:00:00', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 7, '2025-05-27 14:30:00', 0, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, 8, '2025-05-28 09:15:00', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 1, 2, '2025-05-29 11:00:00', 0, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 2, 7, '2025-05-30 16:00:00', 0, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_details`
--

CREATE TABLE `appointment_details` (
  `ID` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `pricePerUnit` decimal(10,2) DEFAULT NULL,
  `totalPrice` decimal(10,2) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment_details`
--

INSERT INTO `appointment_details` (`ID`, `appointmentID`, `serviceID`, `quantity`, `pricePerUnit`, `totalPrice`, `createdAt`, `updatedAt`) VALUES
(1, 1, 1, 1, 200.00, 200.00, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 1, 2, 1, 150.00, 150.00, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 2, 1, 1, 200.00, 200.00, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 3, 3, 1, 300.00, 300.00, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 4, 8, 1, 120.00, 120.00, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `blood_type`
--

CREATE TABLE `blood_type` (
  `ID` int(11) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_type`
--

INSERT INTO `blood_type` (`ID`, `Type`, `createdAt`, `updatedAt`) VALUES
(1, 'A+', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'A-', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'B+', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'B-', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'AB+', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'AB-', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'O+', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'O-', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `Name`, `createdAt`, `updatedAt`) VALUES
(1, 'Consultation', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Laboratory', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Radiology', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Surgery', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Pharmacy', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Emergency', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Therapy', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `customized_report`
--

CREATE TABLE `customized_report` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `reportName` text NOT NULL,
  `SQLstatement` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customized_report`
--

INSERT INTO `customized_report` (`ID`, `userID`, `reportName`, `SQLstatement`, `createdAt`, `updatedAt`) VALUES
(1, 5, 'Monthly Patient Report', 'SELECT COUNT(*) FROM Patients WHERE MONTH(createdAt) = MONTH(NOW())', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 5, 'Doctor Performance', 'SELECT d.ID, u.FirstName, u.LastName, COUNT(a.ID) as appointments FROM Doctors d JOIN Users u ON d.userID = u.userID LEFT JOIN Appointment a ON d.ID = a.DoctorID GROUP BY d.ID', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 1, 'Revenue Report', 'SELECT SUM(totalPrice) as total_revenue FROM Appointment_Details WHERE DATE(createdAt) >= DATE_SUB(NOW(), INTERVAL 30 DAY)', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`ID`, `name`, `createdAt`, `updatedAt`) VALUES
(1, 'Cardiology', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Pediatrics', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Orthopedics', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Neurology', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Emergency', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Pharmacy', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Administration', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'Reception', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `yearsOfExperince` int(11) DEFAULT 0,
  `rating` int(11) DEFAULT 0,
  `doctorType` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `consultation_fee` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`ID`, `userID`, `yearsOfExperince`, `rating`, `doctorType`, `createdAt`, `updatedAt`, `consultation_fee`) VALUES
(1, 1, 15, 95, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35', 0.00),
(2, 3, 20, 98, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35', 0.00),
(3, 5, 12, 92, 5, '2025-05-25 20:45:35', '2025-05-25 20:45:35', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_types`
--

CREATE TABLE `doctor_types` (
  `ID` int(11) NOT NULL,
  `Specialization` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_types`
--

INSERT INTO `doctor_types` (`ID`, `Specialization`, `createdAt`, `updatedAt`) VALUES
(1, 'Cardiologist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Pediatrician', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Orthopedic Surgeon', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Neurologist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'General Practitioner', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Emergency Medicine', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Radiologist', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `dosage_form`
--

CREATE TABLE `dosage_form` (
  `ID` int(11) NOT NULL,
  `Form` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosage_form`
--

INSERT INTO `dosage_form` (`ID`, `Form`, `createdAt`, `updatedAt`) VALUES
(1, 'Tablet', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Capsule', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Syrup', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Injection', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Cream', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Drops', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Inhaler', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `Id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`Id`, `userID`, `email`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'ahmed.hassan@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 'fatma.mohamed@gmail.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, 'omar.ali@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 4, 'nadia.ibrahim@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 5, 'khaled.mahmoud@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 6, 'amira.youssef@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 7, 'mostafa.abdel@gmail.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 8, 'mona.farouk@clinicus.com', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_provider`
--

CREATE TABLE `insurance_provider` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount` int(11) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `insurance_provider`
--

INSERT INTO `insurance_provider` (`ID`, `name`, `discount`, `createdAt`, `updatedAt`) VALUES
(1, 'Egyptian Health Insurance', 20, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Private Medical Insurance', 15, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Government Employee Insurance', 25, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Student Health Insurance', 30, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Senior Citizen Insurance', 35, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Corporate Health Plan', 18, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`ID`, `name`, `createdAt`, `updatedAt`) VALUES
(1, 'Arabic', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'English', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'French', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'German', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Spanish', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Italian', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Turkish', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `ID` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `serviceID` int(11) DEFAULT NULL,
  `appointmentID` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`ID`, `patientID`, `serviceID`, `appointmentID`, `description`, `createdBy`, `createdAt`, `updatedAt`) VALUES
(1, 1, 1, 1, 'Patient complained of chest pain. ECG normal. Prescribed rest.', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 1, 2, 'Regular checkup. Blood pressure slightly elevated.', 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, 3, 3, 'X-ray showed minor fracture in left wrist.', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 1, 2, 1, 'Blood test results normal except slightly high cholesterol.', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `medication_info`
--

CREATE TABLE `medication_info` (
  `serviceID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `brandName` varchar(100) DEFAULT NULL,
  `dosageForm` int(11) DEFAULT NULL,
  `strenght` int(11) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `expireDuration` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medication_info`
--

INSERT INTO `medication_info` (`serviceID`, `name`, `brandName`, `dosageForm`, `strenght`, `unit`, `category`, `expireDuration`, `createdAt`, `updatedAt`) VALUES
(1, 'Paracetamol', 'Panadol', 1, 500, 1, 5, 36, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Amoxicillin', 'Augmentin', 2, 250, 1, 5, 24, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Salbutamol', 'Ventolin', 7, 100, 4, 5, 36, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Insulin', 'Lantus', 4, 100, 5, 5, 18, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Aspirin', 'Aspocid', 1, 75, 1, 5, 48, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Omeprazole', 'Losec', 2, 20, 1, 5, 36, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `Id` int(11) NOT NULL,
  `messageTemplate` text NOT NULL,
  `typeID` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`Id`, `messageTemplate`, `typeID`, `createdAt`, `updatedAt`) VALUES
(1, 'Your appointment with Dr. {doctor_name} is scheduled for {date} at {time}', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Your test results are ready. Please contact the clinic.', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Payment of {amount} EGP is due for your recent visit.', 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Welcome to Clinicus Health System!', 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Emergency: Please contact the clinic immediately.', 5, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Your prescription is ready for pickup.', 6, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `message_type`
--

CREATE TABLE `message_type` (
  `Id` int(11) NOT NULL,
  `Type` text NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_type`
--

INSERT INTO `message_type` (`Id`, `Type`, `createdAt`, `updatedAt`) VALUES
(1, 'Appointment Reminder', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Test Results', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Payment Due', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'General Notification', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Emergency Alert', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Prescription Ready', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `message_user`
--

CREATE TABLE `message_user` (
  `Id` int(11) NOT NULL,
  `messageID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_user`
--

INSERT INTO `message_user` (`Id`, `messageID`, `userID`, `createdAt`, `updatedAt`) VALUES
(1, 1, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 7, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, 8, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 4, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 1, 7, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 6, 8, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`ID`, `name`, `Type`, `createdAt`, `updatedAt`) VALUES
(1, 'Installments', 'Payment', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Full Payment', 'Payment', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Partial Payment', 'Payment', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Insurance Coverage', 'Coverage', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Self Pay', 'Coverage', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `ID` int(11) NOT NULL,
  `friendlyName` varchar(255) NOT NULL,
  `linkAddress` varchar(255) DEFAULT NULL,
  `HTML` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`ID`, `friendlyName`, `linkAddress`, `HTML`, `createdAt`, `updatedAt`) VALUES
(1, 'Dashboard', '/dashboard', '<h1>Dashboard</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Appointments', '/appointments', '<h1>Appointments</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Patients', '/patients', '<h1>Patients</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Doctors', '/doctors', '<h1>Doctors</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Reports', '/reports', '<h1>Reports</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Settings', '/settings', '<h1>Settings</h1>', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `bloodType` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`ID`, `userID`, `bloodType`, `createdAt`, `updatedAt`) VALUES
(1, 2, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 7, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 8, 5, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `patient_insurance`
--

CREATE TABLE `patient_insurance` (
  `ID` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `providerID` int(11) NOT NULL,
  `insuranceNO` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_insurance`
--

INSERT INTO `patient_insurance` (`ID`, `patientID`, `providerID`, `insuranceNO`, `createdAt`, `updatedAt`) VALUES
(1, 1, 1, 'EHI-2025-001234', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 2, 'PMI-2025-005678', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, 3, 'GEI-2025-009012', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`ID`, `name`, `createdAt`, `updatedAt`) VALUES
(1, 'Cash', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Credit Card', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Debit Card', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Bank Transfer', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Insurance', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Mobile Payment', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_options`
--

CREATE TABLE `payment_method_options` (
  `ID` int(11) NOT NULL,
  `paymentID` int(11) NOT NULL,
  `optionID` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method_options`
--

INSERT INTO `payment_method_options` (`ID`, `paymentID`, `optionID`, `createdAt`, `updatedAt`) VALUES
(1, 1, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 2, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 3, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 4, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 5, 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 6, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_values`
--

CREATE TABLE `payment_method_values` (
  `ID` int(11) NOT NULL,
  `PMOpID` int(11) NOT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `appointementID` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method_values`
--

INSERT INTO `payment_method_values` (`ID`, `PMOpID`, `value`, `appointementID`, `createdAt`, `updatedAt`) VALUES
(1, 1, 350.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 3, 200.00, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 4, 300.00, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 5, 120.00, 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`ID`, `name`, `createdAt`, `updatedAt`) VALUES
(1, 'Head Doctor', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Senior Nurse', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Junior Doctor', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Pharmacist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Receptionist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Manager', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Administrator', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'Technician', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `render_payment_methods`
--

CREATE TABLE `render_payment_methods` (
  `ID` int(11) NOT NULL,
  `pmID` int(11) NOT NULL,
  `HTML` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `render_payment_methods`
--

INSERT INTO `render_payment_methods` (`ID`, `pmID`, `HTML`, `createdAt`, `updatedAt`) VALUES
(1, 1, '<div class=\"cash-payment\">Cash Payment Form</div>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, '<div class=\"card-payment\">Credit Card Form</div>', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 5, '<div class=\"insurance-payment\">Insurance Claim Form</div>', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`ID`, `name`, `category`, `price`, `active`, `createdAt`, `updatedAt`) VALUES
(1, 'General Consultation', 1, 200.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Blood Test', 2, 150.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'X-Ray', 3, 300.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Heart Surgery', 4, 15000.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Paracetamol', 5, 25.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Emergency Visit', 6, 500.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Physiotherapy', 7, 180.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'ECG', 2, 120.00, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `ID` int(11) NOT NULL,
  `serviceName` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`ID`, `serviceName`, `description`, `createdAt`, `updatedAt`) VALUES
(1, 'Medical Consultation', 'General medical consultation with doctor', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Diagnostic Test', 'Laboratory and radiology tests', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Surgical Procedure', 'Surgical interventions and operations', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Medication', 'Prescribed medications and drugs', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Emergency Care', 'Emergency medical services', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Therapy', 'Physical and rehabilitation therapy', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `hiredAt` date DEFAULT NULL,
  `departmentID` int(11) DEFAULT NULL,
  `positionID` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`ID`, `userID`, `hiredAt`, `departmentID`, `positionID`, `createdAt`, `updatedAt`) VALUES
(1, 4, '2020-01-15', 2, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 6, '2019-06-10', 8, 5, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 8, '2021-03-20', 6, 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `telephone`
--

CREATE TABLE `telephone` (
  `Id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `telephone` varchar(13) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telephone`
--

INSERT INTO `telephone` (`Id`, `userID`, `telephone`, `createdAt`, `updatedAt`) VALUES
(1, 1, '+201012345678', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, '+201087654321', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 3, '+201123456789', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 4, '+201198765432', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 5, '+201234567890', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 6, '+201156789012', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 7, '+201178901234', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 8, '+201145678901', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `translation`
--

CREATE TABLE `translation` (
  `ID` int(11) NOT NULL,
  `firstLangCode` int(11) NOT NULL,
  `secondLangCode` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translation`
--

INSERT INTO `translation` (`ID`, `firstLangCode`, `secondLangCode`, `createdAt`, `updatedAt`) VALUES
(1, 1, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 1, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 2, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `translation_details`
--

CREATE TABLE `translation_details` (
  `ID` int(11) NOT NULL,
  `TransID` int(11) NOT NULL,
  `pageID` int(11) DEFAULT NULL,
  `wordID` int(11) NOT NULL,
  `TransWordID` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translation_details`
--

INSERT INTO `translation_details` (`ID`, `TransID`, `pageID`, `wordID`, `TransWordID`, `createdAt`, `updatedAt`) VALUES
(1, 1, 1, 2, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 2, 1, 1, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 1, 2, 4, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 2, 2, 3, 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `ID` int(11) NOT NULL,
  `Name` varchar(15) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`ID`, `Name`, `createdAt`, `updatedAt`) VALUES
(1, 'mg', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'ml', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'g', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'mcg', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'IU', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'tablets', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'capsules', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` datetime DEFAULT NULL,
  `addressID` int(11) DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `FirstName`, `LastName`, `username`, `email`, `phone`, `password`, `dob`, `addressID`, `roleID`, `createdAt`, `updatedAt`) VALUES
(1, 'Ahmed', 'Hassan', 'ahmed.hassan', '', '', 'hashed_password_1', '1980-05-15 00:00:00', 1, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Fatma', 'Mohamed', 'fatma.mohamed', '', '', 'hashed_password_2', '1985-08-22 00:00:00', 2, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Omar', 'Ali', 'omar.ali', '', '', 'hashed_password_3', '1975-12-10 00:00:00', 3, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Nadia', 'Ibrahim', 'nadia.ibrahim', '', '', 'hashed_password_4', '1990-03-18 00:00:00', 4, 4, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Khaled', 'Mahmoud', 'khaled.mahmoud', '', '', 'hashed_password_5', '1978-07-25 00:00:00', 5, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Amira', 'Youssef', 'amira.youssef', '', '', 'hashed_password_6', '1988-11-12 00:00:00', 6, 5, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Mostafa', 'Abdel Rahman', 'mostafa.abdel', '', '', 'hashed_password_7', '1982-09-05 00:00:00', 7, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'Mona', 'Farouk', 'mona.farouk', '', '', 'hashed_password_8', '1987-04-28 00:00:00', 8, 6, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(9, 'Yassin', 'Bedier', 'yassin.bedier', 'yassin.bedier@gmail.com', '01550447237', '$2y$10$eLZovqj5WuEI8dnfUS/pnO5H8rQ6qZcgjk3FNp9xjmOyPoTNQyJVO', '2007-01-21 00:00:00', 9, 3, '2025-05-27 23:44:14', '2025-05-28 00:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `userTypeID` int(11) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`userTypeID`, `Type`, `createdAt`, `updatedAt`) VALUES
(1, 'Admin', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'Doctor', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Patient', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'Nurse', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Receptionist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'Pharmacist', '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Manager', '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_type_pages`
--

CREATE TABLE `user_type_pages` (
  `ID` int(11) NOT NULL,
  `userTypeID` int(11) NOT NULL,
  `pageID` int(11) NOT NULL,
  `orderValue` int(11) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_type_pages`
--

INSERT INTO `user_type_pages` (`ID`, `userTypeID`, `pageID`, `orderValue`, `createdAt`, `updatedAt`) VALUES
(1, 1, 1, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 1, 5, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 1, 6, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 2, 1, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 2, 2, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 2, 3, 3, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 3, 1, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 3, 2, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(9, 4, 1, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(10, 4, 3, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(11, 5, 1, 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(12, 5, 2, 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

CREATE TABLE `word` (
  `ID` int(11) NOT NULL,
  `word` varchar(50) NOT NULL,
  `languageID` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `word`
--

INSERT INTO `word` (`ID`, `word`, `languageID`, `createdAt`, `updatedAt`) VALUES
(1, 'Dashboard', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(2, 'لوحة التحكم', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(3, 'Appointment', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(4, 'موعد', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(5, 'Patient', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(6, 'مريض', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(7, 'Doctor', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(8, 'طبيب', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(9, 'Medicine', 2, '2025-05-25 20:45:35', '2025-05-25 20:45:35'),
(10, 'دواء', 1, '2025-05-25 20:45:35', '2025-05-25 20:45:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `DoctorID` (`DoctorID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `appointment_details`
--
ALTER TABLE `appointment_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `blood_type`
--
ALTER TABLE `blood_type`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customized_report`
--
ALTER TABLE `customized_report`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `docotrType` (`doctorType`);

--
-- Indexes for table `doctor_types`
--
ALTER TABLE `doctor_types`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `dosage_form`
--
ALTER TABLE `dosage_form`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `insurance_provider`
--
ALTER TABLE `insurance_provider`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `serviceID` (`serviceID`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `medication_info`
--
ALTER TABLE `medication_info`
  ADD PRIMARY KEY (`serviceID`),
  ADD KEY `dosageForm` (`dosageForm`),
  ADD KEY `unit` (`unit`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `typeID` (`typeID`);

--
-- Indexes for table `message_type`
--
ALTER TABLE `message_type`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `message_user`
--
ALTER TABLE `message_user`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `messageID` (`messageID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `bloodType` (`bloodType`);

--
-- Indexes for table `patient_insurance`
--
ALTER TABLE `patient_insurance`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `providerID` (`providerID`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment_method_options`
--
ALTER TABLE `payment_method_options`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `paymentID` (`paymentID`),
  ADD KEY `optionID` (`optionID`);

--
-- Indexes for table `payment_method_values`
--
ALTER TABLE `payment_method_values`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PMOpID` (`PMOpID`),
  ADD KEY `appointementID` (`appointementID`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `render_payment_methods`
--
ALTER TABLE `render_payment_methods`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pmID` (`pmID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `departmentID` (`departmentID`),
  ADD KEY `positionID` (`positionID`);

--
-- Indexes for table `telephone`
--
ALTER TABLE `telephone`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `firstLangCode` (`firstLangCode`),
  ADD KEY `secondLangCode` (`secondLangCode`);

--
-- Indexes for table `translation_details`
--
ALTER TABLE `translation_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TransID` (`TransID`),
  ADD KEY `pageID` (`pageID`),
  ADD KEY `wordID` (`wordID`),
  ADD KEY `TransWordID` (`TransWordID`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `addressID` (`addressID`),
  ADD KEY `roleID` (`roleID`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`userTypeID`);

--
-- Indexes for table `user_type_pages`
--
ALTER TABLE `user_type_pages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userTypeID` (`userTypeID`),
  ADD KEY `pageID` (`pageID`);

--
-- Indexes for table `word`
--
ALTER TABLE `word`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `languageID` (`languageID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `appointment_details`
--
ALTER TABLE `appointment_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_type`
--
ALTER TABLE `blood_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customized_report`
--
ALTER TABLE `customized_report`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_types`
--
ALTER TABLE `doctor_types`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dosage_form`
--
ALTER TABLE `dosage_form`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `insurance_provider`
--
ALTER TABLE `insurance_provider`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medication_info`
--
ALTER TABLE `medication_info`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `message_type`
--
ALTER TABLE `message_type`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `message_user`
--
ALTER TABLE `message_user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_insurance`
--
ALTER TABLE `patient_insurance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_method_options`
--
ALTER TABLE `payment_method_options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_method_values`
--
ALTER TABLE `payment_method_values`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `render_payment_methods`
--
ALTER TABLE `render_payment_methods`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `telephone`
--
ALTER TABLE `telephone`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `translation`
--
ALTER TABLE `translation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `translation_details`
--
ALTER TABLE `translation_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `userTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_type_pages`
--
ALTER TABLE `user_type_pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `word`
--
ALTER TABLE `word`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`DoctorID`) REFERENCES `doctors` (`ID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `appointment_details`
--
ALTER TABLE `appointment_details`
  ADD CONSTRAINT `appointment_details_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`ID`),
  ADD CONSTRAINT `appointment_details_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`ID`);

--
-- Constraints for table `customized_report`
--
ALTER TABLE `customized_report`
  ADD CONSTRAINT `customized_report_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`doctorType`) REFERENCES `doctor_types` (`ID`);

--
-- Constraints for table `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`patientID`) REFERENCES `patients` (`ID`),
  ADD CONSTRAINT `medical_history_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`ID`),
  ADD CONSTRAINT `medical_history_ibfk_3` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`ID`),
  ADD CONSTRAINT `medical_history_ibfk_4` FOREIGN KEY (`createdBy`) REFERENCES `users` (`userID`);

--
-- Constraints for table `medication_info`
--
ALTER TABLE `medication_info`
  ADD CONSTRAINT `medication_info_ibfk_1` FOREIGN KEY (`dosageForm`) REFERENCES `dosage_form` (`ID`),
  ADD CONSTRAINT `medication_info_ibfk_2` FOREIGN KEY (`unit`) REFERENCES `unit` (`ID`),
  ADD CONSTRAINT `medication_info_ibfk_3` FOREIGN KEY (`category`) REFERENCES `category` (`ID`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `message_type` (`Id`);

--
-- Constraints for table `message_user`
--
ALTER TABLE `message_user`
  ADD CONSTRAINT `message_user_ibfk_1` FOREIGN KEY (`messageID`) REFERENCES `messages` (`Id`),
  ADD CONSTRAINT `message_user_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `patients_ibfk_2` FOREIGN KEY (`bloodType`) REFERENCES `blood_type` (`ID`);

--
-- Constraints for table `patient_insurance`
--
ALTER TABLE `patient_insurance`
  ADD CONSTRAINT `patient_insurance_ibfk_1` FOREIGN KEY (`patientID`) REFERENCES `patients` (`ID`),
  ADD CONSTRAINT `patient_insurance_ibfk_2` FOREIGN KEY (`providerID`) REFERENCES `insurance_provider` (`ID`);

--
-- Constraints for table `payment_method_options`
--
ALTER TABLE `payment_method_options`
  ADD CONSTRAINT `payment_method_options_ibfk_1` FOREIGN KEY (`paymentID`) REFERENCES `payment_methods` (`ID`),
  ADD CONSTRAINT `payment_method_options_ibfk_2` FOREIGN KEY (`optionID`) REFERENCES `options` (`ID`);

--
-- Constraints for table `payment_method_values`
--
ALTER TABLE `payment_method_values`
  ADD CONSTRAINT `payment_method_values_ibfk_1` FOREIGN KEY (`PMOpID`) REFERENCES `payment_method_options` (`ID`),
  ADD CONSTRAINT `payment_method_values_ibfk_2` FOREIGN KEY (`appointementID`) REFERENCES `appointment` (`ID`);

--
-- Constraints for table `render_payment_methods`
--
ALTER TABLE `render_payment_methods`
  ADD CONSTRAINT `render_payment_methods_ibfk_1` FOREIGN KEY (`pmID`) REFERENCES `payment_methods` (`ID`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`ID`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`departmentID`) REFERENCES `department` (`ID`),
  ADD CONSTRAINT `staff_ibfk_3` FOREIGN KEY (`positionID`) REFERENCES `positions` (`ID`);

--
-- Constraints for table `telephone`
--
ALTER TABLE `telephone`
  ADD CONSTRAINT `telephone_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`firstLangCode`) REFERENCES `languages` (`ID`),
  ADD CONSTRAINT `translation_ibfk_2` FOREIGN KEY (`secondLangCode`) REFERENCES `languages` (`ID`);

--
-- Constraints for table `translation_details`
--
ALTER TABLE `translation_details`
  ADD CONSTRAINT `translation_details_ibfk_1` FOREIGN KEY (`TransID`) REFERENCES `translation` (`ID`),
  ADD CONSTRAINT `translation_details_ibfk_2` FOREIGN KEY (`pageID`) REFERENCES `pages` (`ID`),
  ADD CONSTRAINT `translation_details_ibfk_3` FOREIGN KEY (`wordID`) REFERENCES `word` (`ID`),
  ADD CONSTRAINT `translation_details_ibfk_4` FOREIGN KEY (`TransWordID`) REFERENCES `word` (`ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `address` (`Id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `user_types` (`userTypeID`);

--
-- Constraints for table `user_type_pages`
--
ALTER TABLE `user_type_pages`
  ADD CONSTRAINT `user_type_pages_ibfk_1` FOREIGN KEY (`userTypeID`) REFERENCES `user_types` (`userTypeID`),
  ADD CONSTRAINT `user_type_pages_ibfk_2` FOREIGN KEY (`pageID`) REFERENCES `pages` (`ID`);

--
-- Constraints for table `word`
--
ALTER TABLE `word`
  ADD CONSTRAINT `word_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `languages` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
