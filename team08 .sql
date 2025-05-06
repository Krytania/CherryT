-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 06, 2025 at 12:57 PM
-- Server version: 10.11.11-MariaDB-0ubuntu0.24.04.2
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `team08`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Elektronika és kiegészítők'),
(2, 'Háziállatok és felszerelések'),
(3, 'Szórakozás és hobbi'),
(4, 'Sport és szabadidő'),
(5, 'Divat és ruházat'),
(6, 'Kerti eszközök és kiegészítők'),
(7, 'Kozmetikumok és tisztálkodás'),
(8, 'Játékok és társasjátékok'),
(9, 'Háztartási cikkek és gépek'),
(10, 'Könyvek, magazinok és képregények'),
(11, 'Autó, motor és alkatrészek'),
(12, 'Szerszámok és barkácsolás'),
(13, 'Bútor és lakberendezés'),
(14, 'Babaholmi és kellékek'),
(15, 'Művészet és gyűjtemény'),
(16, 'Egyéb');

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `userid`, `code`, `datum`) VALUES
(19, 27, '21d77e4f2f991a05c63c07354c9202b8', '2025-05-01 16:55:26'),
(20, 1, '02f74f94bad03b52de1e84ac556279ab', '2025-05-01 17:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `product_id`, `user_id`) VALUES
(21, 18, 1),
(22, 23, 1),
(15, 33, 2),
(20, 43, 1),
(19, 43, 24),
(16, 50, 2),
(14, 61, 2),
(17, 61, 24),
(11, 62, 24),
(18, 63, 24);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `followed_id`) VALUES
(5, 2, 8),
(7, 2, 1),
(8, 2, 4),
(9, 2, 24),
(10, 2, 11),
(11, 2, 9),
(12, 2, 13),
(13, 2, 7),
(14, 2, 12),
(15, 2, 6),
(16, 2, 15),
(19, 2, 14),
(21, 2, 10),
(22, 2, 5),
(23, 1, 24),
(30, 1, 27),
(33, 1, 28),
(35, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `product_id` varchar(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `product_id`, `user_id`, `name`, `status`) VALUES
(5, '-', 1, 'def_user_prof.png', 'elfogadva'),
(6, '-', 2, '1745944473_6810ff9973f74.jpg', 'elfogadva'),
(7, '-', 3, 'def_user_prof.png', 'elfogadva'),
(8, '-', 4, 'def_user_prof.png', 'elfogadva'),
(9, '-', 5, 'def_user_prof.png', 'elfogadva'),
(10, '-', 6, 'def_user_prof.png', 'elfogadva'),
(11, '-', 7, 'def_user_prof.png', 'elfogadva'),
(12, '-', 8, 'def_user_prof.png', 'elfogadva'),
(13, '-', 9, 'def_user_prof.png', 'elfogadva'),
(14, '-', 10, 'def_user_prof.png', 'elfogadva'),
(15, '-', 11, 'def_user_prof.png', 'elfogadva'),
(16, '-', 12, 'def_user_prof.png', 'elfogadva'),
(17, '-', 13, 'def_user_prof.png', 'elfogadva'),
(18, '-', 14, 'def_user_prof.png', 'elfogadva'),
(19, '-', 15, 'def_user_prof.png', 'elfogadva'),
(20, '16', 1, 'iphone11.jpg', 'elfogadva'),
(21, '16', 1, 'iphone11-2.jpg', 'elfogadva'),
(22, '16', 1, 'iphone11-3.jpg', 'elfogadva'),
(23, '17', 2, 'airbuds.avif', 'elfogadva'),
(24, '17', 2, 'airbuds-2.avif', 'elfogadva'),
(25, '17', 2, 'airbuds-3.avif', 'elfogadva'),
(26, '18', 3, 'nokia3310.jpg', 'elfogadva'),
(27, '18', 3, 'nokia3310-1.jpg', 'elfogadva'),
(28, '18', 3, 'nokia3310-2.jpg', 'elfogadva'),
(29, '19', 4, 'kutyaagy.jpg', 'elfogadva'),
(30, '19', 4, 'kutyaagy-2.jpg', 'elfogadva'),
(31, '19', 4, 'kutyaagy-3.jpg', 'elfogadva'),
(32, '20', 5, 'kaparofa.jpg', 'elfogadva'),
(33, '20', 5, 'kaparofa-2.jpg', 'elfogadva'),
(34, '20', 5, 'kaparofa-3.jpg', 'elfogadva'),
(35, '21', 6, 'madarkalitka.png', 'elfogadva'),
(36, '21', 6, 'madarkalitka-2.png', 'elfogadva'),
(37, '21', 6, 'madarkalitka-3.png', 'elfogadva'),
(38, '22', 7, 'xboxone.avif', 'elfogadva'),
(39, '22', 7, 'xboxone-2.avif', 'elfogadva'),
(40, '22', 7, 'xboxone-3.avif', 'elfogadva'),
(41, '23', 8, 'akusztikusgitar.jpg', 'elfogadva'),
(42, '23', 8, 'akusztikusgitar-2.jpg', 'elfogadva'),
(43, '23', 8, 'akusztikusgitar-3.jpg', 'elfogadva'),
(44, '24', 9, 'monopoly.avif', 'elfogadva'),
(45, '24', 9, 'monopoly-2.avif', 'elfogadva'),
(46, '24', 9, 'monopoly-3.webp', 'elfogadva'),
(47, '25', 10, 'nike.jpg', 'elfogadva'),
(48, '25', 10, 'nike-2.jpg', 'elfogadva'),
(49, '25', 10, 'nike-3.jpg', 'elfogadva'),
(50, '26', 11, 'sulyzo.jpg', 'elfogadva'),
(51, '26', 11, 'sulyzo-2.jpg', 'elfogadva'),
(52, '26', 11, 'sulyzo-3.jpg', 'elfogadva'),
(53, '27', 12, 'kerekpar.webp', 'elfogadva'),
(54, '27', 12, 'kerekpar-2.webp', 'elfogadva'),
(55, '27', 12, 'kerekpar-3.webp', 'elfogadva'),
(56, '28', 13, 'borkabat.avif', 'elfogadva'),
(57, '28', 13, 'borkabat-2.avif', 'elfogadva'),
(58, '28', 13, 'borkabat-3.avif', 'elfogadva'),
(59, '29', 14, 'telikabat.avif', 'elfogadva'),
(60, '29', 14, 'telikabat-2.avif', 'elfogadva'),
(61, '29', 14, 'telikabat-3.avif', 'elfogadva'),
(62, '30', 15, 'farmer.avif', 'elfogadva'),
(63, '30', 15, 'farmer-2.avif', 'elfogadva'),
(64, '30', 15, 'farmer-3.avif', 'elfogadva'),
(65, '31', 1, 'funyiro.webp', 'elfogadva'),
(66, '31', 1, 'funyiro-2.webp', 'elfogadva'),
(67, '31', 1, 'funyiro-3.webp', 'elfogadva'),
(68, '32', 2, 'locsolotomlo.webp', 'elfogadva'),
(69, '32', 2, 'locsolotomlo-2.webp', 'elfogadva'),
(70, '32', 2, 'locsolotomlo-3.webp', 'elfogadva'),
(71, '33', 3, 'kertiszerszam.jpg', 'elfogadva'),
(72, '33', 3, 'kertiszerszam-2.jpg', 'elfogadva'),
(73, '33', 3, 'kertiszerszam3.jpg', 'elfogadva'),
(74, '34', 4, 'parfum.webp', 'elfogadva'),
(75, '34', 4, 'parfum-2.webp', 'elfogadva'),
(76, '34', 4, 'parfum-3.webp', 'elfogadva'),
(77, '35', 5, 'hajvasalo.webp', 'elfogadva'),
(78, '35', 5, 'hajvasalo-2.webp', 'elfogadva'),
(79, '35', 5, 'hajvasalo-3.webp', 'elfogadva'),
(80, '36', 6, 'sminkpaletta.webp', 'elfogadva'),
(81, '36', 6, 'sminkpaletta-2.webp', 'elfogadva'),
(82, '36', 6, 'sminkpaletta-3.webp', 'elfogadva'),
(83, '37', 7, 'legostarwars.webp', 'elfogadva'),
(84, '37', 7, 'legostarwars-2.webp', 'elfogadva'),
(85, '37', 7, 'legostarwars-3.webp', 'elfogadva'),
(86, '38', 8, 'playmobilkaloz.webp', 'elfogadva'),
(87, '38', 8, 'playmobilkaloz-2.webp', 'elfogadva'),
(88, '38', 8, 'playmobilkaloz-3.webp', 'elfogadva'),
(89, '39', 9, 'sakkkeszlet.webp', 'elfogadva'),
(90, '39', 9, 'sakkkeszlet-2.webp', 'elfogadva'),
(91, '39', 9, 'sakkkeszlet-3.webp', 'elfogadva'),
(92, '40', 10, 'mikro.webp', 'elfogadva'),
(93, '40', 10, 'mikro-2.webp', 'elfogadva'),
(94, '40', 10, 'mikro-3.webp', 'elfogadva'),
(95, '41', 11, 'vasalo.webp', 'elfogadva'),
(96, '41', 11, 'vasalo-2.webp', 'elfogadva'),
(97, '41', 11, 'vasalo-3.webp', 'elfogadva'),
(98, '42', 12, 'kavefozo.webp', 'elfogadva'),
(99, '42', 12, 'kavefozo-2.webp', 'elfogadva'),
(100, '42', 12, 'kavefozo-3.webp', 'elfogadva'),
(101, '43', 13, 'harrypotter.jfif', 'elfogadva'),
(102, '43', 13, 'harrypotter-2.jpg', 'elfogadva'),
(103, '43', 13, 'harrypotter-3.jpg', 'elfogadva'),
(104, '44', 14, 'kepregeny.png', 'elfogadva'),
(105, '44', 14, 'kepregeny-2.jpg', 'elfogadva'),
(106, '44', 14, 'kepregeny-3.jpg', 'elfogadva'),
(107, '45', 15, 'regiujsag.jpg', 'elfogadva'),
(108, '45', 15, 'regiujsag-2.jpg', 'elfogadva'),
(109, '45', 15, 'regiujsag-3.jpg', 'elfogadva'),
(110, '46', 1, 'dohanyzoasztal.avif', 'elfogadva'),
(111, '46', 1, 'dohanyzoasztal-2.avif', 'elfogadva'),
(112, '46', 1, 'dohanyzoasztal-3.avif', 'elfogadva'),
(113, '47', 2, 'allolampa.webp', 'elfogadva'),
(114, '47', 2, 'allolampa-2.webp', 'elfogadva'),
(115, '47', 2, 'allolampa-3.webp', 'elfogadva'),
(116, '48', 3, 'regiszek.webp', 'elfogadva'),
(117, '48', 3, 'regiszek-2.webp', 'elfogadva'),
(118, '48', 3, 'regiszek-3.webp', 'elfogadva'),
(119, '49', 4, 'telefontarto.avif', 'elfogadva'),
(120, '49', 4, 'telefontarto-2.avif', 'elfogadva'),
(121, '49', 4, 'telefontarto-3.avif', 'elfogadva'),
(122, '50', 5, 'motoroskesztyu.avif', 'elfogadva'),
(123, '50', 5, 'motoroskesztyu-2.avif', 'elfogadva'),
(124, '50', 5, 'motoroskesztyu-3.avif', 'elfogadva'),
(125, '51', 6, 'autoradio.jpg', 'elfogadva'),
(126, '51', 6, 'autoradio-2.jpg', 'elfogadva'),
(127, '51', 6, 'autoradio-3.jpg', 'elfogadva'),
(128, '52', 7, 'kavekapszula.jpg', 'elfogadva'),
(129, '52', 7, 'kavekapszula-2.jpg', 'elfogadva'),
(130, '52', 7, 'kavekapszula-3.avif', 'elfogadva'),
(131, '53', 8, 'hazimez.jpg', 'elfogadva'),
(132, '53', 8, 'hazimez-2.webp', 'elfogadva'),
(133, '53', 8, 'hazimez-3.jpg', 'elfogadva'),
(134, '54', 9, 'csoki.webp', 'elfogadva'),
(135, '54', 9, 'csoki-2.webp', 'elfogadva'),
(136, '54', 9, 'csoki-3.webp', 'elfogadva'),
(137, '55', 10, 'babakocsi.jpg', 'elfogadva'),
(138, '55', 10, 'babakocsi-2.jpg', 'elfogadva'),
(139, '55', 10, 'babakocsi-3.jpg', 'elfogadva'),
(140, '56', 11, 'babaruha.jpeg', 'elfogadva'),
(141, '56', 11, 'babaruha-2.webp', 'elfogadva'),
(142, '56', 11, 'babaruha-3.webp', 'elfogadva'),
(143, '57', 12, 'etetoszek.jpeg', 'elfogadva'),
(144, '57', 12, 'etetoszek-2.jpeg', 'elfogadva'),
(145, '57', 12, 'etetoszek-3.jpeg', 'elfogadva'),
(146, '58', 13, 'billentyuzet.jpg', 'elfogadva'),
(147, '58', 13, 'billentyuzet-2.jpg', 'elfogadva'),
(148, '58', 13, 'billentyuzet-3.jpg', 'elfogadva'),
(149, '59', 14, 'laptoptaska.jpg', 'elfogadva'),
(150, '59', 14, 'laptoptaska-2.jpg', 'elfogadva'),
(151, '59', 14, 'laptoptaska-3.webp', 'elfogadva'),
(152, '60', 15, 'eger.jpg', 'elfogadva'),
(153, '60', 15, 'eger-2.jpg', 'elfogadva'),
(154, '60', 15, 'eger-3.jpg', 'elfogadva'),
(155, '61', 11, 'borond.jpg', 'elfogadva'),
(156, '61', 1, 'borond-2.jpg', 'elfogadva'),
(157, '61', 1, 'borond-3.jpg', 'elfogadva'),
(158, '62', 2, 'napelemes.webp', 'elfogadva'),
(159, '62', 2, 'napelemes-2.webp', 'elfogadva'),
(160, '62', 2, 'napelemes-3.webp', 'elfogadva'),
(161, '63', 3, 'tarolodoboz.webp', 'elfogadva'),
(162, '63', 3, 'tarolodoboz-2.webp', 'elfogadva'),
(163, '63', 3, 'tarolodoboz-3.webp', 'elfogadva'),
(164, '64', 2, 'img_680d60fdbb4f93.88979734.jpg', 'folyamatban'),
(165, '64', 2, 'img_680d60fdbc57f2.61936231.jpg', 'folyamatban'),
(166, '64', 2, 'img_680d60fdbcc083.16864209.jpg', 'folyamatban'),
(168, '-', 24, '1745944487_6810ffa7adb52.jpeg', 'elfogadva'),
(169, '65', 2, 'img_680f416c3ad781.66426374.png', 'folyamatban'),
(170, '65', 2, 'img_680f416c3b5015.94322920.jpg', 'folyamatban'),
(171, '65', 2, 'img_680f416c3ba742.72231403.jpg', 'folyamatban'),
(172, '-', 25, 'def_user_prof.png', 'elfogadva'),
(173, '-', 26, '1746034191_68125e0f45a4c.jpg', 'elfogadva'),
(174, '66', 18, 'img_68125956432754.89014428.jpeg', 'folyamatban'),
(175, '66', 18, 'img_68125956438195.92115832.jpeg', 'folyamatban'),
(176, '66', 18, 'img_68125956442d92.07567623.jpeg', 'folyamatban'),
(177, '67', 1, 'img_6813ad98335c38.95985987.jpg', 'folyamatban'),
(178, '67', 1, 'img_6813ad9833afb6.48585424.jpg', 'folyamatban'),
(179, '67', 1, 'img_6813ad9833f951.16043243.jpg', 'folyamatban'),
(180, '-', 28, 'def_user_prof.png', 'elfogadva'),
(181, '68', 28, 'img_6813d5bf9543a6.76965027.jpg', 'folyamatban'),
(182, '68', 28, 'img_6813d5bf9593a0.96313605.jpg', 'folyamatban'),
(183, '68', 28, 'img_6813d5bf95dd56.32360916.jpg', 'folyamatban');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `senderid` int(11) NOT NULL,
  `receiverid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `senderid`, `receiverid`, `productid`, `message`, `created_at`) VALUES
(15, 3, 1, 63, 'Szia.', '2025-04-29 16:38:19'),
(16, 3, 1, 63, 'Meg van még?', '2025-04-29 16:38:52'),
(17, 6, 1, 63, 'Jó napot', '2025-04-30 21:04:33'),
(18, 6, 1, 63, 'Kérhetnék kicsit részletesebb képet a termékről??', '2025-04-30 21:05:52'),
(19, 1, 6, 63, 'Természetesen, egy pillanat türelmet.', '2025-04-30 21:07:17'),
(21, 1, 3, 63, 'Szia, még igen.', '2025-04-30 21:13:12'),
(22, 6, 1, 63, 'Rendben, várom', '2025-04-30 23:32:02'),
(23, 3, 1, 63, 'Jajj, de jó! :)', '2025-04-30 21:15:28'),
(24, 1, 3, 63, 'Érdekel?', '2025-04-30 21:39:32'),
(25, 1, 6, 63, 'Kis technikai malőr történt, küldöm, egy másodperc.', '2025-05-01 00:18:41'),
(26, 1, 6, 63, '...', '2025-05-01 00:19:25'),
(27, 1, 3, 63, '???', '2025-05-01 00:25:33'),
(28, 3, 1, 63, 'Itt vagyok, igen, bocsánat, csak nem voltam gépközelben.', '2025-05-01 00:27:04'),
(29, 1, 3, 63, 'Semmi gond. Félretegyem neked, vagy már nem fontos?', '2025-05-01 00:28:40'),
(30, 3, 1, 63, 'Igen, azt megköszönném.', '2025-05-01 00:39:17'),
(31, 1, 3, 63, 'Rendben, félre van téve. Tudod már, hogy mikor tudsz érte eljönni?', '2025-05-01 17:19:15'),
(32, 1, 3, 18, 'Szia. Engem felújítás szempontjából érdekelne a telefon.', '2025-05-01 17:27:29'),
(33, 1, 9, 54, 'Szia! Érdeklődnék, hogy ezek milyen csokoládék? ', '2025-05-01 18:47:42'),
(34, 28, 1, 16, 'Kis kezit csókolom', '2025-05-01 22:20:40'),
(35, 1, 28, 16, 'Jó napot. Érdekli az iPhone?', '2025-05-01 22:21:08'),
(37, 28, 1, 16, 'Jó napot \nHa hiszi, ha nem, igen', '2025-05-01 23:14:24'),
(38, 1, 28, 16, 'Mire cserélne?', '2025-05-01 23:14:48'),
(39, 28, 1, 16, 'Válasszon maga:\n3 opció van...\n1. Földelt kotyogós kávéfőző\n2. Régi bőrképek, számszerint 3\nVagy 3., van itt még egy ló, kicsit sánta, de bírja még az igát', '2025-05-01 23:16:04'),
(40, 1, 28, 16, 'Hát sajnos nem nyerte el egyik sem az érdeklődésemet, de azért köszönöm. További szép estét.', '2025-05-01 23:16:46'),
(41, 28, 1, 16, 'Majd elfelejtettem... van itt még egy hosszúszáru mezőgazdasági sportcipő is, de csak egy fél pár', '2025-05-01 23:17:00'),
(42, 1, 28, 16, 'Mit kezdjek egy fél pár cipővel?', '2025-05-01 23:17:34'),
(43, 28, 1, 16, 'Adja oda a fél lábú kalóznak', '2025-05-01 23:17:51'),
(44, 1, 28, 16, 'Egye fene, áll az alku, úgyis vízkáros a készülék.', '2025-05-01 23:18:18'),
(45, 28, 1, 16, 'Na ezt már szeretem', '2025-05-01 23:18:37'),
(46, 1, 28, 16, 'Akkor holnap délben a Csősztoronynál át tudja venni?', '2025-05-01 23:24:00'),
(47, 28, 1, 16, 'Igen. Ott találkozunk.', '2025-05-01 23:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `productid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `seen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `fromid`, `toid`, `productid`, `type`, `message`, `created_at`, `seen`) VALUES
(2, 2, 8, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:04', 0),
(4, 2, 1, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:31', 0),
(5, 2, 4, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:34', 0),
(6, 2, 24, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:37', 0),
(7, 2, 11, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:41', 0),
(8, 2, 9, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:44', 0),
(9, 2, 13, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:46', 0),
(10, 2, 7, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:49', 0),
(11, 2, 12, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:51', 0),
(12, 2, 6, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:54', 0),
(13, 2, 15, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-27 12:44:56', 0),
(16, 2, 14, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-28 08:32:58', 0),
(25, 24, 2, '62', 'favorite', 'Nonó23 a(z) \'Napelemes töltő\' termékedet kedvencekhez adta.', '2025-04-29 20:04:30', 0),
(26, 24, 2, '62', 'favorite', 'Nonó23 a(z) \'Napelemes töltő\' termékedet kedvencekhez adta.', '2025-04-29 20:05:27', 0),
(27, 24, 2, '62', 'favorite', 'Nonó23 a(z) \'Napelemes töltő\' termékedet kedvencekhez adta.', '2025-04-29 20:08:05', 0),
(28, 24, 3, '63', 'favorite', 'Nonó23 a(z) \'Műanyag tárolódoboz\' termékedet kedvencekhez adta.', '2025-04-29 20:08:52', 0),
(29, 24, 1, '61', 'favorite', 'Nonó23 a(z) \'Régi bőrönd\' termékedet kedvencekhez adta.', '2025-04-29 20:08:57', 0),
(30, 2, 1, '61', 'favorite', 'Korinna4556 a(z) \'Régi bőrönd\' termékedet kedvencekhez adta.', '2025-04-29 20:31:45', 0),
(32, 2, 10, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-29 22:07:57', 0),
(33, 2, 3, '33', 'favorite', 'Korinna4556 a(z) \'Kerti szerszámkészlet\' termékedet kedvencekhez adta.', '2025-04-29 23:13:57', 0),
(34, 2, 5, '-', 'follow', 'Korinna4556 bekövetett.', '2025-04-30 01:12:49', 0),
(35, 2, 5, '50', 'favorite', 'Korinna4556 a(z) \'Motoros kesztyű\' termékedet kedvencekhez adta.', '2025-04-30 01:13:09', 0),
(36, 24, 1, '61', 'favorite', 'Nonó23 a(z) \'Régi bőrönd\' termékedet kedvencekhez adta.', '2025-04-30 11:43:33', 0),
(37, 24, 3, '63', 'favorite', 'Nonó23 a(z) \'Műanyag tárolódoboz\' termékedet kedvencekhez adta.', '2025-04-30 11:43:51', 0),
(38, 24, 13, '43', 'favorite', 'Nonó23 a(z) \'Harry Potter könyvcsomag\' termékedet kedvencekhez adta.', '2025-04-30 11:43:56', 0),
(39, 1, 24, '-', 'follow', 'Doresztosz bekövetett.', '2025-04-30 21:14:34', 0),
(40, 1, 13, '43', 'favorite', 'Doresztosz a(z) \'Harry Potter könyvcsomag\' termékedet kedvencekhez adta.', '2025-04-30 21:51:04', 0),
(41, 1, 2, '-', 'follow', 'Doresztosz bekövetett.', '2025-04-30 21:51:16', 0),
(42, 1, 27, '-', 'follow', 'Doresztosz bekövetett.', '2025-05-01 20:09:44', 0),
(43, 1, 3, '18', 'favorite', 'Doresztosz a(z) \'Régi Nokia 3310\' termékedet kedvencekhez adta.', '2025-05-01 20:11:24', 0),
(44, 1, 8, '23', 'favorite', 'Doresztosz a(z) \'Akusztikus gitár\' termékedet kedvencekhez adta.', '2025-05-01 20:11:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `itscondition` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `accepted` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `userid`, `name`, `description`, `type`, `categoryid`, `itscondition`, `location`, `created_at`, `accepted`) VALUES
(16, 1, 'iPhone 11', 'Használt iPhone 11, enyhe karcolásokkal', 'csere', 1, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(17, 2, 'Samsung Galaxy Buds', 'Vezeték nélküli fülhallgató, bontatlan csomagolásban', 'ingyenes', 1, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(18, 3, 'Régi Nokia 3310', 'Működik, de a kijelző sérült', 'csere', 1, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(19, 4, 'Kutyaágy', 'Puha kutyaágy, közepes méretű', 'csere', 2, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(20, 5, 'Macska kaparófa', 'Újszerű állapotban lévő kaparófa', 'ingyenes', 2, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(21, 6, 'Madárkalitka', 'Kisebb sérüléssel, de használható', 'csere', 2, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(22, 7, 'Xbox One', 'Xbox One konzol + 2 játék, jó állapotban', 'csere', 3, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(23, 8, 'Akusztikus gitár', 'Kezdők számára tökéletes állapotú', 'ingyenes', 3, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(24, 9, 'Monopoly társasjáték', 'Egy figura hiányzik', 'csere', 3, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(25, 10, 'Nike futócipő', '43-as méret, alig hordott', 'csere', 4, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(26, 11, 'Súlyzókészlet', '10kg-os kézisúlyzó pár', 'ingyenes', 4, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(27, 12, 'Kerékpár', 'Régi bicikli, lánc cserére szorul', 'csere', 4, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(28, 13, 'Bőrkabát', 'Fekete, XL-es méret', 'csere', 5, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(29, 14, 'Női télikabát', 'Kiváló állapotú, meleg télikabát', 'ingyenes', 5, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(30, 15, 'Farmernadrág', 'Kisebb szakadás az egyik szárán', 'csere', 5, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(31, 1, 'Elektromos fűnyíró', 'Használt, de tökéletesen működik', 'csere', 6, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(32, 2, 'Locsolótömlő', 'Újszerű állapotban, 10 méteres', 'ingyenes', 6, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(33, 3, 'Kerti szerszámkészlet', 'Néhány szerszám hiányzik', 'csere', 6, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(34, 4, 'Bontatlan parfüm', 'Eredeti illat, női parfüm', 'csere', 7, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(35, 5, 'Hajvasaló', 'Alig használt, jól működik', 'ingyenes', 7, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(36, 6, 'Sminkpaletta', 'Néhány szín már elfogyott', 'csere', 7, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(37, 7, 'LEGO Star Wars', 'Nagyrészt teljes készlet', 'csere', 8, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(38, 8, 'Playmobil kalózhajó', 'Hiánytalan, eredeti csomagolásban', 'ingyenes', 8, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(39, 9, 'Sakk készlet', 'Néhány bábú hiányzik', 'csere', 8, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(40, 10, 'Mikrohullámú sütő', 'Jól működő, használt állapotban', 'csere', 9, 'használt', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(41, 11, 'Vasaló', 'Újszerű, alig használt', 'ingyenes', 9, 'újszerű', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(42, 12, 'Kávéfőző', 'Hibás kijelző, de működik', 'csere', 9, 'sérült', 'Budapest', '2025-03-16 12:51:55', 'elfogadva'),
(43, 13, 'Harry Potter könyvcsomag', 'Az első négy kötet, jó állapotban', 'csere', 10, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(44, 14, 'Marvel képregény gyűjtemény', 'Több régi Marvel képregény egyben', 'ingyenes', 10, 'használt', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(45, 15, 'Régi újságok', 'Néhány ritka példány, gyűjtőknek', 'csere', 10, 'sérült', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(46, 1, 'Fa dohányzóasztal', 'Masszív fa dohányzóasztal, kisebb kopásokkal', 'csere', 11, 'használt', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(47, 2, 'Modern állólámpa', 'Szinte új, minimál dizájn', 'ingyenes', 13, 'újszerű', 'Budapest 1. kerület', '2025-03-16 12:52:43', 'elfogadva'),
(48, 3, 'Régi szék', 'Egy lába kissé instabil, de még használható', 'csere', 11, 'sérült', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(49, 4, 'Autós telefontartó', 'Szélvédőre rögzíthető telefontartó', 'csere', 12, 'használt', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(50, 5, 'Motoros kesztyű', 'Bőr, alig használt', 'ingyenes', 12, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(51, 6, 'Régi autórádió', 'Működőképes, de kopott gombokkal', 'csere', 12, 'sérült', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(52, 7, 'Kávékapszula csomag', 'Többféle íz, bontatlan', 'csere', 13, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(53, 8, 'Házi méz', 'Friss, helyi termelői méz', 'ingyenes', 13, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(54, 9, 'Csokoládékülönlegességek', 'Külföldi prémium csokoládék', 'csere', 13, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(55, 10, 'Babakocsi', 'Használt, de jó állapotú', 'csere', 14, 'használt', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(56, 11, 'Bébi ruhacsomag', '0-6 hónapos babára való ruhák', 'ingyenes', 14, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(57, 12, 'Etetőszék', 'Régi, némi használati nyommal', 'csere', 14, 'sérült', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(58, 13, 'Billentyűzet', 'USB csatlakozós, működőképes', 'csere', 15, 'használt', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(59, 14, 'Laptop táska', '15 colos laptophoz, szinte új', 'ingyenes', 15, 'újszerű', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(60, 15, 'Régi egér', 'Görgője nem mindig működik', 'csere', 15, 'sérült', 'Budapest', '2025-03-16 12:52:43', 'elfogadva'),
(61, 1, 'Régi bőrönd', 'Kisebb kopásokkal, de jól használható', 'csere', 16, 'használt', 'Budapest', '2025-03-16 12:53:22', 'elfogadva'),
(62, 2, 'Napelemes töltő', 'Hordozható, alig használt', 'ingyenes', 16, 'újszerű', 'Budapest 15. kerület', '2025-03-16 12:53:22', 'elfogadva'),
(63, 3, 'Műanyag tárolódoboz', 'Pár karcolással, de még tökéletesen használható', 'csere', 16, 'sérült', 'Budapest', '2025-03-16 12:53:22', 'elfogadva'),
(64, 2, 'Mintás szőnyeg', 'Használt, de nagyon szép állapotú', 'ingyenes', 13, 'használt', 'Budapest 12. kerület', '2025-04-26 22:41:01', 'folyamatban'),
(65, 2, 'h7u9jhu8iőupők', 'lopóúoi9üi', 'csere', 13, 'sérült', 'Budapest 18. kerület', '2025-04-28 08:50:52', 'folyamatban'),
(66, 18, 'Gamer pizza', 'Finom és jó a teljesítménye.', 'csere', 1, 'használt', 'Budapest 11. kerület', '2025-04-30 17:09:42', 'folyamatban'),
(67, 1, 'Ezüst fülbevaló', 'Kihasználatlanság miatt cserélném ezüst fülbevalómat más ezüst ékszerre.', 'csere', 5, 'újszerű', 'Budapest 10. kerület', '2025-05-01 17:21:28', 'folyamatban'),
(68, 28, 'Tankcsapda CD', 'Alig használt', 'csere', 15, 'újszerű', 'Budapest 5. kerület', '2025-05-01 20:12:47', 'folyamatban');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `requesterid` int(11) NOT NULL,
  `requestedid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `requestid` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewed_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `requestid`, `reviewer_id`, `reviewed_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1625, 3, 1, 4, 'A termék jobb volt, mint vártam.', '2025-04-17 22:52:07'),
(2, 6442, 10, 1, 4, 'Minden rendben ment, ajánlom!', '2025-02-18 22:52:07'),
(3, 3212, 4, 1, 3, 'Korrekt csere volt, semmi extra.', '2025-04-10 22:52:07'),
(4, 8147, 12, 2, 2, 'Voltak problémák, de legalább megpróbálta megoldani.', '2025-04-30 22:52:07'),
(5, 2144, 27, 2, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-03-13 22:52:07'),
(6, 2671, 28, 2, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-03-04 22:52:07'),
(7, 8055, 2, 3, 2, 'Voltak problémák, de legalább megpróbálta megoldani.', '2025-03-24 22:52:07'),
(8, 8688, 4, 3, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-03-28 22:52:07'),
(9, 9547, 6, 3, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-03-18 22:52:07'),
(10, 7524, 25, 4, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-04-29 22:52:07'),
(11, 9823, 8, 4, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-02-02 22:52:07'),
(12, 7873, 26, 4, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-02-23 22:52:07'),
(13, 8977, 13, 5, 2, 'Rendben volt, de lehetett volna sokkal jobb.', '2025-04-20 22:52:07'),
(14, 6520, 26, 5, 3, 'Korrekt csere volt, semmi extra.', '2025-03-19 22:52:07'),
(15, 8483, 2, 5, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-04-21 22:52:07'),
(16, 6955, 14, 6, 2, 'Rendben volt, de lehetett volna sokkal jobb.', '2025-04-19 22:52:07'),
(17, 7647, 12, 6, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-04-01 22:52:07'),
(18, 5713, 1, 6, 5, 'Minden gyorsan és zökkenőmentesen zajlott, csak ajánlani tudom!', '2025-04-23 22:52:07'),
(19, 8282, 25, 7, 2, 'Voltak problémák, de legalább megpróbálta megoldani.', '2025-02-21 22:52:07'),
(20, 1166, 9, 7, 4, 'A termék jobb volt, mint vártam.', '2025-04-25 22:52:07'),
(21, 1344, 28, 7, 2, 'Nem volt teljesen zökkenőmentes, de nem is katasztrófa.', '2025-04-06 22:52:07'),
(22, 1005, 10, 8, 4, 'Minden rendben ment, ajánlom!', '2025-03-08 22:52:07'),
(23, 9361, 13, 8, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-04-22 22:52:07'),
(24, 2358, 28, 8, 3, 'Átlagos élmény, de minden rendben ment.', '2025-04-19 22:52:07'),
(25, 7622, 15, 9, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-02-01 22:52:07'),
(26, 8741, 1, 9, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-03-28 22:52:07'),
(27, 7784, 14, 9, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-03-19 22:52:07'),
(28, 3359, 13, 10, 4, 'Gyors és segítőkész partner, elégedett vagyok.', '2025-02-03 22:52:07'),
(29, 2284, 1, 10, 3, 'Átlagos élmény, de minden rendben ment.', '2025-03-31 22:52:07'),
(30, 9806, 6, 10, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-03-12 22:52:07'),
(31, 9764, 10, 11, 4, 'A termék jobb volt, mint vártam.', '2025-04-14 22:52:07'),
(32, 3732, 5, 11, 3, 'Átlagos élmény, de minden rendben ment.', '2025-04-15 22:52:07'),
(33, 2016, 28, 11, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-03-06 22:52:07'),
(34, 8391, 5, 12, 3, 'Pontosan azt kaptam, amit vártam.', '2025-02-04 22:52:07'),
(35, 3598, 24, 12, 3, 'Pontosan azt kaptam, amit vártam.', '2025-04-27 22:52:07'),
(36, 9433, 1, 12, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-02-23 22:52:07'),
(37, 7045, 27, 13, 5, 'Kiváló cserepartner, minden tökéletes volt.', '2025-02-20 22:52:07'),
(38, 2521, 5, 13, 2, 'Rendben volt, de lehetett volna sokkal jobb.', '2025-03-18 22:52:07'),
(39, 4330, 11, 13, 3, 'Átlagos élmény, de minden rendben ment.', '2025-03-30 22:52:07'),
(40, 4229, 11, 14, 1, 'Sérülten kaptam meg a terméket, nem ajánlom.', '2025-04-20 22:52:07'),
(41, 4181, 9, 14, 5, 'Kiváló cserepartner, minden tökéletes volt.', '2025-04-28 22:52:07'),
(42, 2394, 13, 14, 3, 'Átlagos élmény, de minden rendben ment.', '2025-04-01 22:52:07'),
(43, 9752, 11, 15, 5, 'Minden gyorsan és zökkenőmentesen zajlott, csak ajánlani tudom!', '2025-04-13 22:52:07'),
(44, 7526, 3, 15, 2, 'Nem volt teljesen zökkenőmentes, de nem is katasztrófa.', '2025-02-26 22:52:07'),
(45, 8964, 28, 15, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-02-16 22:52:07'),
(46, 1097, 11, 24, 5, 'Kiváló cserepartner, minden tökéletes volt.', '2025-02-11 22:52:07'),
(47, 6626, 13, 24, 5, 'Minden gyorsan és zökkenőmentesen zajlott, csak ajánlani tudom!', '2025-02-26 22:52:07'),
(48, 1859, 8, 24, 5, 'Kiváló cserepartner, minden tökéletes volt.', '2025-02-01 22:52:07'),
(49, 4548, 12, 25, 4, 'A termék jobb volt, mint vártam.', '2025-03-16 22:52:07'),
(50, 3960, 15, 25, 4, 'A termék jobb volt, mint vártam.', '2025-04-06 22:52:07'),
(51, 7149, 28, 25, 4, 'Gyors és segítőkész partner, elégedett vagyok.', '2025-03-08 22:52:07'),
(52, 2133, 25, 26, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-03-14 22:52:07'),
(53, 9238, 9, 26, 3, 'Korrekt csere volt, semmi extra.', '2025-03-28 22:52:07'),
(54, 7235, 6, 26, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-04-23 22:52:07'),
(55, 6319, 2, 27, 1, 'A kommunikáció nehézkes volt, és a termék sem volt megfelelő.', '2025-02-16 22:52:07'),
(56, 5356, 13, 27, 2, 'Rendben volt, de lehetett volna sokkal jobb.', '2025-03-04 22:52:07'),
(57, 6636, 10, 27, 4, 'Minden rendben ment, ajánlom!', '2025-02-16 22:52:07'),
(58, 1799, 8, 28, 5, 'Nagyon kedves és megbízható felhasználó!', '2025-03-18 22:52:07'),
(59, 8014, 15, 28, 2, 'Voltak problémák, de legalább megpróbálta megoldani.', '2025-04-28 22:52:07'),
(60, 8820, 25, 28, 4, 'Minden rendben ment, ajánlom!', '2025-04-14 22:52:07'),
(61, 9542, 1, 3, 5, 'Nagyon rendes volt, mindent meg tudtam vele beszélni. Csak ajánlani tudom.', '2025-05-02 00:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `aboutme` varchar(255) NOT NULL,
  `confirmed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `aboutme`, `confirmed`) VALUES
(1, 'Doresztosz', 'bodnardorina1@gmail.com', '$2y$10$.Tc5JwpALMouLop3t8wy5O3E0U5k/tcwGFDlfOnKwe9WG03XKI2YG', '2025-02-17 22:17:40', '20/L', 1),
(2, 'Korinna4556', 'gregkori03@gmail.com', '$2y$10$/cEjjXvqHadFWTMCiY/bCOMf.bKXogER4hzAQ6cbI75VssR/4DgIq', '2025-03-03 21:09:50', '21 éves vagyok.', 1),
(3, 'kovacs.peter', 'kovacs.peter1985@example.com', '$2y$10$g/6rbaExmXMwKggj0CjI8.R53J6xlvabUQvuxQpaoXNEE5QH7BUve', '2025-03-16 13:32:54', '', 1),
(4, ' nagy.judit', 'nagy.judit67@example.hu', '$2y$10$ef8wvxtoWb6IHM8Y1M6nC.Mr6U/8GqZRCcZImvb89hdbwQvUu4UpO', '2025-03-16 13:34:00', '', 1),
(5, 'szabo.maria', 'szabo.maria@randommail.com', '$2y$10$ss1qm1AmYVj.VSah8ZvqtOhIE6Z.6LqUNPZ2yUe5dGZPN88yUDx16', '2025-03-16 13:35:10', '', 1),
(6, 'horvath.andras', 'horvath.andras@fakemail.net', '$2y$10$eSnZdbRGfwaHyZYXY6DrQODrZCLCafPesKkVFH3s1YreLPjpHclRe', '2025-03-16 13:37:58', '', 1),
(7, 'takacs.laszlo', 'takacs.laszlo89@example.org', '$2y$10$JVdXd8RIUu.2dq9xY6lEIePJ4sw2140F9Y4nQ4KnBqBWIV2zuwRtO', '2025-03-16 13:38:30', '', 1),
(8, 'molnar.katalin', 'molnar.katalin@example.co.uk', '$2y$10$T4NZAWrnWcjDS4JKupdpUunSRDT0NBZnwo3u3nT3APLeasv9JXbXW', '2025-03-16 13:39:05', '', 1),
(9, 'vas.mark', 'vas.mark@xyzmail.hu', '$2y$10$fw8zTNvJmMoJGPV1qP9/q.3WmZyOJpb36/36CytP6JBGnNInZG7LK', '2025-03-16 13:39:40', '', 1),
(10, 'farkas.dora', 'farkas.dora@demoemail.com', '$2y$10$5W.v1eokKw643aHA5mKnBugeCSLnf9h/RRFTsO07iQRzb9AlLxjwW', '2025-03-16 13:40:17', '', 1),
(11, 'jones.gabor', 'jones.gabor@faketestmail.net', '$2y$10$P8bL4SFhDYDt8SOX3eK1J.vPdFX1ef9rD6rtuMC4YXI965rchtxYy', '2025-03-16 13:40:50', '', 1),
(12, 'kovacs.krisztina', 'kovacs.krisztina@randomsite.hu', '$2y$10$cYJqwKzT9EebEaC9jF33NehfMn86qsruh6BWyG8razy/ZIQH03q0K', '2025-03-16 13:41:28', '', 1),
(13, 'varga.jeno', 'varga.jeno@mockmail.org', '$2y$10$reC0iEdfZu9jtWXQdDFcQu3OVV6j.Qr927RCAiatewMFePD9zK2e.', '2025-03-16 13:42:05', '', 1),
(14, 'balazs.reka', 'balazs.reka@sampledomain.net', '$2y$10$5BxdajGKOb8RL9w/ERHAiOtFTO7kelRk97ZLKq4c9sUFVjrS/xspu', '2025-03-16 13:42:38', '', 1),
(15, 'kiss.sandor', 'kiss.sandor@fakemailbox.com', '$2y$10$ICEVDuqgnhBXuAcJdpv8Ye/OiUDGPPOJ4B.80YBbLigChD.eWWEr2', '2025-03-16 13:43:12', '', 1),
(24, 'Nonó23', 'kissnorbert4556@gmail.com', '$2y$10$GoL0N/wETaN1XD4LxDCbKe3W51Vz6Apg.lKqKRCqOu/HVTefa6p2i', '2025-04-27 10:01:54', 'Sziasztok, 23 éves vagyok és nem használt dolgaimat cserélném el.', 1),
(25, 'halaszkira', 'halaszkyra@gmail.com', '$2y$10$fiAhdyE0UDTDmR.CKm2r5.EBV3i4f9ZyfTjOubrsaBNgKCy0ALaPe', '2025-04-30 10:04:21', 'Nagyon szívesen eladományoznám a régi plüsseim :)', 1),
(26, 'whydoulook7feet', 'hbende03@gmail.com', '$2y$10$CKRZIFOfIY5Q3pPcMZgjxOdh7t3EtnvnulauUJ7KnR3Pkw3gakt6i', '2025-04-30 16:32:28', 'Remélem most jó lesz.', 1),
(27, 'Rita1990', 'guttyanrita1990@gmail.com', '$2y$10$nfCxIHFkxR1EQ.58JGC2oel4qHlEuK2VmP05qAdB7m3b7/7E.W6Tm', '2025-05-01 16:55:26', '', 1),
(28, 'Kerep Elek', 'svingola.adrian@gmail.com', '$2y$10$60oXvsuvVoS8f1.m8eoDiut138/JsJyapimyxA6zJS2CXo8qQdAgi', '2025-05-01 20:09:14', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`,`user_id`),
  ADD KEY `fk_favorites_user` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
