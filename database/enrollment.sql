-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2025 at 12:38 AM
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
(4, 30, 41, 7, 85.00, 89.00, 89.00, 88.00, 'Approved'),
(6, 30, 41, 6, 79.00, 80.00, 76.00, 88.00, 'Approved'),
(7, 30, 41, 9, 75.00, 76.00, 77.00, 78.00, 'For Review');

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
(1, 41, 5),
(2, 41, 2),
(3, 41, 6),
(4, 41, 7),
(5, 41, 9);

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
(1, 8, '2024-11-07', '2025-03-21', 'Approved'),
(5, 1, '2002-03-05', '2024-11-30', 'Declined'),
(6, 10, '2025-02-25', '2025-06-10', 'Approved');

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
(11, 41, NULL, 'Mario F. Eugenio', '953659', '145', 'Milkweed', 'Rizal', 'Taguig City');

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
(2, 'Grade 2', 'Grade 2'),
(3, 'Grade 3', 'Grade 3'),
(4, 'Grade 4', 'Grade 4'),
(7, 'Grade 7', 'Grade 7'),
(8, 'Grade 8', 'Grade 8'),
(9, 'Grade 9', 'Grade 9'),
(10, 'Grade 10', 'Grade 10'),
(12, 'Grade 1 ', 'Grade 1');

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
(11, 41, NULL, 'Gina B. Eugenio', '665943', '145', 'Milkweed', 'Rizal', 'Taguig City');

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
  `upon_enrollment` decimal(10,2) DEFAULT NULL,
  `upon_enrollment_divided` decimal(10,2) DEFAULT NULL,
  `partial_upon_divided` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `grade_level`, `tuition_june_to_march`, `partial_upon`, `total_whole_year`, `pe_uniform`, `books`, `school_uniform`, `upon_enrollment`, `upon_enrollment_divided`, `partial_upon_divided`) VALUES
(6, '3', NULL, 5000.00, 20000.00, NULL, NULL, NULL, 10000.00, 775.00, 1165.00),
(7, '2', NULL, NULL, 1111.00, NULL, NULL, NULL, 3211.00, NULL, NULL),
(9, '3', NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL),
(11, '7', NULL, NULL, 5000.00, NULL, NULL, NULL, 1000.00, NULL, NULL),
(12, '8', NULL, NULL, 5000.00, NULL, NULL, NULL, 200.00, NULL, NULL),
(13, '8', NULL, 600.00, 5000.00, NULL, NULL, NULL, 200.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `room_description`) VALUES
(2, '102', 'Room 1021'),
(4, '105', 'Room 105'),
(5, '106', 'Room 106');

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
(2, 1, 'English', 15, '14:01:00', '15:02:00', 6, 4, 1, 'Monday'),
(5, 2, 'Math', 15, '03:30:00', '16:33:00', 7, 2, 1, 'Tuesday'),
(6, 12, 'Araling Panlipunan', 15, '16:00:00', '17:00:00', 6, 2, 8, 'Tuesday'),
(7, 3, 'English', 15, '14:00:00', '15:00:00', 10, 2, 1, 'Friday'),
(11, 2, 'English', 26, '01:22:00', '14:24:00', 9, 4, 1, 'Monday'),
(15, 8, 'Math', 15, '01:33:00', '04:36:00', 8, 2, 7, 'Monday');

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
(6, 12, 'Matulungin', 'Example Description', 51),
(8, 8, 'Jose Rizal', 'Jose Rizal', 30),
(9, 2, 'Sampaguita ', 'Sampaguita Description', 25),
(10, 3, 'Banahaw ', 'Banahaw Description', 30);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(255) NOT NULL,
  `email` varchar(55) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `student_house_number` varchar(255) NOT NULL,
  `student_street` varchar(255) NOT NULL,
  `student_barangay` varchar(255) NOT NULL,
  `student_municipality` varchar(255) NOT NULL,
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
(41, 30, 3, 'Shiloh Eugenio', '2005-03-03', 'Makati City', NULL, 19, 'Female', '145', 'Milkweed', 'Rizal', 'Taguig City', 'Arriane Camille Pamintuan', 'Fort Bonifacio High School', 'H375+C74, Dr Jose P. Rizal Ext, Taguig, Metro Manila', 2, NULL, '[\"..\\/uploads\\/Portfolio-Link.pdf\"]', '../uploads/images/unnamed.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_description`) VALUES
(1, 'English', 'English'),
(7, 'Math', 'Math Description '),
(8, 'Araling Panlipunan', 'Araling Panlipunan');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
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
(1, 30, 2025.00, 'Cash', NULL, '0158836543', NULL, NULL, NULL, 0.00, '2025-03-07 17:53:55', 2, '0000-00-00');

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
(15, 'teacher', '$2y$10$mjuZYXPx1OmqUZZxzQTTMum6B55TVCT7UbVQCrksbdMaB8tw6DC5a', 'teacher', 'Maria ', 'Reyes', '123', '123@teacher.com'),
(20, 'sample321', '$2y$10$Ci2VONYvbimQu7qUYPrGc.YSzbrCQgVHmfobnYO4jmg1kQRFjUy6W', 'parent', 'sample', 'sample', '123', 'louiseruzzelep@gmail.com'),
(21, 'sample1', '$2y$10$mbiv2nTlVNqp65bCEFA1H.BdO4l158o6vr/n21PXwvw7/bDT6lx42', 'student', 'sample', 'sample', '123', '321@sample.com'),
(25, '147', '$2y$10$7PkM/DEbTTVa1mFOEBzIu.3OskiU4MSsqSoUTu.IOMsfighH36Obe', 'accounting', '12313', '23123', '123213123', '213123@gmail.com'),
(26, 'teacher1', '$2y$10$WunjE3B/r0JPAgp8WRjyGezKSoU7O6w.BCGrqpxpWFn/FeKa35Rai', 'teacher', 'teacher1', 'teacher1', '123', 'teacher@gmail.com'),
(27, 'louise', '$2y$10$aQVhXYY9PwLEiBEPxto3luB4lP.UafdroDhDn2ly3yHEHRM/YdyDC', 'student', 'lou', 'pon', '099999', 'knize.gaming@gmail.com'),
(29, '098765432123', '$2y$10$YKJ9./xIL4O26.G1aF9afexuWx10u3XbB.Xd4faBixLKKpgvt/dUK', 'parent', 'louise ruzzele', 'ponciano', '09917139528', 'louiseruzzelep@gmail.com'),
(30, 'k11833184', '$2y$10$u8b/XLblAg69C/TRkTts0usf7A0BIqK2LvvoH8wmxhldxIlly3k8a', 'student', 'Shiloh', 'Eugenio', '09168759399', 'shiloheugenio21@gmail.com'),
(40, 'shiloheugenio21@gmail.com', '$2y$10$u8b/XLblAg69C/TRkTts0usf7A0BIqK2LvvoH8wmxhldxIlly3k8a', 'student', 'Jade', 'Bantilo', '09631158796', 'shiloheugenio21@gmail.com');

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
  ADD PRIMARY KEY (`room_id`);

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
  ADD PRIMARY KEY (`subject_id`);

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
  MODIFY `encodedgrades_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `encodedstudentsubjects`
--
ALTER TABLE `encodedstudentsubjects`
  MODIFY `encoded_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrollmentschedule`
--
ALTER TABLE `enrollmentschedule`
  MODIFY `enrollmentschedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `father_information`
--
ALTER TABLE `father_information`
  MODIFY `father_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `generatedcor`
--
ALTER TABLE `generatedcor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gradelevel`
--
ALTER TABLE `gradelevel`
  MODIFY `gradelevel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mother_information`
--
ALTER TABLE `mother_information`
  MODIFY `mother_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `gradelevel` (`gradelevel_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
