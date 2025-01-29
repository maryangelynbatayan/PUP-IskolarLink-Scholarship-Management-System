-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2025 at 07:28 AM
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
-- Database: `iskolarlink_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'san@gmail.com', '$2y$10$fOb2rQ22SwvCq03ztI1sguAHfCFwnEE7agz54h.5dlzeE7AlBufUK', 'aso', '2025-01-15 12:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `num_slots` int(11) DEFAULT NULL,
  `eligible_courses` text DEFAULT NULL,
  `application_link` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`id`, `title`, `description`, `num_slots`, `eligible_courses`, `application_link`, `image_url`, `created_at`, `updated_at`) VALUES
(7, 'testing', 'testing', 1234, 'qeawewq', 'https://www.youtube.com/watch?v=xvFZjo5PgG0', 'https://the-post-assets.sgp1.digitaloceanspaces.com/2023/03/DOST-SCHOLARSHIP_thumbnail.png', '2025-01-25 06:10:24', '2025-01-25 06:10:24'),
(8, 'testing2', 'testing2', 12, 'BSIT', 'https://www.youtube.com/watch?v=OVY-Ov6VCXA', 'https://www.britishcouncil.ph/sites/default/files/styles/bc-landscape-800x450/public/solas_ched.png?itok=enQGeCIB', '2025-01-25 06:14:32', '2025-01-25 06:14:32'),
(9, 'testing3', 'testing 3', 2, 'BSCpE', 'https://www.youtube.com/shorts/r4xyvd0gtPY', 'https://cdn11.bigcommerce.com/s-55de4/images/stencil/608x608/products/1545/3761/0018018_san-miguel-steinie-beer-bottle_600__74384.1587872693.jpg?c=2', '2025-01-25 06:17:26', '2025-01-25 06:17:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `student_number`, `password`, `created_at`, `gender`, `date_of_birth`, `place_of_birth`, `profile_img`, `contact_number`, `address`) VALUES
(8, 'Angela', 'Calasan Malinay', 'angelacmalinay@iskolarngbayan.pup.edu.ph', '2022-08315-MN-0', '1226Angela!', '2025-01-24 12:48:55', 'Female', '2002-12-26', 'Quezon City', 'uploads/profile_8.jpg', '09666409817', '98 A 18TH Avenue Cubao, Quezon Citys'),
(9, 'Jasper', 'Peñascosa Lorredo', 'jasperplorredo@iskolarngbayan.pup.edu.ph', '2022-08449-MN-0', '0628Jasper!', '2025-01-24 12:48:55', 'Male', '2004-06-28', 'Quezon City', NULL, '09206143002', '49 - B Alley 4 2nd West Crame, San Juan City'),
(10, 'Kyle Andrei', 'Domanico de Gracia', 'kyleandreiddegracia@iskolarngbayan.pup.edu.ph', '2022-08295-MN-0', '1229Kyle!', '2025-01-24 12:48:55', 'Male', '2003-12-29', 'Quezon City', NULL, '09472137760', '#231 J.P Rizal Street, Barangay Bayanihan, Project 4, Quezon City'),
(11, 'Meleathe Grace', 'Tantay Dantes', 'meleathegracetdantes@iskolarngbayan.pup.edu.ph', '2022-08010-MN-0', '1122Meleathe!', '2025-01-24 12:48:55', 'Female', '2004-11-22', 'Pasay City', NULL, '09209080709222', 'Silk Residences Santol Street, Manila');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
