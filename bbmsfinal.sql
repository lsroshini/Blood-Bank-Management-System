-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2023 at 07:14 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbm`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStockDetails` ()   BEGIN
    SELECT * FROM stock;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTransactionData` ()   BEGIN
    SELECT
        t.donor_id AS 'Donor ID',
        CONCAT(d.first_name, ' ', d.last_name) AS 'Donor Name',
        t.receiver_id AS 'Receiver ID',
        CONCAT(r.first_name, ' ', r.last_name) AS 'Receiver Name',
        d.blood_group AS 'Blood Group',
        t.transaction_date AS 'Transaction Date'
    FROM
        transactions t
    INNER JOIN
        donors d ON t.donor_id = d.donor_id
    INNER JOIN
        receivers r ON t.receiver_id = r.receiver_id;
END$$

CREATE TRIGGER after_insert_camp_donation
AFTER INSERT ON camp_donations_record
FOR EACH ROW
BEGIN
    -- Update units_collected in blood_donation_camp
    UPDATE blood_donation_camp
    SET units_collected = units_collected + 1
    WHERE drive_id = NEW.drive_id;

    -- Update units_available in stock
    UPDATE stock
    SET units_available = units_available + 1
    WHERE blood_group = (
        SELECT blood_group
        FROM blood_camp_donors
        WHERE aadhar_number = NEW.donor_aadhar
    );
END;


-- --------------------------------------------------------

--
-- Table structure for table `blood_camp_donors`
--

CREATE TABLE `blood_camp_donors` (
  `aadhar_number` varchar(12) NOT NULL CHECK (octet_length(`aadhar_number`) = 12),
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` varchar(6) DEFAULT NULL CHECK (`gender` in ('Male','Female','Other')),
  `phone` varchar(10) DEFAULT NULL CHECK (octet_length(`phone`) = 10),
  `dob` date DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL CHECK (`blood_group` in ('A+','A-','B+','B-','AB+','AB-','O+','O-'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_camp_donors`
--

INSERT INTO `blood_camp_donors` (`aadhar_number`, `fname`, `lname`, `gender`, `phone`, `dob`, `blood_group`) VALUES
('789012345688', 'Saanvi', 'B', 'Female', '9878945454', '2000-07-21', 'A+'),
('789012345699', 'Zoya', 'M', 'Female', '9878946666', '2000-07-01', 'B+');

-- --------------------------------------------------------

--
-- Table structure for table `blood_donation_camp`
--

CREATE TABLE `blood_donation_camp` (
  `drive_id` int(11) NOT NULL,
  `drive_name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `units_collected` int(11) DEFAULT 0 CHECK (`units_collected` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_donation_camp`
--

INSERT INTO `blood_donation_camp` (`drive_id`, `drive_name`, `location`, `start_date`, `end_date`, `units_collected`) VALUES
(1, 'Be a hero, donate blood', 'Coimbatore', '2023-11-06', '2023-11-08', 8),
(5, 'Share the gift of life', 'Coimbatore', '2023-11-07', '2023-11-08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `camp_donations_record`
--

CREATE TABLE `camp_donations_record` (
  `drive_id` int(11) NOT NULL,
  `donor_aadhar` varchar(12) NOT NULL CHECK (octet_length(`donor_aadhar`) = 12),
  `donation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `camp_donations_record`
--

INSERT INTO `camp_donations_record` (`drive_id`, `donor_aadhar`, `donation_date`) VALUES
(1, '789012345688', '2023-11-07'),
(5, '789012345699', '2023-11-07');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `aadhar_number` varchar(12) NOT NULL CHECK (octet_length(`aadhar_number`) = 12),
  `gender` varchar(6) NOT NULL CHECK (`gender` in ('Male','Female','Other')),
  `phone` varchar(10) NOT NULL CHECK (octet_length(`phone`) = 10),
  `city` varchar(50) NOT NULL,
  `locality` varchar(100) NOT NULL,
  `blood_group` varchar(5) NOT NULL CHECK (`blood_group` in ('A+','A-','B+','B-','AB+','AB-','O+','O-')),
  `dob` date NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `number_of_donations` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `first_name`, `last_name`, `aadhar_number`, `gender`, `phone`, `city`, `locality`, `blood_group`, `dob`, `last_donation_date`, `number_of_donations`) VALUES
(3, 'Himani', 'K H', '123456789013', 'female', '9384734878', 'Coimbatore', 'Kalapatti', 'B-', '2004-12-29', '2023-01-12', 0),
(5, 'Aadhya', 'R', '901234567890', 'Female', '9988765674', 'Coimbatore', 'Kalapatti', 'O-', '1998-07-27', NULL, 0),
(7, 'Aaradhya', 'S', '901234567891', 'Female', '9988765677', 'Coimbatore', 'Kalapatti', 'A+', '1998-07-11', '2023-01-29', 0),
(8, 'Ananya', 'V', '789012345876', 'Female', '9488074887', 'Coimbatore', 'Kalapatti', 'A+', '1999-07-16', '2023-11-07', 4);

-- --------------------------------------------------------

--
-- Table structure for table `receivers`
--

CREATE TABLE `receivers` (
  `receiver_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL CHECK (`gender` in ('Male','Female','Other')),
  `phone` varchar(10) NOT NULL CHECK (octet_length(`phone`) = 10),
  `blood_group` varchar(5) NOT NULL CHECK (`blood_group` in ('A+','A-','B+','B-','AB+','AB-','O+','O-')),
  `guardian_name` varchar(100) NOT NULL,
  `guardian_phone` varchar(10) NOT NULL CHECK (octet_length(`guardian_phone`) = 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receivers`
--

INSERT INTO `receivers` (`receiver_id`, `first_name`, `last_name`, `dob`, `gender`, `phone`, `blood_group`, `guardian_name`, `guardian_phone`) VALUES
(1, 'lily', 'h', '2001-06-06', 'Female', '9876543210', 'B-', 'lola', '8976543210'),
(2, 'Sophia', 'J', '2010-07-07', 'Female', '9787543412', 'AB-', 'John', '9888776543'),
(3, 'Luna', 'P', '2016-06-10', 'Female', '9876987655', 'B-', 'Emily', '9876598765'),
(4, 'Diya', 'S', '2013-02-15', 'Female', '9998765432', 'A+', 'Shreya', '9998765432');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `blood_group` varchar(5) NOT NULL CHECK (`blood_group` in ('A+','A-','B+','B-','AB+','AB-','O+','O-')),
  `units_available` int(11) NOT NULL CHECK (`units_available` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`blood_group`, `units_available`) VALUES
('A+', 35),
('A-', 7),
('AB+', 3),
('AB-', 1),
('B+', 11),
('B-', 2),
('O+', 41),
('O-', 8);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `donor_id`, `receiver_id`, `transaction_date`) VALUES
(2, 8, 4, '2023-11-07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_camp_donors`
--
ALTER TABLE `blood_camp_donors`
  ADD PRIMARY KEY (`aadhar_number`);

--
-- Indexes for table `blood_donation_camp`
--
ALTER TABLE `blood_donation_camp`
  ADD PRIMARY KEY (`drive_id`);

--
-- Indexes for table `camp_donations_record`
--
ALTER TABLE `camp_donations_record`
  ADD PRIMARY KEY (`drive_id`,`donor_aadhar`),
  ADD KEY `donor_aadhar` (`donor_aadhar`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `aadhar_number` (`aadhar_number`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `receivers`
--
ALTER TABLE `receivers`
  ADD PRIMARY KEY (`receiver_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`blood_group`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_donation_camp`
--
ALTER TABLE `blood_donation_camp`
  MODIFY `drive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `receivers`
--
ALTER TABLE `receivers`
  MODIFY `receiver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `camp_donations_record`
--
ALTER TABLE `camp_donations_record`
  ADD CONSTRAINT `camp_donations_record_ibfk_1` FOREIGN KEY (`drive_id`) REFERENCES `blood_donation_camp` (`drive_id`),
  ADD CONSTRAINT `camp_donations_record_ibfk_2` FOREIGN KEY (`donor_aadhar`) REFERENCES `blood_camp_donors` (`aadhar_number`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `receivers` (`receiver_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
