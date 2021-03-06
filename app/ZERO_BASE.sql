-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 31-Out-2016 às 02:08
-- Versão do servidor: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `album_charts`
--

CREATE TABLE `album_charts` (
  `id` int(11) NOT NULL,
  `idweek` int(11) NOT NULL,
  `album` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `alb_mbid` varchar(255) NOT NULL,
  `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `art_mbid` varchar(255) NOT NULL,
  `playcount` int(11) NOT NULL,
  `rank` int(6) NOT NULL,
  `updated` bigint(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `artist_charts`
--

CREATE TABLE `artist_charts` (
  `id` int(11) NOT NULL,
  `idweek` int(11) NOT NULL,
  `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `art_mbid` varchar(255) NOT NULL,
  `playcount` int(11) NOT NULL,
  `rank` int(5) NOT NULL,
  `updated` bigint(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `friend`
--

CREATE TABLE `friend` (
  `id` int(11) NOT NULL,
  `iduser_one` int(11) NOT NULL,
  `iduser_two` int(11) NOT NULL,
  `accepted` int(11) NOT NULL,
  `notified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `music_charts`
--

CREATE TABLE `music_charts` (
  `id` int(11) NOT NULL,
  `idweek` int(11) NOT NULL,
  `music` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mus_mbid` varchar(255) DEFAULT NULL,
  `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `art_mbid` varchar(255) NOT NULL,
  `playcount` int(11) NOT NULL,
  `rank` int(7) NOT NULL,
  `updated` bigint(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plaque`
--

CREATE TABLE `plaque` (
  `id` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `certified` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `art_limit` int(11) NOT NULL,
  `alb_limit` int(11) NOT NULL,
  `mus_limit` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `show_images` int(2) NOT NULL,
  `show_dropouts` int(2) NOT NULL,
  `show_first_image` int(2) NOT NULL,
  `show_move` int(2) NOT NULL,
  `show_playcounts` int(2) NOT NULL,
  `lang` int(11) NOT NULL,
  `alb_cert_gold` int(11) NOT NULL,
  `alb_cert_platinum` int(11) NOT NULL,
  `alb_cert_diamond` int(11) NOT NULL,
  `mus_cert_gold` int(11) NOT NULL,
  `mus_cert_platinum` int(11) NOT NULL,
  `mus_cert_diamond` int(11) NOT NULL,
  `show_cert` int(11) NOT NULL,
  `show_chart_cert` int(11) NOT NULL,
  `show_plaque` int(11) NOT NULL,
  `cert_type` int(11) NOT NULL,
  `show_times` int(11) NOT NULL,
  `theme` int(11) NOT NULL,
  `show_points` int(11) NOT NULL,
  `hide_livechart` int(11) NOT NULL,
  `visibility` int(11) NOT NULL,
  `start_day` int(11) NOT NULL,
  `show_wkl_cert` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lfm_register` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `week`
--

CREATE TABLE `week` (
  `id` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `from_day` date NOT NULL,
  `to_day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album_charts`
--
ALTER TABLE `album_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_alb_week_1` (`idweek`);

--
-- Indexes for table `artist_charts`
--
ALTER TABLE `artist_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_art_week_1` (`idweek`);

--
-- Indexes for table `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `music_charts`
--
ALTER TABLE `music_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_mus_week_1` (`idweek`);

--
-- Indexes for table `plaque`
--
ALTER TABLE `plaque`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_sett_1` (`iduser`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `week`
--
ALTER TABLE `week`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_week_1` (`iduser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album_charts`
--
ALTER TABLE `album_charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `artist_charts`
--
ALTER TABLE `artist_charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `friend`
--
ALTER TABLE `friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `music_charts`
--
ALTER TABLE `music_charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plaque`
--
ALTER TABLE `plaque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `week`
--
ALTER TABLE `week`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
