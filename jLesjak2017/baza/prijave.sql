-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2017 at 11:24 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prijave`
--

-- --------------------------------------------------------

--
-- Table structure for table `mentor`
--

CREATE TABLE `mentor` (
  `MentorID` int(11) NOT NULL,
  `Ime_mentorja` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek_mentorja` varchar(30) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `mentor`
--

INSERT INTO `mentor` (`MentorID`, `Ime_mentorja`, `Priimek_mentorja`) VALUES
(24, 'James', 'Smith'),
(25, 'Robert', 'Johnson'),
(26, 'Michael', 'Davis'),
(27, 'Linda', 'Anderson'),
(28, 'Mary', 'Clark');

-- --------------------------------------------------------

--
-- Table structure for table `uporabnik`
--

CREATE TABLE `uporabnik` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `MentorID` int(11) DEFAULT NULL,
  `privilegij` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uporabnik`
--

INSERT INTO `uporabnik` (`userID`, `username`, `password`, `MentorID`, `privilegij`, `email`) VALUES
(77, 'JSmith91', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', 24, 1, 'jamesmith@gmail.com'),
(78, 'admin', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', NULL, 1, ''),
(79, 'Robson', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', 25, 0, 'robbi@gmail.com'),
(80, 'linderson512', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', 27, 0, ''),
(81, 'michael128', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', 26, 0, 'davis113@gmail.com'),
(82, 'meri123', 'f03832f57d685855772655d066924d9dafa9512d1cefff1c1d19e2e447db6de7a8a0a2e4612a7e03bef234b4994a3a994366d5bdf3161bcdbc4eb394e695ffbf', 28, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `vloga`
--

CREATE TABLE `vloga` (
  `EMSO` char(13) COLLATE utf8_slovenian_ci NOT NULL,
  `Ime` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `Izobrazevalni_program` enum('Tehnik računalništva','Elektrotehnik') COLLATE utf8_slovenian_ci NOT NULL,
  `Razred` char(7) COLLATE utf8_slovenian_ci NOT NULL,
  `Naslov_naloge` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `Naslov_naloge_eng` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `Opis_naloge` varchar(1000) COLLATE utf8_slovenian_ci NOT NULL,
  `Datum` date NOT NULL,
  `Podpis_kandidata` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `MentorID` int(11) NOT NULL,
  `Odobritev_mentor` enum('Nepregledano','Odobreno','Zavrnjeno') COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'Nepregledano',
  `Odobritev_komisija` enum('Nepregledano','Odobreno','Zavrnjeno') COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'Nepregledano'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `vloga`
--

INSERT INTO `vloga` (`EMSO`, `Ime`, `Priimek`, `Izobrazevalni_program`, `Razred`, `Naslov_naloge`, `Naslov_naloge_eng`, `Opis_naloge`, `Datum`, `Podpis_kandidata`, `MentorID`, `Odobritev_mentor`, `Odobritev_komisija`) VALUES
('0404998500048', 'Anže', 'Škerjanc', 'Elektrotehnik', 'E4B', 'asd', 'asd', 'asd', '2017-04-11', 'asd', 26, 'Nepregledano', 'Nepregledano'),
('101006500006', 'Daniel', 'Harris', 'Tehnik računalništva', 'Odrasli', 'Aplikacija', 'Application', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', '2017-01-31', 'danny', 25, 'Odobreno', 'Odobreno'),
('1707998500214', 'Jordan', 'Lesjak', 'Tehnik računalništva', 'R4C', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'consectetur adipiscing elit. Sed porttitor placerat magna sed sollicitudin. Vestibulum mattis urna quis purus feugiat, ut dapibus tortor congue. In hac habitasse platea dictumst.', '2017-04-01', 'jordan', 26, 'Odobreno', 'Zavrnjeno'),
('2342342342342', 'William', 'Moore', 'Tehnik računalništva', 'R4D', 'Zvočnik', 'Loudspeaker', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur', '2017-01-03', 'WillyM', 26, 'Zavrnjeno', 'Nepregledano'),
('2609998500097', 'Tilen', 'Gombač', 'Elektrotehnik', 'E4D', 'Programski jezik', 'Programming language', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur', '2017-02-13', 'Gombi', 25, 'Odobreno', 'Nepregledano'),
('2902932505526', 'Stacy', 'Lewis', 'Tehnik računalništva', 'R4C', 'Arduino robot', 'Arduino robot', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident', '2017-02-11', 'stacie', 27, 'Zavrnjeno', 'Nepregledano'),
('5656565656565', 'David', 'Waters', 'Elektrotehnik', 'E4A', 'Igra kača', 'Snake game', 'Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est,', '2017-01-03', 'dav', 26, 'Odobreno', 'Nepregledano'),
('6546546546546', 'Mark', 'Hall', 'Tehnik računalništva', 'R4D', 'Voltmeter', 'Voltmeter', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness', '2016-12-10', 'mark', 26, 'Odobreno', 'Odobreno'),
('6546548546549', 'Thomas', 'Jones', 'Tehnik računalništva', 'R4D', 'Igra tetris', 'Tetris game', 'Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo', '2016-12-24', 'tomaž', 26, 'Odobreno', 'Zavrnjeno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mentor`
--
ALTER TABLE `mentor`
  ADD PRIMARY KEY (`MentorID`),
  ADD KEY `MentorID` (`MentorID`);

--
-- Indexes for table `uporabnik`
--
ALTER TABLE `uporabnik`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `MentorID` (`MentorID`),
  ADD UNIQUE KEY `MentorID_2` (`MentorID`),
  ADD UNIQUE KEY `MentorID_4` (`MentorID`),
  ADD UNIQUE KEY `MentorID_5` (`MentorID`),
  ADD KEY `MentorID_3` (`MentorID`) USING BTREE;

--
-- Indexes for table `vloga`
--
ALTER TABLE `vloga`
  ADD PRIMARY KEY (`EMSO`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mentor`
--
ALTER TABLE `mentor`
  MODIFY `MentorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `uporabnik`
--
ALTER TABLE `uporabnik`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
