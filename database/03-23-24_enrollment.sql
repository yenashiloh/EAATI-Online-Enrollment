-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2024 at 04:24 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `tuition_june_to_march` decimal(10,2) DEFAULT NULL,
  `partial_upon` decimal(10,2) DEFAULT NULL,
  `total_whole_year` decimal(10,2) DEFAULT NULL,
  `pe_uniform` decimal(10,2) DEFAULT NULL,
  `books` decimal(10,2) DEFAULT NULL,
  `school_uniform` decimal(10,2) DEFAULT NULL,
  `upon_enrollment` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `grade_level`, `tuition_june_to_march`, `partial_upon`, `total_whole_year`, `pe_uniform`, `books`, `school_uniform`, `upon_enrollment`) VALUES
(2, '1', '321.00', '321.00', '321.00', '321.00', '321.00', '321.00', '321.00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `grade_level`, `subject_name`, `teacher_id`, `start_time`, `end_time`) VALUES
(3, 'Grade 2', 'English', 15, '22:35:00', '22:36:00');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `business_address_father` varchar(255) NOT NULL,
  `telephone_father` varchar(15) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `business_address_mother` varchar(255) NOT NULL,
  `telephone_mother` varchar(15) NOT NULL,
  `guardian` varchar(255) DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `school_address` varchar(255) DEFAULT NULL,
  `isVerified` int(11) DEFAULT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `requirements` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `userId`, `name`, `dob`, `pob`, `age`, `father_name`, `business_address_father`, `telephone_father`, `mother_name`, `business_address_mother`, `telephone_mother`, `guardian`, `previous_school`, `school_address`, `isVerified`, `grade_level`, `requirements`) VALUES
(12, 20, '123', '2024-03-06', '123', 0, '123', '123', '123', '123', '213', '123', '123', '123', '123', 1, '1', '[\"..\\/uploads\\/Conceptual Framework_Foreign Literature_Statistical.docx\",\"..\\/uploads\\/Create a New Laravel Project.txt\"]');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `first_name`, `last_name`, `contact_number`, `email`) VALUES
(1, 'superadmin', '$2y$10$69EojGWI.uqaTxg.aI9x6eS7yqjVV3ZLX1aFNLbzX1vWVDrK1FovC', 'superadmin', 'super', 'admin', NULL, NULL),
(2, 'registrar', '$2y$10$RrsqT3vi7fYmnXN62Za6O.Ub.izlebiemWVAOyw.F1JMFPsvFGmgq', 'registrar', 'admin', 'registrar', NULL, NULL),
(14, 'accounting', '$2y$10$5UZf2nJ4OSajZfGi7DZ.DOT6FHe1zmDA7W7HEAlYzefQVPLUwSgC2', 'accounting', 'sample', 'accounting', '123', 'sampleaccounting@gmail.com'),
(15, 'teacher', '$2y$10$mjuZYXPx1OmqUZZxzQTTMum6B55TVCT7UbVQCrksbdMaB8tw6DC5a', 'teacher', 'sample', 'teacher', '123', '123@teacher.com'),
(20, 'sample321', '$2y$10$Ci2VONYvbimQu7qUYPrGc.YSzbrCQgVHmfobnYO4jmg1kQRFjUy6W', 'parent', 'sample', 'sample', '123', 'louiseruzzelep@gmail.com'),
(21, 'sample1', '$2y$10$mbiv2nTlVNqp65bCEFA1H.BdO4l158o6vr/n21PXwvw7/bDT6lx42', 'student', 'sample', 'sample', '123', '321@sample.com'),
(25, '147', '$2y$10$7PkM/DEbTTVa1mFOEBzIu.3OskiU4MSsqSoUTu.IOMsfighH36Obe', 'accounting', '12313', '23123', '123213123', '213123@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);

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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
