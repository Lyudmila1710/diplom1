-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2025 at 09:43 AM
-- Server version: 10.11.11-MariaDB-ubu2204
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kosterina_diplom`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `count`, `order_id`) VALUES
(17, 25, 7, 1, 1),
(18, 25, 6, 1, 1),
(19, 25, 7, 1, 2),
(20, 25, 7, 1, 3),
(21, 25, 7, 1, 4),
(22, 25, 6, 1, 5),
(23, 25, 6, 1, 6),
(24, 25, 5, 1, 7),
(25, 25, 6, 1, 8),
(26, 25, 6, 1, 9),
(28, 25, 6, 1, 10),
(33, 25, 7, 1, 11),
(34, 25, 7, 1, 12),
(36, 25, 7, 1, 13),
(37, 25, 6, 1, 13),
(38, 25, 6, 2, 14),
(39, 25, 7, 1, 15),
(40, 25, 6, 1, 16),
(41, 25, 7, 1, 17),
(42, 25, 6, 1, 18),
(43, 25, 6, 1, 19),
(44, 25, 6, 1, 20),
(45, 25, 6, 1, 21),
(46, 25, 6, 1, 22),
(47, 25, 6, 1, 23),
(48, 25, 6, 1, 24),
(51, 25, 6, 2, 25),
(52, 25, 7, 3, 25),
(53, 25, 7, 1, 26),
(54, 25, 6, 1, 26),
(55, 25, 7, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(80) NOT NULL,
  `date_delivery` datetime NOT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('Новый','Подтверждён','В процессе','Отменён','Доставлен') NOT NULL DEFAULT 'Новый',
  `payment` enum('Банковской картой при получении','Наличными при получении') NOT NULL DEFAULT 'Наличными при получении',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `phone`, `address`, `date_delivery`, `comments`, `status`, `payment`, `created_at`) VALUES
(1, '+7(564)-645-34-54', 'Улица рябова дом 62 кв. 87', '2025-05-23 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(2, '+7(435)-345-34-53', 'Улица рябова дом 62 кв. 87', '2025-05-23 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(3, '+7(345)-321-13-43', 'Улица рябова дом 62 кв. 87', '2025-05-24 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(4, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-17 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(5, '+7(354)-353-45-24', 'Улица рябова дом 62 кв. 87', '2025-06-06 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(6, '+7(243)-243-42-34', 'Улица рябова дом 62 кв. 87', '2025-05-21 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(7, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-23 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(8, '+7(323)-432-32-12', 'Улица рябова дом 62 кв. 87', '2025-05-25 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(9, '+7(323)-213-21-31', 'Улица рябова дом 62 кв. 87', '2025-05-24 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(10, '+7(345)-321-13-43', 'Улица рябова дом 62 кв. 87', '2025-05-24 00:00:00', '', 'Новый', 'Наличными при получении', '2025-05-19 10:44:14'),
(11, '+7(656)-564-56-54', 'вава', '2025-05-22 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(12, '+7(565)-656-56-56', 'уцу', '2025-05-30 00:00:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 10:44:14'),
(13, '+7(343)-432-42-34', 'Улица рябова дом 62 кв. 87', '2025-05-28 00:00:00', '', 'Новый', 'Наличными при получении', '2025-05-19 10:44:14'),
(14, '+7(546)-546-54-65', 'Улица рябова дом 62 кв. 87', '2025-05-22 14:35:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 11:34:25'),
(15, '+7(435)-435-43-54', 'Улица рябова дом 62 кв. 87', '2026-03-31 23:43:00', '34', 'Новый', 'Наличными при получении', '2025-05-19 11:36:24'),
(16, '+7(345)-321-13-43', 'Улица рябова дом 62 кв. 87', '2025-05-31 11:37:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 11:37:48'),
(17, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-21 11:43:00', '', 'Новый', 'Банковской картой при получении', '2025-05-19 11:38:23'),
(18, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-21 13:42:00', '', 'Доставлен', 'Банковской картой при получении', '2025-05-19 11:40:42'),
(19, '+7(243)-243-42-34', 'Улица рябова дом 62 кв. 87', '2025-05-21 16:41:00', '', 'Отменён', 'Банковской картой при получении', '2025-05-19 11:42:02'),
(20, '+7(243)-243-42-34', 'Улица рябова дом 62 кв. 87', '2025-05-21 11:48:00', '', 'Отменён', 'Банковской картой при получении', '2025-05-19 11:43:17'),
(21, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-21 11:45:00', '', 'Доставлен', 'Банковской картой при получении', '2025-05-19 11:43:46'),
(22, '+7(323)-213-21-31', 'Улица рябова дом 62 кв. 87', '2025-05-21 11:51:00', '', 'Отменён', 'Банковской картой при получении', '2025-05-19 11:48:27'),
(23, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-21 16:54:00', '', 'Отменён', 'Банковской картой при получении', '2025-05-19 11:49:17'),
(24, '+7(345)-321-13-42', 'Улица рябова дом 62 кв. 87', '2025-05-21 15:53:00', '', 'Доставлен', 'Банковской картой при получении', '2025-05-19 11:49:39'),
(25, '+7(432)-423-42-34', 'Улица рябова дом 62 кв. 87', '2025-05-24 15:30:00', '', 'Отменён', 'Банковской картой при получении', '2025-05-21 13:30:19'),
(26, '+7(432)-432-42-34', '34234', '2025-05-29 19:27:00', '', 'Новый', 'Банковской картой при получении', '2025-05-22 15:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL,
  `weight` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `available` enum('Доступен','Закрыт') NOT NULL DEFAULT 'Доступен'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `type_id`, `description`, `photo`, `cost`, `weight`, `created_at`, `available`) VALUES
(3, 'Тарт творожный рай', 3, 'Нежный, воздушный тарт с кремовой творожной начинкой, который подарит вам незабываемое удовольствие. Идеальный баланс легкости и вкуса!', '/img_product/5-HYaRm1Ra0j_P6EuVCOqIIpKIYKzfY3Vigp_uVK4FuxPzF2kr.png', 125, 0.25, '2025-05-11 12:38:15', 'Доступен'),
(4, 'Крем-брюле в облаках', 8, 'Это настоящий кулинарный рай для любителей утончённых вкусов! Макароны с хрустящей корочкой и мягким миндальным тестом, наполненные изысканным кремом с нотками карамели и ванили, идеально передают вкус знаменитого французского десерта. Каждый укус - это как лёгкий, воздушный облачко, где встречаются изысканность и сладкая нежность. В наборе 6 штучек', '/img_product/f442JkRCdza0bFPtMwAzbKFKzsiBdcXbMuqN0G5mlggg0vozZA.png', 600, 0.1, '2025-05-11 13:09:06', 'Доступен'),
(5, 'Ягоды на закате', 4, 'Это яркая и сочная композиция, где встречаются самые свежие и спелые ягоды, словно убаюкиваемые тёплым светом заката. Хрустящая основа тарталетки в сочетании с лёгким кремом создаёт идеальный контраст с кисло-сладкими нотками ягод. В каждом кусочке - освежающий вкус лета, который дарит заряд бодрости и настроения.', '/img_product/AhEaFE3M5v48RMoTa9wxYFyVKJGg5lIGEJlH01zl5oygYv7AdQ.png', 160, 0.3, '2025-05-11 13:12:36', 'Доступен'),
(6, 'Шоколадное наслаждение', 9, 'Для настоящих ценителей шоколада! Этот кекс - настоящий рай для сладкоежек. Влажная, нежная текстура теста, пропитанная насыщенным шоколадом, и в центре - тягучий, богатый шоколадный крем, который тает во рту. Обернутый в ароматную корочку, каждый кусочек наполняет ощущением уюта и полной гармонии с этим богом сладостей. Восхитительное сочетание для тех, кто не может устоять перед шоколадной магией!', '/img_product/KYxiF7gvQqN_G3ewVbc722s0PYGQogeO1V2oMYI3nW_tyhoKz6.png', 250, 0.28, '2025-05-11 13:15:57', 'Доступен'),
(7, 'Красный бархат', 5, 'Легендарный торт с тонким ароматом какао и нежнейшими воздушными коржами насыщенного рубинового цвета. Слои пропитаны сливочным кремом на основе сливочного сыра, который придаёт десерту бархатистую текстуру и гармоничный вкус. Идеален для особых случаев и романтических моментов.', '/img_product/-dYiN3DznUI6XkfDWE05WFRBPkVMhZnS1CGWqEwxClcx8oiYCD.png', 110, 0.3, '2025-05-13 12:18:21', 'Доступен');

-- --------------------------------------------------------

--
-- Table structure for table `rejection`
--

CREATE TABLE `rejection` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rejection`
--

INSERT INTO `rejection` (`id`, `order_id`, `reason`) VALUES
(1, 23, 'не успеем приготовить в срок\n');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(3, 'Тарт'),
(4, 'Тарталетка'),
(5, 'Торт'),
(8, 'Макарун'),
(9, 'Кекс'),
(11, 'Эклер');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `surname` varchar(80) NOT NULL,
  `username` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `admin` enum('Админ','Пользователь') NOT NULL DEFAULT 'Пользователь',
  `password_reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `username`, `phone`, `email`, `password`, `admin`, `password_reset_token`) VALUES
(1, 'Людмила', 'Костерина', 'krokodil', '+7(343)-454-36-54', 'user@mail.ru', '123', 'Пользователь', NULL),
(2, 'Иван', 'Иванов', '1234', '+7(354)-353-45-24', 'ggat@ri.ru', 'Qwerty12345!', 'Пользователь', NULL),
(3, 'Иван', 'Иванов', '12344', '+7(354)-353-45-24', 'ggat@ri.ru', 'Qwerty12345!', 'Пользователь', NULL),
(4, 'Иван', 'fdsdv', 'ft6u543', '+7(342)-567-78-65', 'fgngfref@mail.ru', 'Qwerty123!Й', 'Пользователь', NULL),
(5, 'Иван', 'fdsdv', '4321fd', '+7(543)-213-45-43', 'fgngfref@mail.ru', 'Qwerty123!', 'Пользователь', NULL),
(6, 'Иван', 'Иванов', '5yrghfg', '+7(345)-654-32-34', 'wertgref@mail.ru', 'Qwerty12345!', 'Пользователь', NULL),
(7, 'Иван', 'efergr', 'rgthfewe', '+7(473)374-34-76', 'wertgref@mail.ru', 'Qwerty12345!', 'Пользователь', NULL),
(8, 'Иван', 'swedfg', 'asdfg', '+7(234)-523-45-67', 'ggat@ri.ru', 'Йцукен123!', 'Пользователь', NULL),
(9, 'Иван', 'dcv', 'dcve', '+7(232)-324-45-65', 'ggat@ri.ru', 'Йцукеqw!2', 'Пользователь', NULL),
(10, 'sdf', 'asdf', 'asdf', '+7(234)-567-87-65', 'sdfgh@mail.ru', 'Qwert12!', 'Пользователь', NULL),
(11, 'sdfЫвапр', 'фывап', '1244', '+7(343)-454-36-54', 'user@mail.ru', 'Qwert12!', 'Пользователь', NULL),
(12, 'sdf', 'efr', 'eddd', '+7(456)-789-08-76', 'qwer@mail.ru', 'Qwert12!', 'Пользователь', NULL),
(13, 'йцц', 'цуйц', '12345', '+7(234)-567-65-43', 'user@mail.ru', 'Qwert12!', 'Пользователь', NULL),
(14, 'йцц', 'йцу', '123456', '+7(345)-678-98-76', 'user@mail.ru', '$2y$13$2JayzPmIkEOWhkqRHCKQjuC0WTQqUVH.vwR.LzFOlRgsRhWJMywKG', 'Пользователь', NULL),
(22, 'йцц', 'wewe', '12333', '+7(132)-331-32-32', 'user@mail.ru', '$2y$13$uSmjZrI7NOl.x4yq9OwLVO.6eaBPXQtJUqOOb.WIPLBhSWxxVovhG', 'Пользователь', NULL),
(23, 'йцц', 'edewd', '1222', '+7(134)-544-32-12', 'ggat@ri.ru', '$2y$13$Db4S/i2OqqkJ2F09/ZekU.N53ytz0n.lO4voEsUNWHxzKE/DpV5Ae', 'Пользователь', NULL),
(24, 'wdwd', 'wdwd', '123123', '+7(323)-213-21-31', 'ggat@ri.ru', '$2y$13$7sXQcHBh4BHySBNOGBjfjecI5OiIKtH2TORMj272j.kJS4TBP16fi', 'Пользователь', NULL),
(25, 'укукаfv', 'укукa', '12211', '+7(345)-321-13-43', 'ggata@ri.ru', '$2y$13$og2qyLuWr1XHDY5JFVzbO.baRgsVul0kNM.pa0FynA/Yr3dCeOsXe', 'Пользователь', NULL),
(26, 'werwe', 'ewrewrew', '122111', '+7(323)-432-32-12', 'ggat@ri.ru', '$2y$13$5w5UJH/SQXjWFT6MQY7XUOZOkywqGUPuoHBhudDY3/OvZsFO5G0V2', 'Пользователь', NULL),
(27, 'werwe', 'efergr', '1211111', '+7(234)-567-87-65', 'qwer@mail.ru', '$2y$13$r7eiQV7neycggJBAJxwp3exmIrGGrZbvyDReNjzxS..pOLk58/EA.', 'Пользователь', NULL),
(28, 'werwe', 'wewe', '12111111', '+7(123)-456-43-21', 'user@mail.ru', '$2y$13$MAetOqMMKJa.66K9PDhQzOyP1GpCv9.srQ.blwPIX4NwY3j.8fSl2', 'Пользователь', NULL),
(29, 'dsd', 'sds', '12344321', '+7(324)-312-12-32', 'user@mail.ru', '$2y$13$4FHy508gWygAiSHinB50peYgmfXuRB1q.ZroHoLI1lBzJDERWgajW', 'Админ', NULL),
(30, 'йццу', 'йуцу', '123123123', '+7(243)-243-42-34', 'ggat@ri.ru', '$2y$13$GuQmXedl/m3VqDcmL.K3ce5rnO.eLLkMoaN4ElsBiORiv.yr2iwhW', 'Пользователь', NULL),
(31, 'Артём', 'Щербаков', 'BOGINYA', '+7(777)-777-77-77', 'bog@mail.ru', '$2y$13$sxlXyX7Nt3f8840E7tepdOVuQ/934.kgdjLOaYi7nim7eg6sFu69C', 'Пользователь', NULL),
(32, '12211', 'wdwd', 'qwerty', '+7(832)-321-32-13', 'ggat@ri.ru', '$2y$13$56I3gqs4tR4a55/gXYfz0uznTjJQscmfitGxLt3bLYMFse1flvuCS', 'Пользователь', NULL),
(33, '12211', 'efergr', '123', '+7(832)-321-32-13', 'ggat@ri.ru', '$2y$13$eM/3fxxF3j6KyulyNsJGJeCgKOZnPIYSAWf264Xuf3RgfXDxVVZ0S', 'Пользователь', NULL),
(34, '12211', 'Иванов', '1221111111', '+7(832)-343-23-21', 'ggat@ri.ru', '$2y$13$gO4//qpfqfjjmGrwtRog0O6JRh8D1Zx/GTktvjzdWYKk/So6pPlY.', 'Пользователь', NULL),
(35, '12211', 'efergr', '1211111123', '+7(234)-567-87-65', 'qwer@mail.ru', '$2y$13$O9DzUm5bUiC6wie7/mwz.OVUz2U4DuMwo45VS5hbyWlBwHE4VXI/W', 'Пользователь', NULL),
(36, '12211', 'sds', '1234432142', '+7(832)-431-21-23', 'user@mail.ru', '$2y$13$cvFjJhZhenOsuyIRWuyFPefgeRNdUh.yO.Oh7B8fZnTjfuMB/59u.', 'Пользователь', NULL),
(37, '12211', 'sds', '123443214245', '+7(832)-431-21-23', 'user@mail.ru', '$2y$13$8KIJ/FImoHYaVKreYG1D1eMXiADbM0ICS2DleSmEagCoHnLBhXUyy', 'Пользователь', NULL),
(38, 'Ilya123', 'Иванов', 'luska12', '+7(354)-353-45-24', 'ggat@ri.ru', '$2y$13$nBlL62IzGGh9BN2URntKleji9FhExjP5oMSiQMVjETNvUYAtnN8Z6', 'Пользователь', NULL),
(39, 'вмвы', 'фывап', 'luska123', '+7(354)-353-45-24', 'ggat@ri.ru', '$2y$13$RR4Jw3zA3tkIZ2XZOxMrz.3HrE091nbqQrKT96PHykFA8suQN.Iiu', 'Пользователь', NULL),
(40, 'вмвыы', 'фывап', '12234', '+7(332)-232-32-31', 'ggat@ri.ru', '$2y$13$CANV.UnBFp4paTmFoCZKUOuO5Hw.yRx47Cgdi5oEkNQObqFE1Q/aO', 'Пользователь', NULL),
(41, 'йцуу', 'йу', '12231', '+7(233)-131-23-21', 'mila.kosterina.96@mail.ru', '$2y$13$81tW0GzQ/W7eCny/N72fFextONzKwHcgPEMdb7N7F1wFWMVfRR6UG', 'Пользователь', '08cv8o7Q1ah1bTxPP5fxkL60pe33we_q_1748201248'),
(42, 'dsfds', 'Иванов', '12lu', '+7(832)-431-21-23', 'luskakosterina5811@gmail.com', '$2y$13$X5xp0viCQ4t8LYojzSnXSOOCCenVwsFZYjB9LQ6HZO7utNiamPl1m', 'Пользователь', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`product_id`,`order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `rejection`
--
ALTER TABLE `rejection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rejection`
--
ALTER TABLE `rejection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Constraints for table `rejection`
--
ALTER TABLE `rejection`
  ADD CONSTRAINT `rejection_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
