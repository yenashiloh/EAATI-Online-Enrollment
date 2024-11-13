-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2024 at 01:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `grade_level` varchar(50) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `grade_level`, `subject_name`, `teacher_id`, `start_time`, `end_time`) VALUES
(3, 'Grade 2', 'English', 15, '22:35:00', '22:36:00');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `lrn` varchar(20) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `extension_name` varchar(10) DEFAULT NULL,
  `birthday` date NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `age` int(11) NOT NULL,
  `mother_tongue` varchar(50) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `street_name` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `municipality_city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `father_last_name` varchar(50) NOT NULL,
  `father_first_name` varchar(50) NOT NULL,
  `father_middle_name` varchar(50) DEFAULT NULL,
  `father_contact_number` varchar(20) NOT NULL,
  `mother_last_name` varchar(50) NOT NULL,
  `mother_first_name` varchar(50) NOT NULL,
  `mother_middle_name` varchar(50) DEFAULT NULL,
  `mother_contact_number` varchar(20) NOT NULL,
  `guardian_last_name` varchar(50) NOT NULL,
  `guardian_first_name` varchar(50) NOT NULL,
  `guardian_middle_name` varchar(50) DEFAULT NULL,
  `guardian_contact_number` varchar(20) NOT NULL,
  `grade_level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `lrn`, `last_name`, `first_name`, `middle_name`, `extension_name`, `birthday`, `place_of_birth`, `sex`, `age`, `mother_tongue`, `house_no`, `street_name`, `barangay`, `municipality_city`, `province`, `country`, `zip_code`, `father_last_name`, `father_first_name`, `father_middle_name`, `father_contact_number`, `mother_last_name`, `mother_first_name`, `mother_middle_name`, `mother_contact_number`, `guardian_last_name`, `guardian_first_name`, `guardian_middle_name`, `guardian_contact_number`, `grade_level`) VALUES
(1, '123', 'louise', 'ponciano', 'r', 'jr', '0123-12-03', 'makati', 'Male', 123, '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '1123', '123', '123', '123', '123', '123', '123'),
(2, '123', 'louise', 'ponciano', 'r', 'jr', '0123-12-03', 'makati', 'Male', 123, '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '1123', '123', '123', '123', '123', '123', '123'),
(3, '123', '123', '123', '123', '123', '0000-00-00', '123', 'Female', 123, '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `first_name`, `last_name`, `contact_number`, `email`) VALUES
(1, 'superadmin', '$2y$10$69EojGWI.uqaTxg.aI9x6eS7yqjVV3ZLX1aFNLbzX1vWVDrK1FovC', 'superadmin', 'super', 'admin', NULL, NULL),
(2, 'registrar', '$2y$10$RrsqT3vi7fYmnXN62Za6O.Ub.izlebiemWVAOyw.F1JMFPsvFGmgq', 'registrar', 'admin', 'registrar', NULL, NULL),
(14, 'accounting', '$2y$10$5UZf2nJ4OSajZfGi7DZ.DOT6FHe1zmDA7W7HEAlYzefQVPLUwSgC2', 'accounting', 'sample', 'accounting', '123', 'sampleaccounting@gmail.com'),
(15, 'teacher', '$2y$10$mjuZYXPx1OmqUZZxzQTTMum6B55TVCT7UbVQCrksbdMaB8tw6DC5a', 'teacher', 'sample', 'teacher', '123', '123@teacher.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
