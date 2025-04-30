-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 02:38 PM
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
-- Database: `enrollment`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
--

CREATE TABLE `academic_year` (
  `academic_year_id` int(11) NOT NULL,
  `year_start` int(11) NOT NULL,
  `year_end` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approvalschedule`
--

CREATE TABLE `approvalschedule` (
  `approvalschedule_id` int(11) NOT NULL,
  `gradelevel_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvalschedule`
--

INSERT INTO `approvalschedule` (`approvalschedule_id`, `gradelevel_id`, `start_date`, `end_date`) VALUES
(1, 3, '2024-05-08', '2024-05-09'),
(2, 4, '2024-11-07', '2024-11-28');

-- --------------------------------------------------------

--
-- Table structure for table `encodedgrades`
--

CREATE TABLE `encodedgrades` (
  `encodedgrades_id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `quarter1` decimal(5,2) DEFAULT NULL,
  `quarter2` decimal(5,2) DEFAULT NULL,
  `quarter3` decimal(5,2) DEFAULT NULL,
  `quarter4` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'For Review'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encodedgrades`
--

INSERT INTO `encodedgrades` (`encodedgrades_id`, `userId`, `student_id`, `schedule_id`, `quarter1`, `quarter2`, `quarter3`, `quarter4`, `status`) VALUES
(9, 73, 3, 5, 90.00, 99.00, 98.00, 95.00, 'For Review'),
(10, 72, 2, 5, 98.00, 99.00, 95.00, 94.00, 'For Review'),
(11, 74, 4, 6, 97.00, 94.00, 92.00, 90.00, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `encodedstudentsubjects`
--

CREATE TABLE `encodedstudentsubjects` (
  `encoded_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encodedstudentsubjects`
--

INSERT INTO `encodedstudentsubjects` (`encoded_id`, `student_id`, `schedule_id`) VALUES
(1, 1, 4),
(2, 2, 5),
(3, 3, 5),
(4, 4, 6),
(5, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `enrollmentschedule`
--

CREATE TABLE `enrollmentschedule` (
  `enrollmentschedule_id` int(11) NOT NULL,
  `gradelevel_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT 'For Review'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollmentschedule`
--

INSERT INTO `enrollmentschedule` (`enrollmentschedule_id`, `gradelevel_id`, `start_date`, `end_date`, `status`) VALUES
(7, 16, '2025-03-03', '2025-03-19', 'Approved'),
(8, 17, '2025-03-03', '2025-03-19', 'Approved'),
(14, 2, '2025-03-11', '2025-04-05', 'Approved'),
(15, 3, '2025-03-11', '2025-04-05', 'Approved'),
(16, 4, '2025-03-11', '2025-04-05', 'Approved'),
(22, 7, '2025-04-03', '2025-04-30', 'Approved'),
(23, 8, '2025-04-03', '2025-04-30', 'Approved'),
(24, 9, '2025-04-03', '2025-04-30', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `father_information`
--

CREATE TABLE `father_information` (
  `father_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `telephone_father` varchar(15) DEFAULT NULL,
  `houseNo_father` varchar(255) DEFAULT NULL,
  `street_father` varchar(255) DEFAULT NULL,
  `barangay_father` varchar(255) DEFAULT NULL,
  `municipality_father` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `father_information`
--

INSERT INTO `father_information` (`father_id`, `student_id`, `userId`, `father_name`, `telephone_father`, `houseNo_father`, `street_father`, `barangay_father`, `municipality_father`) VALUES
(21, 1, 71, 'Mario F Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(22, 2, 72, 'Mario F Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(23, 3, 73, 'Mario F. Eugenio', '953659', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(24, 4, 74, 'Mario F Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(25, 5, 75, 'Mario F Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `generatedcor`
--

CREATE TABLE `generatedcor` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `cor_content` longblob DEFAULT NULL,
  `generate_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gradelevel`
--

CREATE TABLE `gradelevel` (
  `gradelevel_id` int(11) NOT NULL,
  `gradelevel_name` varchar(255) NOT NULL,
  `gradelevel_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gradelevel`
--

INSERT INTO `gradelevel` (`gradelevel_id`, `gradelevel_name`, `gradelevel_description`) VALUES
(2, 'Grade 1', 'Grade 1'),
(3, 'Grade 2', 'Grade 2'),
(4, 'Grade 3', 'Grade 3'),
(7, 'Grade 4', 'Grade 4'),
(8, 'Grade 5', 'Grade 5'),
(9, 'Grade 6', 'Grade 6'),
(10, 'Grade 7', 'Grade 7'),
(12, 'Grade 8', 'Grade 8'),
(16, 'Grade 9', 'Grade 9'),
(19, 'Grade 10', 'Grade 10 Description');

-- --------------------------------------------------------

--
-- Table structure for table `mother_information`
--

CREATE TABLE `mother_information` (
  `mother_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `telephone_mother` varchar(15) DEFAULT NULL,
  `houseNo_mother` varchar(255) DEFAULT NULL,
  `street_mother` varchar(255) DEFAULT NULL,
  `barangay_mother` varchar(255) DEFAULT NULL,
  `municipality_mother` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mother_information`
--

INSERT INTO `mother_information` (`mother_id`, `student_id`, `userId`, `mother_name`, `telephone_mother`, `houseNo_mother`, `street_mother`, `barangay_mother`, `municipality_mother`) VALUES
(19, 1, 71, 'Gina B Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(20, 2, 72, 'Gina B Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(21, 3, 73, 'Gina B. Eugenio', '665943', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(22, 4, 74, 'Gina B Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City'),
(23, 5, 75, 'Gina B Eugenio', '09102258953', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `registration_fee` decimal(10,2) DEFAULT NULL,
  `tuition_fee` decimal(10,2) DEFAULT NULL,
  `total_whole_year` decimal(10,2) DEFAULT NULL,
  `upon_enrollment` decimal(10,2) DEFAULT NULL,
  `miscellaneous_fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `grade_level`, `registration_fee`, `tuition_fee`, `total_whole_year`, `upon_enrollment`, `miscellaneous_fee`) VALUES
(1, '2', 2000.00, 8925.00, 25775.00, NULL, 1628.00),
(3, '9', 2000.00, 10290.00, 27140.00, NULL, 7350.00);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `gradelevel_id` int(11) DEFAULT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `gradelevel_id`, `room_name`, `room_description`) VALUES
(2, 8, '102', 'Room 1021'),
(4, 3, '105', 'Room 105'),
(5, 10, '106', 'Room 106'),
(6, 2, '805', 'Example only'),
(7, 4, '202', 'Description'),
(8, 9, '203', 'Example Description');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `day` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `grade_level`, `subject_name`, `teacher_id`, `start_time`, `end_time`, `section_id`, `room_id`, `subject_id`, `day`) VALUES
(3, 4, 'Science', 15, '08:00:00', '10:00:00', 2, 2, 1, 'Monday'),
(5, 7, 'Science', 15, '09:30:00', '11:00:00', 4, 4, 1, 'Wednesday'),
(6, 8, 'Science', 15, '08:00:00', '11:30:00', 8, 2, 1, 'Tuesday'),
(7, 9, 'Araling Panlipunan', 26, '08:30:00', '10:00:00', 7, 5, 4, 'Monday');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `gradelevel_id` int(11) DEFAULT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_description` text NOT NULL,
  `sectionCapacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `gradelevel_id`, `section_name`, `section_description`, `sectionCapacity`) VALUES
(1, 2, 'Section 1', 'Section 1 Description ', 50),
(2, 3, 'Section 1', 'Section 1 Description', 30),
(3, 4, 'Section 1', 'Section 1 Description', 40),
(4, 7, 'Section 1', 'Section 1 Description', 50),
(6, 8, 'Section 1', 'Section 1 Description', 50),
(7, 9, 'Section 1', 'Section 1 Description', 35),
(8, 10, 'Section 1', 'Section 1 Description', 45),
(10, 12, 'Section 1', 'Section Description', 20);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `pob` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `student_house_number` varchar(255) DEFAULT NULL,
  `student_street` varchar(255) DEFAULT NULL,
  `student_barangay` varchar(255) DEFAULT NULL,
  `student_municipality` varchar(255) DEFAULT NULL,
  `guardian` varchar(255) DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `school_address` varchar(255) DEFAULT NULL,
  `isVerified` int(11) DEFAULT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `isPaidUpon` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `userId`, `grade_level_id`, `name`, `dob`, `pob`, `email`, `age`, `gender`, `student_house_number`, `student_street`, `student_barangay`, `student_municipality`, `guardian`, `previous_school`, `school_address`, `isVerified`, `grade_level`, `requirements`, `image_path`, `isPaidUpon`) VALUES
(1, 71, 9, 'Shiloh Eugenio', '2006-01-01', 'Fugiat dolores ipsa', 'shiloheugenio21@gmail.com', 19, 'Male', '145', 'Pinagsama Taguig City', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'Id voluptatem Volup', NULL, NULL, '[\"..\\/uploads\\/Template_Application-for-Graduation_WEDNESDAY.pdf\"]', 'uploads/images/unnamed.jpg', 0),
(2, 72, 7, 'Jhoanna Robles', '2004-03-03', 'Fugiat dolores ipsa', 'seugenio.k11833184@umak.edu.ph', 21, 'Male', '145', 'Pinagsama Taguig City', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'Id voluptatem Volup', 1, NULL, '[\"..\\/uploads\\/Emmanuel E_RESUME.pdf\"]', 'uploads/images/unnamed.jpg', 0),
(3, 73, 7, 'Arrianne  Camille', '2003-06-10', 'Makati City', 'yenaaacuteee@gmail.com', 21, 'Female', '1223', 'Blueboz St. Brgy. Pinagsama', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'Id voluptatem Volup', 1, NULL, '[\"..\\/uploads\\/Untitled-document.pdf\"]', 'uploads/images/24cb3cce-bcf2-414a-9964-9f96ce1980c1.jpg', 0),
(4, 74, 2, 'Aaron Doe', '2009-03-05', 'Fugiat dolores ipsa', 'iveforever6@gmail.com', 16, 'Male', '145', 'Pinagsama Taguig City', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'Id voluptatem Volup', 1, NULL, '[\"..\\/uploads\\/Untitled-document.pdf\"]', 'uploads/images/24cb3cce-bcf2-414a-9964-9f96ce1980c1.jpg', 0),
(5, 75, 9, 'Ed Penalber', '2008-02-28', 'Fugiat dolores ipsa', 'iveforever6@gmail.com', 17, 'Male', '145', 'Pinagsama Taguig City', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'Id voluptatem Volup', 1, NULL, '[\"..\\/uploads\\/EUGENIO_RECEIPT.pdf\"]', 'uploads/images/24cb3cce-bcf2-414a-9964-9f96ce1980c1.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_description` text NOT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_description`, `grade_level_id`, `teacher_id`) VALUES
(1, 'Science', 'Science Description', 4, 15),
(2, 'English', 'English Description', 4, 15),
(3, 'English', 'Example Description', 3, 15),
(4, 'Araling Panlipunan', 'Subject Descriptiom', 9, 26);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `gcash_number` varchar(20) DEFAULT NULL,
  `reference_number` varchar(20) DEFAULT NULL,
  `screenshot_path` varchar(255) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `installment_type` varchar(50) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `payment_amount`, `payment_method`, `gcash_number`, `reference_number`, `screenshot_path`, `payment_type`, `installment_type`, `balance`, `created_at`, `status`, `payment_date`) VALUES
(1, 71, 5428.00, 'Installment (20% DP)', NULL, '1875344191', NULL, NULL, NULL, 21712.00, '2025-04-30 06:14:15', 2, '2025-04-30'),
(2, 71, 10290.00, 'Installment (20% DP)', NULL, '7411292207', NULL, NULL, NULL, 11422.00, '2025-04-30 06:14:56', 2, '2025-04-30'),
(3, 71, 10290.00, 'Installment (30% DP)', NULL, '8189449982', NULL, NULL, NULL, 1132.00, '2025-04-30 06:15:51', 2, '2025-04-30'),
(4, 71, 1132.00, 'Installment (30% DP)', NULL, '5953977331', NULL, NULL, NULL, 0.00, '2025-04-30 06:28:01', 2, '2025-04-30');

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
  `email` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `first_name`, `last_name`, `contact_number`, `email`, `verification_token`, `is_verified`, `verification_expires`) VALUES
(1, 'superadmin', '$2y$10$69EojGWI.uqaTxg.aI9x6eS7yqjVV3ZLX1aFNLbzX1vWVDrK1FovC', 'superadmin', 'super', 'admin', NULL, NULL, NULL, 1, NULL),
(2, 'registrar', '$2y$10$RrsqT3vi7fYmnXN62Za6O.Ub.izlebiemWVAOyw.F1JMFPsvFGmgq', 'registrar', 'admin', 'registrar', NULL, NULL, NULL, 1, NULL),
(14, 'accounting', '$2y$10$5UZf2nJ4OSajZfGi7DZ.DOT6FHe1zmDA7W7HEAlYzefQVPLUwSgC2', 'accounting', 'sample', 'accounting', '123', 'sampleaccounting@gmail.com', NULL, 1, NULL),
(15, 'teacher', '$2y$10$mjuZYXPx1OmqUZZxzQTTMum6B55TVCT7UbVQCrksbdMaB8tw6DC5a', 'teacher', 'Maria ', 'Reyes', '123', '123@teacher.com', NULL, 1, NULL),
(20, 'sample321', '$2y$10$Ci2VONYvbimQu7qUYPrGc.YSzbrCQgVHmfobnYO4jmg1kQRFjUy6W', 'parent', 'sample', 'sample', '123', 'louiseruzzelep@gmail.com', NULL, 1, NULL),
(21, 'sample1', '$2y$10$mbiv2nTlVNqp65bCEFA1H.BdO4l158o6vr/n21PXwvw7/bDT6lx42', 'student', 'sample', 'sample', '123', '321@sample.com', NULL, 1, NULL),
(25, '147', '$2y$10$7PkM/DEbTTVa1mFOEBzIu.3OskiU4MSsqSoUTu.IOMsfighH36Obe', 'accounting', '12313', '23123', '123213123', '213123@gmail.com', NULL, 1, NULL),
(26, 'teacher1', '$2y$10$WunjE3B/r0JPAgp8WRjyGezKSoU7O6w.BCGrqpxpWFn/FeKa35Rai', 'teacher', 'John', 'Doe', '123', 'teacher@gmail.com', NULL, 1, NULL),
(27, 'louise', '$2y$10$aQVhXYY9PwLEiBEPxto3luB4lP.UafdroDhDn2ly3yHEHRM/YdyDC', 'student', 'lou', 'pon', '099999', 'knize.gaming@gmail.com', NULL, 1, NULL),
(29, '098765432123', '$2y$10$YKJ9./xIL4O26.G1aF9afexuWx10u3XbB.Xd4faBixLKKpgvt/dUK', 'parent', 'louise ruzzele', 'ponciano', '09917139528', 'louiseruzzelep@gmail.com', NULL, 1, NULL),
(71, 'shiloheugenio', '$2y$10$Y56wls/wZhNtjnTr9ikAyubMusF9klcdyJGVGjzoPPeZ8pUPHzoJm', 'student', 'Shiloh', 'Eugenio', '09102258953', 'shiloheugenio21@gmail.com', NULL, 1, NULL),
(72, 'shiii', '$2y$10$VSKtsgFkEMVbRj4lyW3HEet7TBOox9Ql3fwrzrC9e34VlwWbEPaT.', 'student', 'Jhoanna', 'Robles', '09102258953', 'seugenio.k11833184@umak.edu.ph', NULL, 1, NULL),
(73, 'arrianecamille21', '$2y$10$jwzL4PPjFMfsxjj2Qj2/1.sbx.pv1CES9/4cKiXdEbM4XSii/Ihti', 'student', 'Arrianne ', 'Camille', '09631158796', 'yenaaacuteee@gmail.com', NULL, 1, NULL),
(74, 'shieugenio', '$2y$10$qMtFfk38BMgtw5SUeb/J3.S129cRJoUcmDjIPKL0haT0TxdMmr/0a', 'student', 'Aaron', 'Doe', '09168759399', 'example-email@gmail.com', 'bd0652805d9888363f75bf6bac68d221f416f1512ed6bff0867d641e8a4f5c95', 0, '2025-04-12 22:08:55'),
(75, 'edpenalber', '$2y$10$xcRx9sJCGGVxQrXB9h7yEuevLKN/B7EVP660eLZTWnb2qzj0607Xa', 'student', 'Ed', 'Penalber', '09457878924', 'iveforever6@gmail.com', NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_year`
--
ALTER TABLE `academic_year`
  ADD PRIMARY KEY (`academic_year_id`);

--
-- Indexes for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  ADD PRIMARY KEY (`approvalschedule_id`);

--
-- Indexes for table `encodedgrades`
--
ALTER TABLE `encodedgrades`
  ADD PRIMARY KEY (`encodedgrades_id`),
  ADD KEY `fk_encodedgrades_user` (`userId`);

--
-- Indexes for table `encodedstudentsubjects`
--
ALTER TABLE `encodedstudentsubjects`
  ADD PRIMARY KEY (`encoded_id`);

--
-- Indexes for table `enrollmentschedule`
--
ALTER TABLE `enrollmentschedule`
  ADD PRIMARY KEY (`enrollmentschedule_id`);

--
-- Indexes for table `father_information`
--
ALTER TABLE `father_information`
  ADD PRIMARY KEY (`father_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `generatedcor`
--
ALTER TABLE `generatedcor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gradelevel`
--
ALTER TABLE `gradelevel`
  ADD PRIMARY KEY (`gradelevel_id`);

--
-- Indexes for table `mother_information`
--
ALTER TABLE `mother_information`
  ADD PRIMARY KEY (`mother_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `fk_rooms_gradelevel` (`gradelevel_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_grade_level` (`grade_level_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `grade_level_id` (`grade_level_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `academic_year`
--
ALTER TABLE `academic_year`
  MODIFY `academic_year_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  MODIFY `approvalschedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `encodedgrades`
--
ALTER TABLE `encodedgrades`
  MODIFY `encodedgrades_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `encodedstudentsubjects`
--
ALTER TABLE `encodedstudentsubjects`
  MODIFY `encoded_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrollmentschedule`
--
ALTER TABLE `enrollmentschedule`
  MODIFY `enrollmentschedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `father_information`
--
ALTER TABLE `father_information`
  MODIFY `father_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `generatedcor`
--
ALTER TABLE `generatedcor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gradelevel`
--
ALTER TABLE `gradelevel`
  MODIFY `gradelevel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mother_information`
--
ALTER TABLE `mother_information`
  MODIFY `mother_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `encodedgrades`
--
ALTER TABLE `encodedgrades`
  ADD CONSTRAINT `fk_encodedgrades_user` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `father_information`
--
ALTER TABLE `father_information`
  ADD CONSTRAINT `father_information_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `mother_information`
--
ALTER TABLE `mother_information`
  ADD CONSTRAINT `mother_information_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_gradelevel` FOREIGN KEY (`gradelevel_id`) REFERENCES `gradelevel` (`gradelevel_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `gradelevel` (`gradelevel_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`grade_level_id`) REFERENCES `gradelevel` (`gradelevel_id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
