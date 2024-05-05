-- phpMyAdmin SQL Dump
-- version 5.1.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql-mitom.alwaysdata.net
-- Generation Time: Sep 28, 2022 at 02:49 AM
-- Server version: 10.6.8-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mitom_dr`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `prefix` text DEFAULT NULL,
  `sticked` text NOT NULL DEFAULT '\'n\'',
  `blocked` text NOT NULL DEFAULT '\'n\'',
  `view` int(11) NOT NULL DEFAULT 1,
  `user_view` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `comment` int(11) NOT NULL DEFAULT 0,
  `chaplist` int(11) NOT NULL DEFAULT 0,
  `chapTitle` varchar(255) NOT NULL DEFAULT '2',
  `buyfile` int(11) NOT NULL DEFAULT 0,
  `like` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `keyword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chap`
--

CREATE TABLE `chap` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `box` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cmt`
--

CREATE TABLE `cmt` (
  `id` int(11) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blogid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filecate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filesize` int(11) NOT NULL,
  `passphrase` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sei',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'private',
  `price` int(11) DEFAULT NULL,
  `saleoff` int(11) DEFAULT NULL,
  `condition` int(11) DEFAULT NULL,
  `mua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blogid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `img`
--

CREATE TABLE `img` (
  `id` int(11) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE `mail` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_receiver` varchar(255) DEFAULT NULL,
  `nick` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `update_time` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `view` varchar(255) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `author` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `blogid` int(11) DEFAULT 0,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penalty_turn`
--

CREATE TABLE `penalty_turn` (
  `id` int(10) UNSIGNED NOT NULL,
  `player1` varchar(255) NOT NULL DEFAULT 'sei',
  `ip_player1` varchar(255) DEFAULT NULL,
  `player2` varchar(255) DEFAULT NULL,
  `point` int(11) NOT NULL DEFAULT 100,
  `catch` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `result` varchar(255) DEFAULT 'chuabat',
  `point_result` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `play_dragon`
--

CREATE TABLE `play_dragon` (
  `id` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `dragon` int(1) NOT NULL,
  `name` varchar(20) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `hp` int(11) NOT NULL,
  `mp` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `shield` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `time_an` int(11) NOT NULL,
  `time_ball` int(11) NOT NULL,
  `nvan` int(11) NOT NULL,
  `nvchoi` int(11) NOT NULL,
  `nvkill` int(11) NOT NULL,
  `reward` int(11) DEFAULT 0,
  `atk` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(10) UNSIGNED NOT NULL,
  `bot` varchar(255) NOT NULL,
  `topic_bot` int(11) NOT NULL,
  `dragon` int(11) DEFAULT 0,
  `dragon_league` int(11) DEFAULT 0,
  `dragon_reward` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `bot`, `topic_bot`, `dragon`, `dragon_league`, `dragon_reward`) VALUES
(1, 'bot', 0, 0, 15552000, 15000);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `nick` varchar(255) NOT NULL DEFAULT 'sei',
  `content` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boy',
  `avt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '15',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '8',
  `new_mail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_list` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocklist` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `xu` int(11) NOT NULL DEFAULT 500,
  `mortgage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `db` int(11) NOT NULL DEFAULT 0,
  `cmt` int(11) NOT NULL DEFAULT 0,
  `reg` int(11) NOT NULL DEFAULT 0,
  `on` int(11) NOT NULL DEFAULT 0,
  `time_ban` text COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vip_exp` int(11) DEFAULT 0,
  `sft_time` int(11) DEFAULT NULL,
  `sft_level` int(11) DEFAULT 0,
  `farm` int(11) NOT NULL DEFAULT 0,
  `blog` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `do` int(11) NOT NULL DEFAULT 0,
  `frog` int(11) NOT NULL DEFAULT 0,
  `karma` int(11) NOT NULL DEFAULT 0,
  `relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'single',
  `propose` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ring` int(3) DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'desktop',
  `thememode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `style` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_farm_animals`
--

CREATE TABLE `user_farm_animals` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(1024) NOT NULL,
  `cena` varchar(1024) NOT NULL,
  `dohod` varchar(1024) NOT NULL,
  `rand1` varchar(1024) NOT NULL,
  `rand2` varchar(1024) NOT NULL,
  `oput` varchar(1024) NOT NULL,
  `time` varchar(1024) DEFAULT NULL,
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `vu` varchar(1024) NOT NULL,
  `note` varchar(1024) NOT NULL,
  `donvi` varchar(1024) NOT NULL,
  `songtrong` varchar(1024) DEFAULT NULL,
  `type` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user_farm_animals`
--

INSERT INTO `user_farm_animals` (`id`, `name`, `cena`, `dohod`, `rand1`, `rand2`, `oput`, `time`, `level`, `vu`, `note`, `donvi`, `songtrong`, `type`) VALUES
(1, 'Gà', '200', '13', '20', '30', '520', '86400', 15, '1', 'Trứng Gà', 'quả', '432000', '1'),
(2, 'Heo', '200', '28', '80', '120', '50', '172800', 22, '1', 'Thịt Heo', 'kg', '604800', '2'),
(3, 'Bò sữa', '200', '34', '51', '54', '75', '129600', 33, '1', 'Sữa Bò', 'lít', '604800', '3'),
(4, 'Cừu', '200', '43', '32', '36', '800', '216000', 40, '1', 'Lông Cừu', 'kg', '864000', '4');

-- --------------------------------------------------------

--
-- Table structure for table `user_farm_area`
--

CREATE TABLE `user_farm_area` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `item_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `end_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `water_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `grass` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `pest` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ns` tinyint(3) UNSIGNED NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user_farm_area`
--

INSERT INTO `user_farm_area` (`id`, `user_id`, `item_id`, `time`, `end_time`, `water_time`, `grass`, `pest`, `ns`) VALUES
(1, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(2, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(3, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(4, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(5, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(6, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(7, 7, 0, 0, 0, 0, 0, 0, 100),
(8, 7, 0, 0, 0, 0, 0, 0, 100),
(9, 7, 0, 0, 0, 0, 0, 0, 100),
(10, 7, 0, 0, 0, 0, 0, 0, 100),
(11, 7, 0, 0, 0, 0, 0, 0, 100),
(12, 7, 0, 0, 0, 0, 0, 0, 100),
(13, 156, 16, 1664317966, 1664497966, 1664317966, 0, 0, 100),
(14, 156, 16, 1664317966, 1664497966, 1664317966, 0, 0, 100),
(15, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(16, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(17, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(18, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(19, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(20, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(21, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(22, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(23, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(24, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(25, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(26, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(27, 19, 0, 0, 0, 0, 0, 0, 100),
(28, 19, 0, 0, 0, 0, 0, 0, 100),
(29, 19, 0, 0, 0, 0, 0, 0, 100),
(30, 19, 0, 0, 0, 0, 0, 0, 100),
(31, 19, 0, 0, 0, 0, 0, 0, 100),
(32, 19, 0, 0, 0, 0, 0, 0, 100),
(33, 158, 0, 0, 0, 0, 0, 0, 100),
(34, 158, 0, 0, 0, 0, 0, 0, 100),
(35, 158, 0, 0, 0, 0, 0, 0, 100),
(36, 158, 0, 0, 0, 0, 0, 0, 100),
(37, 158, 0, 0, 0, 0, 0, 0, 100),
(38, 158, 0, 0, 0, 0, 0, 0, 100),
(39, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(40, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(41, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(42, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(43, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(44, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(45, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(46, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(47, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(48, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(49, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(50, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(51, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(52, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(53, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(54, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(55, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(56, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(57, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(58, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(59, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(60, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(61, 7, 0, 0, 0, 0, 0, 0, 100),
(62, 26, 0, 0, 0, 0, 0, 0, 100),
(63, 26, 0, 0, 0, 0, 0, 0, 100),
(64, 26, 0, 0, 0, 0, 0, 0, 100),
(65, 26, 0, 0, 0, 0, 0, 0, 100),
(66, 26, 0, 0, 0, 0, 0, 0, 100),
(67, 26, 0, 0, 0, 0, 0, 0, 100),
(68, 76, 0, 0, 0, 0, 0, 0, 100),
(69, 76, 0, 0, 0, 0, 0, 0, 100),
(70, 76, 0, 0, 0, 0, 0, 0, 100),
(71, 76, 0, 0, 0, 0, 0, 0, 100),
(72, 76, 0, 0, 0, 0, 0, 0, 100),
(73, 76, 0, 0, 0, 0, 0, 0, 100),
(74, 137, 0, 0, 0, 0, 0, 0, 100),
(75, 137, 0, 0, 0, 0, 0, 0, 100),
(76, 137, 0, 0, 0, 0, 0, 0, 100),
(77, 137, 0, 0, 0, 0, 0, 0, 100),
(78, 137, 0, 0, 0, 0, 0, 0, 100),
(79, 137, 0, 0, 0, 0, 0, 0, 100),
(80, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(81, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(82, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(83, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(84, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(85, 142, 16, 1663390446, 1663570446, 1663498589, 0, 0, 100),
(86, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(87, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(88, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(89, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(90, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(91, 14, 15, 1663419257, 1663592057, 1663419257, 0, 0, 100),
(92, 150, 0, 0, 0, 0, 0, 0, 100),
(93, 150, 0, 0, 0, 0, 0, 0, 100),
(94, 150, 0, 0, 0, 0, 0, 0, 100),
(95, 150, 0, 0, 0, 0, 0, 0, 100),
(96, 150, 0, 0, 0, 0, 0, 0, 100),
(97, 150, 0, 0, 0, 0, 0, 0, 100),
(98, 42, 1, 1663500309, 1663503509, 1663500309, 0, 0, 100),
(99, 42, 0, 0, 0, 0, 0, 0, 100),
(100, 42, 0, 0, 0, 0, 0, 0, 100),
(101, 42, 0, 0, 0, 0, 0, 0, 100),
(102, 42, 0, 0, 0, 0, 0, 0, 100),
(103, 42, 0, 0, 0, 0, 0, 0, 100),
(104, 57, 0, 0, 0, 0, 0, 0, 100),
(105, 57, 0, 0, 0, 0, 0, 0, 100),
(106, 57, 0, 0, 0, 0, 0, 0, 100),
(107, 57, 0, 0, 0, 0, 0, 0, 100),
(108, 57, 0, 0, 0, 0, 0, 0, 100),
(109, 57, 0, 0, 0, 0, 0, 0, 100),
(110, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(111, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(112, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(113, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(114, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(115, 32, 16, 1659699917, 1659879917, 1659699917, 0, 0, 100),
(116, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(117, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(118, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(119, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(120, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(121, 145, 16, 1664295696, 1664475696, 1664295696, 0, 0, 100),
(122, 32, 16, 1659795518, 1659975518, 1659795518, 0, 0, 100),
(123, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(124, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(125, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(126, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(127, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(128, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(129, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(130, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(131, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(132, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(133, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(134, 25, 16, 1660092893, 1660272893, 1660092931, 0, 0, 100),
(135, 9, 0, 0, 0, 0, 0, 0, 100),
(136, 9, 0, 0, 0, 0, 0, 0, 100),
(137, 9, 0, 0, 0, 0, 0, 0, 100),
(138, 9, 0, 0, 0, 0, 0, 0, 100),
(139, 9, 0, 0, 0, 0, 0, 0, 100),
(140, 9, 0, 0, 0, 0, 0, 0, 100),
(141, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(142, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(143, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(144, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(145, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(146, 160, 16, 1660751216, 1660931216, 1660751250, 0, 0, 100),
(147, 64, 0, 0, 0, 0, 0, 0, 100),
(148, 64, 0, 0, 0, 0, 0, 0, 100),
(149, 64, 0, 0, 0, 0, 0, 0, 100),
(150, 64, 0, 0, 0, 0, 0, 0, 100),
(151, 64, 0, 0, 0, 0, 0, 0, 100),
(152, 64, 0, 0, 0, 0, 0, 0, 100),
(153, 64, 0, 0, 0, 0, 0, 0, 100),
(154, 64, 0, 0, 0, 0, 0, 0, 100),
(155, 64, 0, 0, 0, 0, 0, 0, 100),
(156, 144, 0, 0, 0, 0, 0, 0, 100),
(157, 144, 0, 0, 0, 0, 0, 0, 100),
(158, 144, 0, 0, 0, 0, 0, 0, 100),
(159, 144, 0, 0, 0, 0, 0, 0, 100),
(160, 144, 0, 0, 0, 0, 0, 0, 100),
(161, 144, 0, 0, 0, 0, 0, 0, 100),
(162, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(163, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(164, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(165, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(166, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(167, 6, 16, 1664210793, 1664390793, 1664292925, 0, 0, 100),
(168, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(169, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(170, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(171, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(172, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(173, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(174, 30, 0, 0, 0, 0, 0, 0, 100),
(175, 30, 0, 0, 0, 0, 0, 0, 100),
(176, 30, 0, 0, 0, 0, 0, 0, 100),
(177, 30, 0, 0, 0, 0, 0, 0, 100),
(178, 30, 0, 0, 0, 0, 0, 0, 100),
(179, 30, 0, 0, 0, 0, 0, 0, 100),
(180, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(181, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(182, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(183, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(184, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(185, 152, 16, 1663458712, 1663638712, 1663458718, 0, 0, 100),
(186, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(187, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(188, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(189, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(190, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(191, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(192, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(193, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(194, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(195, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(196, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(197, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(198, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(199, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(200, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(201, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(202, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(203, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(204, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(205, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(206, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(207, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(208, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(209, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(210, 4, 15, 1663887123, 1664059923, 1663887123, 0, 0, 100),
(211, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(212, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(213, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(214, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(215, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(216, 164, 10, 1664168903, 1664208503, 1664168903, 0, 0, 100),
(217, 124, 0, 0, 0, 0, 0, 0, 100),
(218, 124, 0, 0, 0, 0, 0, 0, 100),
(219, 124, 0, 0, 0, 0, 0, 0, 100),
(220, 124, 0, 0, 0, 0, 0, 0, 100),
(221, 124, 0, 0, 0, 0, 0, 0, 100),
(222, 124, 0, 0, 0, 0, 0, 0, 100),
(223, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(224, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(225, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(226, 44, 0, 0, 0, 0, 0, 0, 100),
(227, 44, 0, 0, 0, 0, 0, 0, 100),
(228, 44, 0, 0, 0, 0, 0, 0, 100),
(229, 44, 0, 0, 0, 0, 0, 0, 100),
(230, 44, 0, 0, 0, 0, 0, 0, 100),
(231, 44, 0, 0, 0, 0, 0, 0, 100),
(232, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(233, 27, 16, 1663226373, 1663406373, 1663226373, 0, 0, 100),
(234, 163, 15, 1662463606, 1662636406, 1662463606, 0, 0, 100),
(235, 163, 15, 1662463606, 1662636406, 1662463606, 0, 0, 100),
(236, 163, 15, 1662463606, 1662636406, 1662463606, 0, 0, 100),
(237, 163, 15, 1662463606, 1662636406, 1662463606, 0, 0, 100),
(238, 163, 0, 0, 0, 0, 0, 0, 100),
(239, 163, 0, 0, 0, 0, 0, 0, 100),
(240, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(241, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(242, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(243, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(244, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(245, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(246, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(247, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(248, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(249, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(250, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(251, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(252, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(253, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(254, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(255, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(256, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(257, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(258, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(259, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(260, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(261, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(262, 169, 15, 1662573001, 1662745801, 1662573001, 0, 0, 100),
(263, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(264, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(265, 147, 0, 0, 0, 0, 0, 0, 100),
(266, 147, 0, 0, 0, 0, 0, 0, 100),
(267, 147, 0, 0, 0, 0, 0, 0, 100),
(268, 147, 0, 0, 0, 0, 0, 0, 100),
(269, 147, 0, 0, 0, 0, 0, 0, 100),
(270, 147, 0, 0, 0, 0, 0, 0, 100),
(271, 65, 15, 1664170881, 1664343681, 1664170881, 0, 0, 100),
(272, 175, 0, 0, 0, 0, 0, 0, 100),
(273, 175, 0, 0, 0, 0, 0, 0, 100),
(274, 175, 0, 0, 0, 0, 0, 0, 100),
(275, 175, 0, 0, 0, 0, 0, 0, 100),
(276, 175, 0, 0, 0, 0, 0, 0, 100),
(277, 175, 0, 0, 0, 0, 0, 0, 100),
(278, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(279, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(280, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(281, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(282, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(283, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(284, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(285, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(286, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(287, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(288, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(289, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(290, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(291, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(292, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(293, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(294, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(295, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(296, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(297, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(298, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(299, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(300, 176, 15, 1664290472, 1664463272, 1664290501, 0, 0, 100),
(301, 43, 15, 1664285999, 1664458799, 1664285999, 0, 0, 100),
(302, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(303, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(304, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(305, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(306, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(307, 67, 1, 1663767511, 1663770711, 1663767519, 0, 0, 100),
(308, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(309, 5, 15, 1664197260, 1664370060, 1664258058, 0, 0, 100),
(310, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(311, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(312, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(313, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(314, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(315, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(316, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(317, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(318, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(319, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(320, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(321, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(322, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(323, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100),
(324, 156, 16, 1664317967, 1664497967, 1664317967, 0, 0, 100);

-- --------------------------------------------------------

--
-- Table structure for table `user_farm_compound`
--

CREATE TABLE `user_farm_compound` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'solid',
  `cuonghoa` varchar(5) NOT NULL DEFAULT 'no',
  `name` varchar(255) NOT NULL,
  `farm` int(11) DEFAULT 0,
  `botmathuat` int(11) DEFAULT 0,
  `longvu` int(11) DEFAULT 0,
  `phale` int(11) DEFAULT 0,
  `tiaset` int(11) DEFAULT 0,
  `haoquang` int(11) DEFAULT 0,
  `thongthao` int(11) DEFAULT 0,
  `c1` int(11) NOT NULL DEFAULT 0,
  `c2` int(11) NOT NULL DEFAULT 0,
  `c3` int(11) NOT NULL DEFAULT 0,
  `c4` int(11) NOT NULL DEFAULT 0,
  `c5` int(11) NOT NULL DEFAULT 0,
  `c6` int(11) NOT NULL DEFAULT 0,
  `c7` int(11) NOT NULL DEFAULT 0,
  `c8` int(11) NOT NULL DEFAULT 0,
  `c9` int(11) NOT NULL DEFAULT 0,
  `c10` int(11) NOT NULL DEFAULT 0,
  `c11` int(11) NOT NULL DEFAULT 0,
  `c12` int(11) NOT NULL DEFAULT 0,
  `c13` int(11) NOT NULL DEFAULT 0,
  `c14` int(11) NOT NULL DEFAULT 0,
  `c15` int(11) NOT NULL DEFAULT 0,
  `c16` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_farm_compound`
--

INSERT INTO `user_farm_compound` (`id`, `type`, `cuonghoa`, `name`, `farm`, `botmathuat`, `longvu`, `phale`, `tiaset`, `haoquang`, `thongthao`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`, `c7`, `c8`, `c9`, `c10`, `c11`, `c12`, `c13`, `c14`, `c15`, `c16`) VALUES
(1, 'solid', 'no', 'botmathuat', 50, 0, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 0),
(2, 'solid', 'no', 'longvu', 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15, 0, 0, 0, 0),
(3, 'solid', 'no', 'phale', 5, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20),
(4, 'solid', 'no', 'tiaset', 5, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'solid', 'no', 'haoquang', 15, 1, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 200),
(6, 'solid', 'no', 'thongthao', 5, 5, 1, 1, 1, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'liquid', 'yes', 'longvu', 0, 5, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'liquid', 'yes', 'phale', 0, 5, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'liquid', 'yes', 'tiaset', 0, 2, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'liquid', 'yes', 'haoquang', 0, 7, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'liquid', 'yes', 'thongthao', 0, 1, 0, 0, 0, 5, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_farm_crops`
--

CREATE TABLE `user_farm_crops` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(1024) NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `price` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `currency` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `min` tinyint(2) UNSIGNED NOT NULL DEFAULT 0,
  `max` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cost` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user_farm_crops`
--

INSERT INTO `user_farm_crops` (`id`, `name`, `type`, `price`, `currency`, `min`, `max`, `cost`, `time`, `level`) VALUES
(1, 'Củ cải', 1, 5, 0, 10, 20, 1, 3200, 1),
(2, 'Ngô', 1, 5, 0, 10, 360, 1, 86400, 1),
(3, 'Cà tím', 1, 5, 0, 10, 138, 1, 28800, 1),
(4, 'Cà chua', 1, 5, 0, 10, 75, 1, 14400, 1),
(5, 'Ớt', 1, 5, 0, 10, 45, 1, 3600, 1),
(6, 'Bí ngô', 1, 5, 0, 10, 80, 1, 7200, 1),
(7, 'Dâu tây', 1, 5, 0, 10, 97, 1, 18000, 1),
(8, 'Táo', 1, 5, 0, 10, 300, 1, 61200, 1),
(9, 'Dưa hấu', 1, 5, 0, 10, 138, 1, 28800, 1),
(10, 'Chuối', 1, 5, 0, 10, 180, 1, 39600, 1),
(11, 'Cam', 1, 5, 0, 10, 420, 1, 90000, 1),
(12, 'Nho', 1, 5, 0, 10, 240, 1, 57600, 1),
(13, 'Dưa lưới', 1, 5, 0, 10, 189, 1, 43200, 1),
(14, 'Dứa', 1, 5, 0, 10, 165, 1, 36000, 1),
(15, 'Lúa nước', 1, 5, 0, 10, 720, 1, 172800, 1),
(16, 'Măng cụt', 1, 5, 0, 10, 756, 1, 180000, 1),
(17, 'Khế', 0, 0, 0, 0, 100, 10, 28800, 13);

-- --------------------------------------------------------

--
-- Table structure for table `user_farm_warehouse`
--

CREATE TABLE `user_farm_warehouse` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `item_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `harvest` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_maduoc`
--

CREATE TABLE `user_maduoc` (
  `id` int(11) NOT NULL,
  `nick` varchar(255) NOT NULL,
  `botmathuat` int(11) NOT NULL DEFAULT 0,
  `longvu` int(11) NOT NULL DEFAULT 0,
  `longvu_liquid` int(11) NOT NULL DEFAULT 0,
  `phale` int(11) NOT NULL DEFAULT 0,
  `phale_liquid` int(11) NOT NULL DEFAULT 0,
  `tiaset` int(11) NOT NULL DEFAULT 0,
  `tiaset_liquid` int(11) NOT NULL DEFAULT 0,
  `haoquang` int(11) NOT NULL DEFAULT 0,
  `haoquang_liquid` int(11) NOT NULL DEFAULT 0,
  `thongthao` int(11) NOT NULL DEFAULT 0,
  `thongthao_liquid` int(11) NOT NULL DEFAULT 0,
  `level_thongthao` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_married`
--

CREATE TABLE `user_married` (
  `id` int(10) UNSIGNED NOT NULL,
  `wife` varchar(30) NOT NULL,
  `husband` varchar(30) DEFAULT NULL,
  `ring` int(3) NOT NULL DEFAULT 0,
  `ring_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `gift_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gift_list`)),
  `time_start` int(11) NOT NULL,
  `playlist` text DEFAULT NULL,
  `confirm` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_wall`
--

CREATE TABLE `user_wall` (
  `id` int(10) UNSIGNED NOT NULL,
  `wall` varchar(255) NOT NULL DEFAULT 'sei',
  `status` varchar(255) NOT NULL DEFAULT 'public',
  `author` varchar(255) NOT NULL DEFAULT 'sei',
  `content` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `youtube`
--

CREATE TABLE `youtube` (
  `id` int(11) UNSIGNED NOT NULL,
  `uploader` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `title` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `type` varchar(10) NOT NULL DEFAULT 'youtube',
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chap`
--
ALTER TABLE `chap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmt`
--
ALTER TABLE `cmt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penalty_turn`
--
ALTER TABLE `penalty_turn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `play_dragon`
--
ALTER TABLE `play_dragon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_farm_animals`
--
ALTER TABLE `user_farm_animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_farm_area`
--
ALTER TABLE `user_farm_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_farm_compound`
--
ALTER TABLE `user_farm_compound`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_farm_crops`
--
ALTER TABLE `user_farm_crops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_farm_warehouse`
--
ALTER TABLE `user_farm_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_maduoc`
--
ALTER TABLE `user_maduoc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_married`
--
ALTER TABLE `user_married`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_wall`
--
ALTER TABLE `user_wall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youtube`
--
ALTER TABLE `youtube`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chap`
--
ALTER TABLE `chap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmt`
--
ALTER TABLE `cmt`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penalty_turn`
--
ALTER TABLE `penalty_turn`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `play_dragon`
--
ALTER TABLE `play_dragon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_farm_animals`
--
ALTER TABLE `user_farm_animals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_farm_area`
--
ALTER TABLE `user_farm_area`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `user_farm_compound`
--
ALTER TABLE `user_farm_compound`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_farm_crops`
--
ALTER TABLE `user_farm_crops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_farm_warehouse`
--
ALTER TABLE `user_farm_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_maduoc`
--
ALTER TABLE `user_maduoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_married`
--
ALTER TABLE `user_married`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_wall`
--
ALTER TABLE `user_wall`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `youtube`
--
ALTER TABLE `youtube`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
