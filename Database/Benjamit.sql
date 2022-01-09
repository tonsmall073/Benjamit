-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2022 at 12:57 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Benjamit`
--

-- --------------------------------------------------------

--
-- Table structure for table `Member`
--

CREATE TABLE `Member` (
  `Id` int(3) NOT NULL COMMENT 'คีย์หลัก',
  `IdTitle` int(3) NOT NULL COMMENT 'รหัสคำนำหน้า',
  `FirstName` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อจริง',
  `LastName` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'นามสกุล',
  `NickName` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อเล่น',
  `BirthDate` date NOT NULL COMMENT 'วันเดือนปเกิด',
  `UserId` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อผู้ใช้งาน',
  `Password` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสผ่าน',
  `IdUserRights` int(2) NOT NULL COMMENT 'คีย์ลองสิทธิผู้ใช้งาน',
  `ActiveStatus` bit(1) NOT NULL COMMENT 'สถานะใช้งาน 0=ใช้งาน 1=ไม่ใช้งาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`Id`, `IdTitle`, `FirstName`, `LastName`, `NickName`, `BirthDate`, `UserId`, `Password`, `IdUserRights`, `ActiveStatus`) VALUES
(1, 3, 'วิศรุต', 'รูปเขียน', 'ต้น', '1992-01-11', 'tonsmall', 'test001', 1, b'0'),
(2, 1, 'วศินี', 'รูปเขียน', 'หวาน', '1998-12-23', 'whanyen', 'test002', 1, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `NameTitle`
--

CREATE TABLE `NameTitle` (
  `Id` int(3) NOT NULL COMMENT 'คีย์หลัก',
  `Name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อคำนำหน้า',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `NameTitle`
--

INSERT INTO `NameTitle` (`Id`, `Name`, `ActiveStatus`) VALUES
(1, 'นางสาว', b'0'),
(2, 'นาง', b'0'),
(3, 'นาย', b'0'),
(4, 'เด็กหญิง', b'0'),
(5, 'เด็กชาย', b'0'),
(6, 'พระสงฆ์', b'0'),
(7, 'บาทหลวง', b'0'),
(8, 'หม่อมหลวง', b'0'),
(9, 'หม่อมราชวงศ์', b'0'),
(10, 'หม่อมเจ้า', b'0'),
(11, 'ศาสตราจารย์เกียรติคุณ', b'0'),
(12, 'ศาสตราจารย์', b'0'),
(13, 'ผู้ช่วยศาสตราจารย์', b'0'),
(14, 'รองศาสตราจารย์', b'0'),
(15, 'Miss', b'0'),
(16, 'Mrs.', b'0'),
(17, 'Mr.', b'0'),
(18, 'Miss', b'0'),
(19, 'Master', b'0'),
(20, 'Buddhist Monk', b'0'),
(21, 'Rev.', b'0'),
(22, 'Mom Luang (M.L.)', b'0'),
(23, 'Mom Rajawong (M.R.)', b'0'),
(24, 'Mom Chao (M.C.)', b'0'),
(25, 'Emeritus Professor', b'0'),
(26, 'Professor', b'0'),
(27, 'Assistant Professor', b'0'),
(28, 'Associate Professor', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `ProductName`
--

CREATE TABLE `ProductName` (
  `Id` int(5) NOT NULL COMMENT 'คีย์หลัก',
  `Name` varchar(35) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อสินค้า',
  `DetailAboutProduct` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'รายละเอียดเกียวกับตัวสินค้า',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse',
  `IdMemberSave` int(5) NOT NULL COMMENT 'คีย์ลองสมาชิกที่บันทึก',
  `SaveDate` datetime NOT NULL COMMENT 'วันที่บันทึกข้อมูลและแก้ไขข้อมูลล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProductName`
--

INSERT INTO `ProductName` (`Id`, `Name`, `DetailAboutProduct`, `ActiveStatus`, `IdMemberSave`, `SaveDate`) VALUES
(1, 'Tontest', '', b'0', 1, '2021-12-10 21:39:12'),
(2, 'Tontest02', '', b'0', 1, '2021-11-13 14:34:22'),
(3, 'Tontest022', '', b'0', 1, '2021-11-13 20:38:24'),
(4, 'Tontest0', '', b'0', 2, '2021-11-13 20:53:49'),
(5, 'Tontest01', '', b'0', 1, '2021-11-13 20:54:40'),
(6, 'test033', '', b'0', 1, '2021-11-13 20:55:51'),
(7, 'Tontest0225', '', b'0', 1, '2021-11-14 20:59:40'),
(8, 'Tontest0233', '', b'0', 1, '2021-11-15 18:41:45'),
(9, 'Tontest0233', '', b'0', 1, '2021-11-15 18:42:50'),
(10, 'testtttt', '', b'0', 1, '2021-11-15 18:52:17'),
(11, 'ตาตาตา', '', b'0', 1, '2021-11-18 20:38:02'),
(12, 'dddddd', 'กระบอกดีจ้า', b'0', 1, '2021-11-18 21:40:40'),
(13, 'testdddddd', 'ddddddจ้า', b'0', 1, '2021-11-18 21:43:44'),
(14, 'ก็อกนํ้า ซันว่า 1/2', '-', b'0', 1, '2021-12-04 19:53:55'),
(15, 'ก็อกนํ้า ซันว่า 3/4', 'dddd', b'0', 1, '2021-12-04 19:58:06'),
(16, 'ก็อกนํ้า ซันว่า 5/8', '-', b'0', 1, '2021-12-04 19:59:09'),
(17, 'ก็อกนํ้า ซันว่า 1 นิ้ว', '-', b'0', 1, '2021-12-04 20:01:46'),
(18, 'ก็อกนํ้า No Name', '-', b'0', 1, '2021-12-04 20:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `ProductPicture`
--

CREATE TABLE `ProductPicture` (
  `Id` int(5) NOT NULL COMMENT 'คีย์หลัก',
  `ImageFile` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'เส้นทางของไฟล์ภาพ',
  `IdProductName` int(5) NOT NULL COMMENT 'คีย์ลองชื่อสินค้า',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProductPicture`
--

INSERT INTO `ProductPicture` (`Id`, `ImageFile`, `IdProductName`, `ActiveStatus`) VALUES
(1, '11202111121215580.png', 1, b'0'),
(2, '12202111121518220.png', 2, b'0'),
(3, '167202111140.png', 6, b'0'),
(4, '167202111140.png', 1, b'0'),
(5, '11417202112040.png', 14, b'0'),
(6, '11619202112040.png', 16, b'0'),
(7, '11720202112040.png', 17, b'0'),
(8, '11821202112040.png', 18, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `ProductPrice`
--

CREATE TABLE `ProductPrice` (
  `Id` int(6) NOT NULL COMMENT 'คีย์หลัก',
  `CostPrice` decimal(10,2) NOT NULL COMMENT 'ราคาต้นทุน',
  `SalePrice` decimal(10,2) NOT NULL COMMENT 'ราคาขาย',
  `IdProductName` int(6) NOT NULL COMMENT 'คีย์ลองชื่อสินค้า',
  `IdUnitType` int(3) NOT NULL COMMENT 'คีย์ลองประเภทหน่วย',
  `IdBarcode` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัส Barcode',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProductPrice`
--

INSERT INTO `ProductPrice` (`Id`, `CostPrice`, `SalePrice`, `IdProductName`, `IdUnitType`, `IdBarcode`, `ActiveStatus`) VALUES
(1, '500.10', '1000.20', 1, 41, '1202111121215581', b'0'),
(2, '1000.00', '2000.00', 1, 165, '555125555', b'0'),
(3, '80.00', '160.00', 2, 62, '2202111121518221', b'0'),
(4, '8.00', '16.00', 2, 207, '2202111121518222', b'0'),
(5, '4244.00', '4200.51', 3, 87, '3202111140413041', b'0'),
(6, '4000.00', '5000.00', 4, 123, '4202111140414261', b'0'),
(7, '5000.00', '4000.00', 5, 60, '5202111140416141', b'0'),
(8, '10.00', '20.00', 6, 146, '167202111141', b'0'),
(9, '50.00', '60.00', 7, 192, '178202111141', b'0'),
(10, '200.00', '300.00', 8, 195, '189202111151', b'0'),
(11, '4000.00', '5000.00', 8, 22, '189202111152', b'0'),
(12, '200.00', '300.00', 9, 195, '199202111151', b'0'),
(13, '4000.00', '5000.00', 9, 22, '199202111152', b'0'),
(14, '400.00', '800.00', 10, 51, '11013202111151', b'0'),
(15, '3333.33', '4444.44', 11, 87, '11114202111161', b'0'),
(16, '50.00', '100.00', 12, 197, '11215202111181', b'0'),
(17, '50.00', '100.00', 13, 64, '11316202111181', b'0'),
(18, '80.00', '110.00', 14, 172, '11417202112041', b'0'),
(19, '111.00', '1111.00', 15, 123, '11518202112041', b'0'),
(20, '22.00', '5555.00', 16, 172, '11619202112041', b'0'),
(21, '22.00', '5555.00', 17, 172, '11720202112041', b'0'),
(22, '22.00', '5555.00', 18, 172, '11821202112041', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `ProductRelatedName`
--

CREATE TABLE `ProductRelatedName` (
  `Id` int(6) NOT NULL COMMENT 'คีย์หลัก',
  `Name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อเรียกสินค้าที่เกี่ยวข้อง',
  `IdProductName` int(6) NOT NULL COMMENT 'คีย์ลองชื่อสินค้า',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProductRelatedName`
--

INSERT INTO `ProductRelatedName` (`Id`, `Name`, `IdProductName`, `ActiveStatus`) VALUES
(1, 'ton1', 1, b'0'),
(2, 'ton2', 1, b'0'),
(3, 'ton222', 2, b'0'),
(4, 'ton333', 2, b'0'),
(5, 'ton444', 2, b'0'),
(6, 'ผลงานโฟร์', 6, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `UnitType`
--

CREATE TABLE `UnitType` (
  `Id` int(11) NOT NULL COMMENT 'คีย์หลัก',
  `UnitName` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อหน่วย',
  `ActiveStatus` bit(1) NOT NULL COMMENT '0 = use 1 = unuse'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `UnitType`
--

INSERT INTO `UnitType` (`Id`, `UnitName`, `ActiveStatus`) VALUES
(1, 'นิ้ว', b'0'),
(2, 'ตารางนิ้ว', b'0'),
(3, 'ลูกบาศก์นิ้ว', b'0'),
(4, 'ต่อ mille', b'0'),
(5, 'ซีเมนส์ต่อเมตร', b'0'),
(6, 'เอเคอร์', b'0'),
(7, 'หน่วยนับกิจกรรม', b'0'),
(8, 'ถุง', b'0'),
(9, 'เล่ม', b'0'),
(10, 'แกลลอนต่อชั่วโมง (US)', b'0'),
(11, 'ขวด', b'0'),
(12, 'ลูกบาศก์เซนติเมตร/วินาที', b'0'),
(13, 'ตู้', b'0'),
(14, 'กระป๋อง', b'0'),
(15, 'คัน', b'0'),
(16, 'ลูกบาศก์เซนติเมตร', b'0'),
(17, 'ลูกบาศก์เดซิเมตร', b'0'),
(18, 'เซนติลิตร', b'0'),
(19, 'เซนติเมตร', b'0'),
(20, 'ตารางเซนติเมตร', b'0'),
(21, 'เซนติเมตร/วินาที', b'0'),
(22, 'กล่อง', b'0'),
(23, 'คาร์ตัน', b'0'),
(24, 'ถ้วย', b'0'),
(25, 'หีบ', b'0'),
(26, 'วัน', b'0'),
(27, 'องศา', b'0'),
(28, 'เดซิเมตร', b'0'),
(29, 'ถัง', b'0'),
(30, 'โหล', b'0'),
(31, 'ชิ้น', b'0'),
(32, 'หน่วยเอมไซน์/มิลลิลิตร', b'0'),
(33, 'หน่วยเอมไซน์', b'0'),
(34, 'แฟ้ม', b'0'),
(35, 'ออนซ์หน่วยวัดของเหลว US', b'0'),
(36, 'ฟุต', b'0'),
(37, 'ตารางฟุต', b'0'),
(38, 'ลูกบาศก์ฟุต', b'0'),
(39, 'กิกะจูล', b'0'),
(40, 'กรัม', b'0'),
(41, 'US แกลลอน', b'0'),
(42, 'กรัมทองคำ', b'0'),
(43, 'gram act.ingrd / liter', b'0'),
(44, 'กรัม/ลิตร', b'0'),
(45, 'กรัมต่อโมล', b'0'),
(46, 'กรัม/ตารางเมตร', b'0'),
(47, 'กรัมต่อลูกบาศก์เมตร', b'0'),
(48, 'กิกะโอห์ม', b'0'),
(49, 'แกลลอนต่อไมล์ (US)', b'0'),
(50, 'ตัวใหญ่', b'0'),
(51, 'กลุ่ม', b'0'),
(52, 'ชั่วโมง', b'0'),
(53, 'เฮกตาร์', b'0'),
(54, 'เฮกโตลิตร', b'0'),
(55, 'ชั่วโมง', b'0'),
(56, 'พิโคฟาเรด', b'0'),
(57, 'จูล/กิโลกรัม', b'0'),
(58, 'จูล/โมล', b'0'),
(59, 'งาน', b'0'),
(60, 'Kilogram act. ingrd.', b'0'),
(61, 'กิโลกรัมต่อลูกบาศก์เดซิเมตร', b'0'),
(62, 'กิโลกรัม', b'0'),
(63, 'กิโลกรัม/โมล', b'0'),
(64, 'กิโลกรัมต่อวินาที', b'0'),
(65, 'kg act.ingrd. / kg', b'0'),
(66, 'กิโลจูล/กิโลกรัม', b'0'),
(67, 'กิโลจูล/โมล', b'0'),
(68, 'กิโลเมตร', b'0'),
(69, 'ตารางกิโลเมตร', b'0'),
(70, 'กิโลเมตรต่อชั่วโมง', b'0'),
(71, 'เคลวิน/นาที', b'0'),
(72, 'เคลวิน/วินาที', b'0'),
(73, 'กิโลปาสคาล', b'0'),
(74, 'กิโลโวลต์แอมแปร์', b'0'),
(75, 'ลิตร', b'0'),
(76, 'US ปอนด์', b'0'),
(77, 'ลิตรต่อ 100 กิโลเมตร', b'0'),
(78, 'ลิตร/นาที', b'0'),
(79, 'ลิตร/โมลวินาที', b'0'),
(80, 'ลิตรต่อชั่วโมง', b'0'),
(81, 'Kilotonne', b'0'),
(82, 'เมตร', b'0'),
(83, 'โมลต่อลิตร', b'0'),
(84, 'โมลต่อลูกบาศก์เมตร', b'0'),
(85, 'เมตร/วินาที', b'0'),
(86, 'ตารางเมตร', b'0'),
(87, '1 / ตารางเมตร', b'0'),
(88, 'ตารางเมตร/วินาที', b'0'),
(89, 'ลูกบาศก์เมตร', b'0'),
(90, 'ลูกบาศก์เมตร/ชั่วโมง', b'0'),
(91, 'ลูกบาศก์เมตร/วินาที', b'0'),
(92, 'เครื่อง', b'0'),
(93, 'มัด', b'0'),
(94, 'เมกะจูล', b'0'),
(95, 'มิลลิกรัม', b'0'),
(96, 'มิลลิกรัม/ลิตร', b'0'),
(97, 'เมกะโอมห์', b'0'),
(98, 'มิลลิกรัม/ลูกบาศก์เมตร', b'0'),
(99, 'เมตร/ชั่วโมง', b'0'),
(100, 'เมกะโวลต์', b'0'),
(101, 'ไมล์', b'0'),
(102, 'ตารางไมล์', b'0'),
(103, 'นาที', b'0'),
(104, 'ไมโครวินาที', b'0'),
(105, 'มิลลิลิตร', b'0'),
(106, 'Milliliter act. ingr.', b'0'),
(107, 'มิลลิเมตร', b'0'),
(108, 'ตารางมิลลิเมตร', b'0'),
(109, 'ลูกบาศก์มิลลิเมตร', b'0'),
(110, 'เมกะนิวตัน', b'0'),
(111, 'มิลลินิวตัน/เมตร', b'0'),
(112, 'ไมล์ต่อแกลลอน (US)', b'0'),
(113, 'มิลิโมลต่อลิตร', b'0'),
(114, 'มิลลิปาสคาลวินาที', b'0'),
(115, 'พิโควินาที', b'0'),
(116, 'เมตร/วินาทีกำลังสอง', b'0'),
(117, 'ไมโครซเมนส์ต่อเซนติเมตร', b'0'),
(118, 'มิลลิวินาที', b'0'),
(119, 'เดือน', b'0'),
(120, 'เมกะวัตต์ ชั่วโมง', b'0'),
(121, 'นาโนแอมแปร์', b'0'),
(122, 'นาโนเมตร', b'0'),
(123, 'Gram act. ingrd.', b'0'),
(124, 'กิโลนิวตัน', b'0'),
(125, 'นิวตัน/เมตร', b'0'),
(126, 'นิวตัน/ตารางมิลลิเมตร', b'0'),
(127, 'นาโนวินาที', b'0'),
(128, 'ออนซ์', b'0'),
(129, 'จุด', b'0'),
(130, 'คู่', b'0'),
(131, 'แพค/ห่อ', b'0'),
(132, 'แพลเลต', b'0'),
(133, 'ปาสคาลวินาที', b'0'),
(134, 'เมกะโวลต์แอมแปร์', b'0'),
(135, 'กิโลกรัมต่อลูกบาศก์เมตร', b'0'),
(136, 'หนึ่ง/นาที', b'0'),
(137, 'อัตราส่วนพันล้าน', b'0'),
(138, 'อัตราส่วนล้าน', b'0'),
(139, 'อัตราส่วนล้านล้าน', b'0'),
(140, 'งวด', b'0'),
(141, 'คน', b'0'),
(142, 'ไพนท์, หน่วยวัดขนาดของเหลว US', b'0'),
(143, 'กิโลโมล', b'0'),
(144, 'ควอรท,หน่วยวัดขนาดของเหลว US', b'0'),
(145, 'มิลลิฟาเรด', b'0'),
(146, 'กรัม/ลูกบาศก์เซนติเมตร', b'0'),
(147, 'รีม', b'0'),
(148, 'Roll (ม้วน)', b'0'),
(149, 'นาโนฟาเรด', b'0'),
(150, 'ผืน', b'0'),
(151, 'แผ่น', b'0'),
(152, 'ชุด', b'0'),
(153, 'ท่อน', b'0'),
(154, 'ระบบ', b'0'),
(155, 'หลักพัน', b'0'),
(156, 'ครั้ง', b'0'),
(157, 'ตัน', b'0'),
(158, 'ตัน/ลูกบาศก์เมตร', b'0'),
(159, 'US ตัน', b'0'),
(160, 'ท่อ', b'0'),
(161, 'แท่ง', b'0'),
(162, 'ขด', b'0'),
(163, 'โคม', b'0'),
(164, 'คิว', b'0'),
(165, 'ปี๊บ', b'0'),
(166, 'ซอง', b'0'),
(167, 'ดวง', b'0'),
(168, 'ดอก', b'0'),
(169, 'แผง', b'0'),
(170, 'ตลับ', b'0'),
(171, 'เที่ยว', b'0'),
(172, 'ตัว', b'0'),
(173, 'นัด', b'0'),
(174, 'แท่น', b'0'),
(175, 'บาน', b'0'),
(176, 'ใบ', b'0'),
(177, 'ภาพ/รูป', b'0'),
(178, 'เรือน', b'0'),
(179, 'ล้อ', b'0'),
(180, 'ลัง', b'0'),
(181, 'วง', b'0'),
(182, 'เส้น', b'0'),
(183, 'ลูก', b'0'),
(184, 'หลอด', b'0'),
(185, 'หลัง', b'0'),
(186, 'เม็ด', b'0'),
(187, 'ไมโครแอมแปร์', b'0'),
(188, 'ไมโครฟาเรด', b'0'),
(189, 'ไมโครเมตร', b'0'),
(190, 'ไมโครกรัม/ลูกบาศก์เมตร', b'0'),
(191, 'ไมโครลิตร', b'0'),
(192, 'กระสอบ', b'0'),
(193, 'ไมโครกรัม/ลิตร', b'0'),
(194, 'กรง', b'0'),
(195, 'กรอบ', b'0'),
(196, 'กระถาง', b'0'),
(197, 'กระบอก', b'0'),
(198, 'ก้อน', b'0'),
(199, 'หน่วย', b'0'),
(200, 'วัสดุที่คิดมูลค่าเท่านั้น', b'0'),
(201, 'โวลต์แอมแปร์', b'0'),
(202, 'สัปดาห์', b'0'),
(203, 'ปี', b'0'),
(204, 'หลา', b'0'),
(205, 'ตารางหลา', b'0'),
(206, 'ลูกบาศก์หลา', b'0'),
(207, 'ขีด', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `UserRights`
--

CREATE TABLE `UserRights` (
  `Id` int(3) NOT NULL,
  `PrivilegeName` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `UserRights`
--

INSERT INTO `UserRights` (`Id`, `PrivilegeName`) VALUES
(1, 'Admin'),
(2, 'Employee'),
(3, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `NameTitle`
--
ALTER TABLE `NameTitle`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ProductName`
--
ALTER TABLE `ProductName`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ProductPicture`
--
ALTER TABLE `ProductPicture`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ProductPrice`
--
ALTER TABLE `ProductPrice`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ProductRelatedName`
--
ALTER TABLE `ProductRelatedName`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `UnitType`
--
ALTER TABLE `UnitType`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `UserRights`
--
ALTER TABLE `UserRights`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Member`
--
ALTER TABLE `Member`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `NameTitle`
--
ALTER TABLE `NameTitle`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `ProductName`
--
ALTER TABLE `ProductName`
  MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ProductPicture`
--
ALTER TABLE `ProductPicture`
  MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ProductPrice`
--
ALTER TABLE `ProductPrice`
  MODIFY `Id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ProductRelatedName`
--
ALTER TABLE `ProductRelatedName`
  MODIFY `Id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `UnitType`
--
ALTER TABLE `UnitType`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'คีย์หลัก', AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `UserRights`
--
ALTER TABLE `UserRights`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
