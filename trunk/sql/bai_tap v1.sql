-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2014 at 09:23 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bai_tap`
--
CREATE DATABASE IF NOT EXISTS `bai_tap` DEFAULT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci;
USE `bai_tap`;

-- --------------------------------------------------------

--
-- Table structure for table `th_baitap`
--

CREATE TABLE IF NOT EXISTS `th_baitap` (
  `maso` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mamuc` varchar(10) COLLATE utf8_vietnamese_ci NOT NULL,
  `tieude` varchar(100) COLLATE utf8_vietnamese_ci NOT NULL,
  `noidung` text COLLATE utf8_vietnamese_ci NOT NULL,
  `nopbai` tinyint(4) NOT NULL DEFAULT '0',
  `ngaylam` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`maso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci COMMENT='Các bài tập - thực hành' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `th_baitap`
--

INSERT INTO `th_baitap` (`maso`, `mamuc`, `tieude`, `noidung`, `nopbai`, `ngaylam`) VALUES
(1, 'tin11', 'Bài tập và thực hành số 5', '<p>Làm bài tập và thực hành <strong>SGK</strong></p>\r\n', 0, '2014-11-12 15:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `th_nopbai`
--

CREATE TABLE IF NOT EXISTS `th_nopbai` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hoten` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lop` varchar(5) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ngaygio` datetime NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baitap` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `th_nopbai`
--

INSERT INTO `th_nopbai` (`id`, `hoten`, `lop`, `ngaygio`, `ip`, `file`, `baitap`) VALUES
(1, 'Trần Hữu Nam', '11A0', '2014-11-12 15:07:24', '127.0.0.1', 'dulieu.txt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tn_cauhoi`
--

CREATE TABLE IF NOT EXISTS `tn_cauhoi` (
  `maso` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lop` tinyint(2) DEFAULT '10' COMMENT 'Khối lớp',
  `chuong` tinyint(1) DEFAULT '0' COMMENT 'Chương mấy',
  `bai` tinyint(1) DEFAULT '0' COMMENT 'Bài mấy',
  `dokho` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Độ khó 1 2 3 4 5',
  `kieu` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Kiểu 1 = trắc nghiệm',
  `noidung` varchar(250) COLLATE utf8_vietnamese_ci NOT NULL,
  `loaitru` varchar(200) COLLATE utf8_vietnamese_ci NOT NULL COMMENT 'Loại trừ các câu hỏi tương tự',
  `kichhoat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Sử dụng hay không',
  `ngaylam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`maso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci COMMENT='Các câu hỏi trắc nghiệm' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tn_cauhoi`
--

INSERT INTO `tn_cauhoi` (`maso`, `lop`, `chuong`, `bai`, `dokho`, `kieu`, `noidung`, `loaitru`, `kichhoat`, `ngaylam`) VALUES
(1, 11, 4, 12, 2, 1, 'Phần tử của xâu là gì ?', '', 1, '2014-11-11 13:43:01'),
(2, 11, 4, 12, 1, 1, 'Số lượng kí tự trong xâu được gọi là gì của xâu?', '', 1, '2014-11-11 13:01:24'),
(3, 11, 4, 12, 1, 1, 'Số lượng kí tự tối thiểu của xâu là bao nhiêu?', '', 1, '2014-11-11 13:05:20'),
(4, 11, 4, 11, 1, 1, 'Từ khoá để khai báo mảng là gì?', '', 1, '2014-11-11 16:22:36'),
(5, 11, 4, 12, 2, 1, 'Xâu rỗng là xâu ...', '', 1, '2014-11-12 00:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `tn_ketqua`
--

CREATE TABLE IF NOT EXISTS `tn_ketqua` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bai` int(11) unsigned NOT NULL COMMENT 'Mã bài trắc nghiệm',
  `hoten` varchar(60) COLLATE utf8_vietnamese_ci NOT NULL,
  `lop` varchar(8) COLLATE utf8_vietnamese_ci NOT NULL,
  `cauhoi` varchar(250) COLLATE utf8_vietnamese_ci NOT NULL COMMENT 'Các câu hỏi được chọn',
  `traloi` varchar(250) COLLATE utf8_vietnamese_ci NOT NULL COMMENT 'Các câu đã trả lời',
  `caudung` tinyint(1) unsigned NOT NULL COMMENT 'Số câu đúng',
  `lucvao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Lúc đăng nhập',
  `lucdau` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Lúc bắt đầu làm bài',
  `lucnop` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Lúc nộp bài',
  `ip` varchar(15) COLLATE utf8_vietnamese_ci NOT NULL COMMENT 'Địa chỉ IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci COMMENT='Kết quả kiểm tra' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tn_ketqua`
--

INSERT INTO `tn_ketqua` (`id`, `bai`, `hoten`, `lop`, `cauhoi`, `traloi`, `caudung`, `lucvao`, `lucdau`, `lucnop`, `ip`) VALUES
(1, 1, 'Tran Huu Nam', '11', '|1:3,2,1,4,|2:5,7,6,8,|3:9,10,11,12,', '', 0, '2014-11-11 19:24:21', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '127.0.0.1'),
(3, 1, 'Tran Huu Nam', '11', '|2:6,5,7,8,|5:17,19,20,18,|1:1,2,4,3,', '', 3, '2014-11-12 04:38:48', '2014-11-12 04:38:15', '2014-11-12 04:38:48', '127.0.0.1'),
(4, 1, 'Tran Huu Nam', '11', '|3:9,11,10,12,|1:4,1,3,2,|2:6,8,7,5,', '9,1,5', 3, '2014-11-12 04:42:08', '2014-11-12 04:41:17', '2014-11-12 04:42:08', '127.0.0.1'),
(5, 1, 'Tran Huu Nam', '11', '|5:20,19,17,18,|1:3,2,1,4,|2:8,6,5,7,', '20,3,6', 0, '2014-11-12 04:51:47', '2014-11-12 04:50:28', '2014-11-12 04:51:47', '127.0.0.1'),
(6, 1, 'Tran Huu Nam', '11', '|2:5,7,6,8,|5:17,18,20,19,|1:1,2,3,4,', '7,17,3', 1, '2014-11-12 04:57:27', '2014-11-12 04:57:01', '2014-11-12 04:57:27', '127.0.0.1'),
(7, 1, 'Tran Huu Nam', '12', '|3:10,11,9,12,|1:1,3,4,2,|2:5,8,6,7,', '9,1,5', 3, '2014-11-12 08:36:18', '2014-11-12 08:35:52', '2014-11-12 08:36:18', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `tn_kiemtra`
--

CREATE TABLE IF NOT EXISTS `tn_kiemtra` (
  `maso` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kichhoat` tinyint(1) NOT NULL DEFAULT '1',
  `cauhoi` varchar(250) COLLATE utf8_vietnamese_ci NOT NULL COMMENT 'Các câu hỏi',
  `socau` tinyint(2) unsigned NOT NULL DEFAULT '10' COMMENT 'Số câu trong bài KT',
  `sogiay` smallint(5) unsigned NOT NULL DEFAULT '300' COMMENT 'Số giây làm bài',
  `lop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Khối lớp',
  `tenbai` varchar(250) COLLATE utf8_vietnamese_ci NOT NULL,
  `ngaylam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`maso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci COMMENT='Bài kiểm tra trắc nghiệm' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tn_kiemtra`
--

INSERT INTO `tn_kiemtra` (`maso`, `kichhoat`, `cauhoi`, `socau`, `sogiay`, `lop`, `tenbai`, `ngaylam`) VALUES
(1, 1, '1,2,3,5', 10, 1500, 11, 'Kiểm tra 15 phút - Kiểu xâu', '2014-11-12 00:12:50');

-- --------------------------------------------------------

--
-- Table structure for table `tn_loaitru`
--

CREATE TABLE IF NOT EXISTS `tn_loaitru` (
  `cau1` int(11) NOT NULL,
  `cau2` int(11) NOT NULL,
  UNIQUE KEY `cau1_2` (`cau1`,`cau2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `tn_loaitru`
--

INSERT INTO `tn_loaitru` (`cau1`, `cau2`) VALUES
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tn_traloi`
--

CREATE TABLE IF NOT EXISTS `tn_traloi` (
  `maso` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cauhoi` int(11) NOT NULL DEFAULT '0' COMMENT 'Câu hỏi nào',
  `noidung` varchar(200) COLLATE utf8_vietnamese_ci NOT NULL,
  `dung` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Đúng hay sai?',
  `luotchon` int(10) NOT NULL DEFAULT '0',
  `ngaylam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`maso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci COMMENT='Các câu trả lời' AUTO_INCREMENT=21 ;

--
-- Dumping data for table `tn_traloi`
--

INSERT INTO `tn_traloi` (`maso`, `cauhoi`, `noidung`, `dung`, `luotchon`, `ngaylam`) VALUES
(1, 1, 'kí tự', 1, 1, '2014-11-12 00:15:00'),
(2, 1, 'chữ số', 0, 0, '2014-11-11 14:02:29'),
(3, 1, 'số', 0, 2, '2014-11-11 14:03:01'),
(4, 1, 'chữ cái', 0, 0, '2014-11-11 14:03:42'),
(5, 2, 'độ dài', 1, 1, '2014-11-11 14:24:47'),
(6, 2, 'khả năng', 0, 1, '2014-11-11 14:25:02'),
(7, 2, 'sức chứa', 0, 1, '2014-11-11 14:25:11'),
(8, 2, 'chiều rộng', 0, 0, '2014-11-11 14:25:23'),
(9, 3, '0', 1, 1, '2014-11-11 14:26:21'),
(10, 3, '1', 0, 0, '2014-11-11 14:26:25'),
(11, 3, '255', 0, 0, '2014-11-11 14:27:09'),
(12, 3, '5', 0, 0, '2014-11-11 14:27:37'),
(13, 4, 'array', 1, 0, '2014-11-11 16:22:54'),
(14, 4, 'raray', 0, 0, '2014-11-11 16:23:08'),
(15, 4, 'aray', 0, 0, '2014-11-11 16:23:35'),
(16, 4, 'yarar', 0, 0, '2014-11-11 16:23:59'),
(17, 5, 'có độ dài bằng 0', 1, 1, '2014-11-12 00:09:34'),
(18, 5, 'có độ dài bằng 1', 0, 0, '2014-11-12 00:09:49'),
(19, 5, 'chỉ gồm các kí tự giống nhau', 0, 0, '2014-11-12 00:10:24'),
(20, 5, 'chỉ chứa dấu cách', 0, 1, '2014-11-12 00:10:52');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
