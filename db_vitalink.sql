-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 12:06 PM
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
-- Database: `db_vitalink`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `email`, `password`) VALUES
(1, 'admin1@vitalink.com', 'admin1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employees`
--

CREATE TABLE `tbl_employees` (
  `id` int(10) NOT NULL,
  `employee_id` int(10) NOT NULL,
  `employee_email` varchar(50) NOT NULL,
  `employee_password` varchar(25) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `position` varchar(50) NOT NULL,
  `contact_num` bigint(11) NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employees`
--

INSERT INTO `tbl_employees` (`id`, `employee_id`, `employee_email`, `employee_password`, `firstname`, `lastname`, `position`, `contact_num`, `address`) VALUES
(1, 1, 'employee1test@vitalink.com', 'employee1test', 'Juan', 'De la Cruz', 'Staff', 1111111111, 'Manila, Philippines'),
(2, 2, 'employee2test@vitalink.com', 'employee2test', 'Maria', 'De la Cruz', 'Staff', 22222222222, 'Manila, Philippines'),
(3, 3, 'saludo03@vitalink.com', '$2y$10$phWv79ap1tLfyg4vTN', 'John Benedict', 'Saludo', 'Supervisor', 9762855733, 'Cainta, Rizal'),
(4, 4, 'smith04@vitalink.com', '$2y$10$sBf7gwbGq6rwY0taNU', 'Alice', 'Smith', 'Inventory Manager', 9186659776, 'Pasig, Metro Manila'),
(5, 5, 'jones05@vitalink.com', '$2y$10$XZk8.8cvdzJRGyYpvD', 'Bob', 'Jones', 'Supply Clerk', 9182345678, 'Mandaluyong, Metro Manila');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `supplier_email` varchar(50) NOT NULL,
  `supplier_number` bigint(11) NOT NULL,
  `contract_startdate` varchar(50) NOT NULL,
  `contract_enddate` varchar(50) NOT NULL,
  `contract_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`id`, `supplier_name`, `contact_person`, `supplier_email`, `supplier_number`, `contract_startdate`, `contract_enddate`, `contract_status`) VALUES
(1, 'MedSupply Inc.', 'John Carter', 'john@medsupply.com', 9179876543, '2024-01-01', '2026-01-01', 'Active'),
(2, 'HealthPlus Corp.', 'Lisa Adams', 'lisa@healthplus.com', 9178889966, '2023-12-01', '2027-12-01', 'Active'),
(3, 'VitalMed Ltd.', 'Mark Benson', 'mark@vitalmed.com', 9175554433, '2024-02-15', '2025-03-15', 'Active'),
(4, 'LifeCare Partners', 'Susan Miller', 'susan@lifecare.com', 9172223344, '2023-12-07', '2025-11-29', 'Active'),
(5, 'Materials Inc.', 'Kim Jennie', 'jennie@materials.com', 9826733212, '2023-11-20', '2024-12-01', 'Expired');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplychain`
--

CREATE TABLE `tbl_supplychain` (
  `id` int(20) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `quantity` int(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL,
  `delivery_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_supplychain`
--

INSERT INTO `tbl_supplychain` (`id`, `item_name`, `quantity`, `supplier_id`, `status`, `delivery_status`) VALUES
(1, 'Surgical Masks', 5000, 1, 'In Stock', 'Not Ordered'),
(2, 'Latex Gloves', 2500, 1, 'In Stock', 'Not Ordered'),
(3, 'Hand Sanitizers', 2000, 2, 'In Stock', 'Not Ordered'),
(4, 'Digital Thermometers', 20, 2, 'Low Stock', 'Delivering'),
(5, 'Face Shields', 1000, 3, 'In Stock', 'Pending'),
(6, 'PPE Suits', 100, 3, 'Low Stock', 'Pending'),
(7, 'Syringes (5mL)', 8000, 4, 'In Stock', 'Not Ordered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_employees`
--
ALTER TABLE `tbl_employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_supplychain`
--
ALTER TABLE `tbl_supplychain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_employees`
--
ALTER TABLE `tbl_employees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_supplychain`
--
ALTER TABLE `tbl_supplychain`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_supplychain`
--
ALTER TABLE `tbl_supplychain`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `tbl_supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
