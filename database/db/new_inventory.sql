-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 22, 2022 at 07:41 AM
-- Server version: 5.7.24
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `genuine_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_type` int(11) NOT NULL DEFAULT '2',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `debit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_type`, `name`, `account_number`, `bank_id`, `opening_balance`, `debit`, `credit`, `balance`, `remark`, `status`, `admin_id`, `created_at`, `updated_at`, `branch_id`) VALUES
(30, 1, 'Cash', NULL, NULL, '0.00', '450.00', '0.00', '450.00', NULL, 1, 2, NULL, '2022-09-12 12:24:23', NULL),
(31, 5, 'Sales', NULL, NULL, '0.00', '0.00', '292400.00', '292400.00', NULL, 1, 2, NULL, '2022-09-13 07:27:37', NULL),
(33, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-09-12 06:06:59', NULL),
(34, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-07 04:02:42', NULL),
(35, 6, 'Sale Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-09-12 06:11:02', NULL),
(36, 3, 'Purchase', NULL, NULL, '0.00', '46500.00', '0.00', '46500.00', NULL, 1, 2, NULL, '2022-09-12 12:23:57', NULL),
(37, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 04:57:55', NULL),
(38, 8, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(40, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-05 10:45:54', NULL),
(41, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-03-03 11:45:55', NULL),
(43, 1, 'Cash', NULL, NULL, '0.00', '0.00', '101000.00', '-101000.00', NULL, 1, 2, NULL, '2022-02-14 08:12:19', NULL),
(44, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(45, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(46, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(47, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(48, 7, 'Buy Goods', NULL, NULL, '0.00', '40000.00', '0.00', '40000.00', NULL, 1, 2, NULL, '2022-02-13 13:51:03', NULL),
(49, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(50, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(51, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(52, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(53, 14, 'Advance Salary', NULL, NULL, '0.00', '1000.00', '0.00', '1000.00', NULL, 1, 2, NULL, '2022-02-13 14:08:01', NULL),
(54, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(55, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 07:03:33', NULL),
(56, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(57, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(58, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(59, 26, 'Profit & Loss A/C', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(60, 15, 'Furniture', NULL, NULL, '0.00', '60000.00', '0.00', '60000.00', NULL, 1, 21, NULL, '2022-02-13 11:27:23', NULL),
(61, 14, 'Loan & Advance', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 04:58:55', NULL),
(62, 10, 'Payable interest', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-02-16 06:11:32', NULL),
(63, 15, 'Vehicles', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(64, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(65, 1, 'Cash', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(66, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(67, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(68, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(69, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(70, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(71, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(72, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(73, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(74, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(75, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(76, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(77, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(78, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(79, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(80, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(81, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(82, 1, 'Cash', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(83, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(84, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(85, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(86, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(87, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(88, 7, 'Office Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(89, 7, 'Cartage', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(90, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(91, 8, 'Advertisement Expenses', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(92, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(93, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(94, 10, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(95, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(96, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(97, 14, 'Loan&Advances', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(98, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(99, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(100, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(101, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(102, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(103, 24, 'Income', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(104, 24, 'Discount On Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(105, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(106, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(107, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(108, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-24 05:47:38', NULL),
(109, 1, 'Cash', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(110, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(111, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(112, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(113, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(114, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(115, 7, 'Office Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(116, 7, 'Cartage', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(117, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(118, 8, 'Advertisement Expenses', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(119, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(120, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(121, 10, 'Current Liability', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(122, 10, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(123, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(124, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(125, 14, 'Loan&Advances', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(126, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(127, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(128, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(129, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(130, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(131, 24, 'Income', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(132, 24, 'Discount On Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(133, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(134, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(135, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(136, 1, 'Cash', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 06:58:29', NULL),
(137, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 06:58:29', NULL),
(138, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(139, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 06:58:15', NULL),
(140, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(141, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-03-03 11:47:49', NULL),
(142, 7, 'Office Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(143, 7, 'Cartage', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(144, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(145, 8, 'Advertisement Expenses', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(146, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(147, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(148, 10, 'Current Liability', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(149, 10, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(150, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(151, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(152, 14, 'Loan&Advances', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(153, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(154, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(155, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(156, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(157, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(158, 24, 'Income', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(159, 24, 'Discount On Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(160, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(161, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(162, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(163, 11, 'ZYX Account', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-04-18 04:58:12', NULL),
(165, 1, 'Cash', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(166, 3, 'Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, '2022-09-02 07:08:06', NULL),
(167, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(168, 5, 'Sales', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(169, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(170, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(171, 7, 'Office Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(172, 7, 'Cartage', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(173, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(174, 8, 'Advertisement Expenses', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(175, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(176, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(177, 10, 'Current Liability', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(178, 10, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(179, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(180, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(181, 14, 'Loan&Advances', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(182, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(183, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(184, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(185, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(186, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(187, 24, 'Income', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(188, 24, 'Discount On Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(189, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(190, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(191, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(192, 1, 'Cash', NULL, NULL, '0.00', '110.00', '300.00', '-190.00', NULL, 1, 2, NULL, '2022-09-13 13:20:06', NULL),
(193, 3, 'Purchase', NULL, NULL, '0.00', '1000.00', '0.00', '1000.00', NULL, 1, 2, NULL, '2022-09-13 12:42:33', NULL),
(194, 4, 'Purchase Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(195, 5, 'Sales', NULL, NULL, '0.00', '0.00', '120.00', '120.00', NULL, 1, 2, NULL, '2022-09-13 13:02:24', NULL),
(196, 6, 'Sales Return', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(197, 7, 'Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(198, 7, 'Office Expense', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(199, 7, 'Cartage', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(200, 7, 'Buy Goods', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(201, 8, 'Advertisement Expenses', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(202, 8, 'Rent Paid', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(203, 9, 'Current Asset', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(204, 10, 'Current Liability', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(205, 10, 'Salary Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(206, 10, 'Tax Deducted Payable', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(207, 13, 'Loan Liabilities', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(208, 14, 'Loan&Advances', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(209, 14, 'Advance Salary', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(210, 15, 'Furniture', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(211, 15, 'Vehicle', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(212, 22, 'Stock Adjustment', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(213, 23, 'Production', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(214, 24, 'Income', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(215, 24, 'Discount On Purchase', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(216, 24, 'Discount Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(217, 25, 'Interest Received', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL),
(218, 26, 'Capital', NULL, NULL, '0.00', '0.00', '0.00', '0.00', NULL, 1, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_branches`
--

CREATE TABLE `account_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_branches`
--

INSERT INTO `account_branches` (`id`, `branch_id`, `account_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(2, NULL, 30, 0, NULL, NULL),
(3, NULL, 31, 0, NULL, NULL),
(5, NULL, 33, 0, NULL, NULL),
(6, NULL, 34, 0, NULL, NULL),
(7, NULL, 35, 0, NULL, NULL),
(8, NULL, 36, 0, NULL, NULL),
(9, NULL, 37, 0, NULL, NULL),
(10, NULL, 38, 0, NULL, NULL),
(12, NULL, 40, 0, NULL, NULL),
(13, NULL, 41, 0, NULL, NULL),
(33, NULL, 61, 0, NULL, NULL),
(34, NULL, 62, 0, NULL, NULL),
(35, NULL, 63, 0, NULL, NULL),
(36, NULL, 64, 0, NULL, NULL),
(80, NULL, 108, 0, NULL, NULL),
(135, NULL, 163, 0, NULL, NULL),
(136, 1, 192, 0, NULL, NULL),
(137, 1, 193, 0, NULL, NULL),
(138, 1, 194, 0, NULL, NULL),
(139, 1, 195, 0, NULL, NULL),
(140, 1, 196, 0, NULL, NULL),
(141, 1, 197, 0, NULL, NULL),
(142, 1, 198, 0, NULL, NULL),
(143, 1, 199, 0, NULL, NULL),
(144, 1, 200, 0, NULL, NULL),
(145, 1, 201, 0, NULL, NULL),
(146, 1, 202, 0, NULL, NULL),
(147, 1, 203, 0, NULL, NULL),
(148, 1, 204, 0, NULL, NULL),
(149, 1, 205, 0, NULL, NULL),
(150, 1, 206, 0, NULL, NULL),
(151, 1, 207, 0, NULL, NULL),
(152, 1, 208, 0, NULL, NULL),
(153, 1, 209, 0, NULL, NULL),
(154, 1, 210, 0, NULL, NULL),
(155, 1, 211, 0, NULL, NULL),
(156, 1, 212, 0, NULL, NULL),
(157, 1, 213, 0, NULL, NULL),
(158, 1, 214, 0, NULL, NULL),
(159, 1, 215, 0, NULL, NULL),
(160, 1, 216, 0, NULL, NULL),
(161, 1, 217, 0, NULL, NULL),
(162, 1, 218, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_ledgers`
--

CREATE TABLE `account_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `voucher_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_recover_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contra_credit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contra_debit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `amount_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'debit/credit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_ledgers`
--

INSERT INTO `account_ledgers` (`id`, `branch_id`, `date`, `voucher_type`, `account_id`, `expense_id`, `expense_payment_id`, `sale_id`, `sale_payment_id`, `supplier_payment_id`, `sale_return_id`, `purchase_id`, `purchase_payment_id`, `customer_payment_id`, `purchase_return_id`, `adjustment_id`, `stock_adjustment_recover_id`, `payroll_id`, `payroll_payment_id`, `production_id`, `loan_id`, `loan_payment_id`, `contra_credit_id`, `contra_debit_id`, `debit`, `credit`, `running_balance`, `amount_type`, `created_at`, `updated_at`) VALUES
(1, NULL, '2022-02-12 07:44:54', '0', 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-12 07:44:54', '2022-04-18 04:55:12'),
(2, NULL, '2022-02-12 07:50:05', '0', 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-12 07:50:05', '2022-02-12 07:50:05'),
(4, NULL, '2022-02-12 08:16:28', '0', 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '100.00', 'debit', '2022-02-12 08:16:28', '2022-03-03 11:46:03'),
(5, NULL, '2022-02-12 08:16:55', '0', 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-12 08:16:55', '2022-03-03 11:45:28'),
(6, NULL, '2022-02-12 08:17:19', '0', 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-12 08:17:19', '2022-02-12 08:17:19'),
(7, NULL, '2022-02-12 08:20:22', '0', 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-12 08:20:22', '2022-02-12 08:20:22'),
(8, NULL, '2022-02-12 08:21:10', '0', 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-12 08:21:10', '2022-02-12 08:21:10'),
(11, NULL, '2022-02-12 08:27:48', '0', 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-12 08:27:48', '2022-02-12 08:27:48'),
(23, NULL, '2022-02-13 10:58:43', '0', 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 10:58:43', '2022-04-05 10:45:54'),
(26, NULL, '2022-02-13 11:00:41', '0', 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:00:41', '2022-03-03 11:45:55'),
(33, NULL, '2022-02-13 11:18:16', '0', 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(34, NULL, '2022-02-13 11:18:16', '0', 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(35, NULL, '2022-02-13 11:18:16', '0', 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(36, NULL, '2022-02-13 11:18:16', '0', 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(37, NULL, '2022-02-13 11:18:16', '0', 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(38, NULL, '2022-02-13 11:18:16', '0', 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(39, NULL, '2022-02-13 11:18:16', '0', 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(40, NULL, '2022-02-13 11:18:16', '0', 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(41, NULL, '2022-02-13 11:18:16', '0', 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(42, NULL, '2022-02-13 11:18:16', '0', 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(43, NULL, '2022-02-13 11:18:16', '0', 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(44, NULL, '2022-02-13 11:18:16', '0', 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(45, NULL, '2022-02-13 11:18:16', '0', 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(46, NULL, '2022-02-13 11:18:16', '0', 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(47, NULL, '2022-02-13 11:18:16', '0', 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:16', '2022-02-13 11:18:16'),
(48, NULL, '2022-02-13 11:18:17', '0', 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:17', '2022-02-13 11:18:17'),
(49, NULL, '2022-02-13 11:18:17', '0', 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-13 11:18:17', '2022-02-13 11:18:17'),
(50, NULL, '2022-02-13 11:24:45', '0', 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-13 11:24:45', '2022-02-13 11:24:45'),
(80, NULL, '2022-02-14 08:19:18', '0', 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-14 08:19:18', '2022-02-14 08:19:18'),
(89, NULL, '2022-02-14 08:30:08', '0', 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-14 08:30:08', '2022-02-16 06:11:32'),
(90, NULL, '2022-02-14 11:12:44', '0', 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-14 11:12:44', '2022-02-14 11:12:44'),
(91, NULL, '2022-02-14 13:09:28', '0', 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-14 13:09:28', '2022-02-14 13:09:28'),
(96, NULL, '2022-02-16 04:50:44', '0', 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:44', '2022-02-16 04:50:44'),
(97, NULL, '2022-02-16 04:50:45', '0', 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(98, NULL, '2022-02-16 04:50:45', '0', 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(99, NULL, '2022-02-16 04:50:45', '0', 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(100, NULL, '2022-02-16 04:50:45', '0', 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(101, NULL, '2022-02-16 04:50:45', '0', 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(102, NULL, '2022-02-16 04:50:45', '0', 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(103, NULL, '2022-02-16 04:50:45', '0', 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(104, NULL, '2022-02-16 04:50:45', '0', 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(105, NULL, '2022-02-16 04:50:45', '0', 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(106, NULL, '2022-02-16 04:50:45', '0', 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(107, NULL, '2022-02-16 04:50:45', '0', 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(108, NULL, '2022-02-16 04:50:45', '0', 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(109, NULL, '2022-02-16 04:50:45', '0', 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(110, NULL, '2022-02-16 04:50:45', '0', 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(111, NULL, '2022-02-16 04:50:45', '0', 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(112, NULL, '2022-02-16 04:50:45', '0', 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 04:50:45', '2022-02-16 04:50:45'),
(113, NULL, '2022-02-16 05:06:29', '0', 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(114, NULL, '2022-02-16 05:06:29', '0', 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(115, NULL, '2022-02-16 05:06:29', '0', 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(116, NULL, '2022-02-16 05:06:29', '0', 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(117, NULL, '2022-02-16 05:06:29', '0', 86, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(118, NULL, '2022-02-16 05:06:29', '0', 87, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(119, NULL, '2022-02-16 05:06:29', '0', 88, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(120, NULL, '2022-02-16 05:06:29', '0', 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(121, NULL, '2022-02-16 05:06:29', '0', 90, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(122, NULL, '2022-02-16 05:06:29', '0', 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(123, NULL, '2022-02-16 05:06:29', '0', 92, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(124, NULL, '2022-02-16 05:06:29', '0', 93, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(125, NULL, '2022-02-16 05:06:29', '0', 94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(126, NULL, '2022-02-16 05:06:29', '0', 95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(127, NULL, '2022-02-16 05:06:29', '0', 96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:29', '2022-02-16 05:06:29'),
(128, NULL, '2022-02-16 05:06:30', '0', 97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(129, NULL, '2022-02-16 05:06:30', '0', 98, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(130, NULL, '2022-02-16 05:06:30', '0', 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(131, NULL, '2022-02-16 05:06:30', '0', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(132, NULL, '2022-02-16 05:06:30', '0', 101, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(133, NULL, '2022-02-16 05:06:30', '0', 102, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(134, NULL, '2022-02-16 05:06:30', '0', 103, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(135, NULL, '2022-02-16 05:06:30', '0', 104, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(136, NULL, '2022-02-16 05:06:30', '0', 105, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(137, NULL, '2022-02-16 05:06:30', '0', 106, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(138, NULL, '2022-02-16 05:06:30', '0', 107, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-16 05:06:30', '2022-02-16 05:06:30'),
(156, NULL, '2022-02-19 04:53:39', '0', 108, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-19 04:53:39', '2022-02-19 04:53:39'),
(179, NULL, '2022-02-20 08:59:47', '0', 109, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(180, NULL, '2022-02-20 08:59:47', '0', 110, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(181, NULL, '2022-02-20 08:59:47', '0', 111, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(182, NULL, '2022-02-20 08:59:47', '0', 112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(183, NULL, '2022-02-20 08:59:47', '0', 113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(184, NULL, '2022-02-20 08:59:47', '0', 114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(185, NULL, '2022-02-20 08:59:47', '0', 115, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:47', '2022-02-20 08:59:47'),
(186, NULL, '2022-02-20 08:59:48', '0', 116, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(187, NULL, '2022-02-20 08:59:48', '0', 117, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(188, NULL, '2022-02-20 08:59:48', '0', 118, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(189, NULL, '2022-02-20 08:59:48', '0', 119, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(190, NULL, '2022-02-20 08:59:48', '0', 120, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(191, NULL, '2022-02-20 08:59:48', '0', 121, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(192, NULL, '2022-02-20 08:59:48', '0', 122, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(193, NULL, '2022-02-20 08:59:48', '0', 123, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(194, NULL, '2022-02-20 08:59:48', '0', 124, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(195, NULL, '2022-02-20 08:59:48', '0', 125, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(196, NULL, '2022-02-20 08:59:48', '0', 126, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(197, NULL, '2022-02-20 08:59:48', '0', 127, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(198, NULL, '2022-02-20 08:59:48', '0', 128, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(199, NULL, '2022-02-20 08:59:48', '0', 129, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(200, NULL, '2022-02-20 08:59:48', '0', 130, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(201, NULL, '2022-02-20 08:59:48', '0', 131, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(202, NULL, '2022-02-20 08:59:48', '0', 132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(203, NULL, '2022-02-20 08:59:48', '0', 133, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:48', '2022-02-20 08:59:48'),
(204, NULL, '2022-02-20 08:59:49', '0', 134, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:49', '2022-02-20 08:59:49'),
(205, NULL, '2022-02-20 08:59:49', '0', 135, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 08:59:49', '2022-02-20 08:59:49'),
(206, NULL, '2022-02-20 10:56:23', '0', 136, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:23', '2022-02-20 10:56:23'),
(207, NULL, '2022-02-20 10:56:23', '0', 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:23', '2022-02-20 10:56:23'),
(208, NULL, '2022-02-20 10:56:23', '0', 138, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:23', '2022-02-20 10:56:23'),
(209, NULL, '2022-02-20 10:56:24', '0', 139, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(210, NULL, '2022-02-20 10:56:24', '0', 140, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(211, NULL, '2022-02-20 10:56:24', '0', 141, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(212, NULL, '2022-02-20 10:56:24', '0', 142, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(213, NULL, '2022-02-20 10:56:24', '0', 143, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(214, NULL, '2022-02-20 10:56:24', '0', 144, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(215, NULL, '2022-02-20 10:56:24', '0', 145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(216, NULL, '2022-02-20 10:56:24', '0', 146, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(217, NULL, '2022-02-20 10:56:24', '0', 147, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(218, NULL, '2022-02-20 10:56:24', '0', 148, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(219, NULL, '2022-02-20 10:56:24', '0', 149, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(220, NULL, '2022-02-20 10:56:24', '0', 150, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(221, NULL, '2022-02-20 10:56:24', '0', 151, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(222, NULL, '2022-02-20 10:56:24', '0', 152, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(223, NULL, '2022-02-20 10:56:24', '0', 153, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(224, NULL, '2022-02-20 10:56:24', '0', 154, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(225, NULL, '2022-02-20 10:56:24', '0', 155, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(226, NULL, '2022-02-20 10:56:24', '0', 156, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(227, NULL, '2022-02-20 10:56:24', '0', 157, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-20 10:56:24', '2022-02-20 10:56:24'),
(228, NULL, '2022-02-20 10:56:25', '0', 158, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:25', '2022-02-20 10:56:25'),
(229, NULL, '2022-02-20 10:56:25', '0', 159, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:25', '2022-02-20 10:56:25'),
(230, NULL, '2022-02-20 10:56:25', '0', 160, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:25', '2022-02-20 10:56:25'),
(231, NULL, '2022-02-20 10:56:25', '0', 161, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:25', '2022-02-20 10:56:25'),
(232, NULL, '2022-02-20 10:56:25', '0', 162, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-02-20 10:56:25', '2022-02-20 10:56:25'),
(269, NULL, '2022-02-27 05:51:49', '0', 163, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-02-27 05:51:49', '2022-04-18 04:58:12'),
(665, NULL, '2022-05-09 11:21:03', '0', 165, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(666, NULL, '2022-05-09 11:21:03', '0', 166, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(667, NULL, '2022-05-09 11:21:03', '0', 167, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(668, NULL, '2022-05-09 11:21:03', '0', 168, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(669, NULL, '2022-05-09 11:21:03', '0', 169, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(670, NULL, '2022-05-09 11:21:03', '0', 170, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(671, NULL, '2022-05-09 11:21:03', '0', 171, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(672, NULL, '2022-05-09 11:21:03', '0', 172, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(673, NULL, '2022-05-09 11:21:03', '0', 173, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(674, NULL, '2022-05-09 11:21:03', '0', 174, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(675, NULL, '2022-05-09 11:21:03', '0', 175, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(676, NULL, '2022-05-09 11:21:03', '0', 176, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(677, NULL, '2022-05-09 11:21:03', '0', 177, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:03', '2022-05-09 11:21:03'),
(678, NULL, '2022-05-09 11:21:04', '0', 178, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(679, NULL, '2022-05-09 11:21:04', '0', 179, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(680, NULL, '2022-05-09 11:21:04', '0', 180, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(681, NULL, '2022-05-09 11:21:04', '0', 181, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(682, NULL, '2022-05-09 11:21:04', '0', 182, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(683, NULL, '2022-05-09 11:21:04', '0', 183, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(684, NULL, '2022-05-09 11:21:04', '0', 184, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(685, NULL, '2022-05-09 11:21:04', '0', 185, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(686, NULL, '2022-05-09 11:21:04', '0', 186, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(687, NULL, '2022-05-09 11:21:04', '0', 187, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(688, NULL, '2022-05-09 11:21:04', '0', 188, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(689, NULL, '2022-05-09 11:21:04', '0', 189, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(690, NULL, '2022-05-09 11:21:04', '0', 190, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(691, NULL, '2022-05-09 11:21:04', '0', 191, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-05-09 11:21:04', '2022-05-09 11:21:04'),
(841, NULL, '2022-09-12 12:02:12', '3', 36, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '46200.00', '0.00', '46200.00', 'debit', '2022-09-12 12:02:12', '2022-09-12 12:02:13'),
(842, NULL, '2022-09-12 12:23:57', '3', 36, NULL, NULL, NULL, NULL, NULL, NULL, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '300.00', '0.00', '46500.00', 'debit', '2022-09-12 12:23:57', '2022-09-12 12:23:57'),
(843, NULL, '2022-09-12 12:24:23', '1', 31, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '450.00', '450.00', 'credit', '2022-09-12 12:24:23', '2022-09-12 12:24:23'),
(844, NULL, '2022-09-12 12:24:23', '10', 30, NULL, NULL, NULL, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '450.00', '0.00', '450.00', 'debit', '2022-09-12 12:24:23', '2022-09-12 12:24:23'),
(845, NULL, '2022-09-12 13:26:20', '1', 31, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '57750.00', '58200.00', 'credit', '2022-09-12 13:26:20', '2022-09-12 13:26:20'),
(846, NULL, '2022-09-13 07:16:28', '1', 31, NULL, NULL, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '58550.00', '116750.00', 'credit', '2022-09-13 07:16:28', '2022-09-13 07:16:28'),
(847, NULL, '2022-09-13 07:16:43', '1', 31, NULL, NULL, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '58550.00', '175300.00', 'credit', '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(848, NULL, '2022-09-13 07:17:41', '1', 31, NULL, NULL, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '58550.00', '233850.00', 'credit', '2022-09-13 07:17:41', '2022-09-13 07:17:41'),
(849, NULL, '2022-09-13 07:23:00', '1', 31, NULL, NULL, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '800.00', '234650.00', 'credit', '2022-09-13 07:23:00', '2022-09-13 07:23:00'),
(850, NULL, '2022-09-13 07:27:37', '1', 31, NULL, NULL, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '57750.00', '292400.00', 'credit', '2022-09-13 07:27:37', '2022-09-13 07:27:37'),
(851, NULL, '2022-09-13 12:40:44', '0', 192, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(852, NULL, '2022-09-13 12:40:44', '0', 193, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(853, NULL, '2022-09-13 12:40:44', '0', 194, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(854, NULL, '2022-09-13 12:40:44', '0', 195, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(855, NULL, '2022-09-13 12:40:44', '0', 196, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(856, NULL, '2022-09-13 12:40:44', '0', 197, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(857, NULL, '2022-09-13 12:40:44', '0', 198, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(858, NULL, '2022-09-13 12:40:44', '0', 199, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(859, NULL, '2022-09-13 12:40:44', '0', 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:44', '2022-09-13 12:40:44'),
(860, NULL, '2022-09-13 12:40:45', '0', 201, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(861, NULL, '2022-09-13 12:40:45', '0', 202, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(862, NULL, '2022-09-13 12:40:45', '0', 203, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(863, NULL, '2022-09-13 12:40:45', '0', 204, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(864, NULL, '2022-09-13 12:40:45', '0', 205, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(865, NULL, '2022-09-13 12:40:45', '0', 206, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(866, NULL, '2022-09-13 12:40:45', '0', 207, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(867, NULL, '2022-09-13 12:40:45', '0', 208, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(868, NULL, '2022-09-13 12:40:45', '0', 209, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(869, NULL, '2022-09-13 12:40:45', '0', 210, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(870, NULL, '2022-09-13 12:40:45', '0', 211, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(871, NULL, '2022-09-13 12:40:45', '0', 212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(872, NULL, '2022-09-13 12:40:45', '0', 213, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'debit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(873, NULL, '2022-09-13 12:40:45', '0', 214, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(874, NULL, '2022-09-13 12:40:45', '0', 215, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(875, NULL, '2022-09-13 12:40:45', '0', 216, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(876, NULL, '2022-09-13 12:40:45', '0', 217, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(877, NULL, '2022-09-13 12:40:45', '0', 218, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', 'credit', '2022-09-13 12:40:45', '2022-09-13 12:40:45'),
(878, 1, '2022-09-13 12:42:33', '3', 193, NULL, NULL, NULL, NULL, NULL, NULL, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', '0.00', '1000.00', 'debit', '2022-09-13 12:42:33', '2022-09-13 12:42:33'),
(879, 1, '2022-09-13 13:02:24', '1', 195, NULL, NULL, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '120.00', '120.00', 'credit', '2022-09-13 13:02:24', '2022-09-13 13:02:24'),
(880, 1, '2022-09-13 13:02:50', '18', 192, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '110.00', '0.00', '110.00', 'debit', '2022-09-13 13:02:50', '2022-09-13 13:02:50'),
(881, 1, '2022-09-13 13:20:06', '19', 192, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '300.00', '-190.00', 'credit', '2022-09-13 13:20:06', '2022-09-13 13:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branches` tinyint(1) NOT NULL DEFAULT '0',
  `hrm` tinyint(1) NOT NULL DEFAULT '0',
  `todo` tinyint(1) NOT NULL DEFAULT '0',
  `service` tinyint(1) NOT NULL DEFAULT '0',
  `manufacturing` tinyint(1) NOT NULL DEFAULT '0',
  `e_commerce` tinyint(1) NOT NULL DEFAULT '0',
  `branch_limit` bigint(20) NOT NULL DEFAULT '0',
  `cash_counter_limit` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `branches`, `hrm`, `todo`, `service`, `manufacturing`, `e_commerce`, `branch_limit`, `cash_counter_limit`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 1, 0, 99999999999999, 999999999999999999, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_and_users`
--

CREATE TABLE `admin_and_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_type` int(11) DEFAULT NULL COMMENT '1=super_admin,2=admin,3=others',
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_permission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allow_login` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_commission_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `max_sales_discount_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` text COLLATE utf8mb4_unicode_ci,
  `current_address` text COLLATE utf8mb4_unicode_ci,
  `bank_ac_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ac_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_identifier_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_payer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `salary` decimal(22,2) NOT NULL DEFAULT '0.00',
  `salary_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_and_users`
--

INSERT INTO `admin_and_users` (`id`, `prefix`, `name`, `last_name`, `emp_id`, `username`, `email`, `shift_id`, `role_type`, `role_id`, `role_permission_id`, `allow_login`, `branch_id`, `status`, `password`, `sales_commission_percent`, `max_sales_discount_percent`, `phone`, `date_of_birth`, `gender`, `marital_status`, `blood_group`, `photo`, `facebook_link`, `twitter_link`, `instagram_link`, `social_media_1`, `social_media_2`, `custom_field_1`, `custom_field_2`, `guardian_name`, `id_proof_name`, `id_proof_number`, `permanent_address`, `current_address`, `bank_ac_holder_name`, `bank_ac_no`, `bank_name`, `bank_identifier_code`, `bank_branch`, `tax_payer_id`, `language`, `department_id`, `designation_id`, `salary`, `salary_type`, `created_at`, `updated_at`) VALUES
(2, 'Mr', 'Super', 'Admin', NULL, 'superadmin', 'gollachuttelecare@gmail.com', NULL, 1, NULL, 8, 1, NULL, 1, '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai', '0.00', '0.00', NULL, NULL, 'Male', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', NULL, NULL, '0.00', NULL, '2021-04-07 07:04:03', '2022-09-12 06:08:41'),
(3, NULL, 'business', NULL, NULL, 'business2', NULL, NULL, 3, 41, 32, 1, 1, 1, '$2y$10$8vcoJw1CLxCmFmCiG47KHOR7Iwed4S1bDBiam3RrbsW9ypqv7rkd6', '0.00', '0.00', '0122544555', NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '2022-09-13 12:40:46', '2022-09-13 12:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `admin_and_user_logs`
--

CREATE TABLE `admin_and_user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mac_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allowance_employees`
--

CREATE TABLE `allowance_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allowance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `per_unit_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_name`, `type_id`, `branch_id`, `quantity`, `per_unit_value`, `total_value`, `created_at`, `updated_at`) VALUES
(22, 'Toyota car', 9, NULL, '3.00', '1000000.00', '3000000.00', NULL, NULL),
(23, 'Office Advance', 10, NULL, '1.00', '40000.00', '40000.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `asset_types`
--

CREATE TABLE `asset_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_type_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_types`
--

INSERT INTO `asset_types` (`id`, `asset_type_name`, `asset_type_code`, `created_at`, `updated_at`) VALUES
(9, 'Car', 'C-1', NULL, '2021-07-18 05:01:28'),
(10, 'Refundable', '11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch_name`, `address`, `created_at`, `updated_at`) VALUES
(8, 'SONALI BANK', 'Dhaka Branch', 'Dhaka, Bangladesh', NULL, NULL),
(9, 'Bank 1', 'Dhaka', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `barcode_settings`
--

CREATE TABLE `barcode_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_continuous` tinyint(1) NOT NULL DEFAULT '0',
  `top_margin` double(22,4) NOT NULL DEFAULT '0.0000',
  `left_margin` double(22,4) NOT NULL DEFAULT '0.0000',
  `sticker_width` double(22,4) NOT NULL DEFAULT '0.0000',
  `sticker_height` double(22,4) NOT NULL DEFAULT '0.0000',
  `paper_width` double(22,4) NOT NULL DEFAULT '0.0000',
  `paper_height` double(22,4) NOT NULL DEFAULT '0.0000',
  `row_distance` double(22,4) NOT NULL DEFAULT '0.0000',
  `column_distance` double(22,4) NOT NULL DEFAULT '0.0000',
  `stickers_in_a_row` bigint(20) NOT NULL DEFAULT '0',
  `stickers_in_one_sheet` bigint(20) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcode_settings`
--

INSERT INTO `barcode_settings` (`id`, `name`, `description`, `is_continuous`, `top_margin`, `left_margin`, `sticker_width`, `sticker_height`, `paper_width`, `paper_height`, `row_distance`, `column_distance`, `stickers_in_a_row`, `stickers_in_one_sheet`, `is_default`, `is_fixed`, `created_at`, `updated_at`) VALUES
(1, '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', 0, 0.1200, 0.1200, 4.0000, 0.5500, 8.5000, 11.0000, 1.0000, 1.0000, 10, 20, 0, 1, NULL, '2021-07-01 11:09:33'),
(2, 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm', NULL, 1, 0.0000, 0.0000, 2.0000, 0.5000, 1.8000, 0.9843, 0.0000, 0.0000, 1, 1, 1, 1, NULL, '2021-07-01 09:40:09'),
(3, '40 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2\'\' * 0.39\'\', Barcode 40 Per Sheet', NULL, 0, 0.3000, 0.1000, 2.0000, 0.3900, 8.5000, 11.0000, 0.0000, 0.0000, 10, 30, 0, 1, NULL, '2021-07-01 11:55:53'),
(4, '30 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2.4\'\' * 0.55\'\', Barcode 30 Per Sheet', NULL, 0, 0.1000, 0.1000, 2.4000, 0.5500, 8.5000, 11.0000, 0.0000, 0.0000, 30, 30, 0, 1, NULL, '2021-07-01 12:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `invoice_schema_id` bigint(20) UNSIGNED DEFAULT NULL,
  `add_sale_invoice_layout_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pos_sale_invoice_layout_id` bigint(20) UNSIGNED DEFAULT NULL,
  `default_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_permission` tinyint(1) NOT NULL DEFAULT '0',
  `after_purchase_store` tinyint(4) DEFAULT NULL COMMENT '1=branch;2=warehouse',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `branch_code`, `phone`, `city`, `state`, `zip_code`, `alternate_phone_number`, `country`, `email`, `website`, `logo`, `invoice_schema_id`, `add_sale_invoice_layout_id`, `pos_sale_invoice_layout_id`, `default_account_id`, `purchase_permission`, `after_purchase_store`, `created_at`, `updated_at`) VALUES
(1, 'Business Location 2', 'BL2', '0104422554', 'Dhaka', 'Dhaka', '9000', NULL, 'Bangladesh', NULL, NULL, 'default.png', 3, 1, 1, NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_payment_methods`
--

CREATE TABLE `branch_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(28, 'Dell', 'default.png', 1, '2021-08-12 05:21:12', '2021-08-12 05:21:12'),
(29, 'Kaspersky', 'default.png', 1, '2021-08-12 05:26:35', '2021-08-12 05:26:35'),
(30, 'HP', 'default.png', 1, '2021-08-12 05:28:52', '2021-08-12 05:28:52'),
(31, 'Microsoft', 'default.png', 1, '2021-08-12 05:38:24', '2021-08-12 05:38:24'),
(32, 'Zebra', 'default.png', 1, '2021-08-22 09:01:16', '2021-08-22 09:01:16'),
(33, 'MyCustomer', 'default.png', 1, NULL, NULL),
(34, 'Unilever Number 1 Brand', 'default.png', 1, '2022-01-22 08:31:39', '2022-04-06 08:35:27'),
(35, 'Samsung', 'default.png', 1, '2022-04-18 07:06:48', '2022-04-18 07:06:48');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_variants`
--

CREATE TABLE `bulk_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_variant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulk_variants`
--

INSERT INTO `bulk_variants` (`id`, `bulk_variant_name`, `created_at`, `updated_at`) VALUES
(16, 'Ram', '2021-08-26 08:33:10', '2021-08-26 08:33:10'),
(17, 'Storage', '2022-03-13 07:05:22', '2022-03-13 07:05:22'),
(18, 'Size', '2022-03-13 07:06:03', '2022-03-13 07:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_variant_children`
--

CREATE TABLE `bulk_variant_children` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `child_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulk_variant_children`
--

INSERT INTO `bulk_variant_children` (`id`, `bulk_variant_id`, `child_name`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(61, 16, '4GB', 0, '2021-08-26 08:33:10', '2022-04-06 10:07:12'),
(62, 16, '8GB', 0, '2021-08-26 08:33:10', '2022-04-06 10:07:12'),
(63, 16, '12GB', 0, '2021-08-26 08:33:10', '2022-04-06 10:07:13'),
(64, 17, '32GB', 0, '2022-03-13 07:05:22', '2022-03-13 07:05:22'),
(65, 17, '64GB', 0, '2022-03-13 07:05:22', '2022-03-13 07:05:22'),
(66, 17, '128GB', 0, '2022-03-13 07:05:22', '2022-03-13 07:05:22'),
(67, 18, 'M', 0, '2022-03-13 07:06:03', '2022-03-13 07:06:03'),
(68, 18, 'L', 0, '2022-03-13 07:06:03', '2022-03-13 07:06:03'),
(69, 18, 'XL', 0, '2022-03-13 07:06:03', '2022-03-13 07:06:03'),
(70, 18, 'XXL', 0, '2022-03-13 07:06:03', '2022-03-13 07:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `cash_counters`
--

CREATE TABLE `cash_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `counter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_counters`
--

INSERT INTO `cash_counters` (`id`, `branch_id`, `counter_name`, `short_name`, `created_at`, `updated_at`) VALUES
(6, NULL, 'Cash Counter 1', 'CN-1', NULL, NULL),
(7, NULL, 'Counter-1', 'CCN-1', NULL, NULL),
(8, 1, 'Counter-1', 'CCN-1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `sender_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expanse_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit` decimal(22,2) DEFAULT NULL,
  `credit` decimal(22,2) DEFAULT NULL,
  `balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance;8=payroll_payment;9=money_receipt;10=loan-get/pay;11=loan_ins_payment/receive;12=supplier_payment;13=customer_payment',
  `cash_type` tinyint(4) DEFAULT NULL COMMENT '1=debit;2=credit;',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_cash_flow_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_counter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_in_hand` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=open;0=closed;',
  `closing_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_registers`
--

INSERT INTO `cash_registers` (`id`, `sale_account_id`, `cash_counter_id`, `branch_id`, `admin_id`, `cash_in_hand`, `date`, `closed_at`, `closed_amount`, `status`, `closing_note`, `created_at`, `updated_at`) VALUES
(41, 31, NULL, NULL, 2, '0.00', '12-02-2022 01:50:12', '2022-03-15 13:27:03', '2469.00', 0, NULL, '2022-02-12 07:50:12', '2022-03-15 13:27:03'),
(43, 31, 6, NULL, 2, '0.00', '15-03-2022 07:27:10', NULL, '0.00', 1, NULL, '2022-03-15 13:27:10', '2022-03-15 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `cash_register_transactions`
--

CREATE TABLE `cash_register_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_register_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_register_transactions`
--

INSERT INTO `cash_register_transactions` (`id`, `cash_register_id`, `sale_id`, `created_at`, `updated_at`) VALUES
(1, 43, 67, '2022-09-13 07:23:01', '2022-09-13 07:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_category_id`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(113, 'Electronics', NULL, NULL, 'default.png', 1, NULL, NULL),
(114, 'Glossary', NULL, NULL, 'default.png', 1, NULL, NULL),
(115, 'Food', NULL, NULL, 'default.png', 1, NULL, NULL),
(116, 'Mobile', NULL, 113, 'default.png', 1, NULL, NULL),
(117, 'Laptop', NULL, 113, 'default.png', 1, NULL, NULL),
(118, 'Computer accessories', NULL, 113, 'default.png', 1, NULL, NULL),
(119, 'Build Materials', 'This category only for raw mataria.', NULL, 'default.png', 1, '2021-11-13 07:34:42', '2022-03-07 12:05:46'),
(120, 'Smart Watch', 'Smart Watch like xiaomi, Realme, One Plus', 113, 'default.png', 1, NULL, '2022-03-07 12:06:46'),
(121, 'Software', 'For our made software', NULL, 'default.png', 1, NULL, NULL),
(122, 'Liquid', NULL, NULL, 'default.png', 1, '2022-04-06 07:09:46', '2022-04-06 07:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `combo_products`
--

CREATE TABLE `combo_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `combo_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT '0.00',
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contras`
--

CREATE TABLE `contras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` mediumtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousand_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`, `created_at`, `updated_at`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek', ',', '.', NULL, NULL),
(2, 'America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(3, 'Afghanistan', 'Afghanis', 'AF', '؋', ',', '.', NULL, NULL),
(4, 'Argentina', 'Pesos', 'ARS', '$', ',', '.', NULL, NULL),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ', ',', '.', NULL, NULL),
(6, 'Australia', 'Dollars', 'AUD', '$', ',', '.', NULL, NULL),
(7, 'Azerbaijan', 'New Manats', 'AZ', 'ман', ',', '.', NULL, NULL),
(8, 'Bahamas', 'Dollars', 'BSD', '$', ',', '.', NULL, NULL),
(9, 'Barbados', 'Dollars', 'BBD', '$', ',', '.', NULL, NULL),
(10, 'Belarus', 'Rubles', 'BYR', 'p.', ',', '.', NULL, NULL),
(11, 'Belgium', 'Euro', 'EUR', '€', ',', '.', NULL, NULL),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$', ',', '.', NULL, NULL),
(13, 'Bermuda', 'Dollars', 'BMD', '$', ',', '.', NULL, NULL),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b', ',', '.', NULL, NULL),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM', ',', '.', NULL, NULL),
(16, 'Botswana', 'Pula\'s', 'BWP', 'P', ',', '.', NULL, NULL),
(17, 'Bulgaria', 'Leva', 'BG', 'лв', ',', '.', NULL, NULL),
(18, 'Brazil', 'Reais', 'BRL', 'R$', ',', '.', NULL, NULL),
(19, 'Britain [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$', ',', '.', NULL, NULL),
(21, 'Cambodia', 'Riels', 'KHR', '៛', ',', '.', NULL, NULL),
(22, 'Canada', 'Dollars', 'CAD', '$', ',', '.', NULL, NULL),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$', ',', '.', NULL, NULL),
(24, 'Chile', 'Pesos', 'CLP', '$', ',', '.', NULL, NULL),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥', ',', '.', NULL, NULL),
(26, 'Colombia', 'Pesos', 'COP', '$', ',', '.', NULL, NULL),
(27, 'Costa Rica', 'Colón', 'CRC', '₡', ',', '.', NULL, NULL),
(28, 'Croatia', 'Kuna', 'HRK', 'kn', ',', '.', NULL, NULL),
(29, 'Cuba', 'Pesos', 'CUP', '₱', ',', '.', NULL, NULL),
(30, 'Cyprus', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč', ',', '.', NULL, NULL),
(32, 'Denmark', 'Kroner', 'DKK', 'kr', ',', '.', NULL, NULL),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$', ',', '.', NULL, NULL),
(34, 'East Caribbean', 'Dollars', 'XCD', '$', ',', '.', NULL, NULL),
(35, 'Egypt', 'Pounds', 'EGP', '£', ',', '.', NULL, NULL),
(36, 'El Salvador', 'Colones', 'SVC', '$', ',', '.', NULL, NULL),
(37, 'England [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(38, 'Euro', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£', ',', '.', NULL, NULL),
(40, 'Fiji', 'Dollars', 'FJD', '$', ',', '.', NULL, NULL),
(41, 'France', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(42, 'Ghana', 'Cedis', 'GHC', '¢', ',', '.', NULL, NULL),
(43, 'Gibraltar', 'Pounds', 'GIP', '£', ',', '.', NULL, NULL),
(44, 'Greece', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q', ',', '.', NULL, NULL),
(46, 'Guernsey', 'Pounds', 'GGP', '£', ',', '.', NULL, NULL),
(47, 'Guyana', 'Dollars', 'GYD', '$', ',', '.', NULL, NULL),
(48, 'Holland [Netherlands]', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(49, 'Honduras', 'Lempiras', 'HNL', 'L', ',', '.', NULL, NULL),
(50, 'Hong Kong', 'Dollars', 'HKD', '$', ',', '.', NULL, NULL),
(51, 'Hungary', 'Forint', 'HUF', 'Ft', ',', '.', NULL, NULL),
(52, 'Iceland', 'Kronur', 'ISK', 'kr', ',', '.', NULL, NULL),
(53, 'India', 'Rupees', 'INR', '₹', ',', '.', NULL, NULL),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp', ',', '.', NULL, NULL),
(55, 'Iran', 'Rials', 'IRR', '﷼', ',', '.', NULL, NULL),
(56, 'Ireland', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(57, 'Isle of Man', 'Pounds', 'IMP', '£', ',', '.', NULL, NULL),
(58, 'Israel', 'New Shekels', 'ILS', '₪', ',', '.', NULL, NULL),
(59, 'Italy', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$', ',', '.', NULL, NULL),
(61, 'Japan', 'Yen', 'JPY', '¥', ',', '.', NULL, NULL),
(62, 'Jersey', 'Pounds', 'JEP', '£', ',', '.', NULL, NULL),
(63, 'Kazakhstan', 'Tenge', 'KZT', 'лв', ',', '.', NULL, NULL),
(64, 'Korea [North]', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(65, 'Korea [South]', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'лв', ',', '.', NULL, NULL),
(67, 'Laos', 'Kips', 'LAK', '₭', ',', '.', NULL, NULL),
(68, 'Latvia', 'Lati', 'LVL', 'Ls', ',', '.', NULL, NULL),
(69, 'Lebanon', 'Pounds', 'LBP', '£', ',', '.', NULL, NULL),
(70, 'Liberia', 'Dollars', 'LRD', '$', ',', '.', NULL, NULL),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt', ',', '.', NULL, NULL),
(73, 'Luxembourg', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(74, 'Macedonia', 'Denars', 'MKD', 'ден', ',', '.', NULL, NULL),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM', ',', '.', NULL, NULL),
(76, 'Malta', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(77, 'Mauritius', 'Rupees', 'MUR', '₨', ',', '.', NULL, NULL),
(78, 'Mexico', 'Pesos', 'MXN', '$', ',', '.', NULL, NULL),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮', ',', '.', NULL, NULL),
(80, 'Mozambique', 'Meticais', 'MZ', 'MT', ',', '.', NULL, NULL),
(81, 'Namibia', 'Dollars', 'NAD', '$', ',', '.', NULL, NULL),
(82, 'Nepal', 'Rupees', 'NPR', '₨', ',', '.', NULL, NULL),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ', ',', '.', NULL, NULL),
(84, 'Netherlands', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(85, 'New Zealand', 'Dollars', 'NZD', '$', ',', '.', NULL, NULL),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$', ',', '.', NULL, NULL),
(87, 'Nigeria', 'Nairas', 'NG', '₦', ',', '.', NULL, NULL),
(88, 'North Korea', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(89, 'Norway', 'Krone', 'NOK', 'kr', ',', '.', NULL, NULL),
(90, 'Oman', 'Rials', 'OMR', '﷼', ',', '.', NULL, NULL),
(91, 'Pakistan', 'Rupees', 'PKR', '₨', ',', '.', NULL, NULL),
(92, 'Panama', 'Balboa', 'PAB', 'B/.', ',', '.', NULL, NULL),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs', ',', '.', NULL, NULL),
(94, 'Peru', 'Nuevos Soles', 'PE', 'S/.', ',', '.', NULL, NULL),
(95, 'Philippines', 'Pesos', 'PHP', 'Php', ',', '.', NULL, NULL),
(96, 'Poland', 'Zlotych', 'PL', 'zł', ',', '.', NULL, NULL),
(97, 'Qatar', 'Rials', 'QAR', '﷼', ',', '.', NULL, NULL),
(98, 'Romania', 'New Lei', 'RO', 'lei', ',', '.', NULL, NULL),
(99, 'Russia', 'Rubles', 'RUB', 'руб', ',', '.', NULL, NULL),
(100, 'Saint Helena', 'Pounds', 'SHP', '£', ',', '.', NULL, NULL),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼', ',', '.', NULL, NULL),
(102, 'Serbia', 'Dinars', 'RSD', 'Дин.', ',', '.', NULL, NULL),
(103, 'Seychelles', 'Rupees', 'SCR', '₨', ',', '.', NULL, NULL),
(104, 'Singapore', 'Dollars', 'SGD', '$', ',', '.', NULL, NULL),
(105, 'Slovenia', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$', ',', '.', NULL, NULL),
(107, 'Somalia', 'Shillings', 'SOS', 'S', ',', '.', NULL, NULL),
(108, 'South Africa', 'Rand', 'ZAR', 'R', ',', '.', NULL, NULL),
(109, 'South Korea', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(110, 'Spain', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₨', ',', '.', NULL, NULL),
(112, 'Sweden', 'Kronor', 'SEK', 'kr', ',', '.', NULL, NULL),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(114, 'Suriname', 'Dollars', 'SRD', '$', ',', '.', NULL, NULL),
(115, 'Syria', 'Pounds', 'SYP', '£', ',', '.', NULL, NULL),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$', ',', '.', NULL, NULL),
(117, 'Thailand', 'Baht', 'THB', '฿', ',', '.', NULL, NULL),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$', ',', '.', NULL, NULL),
(119, 'Turkey', 'Lira', 'TRY', 'TL', ',', '.', NULL, NULL),
(120, 'Turkey', 'Liras', 'TRL', '£', ',', '.', NULL, NULL),
(121, 'Tuvalu', 'Dollars', 'TVD', '$', ',', '.', NULL, NULL),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴', ',', '.', NULL, NULL),
(123, 'United Kingdom', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(124, 'United States of America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(125, 'Uruguay', 'Pesos', 'UYU', '$U', ',', '.', NULL, NULL),
(126, 'Uzbekistan', 'Sums', 'UZS', 'лв', ',', '.', NULL, NULL),
(127, 'Vatican City', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.', NULL, NULL),
(129, 'Vietnam', 'Dong', 'VND', '₫', ',', '.', NULL, NULL),
(130, 'Yemen', 'Rials', 'YER', '﷼', ',', '.', NULL, NULL),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.', NULL, NULL),
(132, 'Iraq', 'Iraqi dinar', 'IQD', 'د.ع', ',', '.', NULL, NULL),
(133, 'Kenya', 'Kenyan shilling', 'KES', 'KSh', ',', '.', NULL, NULL),
(134, 'Bangladesh', 'Taka', 'BDT', 'TK.', ',', '.', NULL, NULL),
(135, 'Algerie', 'Algerian dinar', 'DZD', 'د.ج', ' ', '.', NULL, NULL),
(136, 'United Arab Emirates', 'United Arab Emirates dirham', 'AED', 'د.إ', ',', '.', NULL, NULL),
(137, 'Uganda', 'Uganda shillings', 'UGX', 'USh', ',', '.', NULL, NULL),
(138, 'Tanzania', 'Tanzanian shilling', 'TZS', 'TSh', ',', '.', NULL, NULL),
(139, 'Angola', 'Kwanza', 'AOA', 'Kz', ',', '.', NULL, NULL),
(140, 'Kuwait', 'Kuwaiti dinar', 'KWD', 'KD', ',', '.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit_limit` decimal(22,2) DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` int(11) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `shipping_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_sale` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_less` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `point` decimal(22,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_walk_in_customer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `contact_id`, `customer_group_id`, `name`, `business_name`, `phone`, `alternative_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `credit_limit`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_sale`, `total_paid`, `total_less`, `total_sale_due`, `total_return`, `total_sale_return_due`, `point`, `status`, `is_walk_in_customer`, `created_at`, `updated_at`) VALUES
(11, 'C-0001', NULL, 'New Customer - Business Location 2', NULL, '0122544555', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, 'Global W', NULL, NULL, NULL, NULL, NULL, '234650.00', '450.00', '0.00', '234200.00', '0.00', '0.00', '0.00', 1, 0, '2022-09-12 12:01:54', '2022-09-13 13:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `customer_credit_limits`
--

CREATE TABLE `customer_credit_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_type` tinyint(4) DEFAULT NULL,
  `credit_limit` decimal(22,2) DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_credit_limits`
--

INSERT INTO `customer_credit_limits` (`id`, `customer_id`, `branch_id`, `created_by_id`, `customer_type`, `credit_limit`, `pay_term`, `pay_term_number`, `created_at`, `updated_at`) VALUES
(10, 11, NULL, 2, NULL, '90000000.00', 1, NULL, '2022-09-12 12:01:54', '2022-09-12 13:26:07'),
(11, 11, 1, 3, NULL, '800000.00', NULL, NULL, '2022-09-13 13:02:09', '2022-09-13 13:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calc_percentage` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_groups`
--

INSERT INTO `customer_groups` (`id`, `group_name`, `calc_percentage`, `created_at`, `updated_at`) VALUES
(7, 'Premium Group', '4.00', NULL, NULL),
(8, 'Bronze Customer', '2.00', NULL, NULL),
(9, 'Silver Group', '1.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=sale;2=sale_payment;3=opening_balance;4=money_receipt;5=supplier_payment',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening_balance',
  `date` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `is_advanced` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'only_for_money_receipt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voucher_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `amount_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'debit/credit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_ledgers`
--

INSERT INTO `customer_ledgers` (`id`, `branch_id`, `customer_id`, `sale_id`, `sale_return_id`, `sale_payment_id`, `customer_payment_id`, `money_receipt_id`, `row_type`, `amount`, `date`, `report_date`, `is_advanced`, `created_at`, `updated_at`, `voucher_type`, `debit`, `credit`, `running_balance`, `amount_type`) VALUES
(42, NULL, 11, NULL, NULL, NULL, NULL, NULL, 1, '0.00', '12-09-2022', '2022-09-12 12:01:54', 0, '2022-09-12 12:01:54', '2022-09-12 13:26:07', '0', '0.00', '0.00', '0.00', 'debit'),
(43, NULL, 11, 62, NULL, NULL, NULL, NULL, 1, '450.00', '12-09-2022', '2022-09-12 12:24:23', 0, '2022-09-12 12:24:23', '2022-09-12 12:24:23', '1', '450.00', '0.00', '0.00', 'debit'),
(44, NULL, 11, NULL, NULL, 61, NULL, NULL, 1, '450.00', '12-09-2022', '2022-09-12 12:24:23', 0, '2022-09-12 12:24:23', '2022-09-12 12:24:23', '3', '0.00', '450.00', '0.00', 'credit'),
(45, NULL, 11, 63, NULL, NULL, NULL, NULL, 1, '57750.00', '12-09-2022', '2022-09-12 13:26:20', 0, '2022-09-12 13:26:20', '2022-09-12 13:26:20', '1', '57750.00', '0.00', '0.00', 'debit'),
(46, NULL, 11, 64, NULL, NULL, NULL, NULL, 1, '58550.00', '13-09-2022', '2022-09-13 07:16:28', 0, '2022-09-13 07:16:28', '2022-09-13 07:16:28', '1', '58550.00', '0.00', '0.00', 'debit'),
(47, NULL, 11, 65, NULL, NULL, NULL, NULL, 1, '58550.00', '13-09-2022', '2022-09-13 07:16:43', 0, '2022-09-13 07:16:43', '2022-09-13 07:16:43', '1', '58550.00', '0.00', '0.00', 'debit'),
(48, NULL, 11, 66, NULL, NULL, NULL, NULL, 1, '58550.00', '13-09-2022', '2022-09-13 07:17:41', 0, '2022-09-13 07:17:41', '2022-09-13 07:17:41', '1', '58550.00', '0.00', '0.00', 'debit'),
(49, NULL, 11, 67, NULL, NULL, NULL, NULL, 1, '800.00', '2022-09-13', '2022-09-13 07:23:00', 0, '2022-09-13 07:23:00', '2022-09-13 07:23:00', '1', '800.00', '0.00', '0.00', 'debit'),
(50, NULL, 11, 68, NULL, NULL, NULL, NULL, 1, '57750.00', '13-09-2022', '2022-09-13 07:27:37', 0, '2022-09-13 07:27:37', '2022-09-13 07:27:37', '1', '57750.00', '0.00', '0.00', 'debit'),
(51, 1, 11, NULL, NULL, NULL, NULL, NULL, 1, '0.00', '12-09-2022', '2022-09-12 12:01:54', 0, '2022-09-13 13:02:10', '2022-09-13 13:02:10', '0', '0.00', '0.00', '0.00', 'debit'),
(52, 1, 11, 69, NULL, NULL, NULL, NULL, 1, '120.00', '13-09-2022', '2022-09-13 13:02:24', 0, '2022-09-13 13:02:24', '2022-09-13 13:02:24', '1', '120.00', '0.00', '0.00', 'debit'),
(53, 1, 11, NULL, NULL, NULL, 1, NULL, 1, '110.00', '13-09-2022', '2022-09-13 13:02:50', 0, '2022-09-13 13:02:50', '2022-09-13 13:02:50', '5', '0.00', '110.00', '0.00', 'credit');

-- --------------------------------------------------------

--
-- Table structure for table `customer_opening_balances`
--

CREATE TABLE `customer_opening_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `is_show_again` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_opening_balances`
--

INSERT INTO `customer_opening_balances` (`id`, `branch_id`, `customer_id`, `amount`, `report_date`, `is_show_again`, `created_by_id`, `created_at`, `updated_at`) VALUES
(12, NULL, 11, '0.00', NULL, 1, 2, '2022-09-12 12:01:54', '2022-09-12 12:01:54'),
(13, 1, 11, '0.00', NULL, 1, 3, '2022-09-13 13:02:09', '2022-09-13 13:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `less_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payments`
--

INSERT INTO `customer_payments` (`id`, `voucher_no`, `reference`, `branch_id`, `customer_id`, `account_id`, `paid_amount`, `less_amount`, `report_date`, `type`, `pay_mode`, `date`, `time`, `month`, `year`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`, `payment_method_id`) VALUES
(1, 'CPV00001', NULL, 1, 11, 192, '110.00', '10.00', '2022-09-13 13:02:50', 1, NULL, '13-09-2022', '07:02:50 pm', 'September', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-13 13:02:50', '2022-09-13 13:02:50', 3);

-- --------------------------------------------------------

--
-- Table structure for table `customer_payment_invoices`
--

CREATE TABLE `customer_payment_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(4) DEFAULT NULL COMMENT '1=sale_payment;2=sale_return_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payment_invoices`
--

INSERT INTO `customer_payment_invoices` (`id`, `customer_payment_id`, `sale_id`, `sale_return_id`, `paid_amount`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, 69, NULL, '110.00', NULL, '2022-09-13 13:02:50', '2022-09-13 13:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` bigint(20) NOT NULL DEFAULT '0',
  `start_at` date DEFAULT NULL,
  `end_at` date DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_type` tinyint(4) NOT NULL DEFAULT '0',
  `discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `price_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `apply_in_customer_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_products`
--

CREATE TABLE `discount_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expanses`
--

CREATE TABLE `expanses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `category_ids` mediumtext COLLATE utf8mb4_unicode_ci,
  `tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expense_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_branch_to_branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expanse_categories`
--

CREATE TABLE `expanse_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expanse_categories`
--

INSERT INTO `expanse_categories` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(41, 'New expense Category', '1', '2022-04-06 19:25:08', '2022-04-06 19:25:08'),
(42, 'Advertisement', '42', '2022-04-06 19:34:12', '2022-04-06 19:34:12'),
(43, 'Test 4', '43', '2022-04-07 04:12:06', '2022-04-07 04:12:06'),
(44, 'Net Bill', '44', '2022-04-08 14:10:04', '2022-04-08 14:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `expanse_payments`
--

CREATE TABLE `expanse_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expanse_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_descriptions`
--

CREATE TABLE `expense_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business` longtext COLLATE utf8mb4_unicode_ci,
  `tax` longtext COLLATE utf8mb4_unicode_ci,
  `product` longtext COLLATE utf8mb4_unicode_ci,
  `sale` longtext COLLATE utf8mb4_unicode_ci,
  `pos` longtext COLLATE utf8mb4_unicode_ci,
  `purchase` longtext COLLATE utf8mb4_unicode_ci,
  `dashboard` longtext COLLATE utf8mb4_unicode_ci,
  `system` longtext COLLATE utf8mb4_unicode_ci,
  `prefix` longtext COLLATE utf8mb4_unicode_ci,
  `send_es_settings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_setting` longtext COLLATE utf8mb4_unicode_ci,
  `sms_setting` longtext COLLATE utf8mb4_unicode_ci,
  `modules` longtext COLLATE utf8mb4_unicode_ci,
  `reward_poing_settings` longtext COLLATE utf8mb4_unicode_ci,
  `mf_settings` text COLLATE utf8mb4_unicode_ci COMMENT 'manufacturing_settings',
  `multi_branches` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `hrm` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `services` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `manufacturing` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `projects` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `essentials` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `e_commerce` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is_activated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `business`, `tax`, `product`, `sale`, `pos`, `purchase`, `dashboard`, `system`, `prefix`, `send_es_settings`, `email_setting`, `sms_setting`, `modules`, `reward_poing_settings`, `mf_settings`, `multi_branches`, `hrm`, `services`, `manufacturing`, `projects`, `essentials`, `e_commerce`, `created_at`, `updated_at`) VALUES
(1, '{\"shop_name\":\"MetaShops\",\"address\":\"Sector 4, Road - 4, House - 17, Uttara, Dhaka, Bangladesh\",\"phone\":\"0170000000\",\"email\":\"genuinepos@genuinepos.com\",\"start_date\":null,\"default_profit\":0,\"currency\":\"TK.\",\"currency_placement\":null,\"date_format\":\"d-m-Y\",\"stock_accounting_method\":\"2\",\"time_format\":\"12\",\"business_logo\":\"631ee0ea63369-.png\",\"timezone\":\"Asia\\/Dhaka\"}', '{\"tax_1_name\":null,\"tax_1_no\":null,\"tax_2_name\":null,\"tax_2_no\":null,\"is_tax_en_purchase_sale\":0}', '{\"product_code_prefix\":\"SD\",\"default_unit_id\":\"null\",\"is_enable_brands\":1,\"is_enable_categories\":1,\"is_enable_sub_categories\":1,\"is_enable_price_tax\":1,\"is_enable_warranty\":1}', '{\"default_sale_discount\":\"0.00\",\"default_tax_id\":\"null\",\"sales_cmsn_agnt\":\"select_form_cmsn_list\",\"default_price_group_id\":\"null\"}', '{\"is_enabled_multiple_pay\":1,\"is_enabled_draft\":1,\"is_enabled_quotation\":1,\"is_enabled_suspend\":1,\"is_enabled_discount\":1,\"is_enabled_order_tax\":1,\"is_show_recent_transactions\":1,\"is_enabled_credit_full_sale\":1,\"is_enabled_hold_invoice\":1}', '{\"is_edit_pro_price\":1,\"is_enable_status\":1,\"is_enable_lot_no\":1}', '{\"view_stock_expiry_alert_for\":\"31\"}', '{\"theme_color\":\"dark-theme\",\"datatable_page_entry\":\"50\"}', '{\"purchase_invoice\":\"PI\",\"sale_invoice\":\"GTC\",\"purchase_return\":\"PRI\",\"stock_transfer\":\"ST\",\"stock_djustment\":\"SA\",\"sale_return\":\"SRI\",\"expenses\":\"ER\",\"supplier_id\":\"S-\",\"customer_id\":\"C-\",\"purchase_payment\":\"PPV\",\"sale_payment\":\"SPV\",\"expanse_payment\":\"EPV\"}', '{\"send_inv_via_email\":0,\"send_notice_via_sms\":0,\"cmr_due_rmdr_via_email\":0,\"cmr_due_rmdr_via_sms\":0}', '[]', '[]', '{\"purchases\":1,\"add_sale\":1,\"pos\":1,\"transfer_stock\":1,\"stock_adjustment\":1,\"expenses\":1,\"accounting\":1,\"contacts\":1,\"hrms\":1,\"requisite\":1,\"manufacturing\":1,\"service\":1}', '{\"enable_cus_point\":0,\"point_display_name\":\"Reward Point\",\"amount_for_unit_rp\":\"10\",\"min_order_total_for_rp\":\"100\",\"max_rp_per_order\":\"50\",\"redeem_amount_per_unit_rp\":\"0.10\",\"min_order_total_for_redeem\":\"500\",\"min_redeem_point\":\"30\",\"max_redeem_point\":\"\"}', '{\"production_ref_prefix\":\"MF\",\"enable_editing_ingredient_qty\":1,\"enable_updating_product_price\":1}', 0, 0, 0, 0, 0, 0, 0, NULL, '2022-10-22 07:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_allowance`
--

CREATE TABLE `hrm_allowance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=fixed;2=percentage',
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `applicable_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_attendances`
--

CREATE TABLE `hrm_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `at_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_note` text COLLATE utf8mb4_unicode_ci,
  `clock_out_note` text COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_ts` timestamp NULL DEFAULT NULL,
  `clock_out_ts` timestamp NULL DEFAULT NULL,
  `at_date_ts` timestamp NULL DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_attendances`
--

INSERT INTO `hrm_attendances` (`id`, `at_date`, `user_id`, `clock_in`, `clock_out`, `work_duration`, `clock_in_note`, `clock_out_note`, `month`, `year`, `clock_in_ts`, `clock_out_ts`, `at_date_ts`, `is_completed`, `created_at`, `updated_at`) VALUES
(1, '22-10-2022', 2, '13:27', NULL, NULL, NULL, NULL, 'October', '2022', '2022-10-22 07:27:00', NULL, '2022-10-21 18:00:00', 0, '2022-10-22 07:27:19', '2022-10-22 07:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_department`
--

CREATE TABLE `hrm_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_department`
--

INSERT INTO `hrm_department` (`id`, `department_name`, `department_id`, `description`, `created_at`, `updated_at`) VALUES
(4, 'Sales Department', 'SD1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_designations`
--

CREATE TABLE `hrm_designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_holidays`
--

CREATE TABLE `hrm_holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_all` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leaves`
--

CREATE TABLE `hrm_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leave_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leavetypes`
--

CREATE TABLE `hrm_leavetypes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_leave_count` int(11) NOT NULL,
  `leave_count_interval` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payrolls`
--

CREATE TABLE `hrm_payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_time` decimal(22,2) NOT NULL DEFAULT '0.00',
  `duration_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_per_unit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_allowance_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_deduction_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_allowances`
--

CREATE TABLE `hrm_payroll_allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allowance_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `allowance_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `allowance_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_deductions`
--

CREATE TABLE `hrm_payroll_deductions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deduction_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` tinyint(4) NOT NULL DEFAULT '1',
  `deduction_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `deduction_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_payments`
--

CREATE TABLE `hrm_payroll_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_shifts`
--

CREATE TABLE `hrm_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_count` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endtime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_shifts`
--

INSERT INTO `hrm_shifts` (`id`, `shift_name`, `start_time`, `late_count`, `endtime`, `created_at`, `updated_at`) VALUES
(4, 'Day Shift', '10:05', NULL, '22:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_layouts`
--

CREATE TABLE `invoice_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout_design` tinyint(4) NOT NULL COMMENT '1=normal_printer;2=pos_printer',
  `show_shop_logo` tinyint(1) NOT NULL DEFAULT '0',
  `header_text` text COLLATE utf8mb4_unicode_ci,
  `is_header_less` tinyint(1) NOT NULL DEFAULT '0',
  `gap_from_top` bigint(20) DEFAULT NULL,
  `show_seller_info` tinyint(1) NOT NULL DEFAULT '0',
  `customer_name` tinyint(1) NOT NULL DEFAULT '1',
  `customer_tax_no` tinyint(1) NOT NULL DEFAULT '0',
  `customer_address` tinyint(1) NOT NULL DEFAULT '0',
  `customer_phone` tinyint(1) NOT NULL DEFAULT '0',
  `sub_heading_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `draft_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challan_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_landmark` tinyint(1) NOT NULL DEFAULT '0',
  `branch_city` tinyint(1) NOT NULL DEFAULT '0',
  `branch_state` tinyint(1) NOT NULL DEFAULT '0',
  `branch_country` tinyint(1) NOT NULL DEFAULT '0',
  `branch_zipcode` tinyint(1) NOT NULL DEFAULT '0',
  `branch_phone` tinyint(1) NOT NULL DEFAULT '0',
  `branch_alternate_number` tinyint(1) NOT NULL DEFAULT '0',
  `branch_email` tinyint(1) NOT NULL DEFAULT '0',
  `product_img` tinyint(1) NOT NULL DEFAULT '0',
  `product_cate` tinyint(1) NOT NULL DEFAULT '0',
  `product_brand` tinyint(1) NOT NULL DEFAULT '0',
  `product_imei` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_type` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_duration` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_discription` tinyint(1) NOT NULL DEFAULT '0',
  `product_discount` tinyint(1) NOT NULL DEFAULT '0',
  `product_tax` tinyint(1) NOT NULL DEFAULT '0',
  `product_price_inc_tax` tinyint(1) NOT NULL DEFAULT '0',
  `product_price_exc_tax` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_notice` text COLLATE utf8mb4_unicode_ci,
  `sale_note` tinyint(1) NOT NULL DEFAULT '0',
  `show_total_in_word` tinyint(1) NOT NULL DEFAULT '0',
  `footer_text` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_layouts`
--

INSERT INTO `invoice_layouts` (`id`, `name`, `layout_design`, `show_shop_logo`, `header_text`, `is_header_less`, `gap_from_top`, `show_seller_info`, `customer_name`, `customer_tax_no`, `customer_address`, `customer_phone`, `sub_heading_1`, `sub_heading_2`, `sub_heading_3`, `invoice_heading`, `quotation_heading`, `draft_heading`, `challan_heading`, `branch_landmark`, `branch_city`, `branch_state`, `branch_country`, `branch_zipcode`, `branch_phone`, `branch_alternate_number`, `branch_email`, `product_img`, `product_cate`, `product_brand`, `product_imei`, `product_w_type`, `product_w_duration`, `product_w_discription`, `product_discount`, `product_tax`, `product_price_inc_tax`, `product_price_exc_tax`, `invoice_notice`, `sale_note`, `show_total_in_word`, `footer_text`, `bank_name`, `bank_branch`, `account_name`, `account_no`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Default layout', 1, 1, NULL, 0, NULL, 1, 1, 0, 1, 1, NULL, NULL, NULL, 'BILL', 'Quotation', 'Draft', 'Challan', 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 'If you need any support, Feel free to contact. phone: 9561646, 088-7165665 Mobile : 01819220726', 0, 1, NULL, NULL, NULL, NULL, NULL, 1, '2021-03-02 12:24:36', '2022-09-13 05:22:36'),
(2, 'Pos Printer Layout', 2, 1, NULL, 0, NULL, 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Invoice', 'Quotation', 'Draft', 'Challan', 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0, 0, 'Invoice Notice', 0, 1, 'Footer Text', 'ff', 'ff', 'ff', 'ff', 0, '2021-03-03 10:20:30', '2022-09-08 04:37:02'),
(3, 'Header Less', 1, 1, NULL, 1, 2, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-03 10:22:42', '2021-05-04 07:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_schemas`
--

CREATE TABLE `invoice_schemas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_digit` tinyint(4) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_schemas`
--

INSERT INTO `invoice_schemas` (`id`, `name`, `format`, `start_from`, `number_of_digit`, `is_default`, `prefix`, `created_at`, `updated_at`) VALUES
(3, 'yyyy', '1', '11', NULL, 0, 'SDC0', '2021-03-02 08:07:36', '2021-06-06 12:05:25'),
(6, 'sss', '1', '00', NULL, 0, 'SD', '2021-03-02 08:56:49', '2021-06-06 12:01:53'),
(9, 'test', '1', NULL, NULL, 1, 'MC', '2021-06-06 12:02:32', '2022-02-02 05:17:16'),
(12, 'TEST-4', '2', '12', NULL, 0, '2021/', '2021-08-16 11:08:29', '2021-08-16 11:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(5, 'default', '{\"uuid\":\"58356425-4270-4984-ad1a-4213c8774492\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:402;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1629956103, 1629956103),
(6, 'default', '{\"uuid\":\"de347da1-c97c-484e-81c1-1a137af8938b\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:405;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1629956205, 1629956205),
(7, 'default', '{\"uuid\":\"ec27010d-80bd-4f3d-9f3c-de4f7802cec7\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:408;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-30 12:56:57.073984\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630306617, 1630306612),
(8, 'default', '{\"uuid\":\"b7a5e735-01b9-462f-a661-72709e8e2218\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:409;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-31 13:43:54.503682\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630395834, 1630395830),
(9, 'default', '{\"uuid\":\"499a5820-b2e7-426b-ac17-20c5883a9a4c\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:410;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-31 15:14:26.839294\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630401266, 1630401261);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_receive` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `loan_reason` text COLLATE utf8mb4_unicode_ci,
  `loan_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_companies`
--

CREATE TABLE `loan_companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `pay_loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_loan_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `get_loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `get_loan_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_pay` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_receive` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pay_loan_payment;2=get_loan_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_distributions`
--

CREATE TABLE `loan_payment_distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pay_loan_payment;2=get_loan_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memos`
--

CREATE TABLE `memos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memo_users`
--

CREATE TABLE `memo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memo_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `is_author` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2020_11_02_100600_create_units_table', 2),
(7, '2020_11_02_100636_create_taxes_table', 2),
(8, '2020_11_03_043450_create_categories_table', 3),
(9, '2020_11_03_050021_create_brands_table', 4),
(11, '2020_11_03_074719_create_product_variants_table', 5),
(12, '2020_11_03_081651_create_combo_products_table', 5),
(13, '2020_11_03_114308_create_product_images_table', 5),
(15, '2020_11_04_121711_create_bulk_variants_table', 7),
(16, '2020_11_04_121816_create_bulk_variant_children_table', 7),
(17, '2020_11_15_053541_create_general_settings_table', 8),
(18, '2020_11_15_082449_create_months_table', 9),
(20, '2020_11_16_091136_create_product_branches_table', 11),
(21, '2020_11_16_091328_create_product_branch_variants_table', 11),
(22, '2020_11_18_062546_create_suppliers_table', 12),
(23, '2020_11_18_104651_create_warehouses_table', 13),
(33, '2020_11_21_121749_create_purchases_table', 16),
(34, '2020_11_21_123246_create_purchase_products_table', 16),
(35, '2020_11_18_104742_create_product_warehouses_table', 17),
(36, '2020_11_18_104801_create_product_warehouse_variants_table', 17),
(47, '2020_12_07_043444_create_purchase_return_products_table', 21),
(60, '2020_12_14_122349_create_banks_table', 26),
(61, '2020_12_15_054045_create_account_types_table', 27),
(62, '2020_12_15_075538_create_accounts_table', 28),
(63, '2020_12_17_051129_create_expanse_categories_table', 29),
(70, '2020_12_17_072305_create_purchase_payments_table', 31),
(72, '2020_12_17_072328_create_expanse_payments_table', 33),
(73, '2020_12_28_084947_create_currencies_table', 34),
(79, '2020_11_23_093916_create_customer_groups_table', 36),
(80, '2020_11_28_081503_create_customers_table', 36),
(83, '2020_11_03_050022_create_warranties_table', 37),
(84, '2020_11_03_074127_create_products_table', 37),
(85, '2020_10_31_075426_create_branches_table', 38),
(86, '2020_12_31_045651_create_payment_methods_table', 38),
(88, '2020_12_31_073536_create_branch_payment_methods_table', 39),
(95, '2020_11_01_074931_create_roles_table', 44),
(96, '2020_11_01_074932_create_role_permissions_table', 44),
(97, '2020_10_31_075427_create_departments_table', 45),
(98, '2020_10_31_075428_create_designations_table', 45),
(100, '2021_02_01_162056_create_hrm_designations_table', 47),
(101, '2021_02_02_104702_create_hrm_department_table', 47),
(102, '2021_02_02_112758_create_hrm_leavetypes_table', 47),
(104, '2021_02_02_164845_create_hrm_allowance_table', 47),
(105, '2021_02_03_113338_create_hrm_leaves_table', 47),
(106, '2021_02_06_104136_create_hrm_shifts_table', 47),
(107, '2021_02_08_143446_create_hrm_attendances_table', 47),
(109, '2021_02_16_130523_create_allowance_employees_table', 48),
(117, '2021_02_17_175850_create_hrm_payrolls_table', 49),
(118, '2021_02_17_180827_create_hrm_payroll_allowances_table', 49),
(119, '2021_02_17_181252_create_hrm_payroll_deductions_table', 49),
(122, '2021_02_18_151938_create_hrm_payroll_payments_table', 50),
(123, '2021_02_27_175944_create_timezones_table', 51),
(125, '2021_03_02_114435_create_invoice_schemas_table', 52),
(126, '2021_03_02_160327_create_invoice_layouts_table', 53),
(128, '2021_03_25_114719_create_money_receipts_table', 54),
(129, '2020_11_28_095207_create_sales_table', 55),
(130, '2021_01_16_114746_create_product_opening_stocks_table', 56),
(131, '2020_11_01_074933_create_admin_and_users_table', 57),
(132, '2020_12_29_085907_create_stock_adjustments_table', 58),
(134, '2020_12_17_083221_create_cash_flows_table', 60),
(135, '2020_12_17_072250_create_sale_payments_table', 61),
(136, '2020_12_29_055123_create_customer_ledgers_table', 62),
(137, '2020_11_28_095232_create_sale_products_table', 63),
(138, '2021_04_14_104001_create_sv_devices_table', 64),
(139, '2021_04_14_104219_create_sv_device_models_table', 65),
(140, '2021_04_14_144159_create_sv_status_table', 65),
(141, '2021_04_15_124601_create_sv_job_sheets_table', 66),
(142, '2021_04_15_135702_create_sv_job_sheets_parts_table', 66),
(143, '2020_12_29_055052_create_supplier_ledgers_table', 67),
(144, '2020_12_07_043342_create_purchase_returns_table', 68),
(145, '2020_12_05_050553_create_sale_returns_table', 69),
(146, '2020_12_05_052157_create_sale_return_products_table', 69),
(147, '2021_01_20_094145_create_cash_registers_table', 70),
(148, '2021_01_20_094227_create_cash_register_transactions_table', 70),
(151, '2020_12_17_055604_create_expanses_table', 71),
(152, '2021_05_23_140809_create_expense_descriptions_table', 71),
(153, '2021_06_03_114200_add_shift_id_to_admin_and_users_table', 72),
(154, '2021_02_02_134638_create_hrm_holidays_table', 73),
(155, '2021_06_03_114704_remove_column_shift_id_from_hrm_attendances_table', 74),
(156, '2021_06_07_141127_create_xyz_table', 75),
(158, '2020_12_12_064712_create_transfer_stock_to_branches_table', 76),
(159, '2020_12_12_065407_create_transfer_stock_to_branch_products_table', 76),
(160, '2020_12_13_060916_create_transfer_stock_to_warehouses_table', 76),
(161, '2020_12_13_060924_create_transfer_stock_to_warehouse_products_table', 76),
(162, '2021_06_15_144538_create_asset_types_table', 77),
(163, '2021_06_15_162732_create_assets_table', 78),
(166, '2021_06_19_011217_create_barcode_settings_table', 81),
(167, '2021_06_19_212930_create_cash_counters_table', 81),
(168, '2021_06_19_213532_add_column_cash_counter_id_from_cash_registers_table', 82),
(169, '2021_06_22_150310_create_price_groups_table', 83),
(170, '2021_06_23_104749_create_price_group_products_table', 84),
(171, '2021_06_26_131932_add_column_branch_id_from_cash_counters_table', 85),
(172, '2020_11_23_093915_create_supplier_products_table', 86),
(173, '2021_07_04_133321_create_admin_and_user_logs_table', 87),
(174, '2021_07_05_143024_create_workspaces_table', 88),
(175, '2021_07_05_144604_create_workspace_attachments_table', 88),
(176, '2021_07_05_150048_create_workspace_users_table', 89),
(177, '2021_07_05_185346_create_workspace_tasks_table', 90),
(178, '2021_07_07_123257_create_memos_table', 91),
(179, '2021_07_07_123333_create_memo_users_table', 91),
(180, '2021_07_07_184937_create_messages_table', 92),
(181, '2021_07_08_163610_create_todos_table', 93),
(183, '2021_07_08_172517_create_todo_users_table', 94),
(185, '2021_07_10_195215_add_column_branch_id_from_warehouses_table', 95),
(186, '2021_07_11_121103_remove_column_warehouse_id_from_sales_table', 96),
(187, '2020_12_29_092109_create_stock_adjustment_products_table', 97),
(188, '2021_08_12_130532_create_addons_table', 98),
(190, '2021_08_19_115939_create_short_menus_table', 99),
(191, '2021_08_19_144935_create_short_menu_users_table', 99),
(192, '2021_08_21_134927_create_pos_short_menus_table', 100),
(193, '2021_08_21_135048_create_pos_short_menu_users_table', 100),
(194, '2021_08_22_181046_create_jobs_table', 101),
(197, '2021_08_28_163833_create_processes_table', 102),
(198, '2021_08_28_174915_create_process_ingredients_table', 102),
(202, '2021_08_31_181734_add_e_commerce_to_addons_table', 104),
(203, '2021_08_30_135333_create_productions_table', 105),
(204, '2021_09_02_103707_create_production_ingredients_table', 105),
(205, '2021_09_02_120021_update_general_setting_table_add_column_send_es_settings', 106),
(206, '2021_09_04_120655_create_loan_companies_table', 107),
(207, '2021_09_04_144845_create_loans_table', 108),
(208, '2021_09_04_160952_add_branch_id_to_loans_table', 109),
(209, '2021_09_05_102218_add_loan_id_to_cash_flows_table', 110),
(210, '2021_09_05_143236_add_columns_to_loans_table', 111),
(211, '2021_09_09_111055_create_supplier_payments_table', 112),
(212, '2021_09_09_111115_create_supplier_payment_invoices_table', 113),
(213, '2021_09_09_165137_add_columns_to_supplier_ledgers_table', 114),
(214, '2021_09_11_105818_create_customer_payments_table', 115),
(215, '2021_09_11_105829_create_customer_payment_invoices_table', 116),
(216, '2021_09_11_113228_add_columns_to_customer_ledgers_table', 116),
(217, '2021_09_11_113553_add_columns_to_sales_table', 117),
(218, '2021_09_11_150200_add_columns_to_cash_flows_table', 117),
(219, '2021_09_11_160156_add_columns_to_purchase_payments_table', 118),
(220, '2021_09_11_160529_add_columns_to_sale_payments_table', 119),
(221, '2021_09_18_120519_add_column_to_suppliers', 120),
(222, '2021_09_19_102537_add_column_to_sale_return_products', 121),
(223, '2021_09_19_171336_add_column_to_supplier_payment_invoices', 122),
(224, '2021_09_20_131524_change_column_purchase_id_made_nullable', 123),
(225, '2021_09_20_172436_add_column_to_purchase_payments', 124),
(227, '2021_09_25_132922_add_column_to_customers', 125),
(229, '2021_09_22_125158_add_column_to_cash_flows', 126),
(230, '2021_10_06_140926_add_column_to_customer_payment_invoices_table', 127),
(231, '2021_10_07_132114_add_column_to_loans_table', 128),
(234, '2021_10_13_173736_add_column_to_cash_flows_table', 130),
(237, '2021_10_14_135021_add_columns_loan_companies_table', 132),
(238, '2021_10_14_141415_add_new_columns_loan_companies_table', 133),
(247, '2021_10_13_154417_create_loan_payments_table', 134),
(248, '2021_10_13_155648_create_loan_payment_distributions_table', 134),
(249, '2021_10_17_130418_add_new_columns_product_branch_variants_table', 134),
(250, '2021_10_17_130830_add_new_columns_products_table', 134),
(251, '2021_10_17_130922_add_new_columns_product_variants_table', 134),
(256, '2021_10_18_120146_add_or_edit_columns_product_branch_variants_table', 135),
(257, '2021_10_18_171227_add_columns_to_purchases_table', 136),
(258, '2021_10_18_181422_add_new_columns_to_purchases_table', 137),
(259, '2021_10_18_181742_create_purchase_order_products_table', 137),
(260, '2021_10_19_102549_add_new_columns_purchase_payments_table', 137),
(261, '2021_10_19_161118_add_new_columns_purchase_order_products_table', 138),
(262, '2021_10_19_174424_add_and_edit_columns_purchases_table', 139),
(263, '2021_10_19_174919_edit_columns_purchase_order_products_table', 140),
(264, '2021_10_20_105414_edit_columns_purchase_products_table', 141),
(265, '2021_10_20_134119_edit_columns_purchases_table', 142),
(266, '2021_10_21_134631_edit_columns_products_table', 143),
(267, '2021_10_21_134714_edit_columns_product_variants_table', 143),
(268, '2021_10_25_123637_add_columns_purchases_table', 144),
(269, '2021_10_27_105018_add_columns_products_table', 145),
(270, '2021_11_09_110242_add_new_cols_product_branches_table', 146),
(271, '2021_11_09_110304_add_new_cols_product_branch_variants_table', 146),
(272, '2021_11_10_113023_remove_columns_customers_table', 147),
(273, '2021_11_10_113047_remove_columns_suppliers_table', 147),
(274, '2021_11_10_114610_add_column_customers_table', 147),
(275, '2021_11_10_121208_remove_column_form_general_settings_table', 148),
(276, '2021_11_11_115245_remove_columns_form_products_table', 149),
(277, '2021_11_11_115302_remove_columns_form_product_variants_table', 149),
(278, '2021_11_11_182630_add_columns_form_product_warehouses_table', 150),
(279, '2021_11_11_182652_add_columns_form_product_warehouse_variants_table', 150),
(280, '2021_11_11_182928_add_columns_form_product_branches_table', 150),
(281, '2021_11_11_183022_add_columns_form_product_branch_variants_table', 150),
(282, '2021_11_14_110149_add_columns_to_product_branches_table', 151),
(283, '2021_11_14_110207_add_columns_to_product_branch_variants_table', 151),
(284, '2021_11_14_110231_add_columns_to_product_warehouses_table', 152),
(285, '2021_11_14_110252_add_columns_to_product_warehouse_variants_table', 152),
(286, '2021_11_14_133556_add_columns_to_productions_table', 153),
(287, '2021_11_14_135251_add_columns_to_production_ingredients_table', 154),
(288, '2021_11_14_135516_add_column_to_productions_table', 154),
(289, '2021_11_14_143142_add_new_column_to_productions_table', 155),
(290, '2021_11_15_112700_add_a_new_column_to_productions_table', 156),
(291, '2021_11_15_123637_edit_column_to_production_ingredients_table', 156),
(292, '2021_11_15_132518_edit_production_id_forign_key_from_production_ingredients_table', 157),
(293, '2021_11_15_133354_add_a_new_column_called_time_to_productions_table', 158),
(294, '2021_11_15_164602_add_2_columns_to_productions_table', 159),
(295, '2021_11_20_111115_edit_column_called_alert_quantity_form_products_table', 160),
(296, '2021_12_26_161252_add_new_column_to_purchase_products_table', 161),
(297, '2021_12_26_162232_add_new_column_to_purchase_order_products_table', 162),
(298, '2022_01_03_134629_add_column_to_role_permissions_table', 163),
(299, '2022_01_03_185118_drop_some_columns_to_role_permissions_table', 164),
(300, '2022_01_04_142457_drop_a_column_to_role_permissions_table', 165),
(301, '2022_01_04_172855_add_new_column_to_role_permissions_table', 166),
(302, '2022_01_04_182835_add_a_new_column_to_role_permissions_table', 167),
(303, '2022_01_04_183526_remove_2_columns_to_role_permissions_table', 168),
(305, '2022_01_05_131102_drop_column_from_sale_payments_table', 169),
(307, '2022_01_05_132959_drop_card_types_table', 170),
(308, '2022_01_05_140526_create_payment_methods_table', 171),
(311, '2022_01_06_171103_drop_column_from_accounts_table', 172),
(312, '2022_01_06_172058_add_more_one_column_from_sale_payments_table', 172),
(313, '2022_01_06_172439_drop_account_types_table', 173),
(314, '2022_01_08_114250_add_more_one_column_to_accounts_table', 174),
(315, '2022_01_17_163420_create_purchase_sale_product_chains_table', 175),
(316, '2022_01_17_163434_add_one_new_column_to_purchase_products_table', 175),
(317, '2022_01_18_162241_edit_money_receipts_table_table', 176),
(318, '2022_01_18_163956_again_edit_money_receipts_table_table', 177),
(319, '2022_01_18_180950_add_some_new_cols_purchase_products_table_table', 178),
(320, '2022_01_19_130812_edit_col_from_purchase_products_table_table', 179),
(321, '2022_01_19_175558_drop_col_from_productions_table_table', 180),
(322, '2022_01_19_175612_add_col_to_productions_table_table', 180),
(323, '2022_01_20_134657_create_test_table', 181),
(324, '2022_01_22_111756_add_branch_id_col_to_purchase_products_table_table', 182),
(325, '2022_01_24_163101_add_col_to_customer_payments_table_table', 183),
(326, '2022_02_01_114532_add_col_expanses_table', 184),
(327, '2022_01_08_130349_change_one_column_to_accounts_table', 185),
(328, '2022_01_08_160229_add_branch_id_column_to_accounts_table', 185),
(329, '2022_01_08_181231_create_account_ledgers_table', 185),
(330, '2022_01_09_123823_create_branch_payment_methods_table', 185),
(331, '2022_01_09_175422_add_new_column_to_expanses_table', 185),
(332, '2022_01_10_144628_add_new_column_to_expanse_payments_table', 185),
(333, '2022_01_11_125944_change_column_to_expanses_table', 185),
(334, '2022_01_11_135029_add_new_columns_to_customer_ledgers_table', 185),
(335, '2022_01_11_161404_add_a_columns_to_customer_ledgers_table', 185),
(336, '2022_01_11_173020_add_new_columns_to_account_ledgers_table', 185),
(337, '2022_01_12_144749_add_new_columns_to_sales_table', 185),
(338, '2022_01_12_175957_add_new_columns_to_sales_return_table', 185),
(339, '2022_01_13_110918_add_new_columns_to_supplier_ledgers_table', 185),
(340, '2022_01_13_172830_add_new_columns_to_purchase_payments_table', 185),
(341, '2022_01_15_112237_add_new_column_to_purchases_table', 185),
(342, '2022_01_15_180030_add_new_column_to_purchase_returns_table', 185),
(343, '2022_01_24_182006_modify_account_ledgers_table_table', 185),
(344, '2022_01_24_182802_create_account_branches_table', 185),
(345, '2022_01_29_185811_add_col_to_supplier_payments_table_table', 185),
(346, '2022_01_30_171011_create_stock_adjustment_recovers_table', 185),
(347, '2022_01_30_171625_add_col_to_stock_adjustments__table_table', 185),
(348, '2022_01_30_171656_add_col_to_account_ledgers_table_table', 185),
(349, '2022_01_31_112030_add_col_to_productions_table', 185),
(350, '2022_01_31_121809_add_col_to_processes_table', 185),
(351, '2022_01_31_143505_add_col_hrm_payroll_payments_table', 185),
(352, '2022_01_31_182416_add_col_loans_table', 185),
(353, '2022_02_01_175057_add_col_loan_payments_table', 185),
(354, '2022_02_03_121955_modify_cash_registers_table', 185),
(355, '2022_02_03_130619_add_col_to_cash_registers_table', 185),
(356, '2022_02_07_135315_create_contras_table', 185),
(357, '2022_02_07_140004_add_cols_to_account_ledgers_table', 185),
(358, '2022_02_08_173932_edit_col_from_admin_and_users_table', 185),
(359, '2022_02_09_115634_modify_to_cash_registers_table', 185),
(360, '2022_02_09_120105_drop_col_to_cash_register_transactions_table', 185),
(361, '2022_02_12_123713_add_col_account_type_to_accounts_table', 185),
(362, '2022_02_19_110059_edit_col_to_purchase_products_table', 186),
(363, '2022_02_20_141505_add_cols_to_addons_table', 187),
(364, '2022_02_22_110317_create_transfer_stock_branch_to_branches_table', 188),
(365, '2022_02_22_111533_create_transfer_stock_branch_to_branch_products_table', 188),
(366, '2022_02_22_175850_add_col_to_expanses_table', 188),
(367, '2022_02_23_182726_add_col_to_transfer_stock_branch_to_branches_table', 189),
(368, '2022_02_26_181948_drop_forign_key_from_transfer_stock_branch_to_branches_table', 190),
(369, '2022_02_26_182009_add_forign_key_to_transfer_stock_branch_to_branches_table', 190),
(370, '2022_03_04_230407_add_col_purchase_return_products_table', 191),
(371, '2022_03_07_174734_add_new_col_categories_table', 192),
(372, '2022_03_14_151822_create_discounts_table', 193),
(373, '2022_03_14_155846_create_discount_products_table', 193),
(374, '2022_03_28_114544_add_new_cols_sale_return_products_table', 194),
(375, '2022_03_29_135913_add_new_cols_sale_returns_table', 195),
(376, '2022_03_29_142345_add_new_forign_key_sale_payments_table', 196),
(377, '2022_03_29_152249_add_new_cols_purchase_products_table', 197),
(378, '2022_03_29_165438_add_new_col_sale_returns_table', 198),
(379, '2022_03_29_165552_edit_col_sale_returns_table', 199),
(380, '2022_03_29_171246_edit_cols_sale_return_products_table', 200),
(381, '2022_03_30_010035_add_col_customer_payment_invoices_table', 201),
(382, '2022_04_04_161211_create_user_activity_logs_table', 202),
(383, '2022_04_12_113944_modify_col_to_payment_methods_table', 203),
(384, '2022_04_12_123145_create_payment_method_settings_table', 204),
(385, '2022_04_13_125214_edit_col_to_suppliers_table', 205),
(386, '2022_04_13_125236_edit_col_to_customers_table', 205),
(387, '2022_04_13_125700_edit_cols_to_sales_table', 206),
(388, '2022_04_19_170709_add_col_to_supplier_table', 207),
(389, '2022_04_19_170900_add_col_to_customer_table', 207),
(390, '2022_04_21_144253_add_cols_to_sales_table', 208),
(391, '2022_04_26_104633_drop_cols_from_sales_table', 209),
(392, '2022_04_26_104634_add_new_cols_to_sales_table', 209),
(393, '2022_04_27_120743_add_new_col_to_purchase_payments_table', 210),
(394, '2022_04_27_163718_add_new_col_to_sale_payments_table', 211),
(395, '2022_05_09_161406_create_warehouse_branches_table', 212),
(396, '2022_05_09_164152_add_new_cols_to_sale_products_table', 212),
(397, '2022_05_16_182220_delete_forign_key_from_products_table', 213),
(398, '2022_05_16_182303_add_new_forign_key_to_products_table', 213),
(399, '2022_05_17_111918_drop_some_foreign_key_from_products_table', 213),
(400, '2022_05_17_112007_add_some_foreign_key_to_products_table', 213),
(401, '2022_05_19_184344_add_new_cols_to_purchase_products_table', 213),
(402, '2022_06_19_135600_edit_col_to_product_branches_table', 214),
(403, '2022_07_04_120025_add_new_col_to_supplier_payments_table', 215),
(404, '2022_07_04_190413_create_purchase_order_product_receives_table', 216),
(405, '2022_07_06_165045_add_new_col_to_suppliers_table', 217),
(406, '2022_07_06_165148_add_a_new_col_to_supplier_payments_table', 218),
(407, '2022_07_06_181810_add_new_col_to_customers_table', 219),
(408, '2022_07_06_181827_add_new_col_to_customer_payments_table', 219),
(409, '2022_07_17_132824_add_new_col_to_customer_ledgers_table', 220),
(410, '2022_07_17_133317_add_new_col_to_supplier_ledgers_table', 220),
(411, '2022_08_29_173720_add_new_col_product_branches_table', 221),
(412, '2022_08_31_144821_create_customer_opening_balances_table', 222),
(413, '2022_08_31_175314_create_customer_credit_limits_table', 223),
(414, '2022_09_02_111250_add_new_col_customer_opening_balances_table', 224),
(415, '2022_09_03_113342_create_supplier_opening_balances_table', 225);

-- --------------------------------------------------------

--
-- Table structure for table `money_receipts`
--

CREATE TABLE `money_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,2) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `is_customer_name` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `receiver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_date` tinyint(1) NOT NULL DEFAULT '0',
  `is_header_less` tinyint(1) NOT NULL DEFAULT '0',
  `gap_from_top` bigint(20) DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`id`, `month`, `created_at`, `updated_at`) VALUES
(1, 'Januaray', NULL, NULL),
(2, 'February', NULL, NULL),
(3, 'March', NULL, NULL),
(4, 'April', NULL, NULL),
(5, 'May', NULL, NULL),
(6, 'June', NULL, NULL),
(7, 'July', NULL, NULL),
(8, 'August', NULL, NULL),
(9, 'September', NULL, NULL),
(10, 'October', NULL, NULL),
(11, 'November', NULL, NULL),
(12, 'December', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('superadmin@gamil.com', '$2y$10$lHAmNtk5ndgUBbE67mnVBOiQMKSzXdi5t2eBQ0WhjhBKRKqX2MLta', '2021-05-25 08:27:05'),
('koalasoftsolution@gmail.com', '$2y$10$JCSHpLLTVM9bjL/.4A76J.p8zQXknzBwuXKDoQUMKCiauh2zHGZ.i', '2022-04-24 05:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `is_fixed`, `created_at`, `updated_at`) VALUES
(3, 'Cash', 1, NULL, '2022-01-06 08:11:04'),
(4, 'Debit-Card', 1, NULL, NULL),
(5, 'Credit-Card', 1, NULL, NULL),
(7, 'Bank-Transfer', 1, NULL, NULL),
(8, 'Cheque', 1, NULL, NULL),
(9, 'American Express', 0, NULL, NULL),
(10, 'Bkash', 0, NULL, NULL),
(11, 'Rocket', 0, NULL, NULL),
(12, 'Nagad', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_settings`
--

CREATE TABLE `payment_method_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method_settings`
--

INSERT INTO `payment_method_settings` (`id`, `payment_method_id`, `branch_id`, `account_id`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(2, 4, NULL, 30, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(3, 5, NULL, NULL, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(4, 7, NULL, 30, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(5, 8, NULL, 30, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(6, 9, NULL, NULL, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(7, 10, NULL, 30, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(8, 11, NULL, NULL, '2022-04-21 11:05:38', '2022-04-21 11:05:38'),
(9, 12, NULL, 30, '2022-04-21 11:05:38', '2022-04-21 11:05:38');

-- --------------------------------------------------------

--
-- Table structure for table `pos_short_menus`
--

CREATE TABLE `pos_short_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pos_short_menus`
--

INSERT INTO `pos_short_menus` (`id`, `url`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'product.categories.index', 'Categories', 'fas fa-th-large', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(2, 'product.subcategories.index', 'SubCategories', 'fas fa-code-branch', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(3, 'product.brands.index', 'Brands', 'fas fa-band-aid', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(4, 'products.all.product', 'Product List', 'fas fa-sitemap', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(5, 'products.add.view', 'Add Product', 'fas fa-plus-circle', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(6, 'product.variants.index', 'Variants', 'fas fa-align-center', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(7, 'product.import.create', 'Import Products', 'fas fa-file-import', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(8, 'product.selling.price.groups.index', 'Price Group', 'fas fa-layer-group', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(9, 'barcode.index', 'G.Barcode', 'fas fa-barcode', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(10, 'product.warranties.index', 'Warranties ', 'fas fa-shield-alt', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(11, 'contacts.supplier.index', 'Suppliers', 'fas fa-address-card', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(12, 'contacts.suppliers.import.create', 'Import Suppliers', 'fas fa-file-import', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(13, 'contacts.customer.index', 'Customers', 'far fa-address-card', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(14, 'contacts.customers.import.create', 'Import Customers', 'fas fa-file-upload', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(15, 'purchases.create', 'Add Purchase', 'fas fa-shopping-cart', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(16, 'purchases.index_v2', 'Purchase List', 'fas fa-list', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(17, 'purchases.returns.index', 'Purchase Return', 'fas fa-undo', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(18, 'sales.create', 'Add Sale', 'fas fa-cart-plus', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(19, 'sales.index2', 'Add Sale List', 'fas fa-tasks', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(20, 'sales.pos.create', 'POS', 'fas fa-cash-register', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(21, 'sales.pos.list', 'POS List', 'fas fa-tasks', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(22, 'sales.drafts', 'Draft List', 'fas fa-drafting-compass', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(23, 'sales.quotations', 'Quotation List', 'fas fa-quote-right', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(24, 'sales.returns.index', 'Sale Returns', 'fas fa-undo', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(25, 'sales.shipments', 'Shipments', 'fas fa-shipping-fast', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(26, 'expanses.create', 'Add Expense', 'fas fa-plus-square', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(27, 'expanses.index', 'Expense List', 'far fa-list-alt', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(28, 'expanses.categories.index', 'Expense Categories Categories', 'fas fa-cubes', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(29, 'users.create', 'Add User', 'fas fa-user-plus', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(30, 'users.index', 'User List', 'fas fa-list-ol', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(31, 'users.role.create', 'Add Role', 'fas fa-plus-circle', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(32, 'users.role.index', 'Role List', 'fas fa-th-list', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(33, 'accounting.banks.index', 'Bank', 'fas fa-university', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(34, 'accounting.types.index', 'Account Types', 'fas fa-th', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(35, 'accounting.accounts.index', 'Accounts', 'fas fa-th', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(36, 'accounting.assets.index', 'Assets', 'fas fa-luggage-cart', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(37, 'accounting.balance.sheet', 'Balance Sheet', 'fas fa-balance-scale', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(38, 'accounting.trial.balance', 'Trial Balance', 'fas fa-balance-scale-right', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(39, 'accounting.cash.flow', 'Cash Flow', 'fas fa-money-bill-wave', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(40, 'settings.general.index', 'General Settings', 'fas fa-cogs', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(41, 'settings.taxes.index', 'Taxes', 'fas fa-percentage', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(42, 'invoices.schemas.index', 'Invoice Schemas', 'fas fa-file-invoice-dollar', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(43, 'invoices.layouts.index', 'Invoice Layouts', 'fas fa-file-invoice', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(44, 'settings.barcode.index', 'Barcode Settings', 'fas fa-barcode', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(45, 'settings.cash.counter.index', 'Cash Counter', 'fas fa-store', '2021-08-21 09:41:00', '2021-08-21 09:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `pos_short_menu_users`
--

CREATE TABLE `pos_short_menu_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pos_short_menu_users`
--

INSERT INTO `pos_short_menu_users` (`id`, `short_menu_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(110, 5, 2, 0, '2021-09-04 08:47:13', '2022-04-07 08:08:04'),
(111, 26, 2, 0, '2022-04-07 08:08:01', '2022-04-07 08:08:04'),
(112, 21, 2, 0, '2022-04-07 08:08:01', '2022-04-07 08:08:04'),
(113, 16, 2, 0, '2022-04-07 08:08:02', '2022-04-07 08:08:04'),
(114, 11, 2, 0, '2022-04-07 08:08:02', '2022-04-07 08:08:04'),
(115, 8, 2, 0, '2022-04-07 08:08:04', '2022-04-07 08:08:04'),
(116, 3, 2, 0, '2022-04-07 08:08:04', '2022-04-07 08:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `price_groups`
--

CREATE TABLE `price_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_groups`
--

INSERT INTO `price_groups` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(10, 'whole sale', NULL, 'Active', NULL, NULL),
(11, 'Retail', NULL, 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `price_group_products`
--

CREATE TABLE `price_group_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `wastage_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_output_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `process_instruction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `process_ingredients`
--

CREATE TABLE `process_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `process_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `final_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(8,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT NULL,
  `parameter_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wasted_quantity` decimal(22,2) DEFAULT NULL,
  `total_final_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `x_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `production_cost` decimal(22,2) DEFAULT NULL,
  `total_cost` decimal(22,2) DEFAULT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT '0',
  `is_last_entry` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_price` tinyint(1) NOT NULL DEFAULT '0',
  `production_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_ingredients`
--

CREATE TABLE `production_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parameter_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `input_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `final_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=general,2=combo,3=digital',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `warranty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `product_cost_with_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `product_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `offer_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_manage_stock` tinyint(1) NOT NULL DEFAULT '1',
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `combo_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `alert_quantity` bigint(20) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_combo` tinyint(1) NOT NULL DEFAULT '0',
  `is_variant` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_in_ecom` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_emi_on_pos` tinyint(1) NOT NULL DEFAULT '0',
  `is_for_sale` tinyint(1) NOT NULL DEFAULT '1',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `expire_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_details` text COLLATE utf8mb4_unicode_ci,
  `is_purchased` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `barcode_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_condition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `number_of_sale` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transfered` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `custom_field_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `type`, `name`, `product_code`, `category_id`, `parent_category_id`, `brand_id`, `unit_id`, `tax_id`, `tax_type`, `warranty_id`, `product_cost`, `product_cost_with_tax`, `profit`, `product_price`, `offer_price`, `is_manage_stock`, `quantity`, `combo_price`, `alert_quantity`, `is_featured`, `is_combo`, `is_variant`, `is_show_in_ecom`, `is_show_emi_on_pos`, `is_for_sale`, `attachment`, `thumbnail_photo`, `expire_date`, `product_details`, `is_purchased`, `barcode_type`, `weight`, `product_condition`, `status`, `number_of_sale`, `total_transfered`, `total_adjusted`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `created_at`, `updated_at`) VALUES
(378, 1, 'Samsung S22 FE', 'SD7134413', 113, 116, 35, 3, 1, 1, 19, '44000.00', '46200.00', '25.00', '55000.00', '0.00', 1, '8996.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '5.00', '0.00', '0.00', NULL, NULL, NULL, '2022-09-06 09:40:36', '2022-09-13 07:27:37'),
(379, 1, 'Test Product', 'SD7439483', 122, NULL, 35, 3, NULL, 1, 19, '300.00', '300.00', '16.67', '350.00', '0.00', 1, '17992.00', '0.00', 0, 0, 0, 1, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '9.00', '0.00', '0.00', NULL, NULL, NULL, '2022-09-12 07:27:08', '2022-09-13 10:54:56'),
(380, 1, 'BL2', 'SD8349452', NULL, NULL, NULL, 3, NULL, 1, NULL, '100.00', '100.00', '20.00', '120.00', '0.00', 1, '9.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-09-13 12:41:15', '2022-09-13 13:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_branches`
--

CREATE TABLE `product_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `total_sale` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchased` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transferred` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_opening_stock` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branches`
--

INSERT INTO `product_branches` (`id`, `branch_id`, `product_id`, `product_quantity`, `status`, `total_sale`, `total_purchased`, `total_adjusted`, `total_transferred`, `total_received`, `total_opening_stock`, `total_sale_return`, `total_purchase_return`, `created_at`, `updated_at`) VALUES
(301, NULL, 378, '8996.00', 1, '5.00', '1.00', '0.00', '0.00', '0.00', '9000.00', '0.00', '0.00', '2022-09-06 09:40:36', '2022-09-13 07:27:37'),
(302, NULL, 379, '17992.00', 1, '9.00', '1.00', '0.00', '0.00', '0.00', '18000.00', '0.00', '0.00', '2022-09-12 07:27:08', '2022-09-13 10:54:56'),
(303, 1, 380, '0.00', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2022-09-13 12:41:16', '2022-09-21 06:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_branch_variants`
--

CREATE TABLE `product_branch_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_quantity` decimal(22,2) DEFAULT '0.00',
  `total_sale` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchased` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transferred` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_opening_stock` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branch_variants`
--

INSERT INTO `product_branch_variants` (`id`, `product_branch_id`, `product_id`, `product_variant_id`, `variant_quantity`, `total_sale`, `total_purchased`, `total_adjusted`, `total_transferred`, `total_received`, `total_opening_stock`, `total_sale_return`, `total_purchase_return`, `created_at`, `updated_at`) VALUES
(1, 302, 379, 1, '8996.00', '5.00', '1.00', '0.00', '0.00', '0.00', '9000.00', '0.00', '0.00', '2022-09-12 07:27:08', '2022-09-13 10:54:56'),
(2, 302, 379, 2, '8996.00', '4.00', '0.00', '0.00', '0.00', '0.00', '9000.00', '0.00', '0.00', '2022-09-12 07:27:08', '2022-09-13 10:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`, `updated_at`) VALUES
(7, NULL, '5fb20b4de8c7c.jpg', '2020-11-15 23:17:02', '2020-11-15 23:17:02'),
(8, NULL, '5fb20b4e6ece3.png', '2020-11-15 23:17:02', '2020-11-15 23:17:02'),
(9, NULL, '5fb20b56cf6c4.jpg', '2020-11-15 23:17:10', '2020-11-15 23:17:10'),
(10, NULL, '5fb20b56da651.png', '2020-11-15 23:17:10', '2020-11-15 23:17:10'),
(11, NULL, '5fb20b9d217d1.jpg', '2020-11-15 23:18:21', '2020-11-15 23:18:21'),
(12, NULL, '5fb20b9d316c5.png', '2020-11-15 23:18:21', '2020-11-15 23:18:21'),
(33, NULL, '5fb224330c206.jpg', '2020-11-16 01:03:15', '2020-11-16 01:03:15'),
(34, NULL, '5fb224331fd5d.png', '2020-11-16 01:03:15', '2020-11-16 01:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_opening_stocks`
--

CREATE TABLE `product_opening_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_opening_stocks`
--

INSERT INTO `product_opening_stocks` (`id`, `branch_id`, `warehouse_id`, `product_id`, `product_variant_id`, `unit_cost_inc_tax`, `quantity`, `subtotal`, `lot_no`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 379, 1, '300.00', '9000.00', '2700000.00', NULL, '2022-09-13 07:15:55', '2022-09-13 07:15:55'),
(2, NULL, NULL, 379, 2, '300.00', '9000.00', '2700000.00', NULL, '2022-09-13 07:15:55', '2022-09-13 07:15:55'),
(3, NULL, NULL, 378, NULL, '46200.00', '9000.00', '415800000.00', NULL, '2022-09-13 07:16:05', '2022-09-13 07:16:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `number_of_sale` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transfered` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_cost` decimal(22,2) NOT NULL,
  `variant_cost_with_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_profit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_price` decimal(22,2) NOT NULL,
  `variant_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_purchased` tinyint(1) NOT NULL DEFAULT '0',
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `variant_code`, `variant_quantity`, `number_of_sale`, `total_transfered`, `total_adjusted`, `variant_cost`, `variant_cost_with_tax`, `variant_profit`, `variant_price`, `variant_image`, `is_purchased`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 379, '8GB', 'SD7439483-2', '8996.00', '5.00', '0.00', '0.00', '300.00', '300.00', '16.67', '450.00', NULL, 1, 0, '2022-09-12 07:27:08', '2022-09-13 10:54:55'),
(2, 379, '4GB', 'SD7439483-1', '8996.00', '4.00', '0.00', '0.00', '300.00', '300.00', '16.67', '350.00', NULL, 0, 0, '2022-09-12 07:27:08', '2022-09-13 10:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouses`
--

CREATE TABLE `product_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT '0.00',
  `total_purchased` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transferred` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_warehouses`
--

INSERT INTO `product_warehouses` (`id`, `warehouse_id`, `product_id`, `product_quantity`, `total_purchased`, `total_adjusted`, `total_transferred`, `total_received`, `total_sale_return`, `total_purchase_return`, `created_at`, `updated_at`) VALUES
(1, 3, 380, '9.00', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2022-09-13 12:42:33', '2022-09-13 13:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouse_variants`
--

CREATE TABLE `product_warehouse_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_quantity` decimal(22,2) DEFAULT '0.00',
  `total_purchased` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_transferred` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` bigint(20) NOT NULL,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `purchase_tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_status` tinyint(4) NOT NULL DEFAULT '1',
  `is_purchased` tinyint(1) NOT NULL DEFAULT '1',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_last_created` tinyint(1) NOT NULL DEFAULT '0',
  `is_return_available` tinyint(1) NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_receiving_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'This field only for order, which numeric status = 3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_account_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `invoice_id`, `warehouse_id`, `branch_id`, `supplier_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount`, `order_discount_type`, `order_discount_amount`, `shipment_details`, `shipment_charge`, `purchase_note`, `purchase_tax_id`, `purchase_tax_percent`, `purchase_tax_amount`, `total_purchase_amount`, `paid`, `due`, `purchase_return_amount`, `purchase_return_due`, `payment_note`, `admin_id`, `purchase_status`, `is_purchased`, `date`, `delivery_date`, `time`, `report_date`, `month`, `year`, `is_last_created`, `is_return_available`, `attachment`, `po_qty`, `po_pending_qty`, `po_received_qty`, `po_receiving_status`, `created_at`, `updated_at`, `purchase_account_id`) VALUES
(55, 'PI00001', NULL, NULL, 92, NULL, NULL, 1, '46200.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '46200.00', '0.00', '46200.00', '0.00', '0.00', NULL, 2, 1, 1, '12-09-2022', NULL, '06:02:12 pm', '2022-09-12 12:02:12', 'September', '2022', 0, 0, NULL, '0.00', '0.00', '0.00', NULL, '2022-09-12 12:02:12', '2022-09-12 12:23:57', 36),
(56, 'PI00056', NULL, NULL, 92, NULL, NULL, 1, '300.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '300.00', '0.00', '300.00', '0.00', '0.00', NULL, 2, 1, 1, '12-09-2022', NULL, '06:23:57 pm', '2022-09-12 12:23:57', 'September', '2022', 1, 0, NULL, '0.00', '0.00', '0.00', NULL, '2022-09-12 12:23:57', '2022-09-12 12:23:57', 36),
(57, 'PI00057', 3, 1, 92, NULL, NULL, 1, '1000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1000.00', '300.00', '700.00', '0.00', '0.00', NULL, 3, 1, 1, '13-09-2022', NULL, '06:42:33 pm', '2022-09-13 12:42:33', 'September', '2022', 1, 0, NULL, '0.00', '0.00', '0.00', NULL, '2022-09-13 12:42:33', '2022-09-13 13:20:06', 193);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_products`
--

CREATE TABLE `purchase_order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pending_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_with_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'Without_tax',
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'inc_tax',
  `ordered_unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'inc_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_product_receives`
--

CREATE TABLE `purchase_order_product_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_product_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_challan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot_number` bigint(20) UNSIGNED DEFAULT NULL,
  `received_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_return_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'only_for_supplier_return_payments',
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_on` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=purchase_invoice_due;2=supplier_due',
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=purchase_due;2=return_due',
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_advanced` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `branch_id`, `invoice_id`, `purchase_id`, `supplier_return_id`, `supplier_id`, `supplier_payment_id`, `account_id`, `pay_mode`, `payment_method_id`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `admin_id`, `note`, `attachment`, `created_at`, `updated_at`, `is_advanced`) VALUES
(1, 1, 'PPV00001', 57, NULL, NULL, 1, 192, NULL, 3, '300.00', 1, 1, NULL, '13-09-2022', NULL, 'September', '2022', '2022-09-13 13:20:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2022-09-13 13:20:06', '2022-09-13 13:20:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'This column for track branch wise FIFO/LIFO method.',
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_with_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'Without_tax',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'With_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `lot_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `product_order_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'when product add from purchase_order_products table',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `left_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_branch_to_branch_product_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_products`
--

INSERT INTO `purchase_products` (`id`, `branch_id`, `purchase_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_cost`, `unit_discount`, `unit_cost_with_discount`, `subtotal`, `unit_tax_percent`, `unit_tax`, `net_unit_cost`, `line_total`, `profit_margin`, `selling_price`, `description`, `is_received`, `lot_no`, `delete_in_update`, `product_order_product_id`, `created_at`, `updated_at`, `left_qty`, `production_id`, `opening_stock_id`, `sale_return_product_id`, `transfer_branch_to_branch_product_id`) VALUES
(978, NULL, 55, 378, NULL, '1.00', 'Piece', '44000.00', '0.00', '44000.00', '44000.00', '5.00', '2200.00', '46200.00', '46200.00', '25.00', '55000.00', NULL, 0, NULL, 0, NULL, '2022-09-12 12:02:12', '2022-09-12 13:26:20', '0.00', NULL, NULL, NULL, NULL),
(979, NULL, 56, 379, 1, '1.00', 'Piece', '300.00', '0.00', '300.00', '300.00', '0.00', '0.00', '300.00', '300.00', '16.67', '450.00', NULL, 0, NULL, 0, NULL, '2022-09-12 12:23:57', '2022-09-12 12:24:24', '0.00', NULL, NULL, NULL, NULL),
(980, NULL, NULL, 379, 1, '9000.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '300.00', '2700000.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2022-09-13 10:54:55', '2022-09-13 10:54:55', '8996.00', NULL, 1, NULL, NULL),
(981, NULL, NULL, 379, 2, '9000.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '300.00', '2700000.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2022-09-13 10:54:56', '2022-09-13 10:54:56', '8996.00', NULL, 2, NULL, NULL),
(982, NULL, NULL, 378, NULL, '9000.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '46200.00', '415800000.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2022-09-13 07:16:05', '2022-09-13 07:27:37', '8996.00', NULL, 3, NULL, NULL),
(983, 1, 57, 380, NULL, '10.00', 'Piece', '100.00', '0.00', '100.00', '1000.00', '0.00', '0.00', '100.00', '1000.00', '20.00', '120.00', NULL, 0, NULL, 0, NULL, '2022-09-13 12:42:33', '2022-09-13 13:02:24', '9.00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_type` tinyint(4) DEFAULT NULL COMMENT '1=purchase_invoice_return;2=supplier_purchase_return',
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_due_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_return_account_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_products`
--

CREATE TABLE `purchase_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'this_field_only_for_purchase_invoice_return.',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost` decimal(8,2) NOT NULL DEFAULT '0.00',
  `return_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_sale_product_chains`
--

CREATE TABLE `purchase_sale_product_chains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sold_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_sale_product_chains`
--

INSERT INTO `purchase_sale_product_chains` (`id`, `purchase_product_id`, `sale_product_id`, `sold_qty`, `created_at`, `updated_at`) VALUES
(2, 979, 2, '1.00', '2022-09-12 12:24:24', '2022-09-12 12:24:24'),
(3, 978, 3, '1.00', '2022-09-12 13:26:20', '2022-09-12 13:26:20'),
(4, 981, 4, '1.00', '2022-09-13 07:16:29', '2022-09-13 07:16:29'),
(5, 980, 5, '1.00', '2022-09-13 07:16:29', '2022-09-13 07:16:29'),
(6, 982, 6, '1.00', '2022-09-13 07:16:29', '2022-09-13 07:16:29'),
(7, 981, 7, '1.00', '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(8, 980, 8, '1.00', '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(9, 982, 9, '1.00', '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(10, 981, 10, '1.00', '2022-09-13 07:17:42', '2022-09-13 07:17:42'),
(11, 980, 11, '1.00', '2022-09-13 07:17:42', '2022-09-13 07:17:42'),
(12, 982, 12, '1.00', '2022-09-13 07:17:42', '2022-09-13 07:17:42'),
(13, 980, 13, '1.00', '2022-09-13 07:23:01', '2022-09-13 07:23:01'),
(14, 981, 14, '1.00', '2022-09-13 07:23:01', '2022-09-13 07:23:01'),
(15, 982, 15, '1.00', '2022-09-13 07:27:37', '2022-09-13 07:27:37'),
(16, 983, 16, '1.00', '2022-09-13 13:02:24', '2022-09-13 13:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(41, 'Testing Role', '2022-03-31 13:04:55', '2022-03-31 13:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user` text COLLATE utf8mb4_unicode_ci,
  `contact` mediumtext COLLATE utf8mb4_unicode_ci,
  `product` text COLLATE utf8mb4_unicode_ci,
  `purchase` text COLLATE utf8mb4_unicode_ci,
  `s_adjust` text COLLATE utf8mb4_unicode_ci,
  `expense` mediumtext COLLATE utf8mb4_unicode_ci,
  `sale` text COLLATE utf8mb4_unicode_ci,
  `register` text COLLATE utf8mb4_unicode_ci,
  `report` text COLLATE utf8mb4_unicode_ci,
  `setup` text COLLATE utf8mb4_unicode_ci,
  `dashboard` text COLLATE utf8mb4_unicode_ci,
  `accounting` text COLLATE utf8mb4_unicode_ci,
  `hrms` text COLLATE utf8mb4_unicode_ci,
  `essential` text COLLATE utf8mb4_unicode_ci,
  `manufacturing` text COLLATE utf8mb4_unicode_ci,
  `project` text COLLATE utf8mb4_unicode_ci,
  `repair` text COLLATE utf8mb4_unicode_ci,
  `superadmin` text COLLATE utf8mb4_unicode_ci,
  `e_commerce` text COLLATE utf8mb4_unicode_ci,
  `others` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_super_admin_role` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `user`, `contact`, `product`, `purchase`, `s_adjust`, `expense`, `sale`, `register`, `report`, `setup`, `dashboard`, `accounting`, `hrms`, `essential`, `manufacturing`, `project`, `repair`, `superadmin`, `e_commerce`, `others`, `is_super_admin_role`, `created_at`, `updated_at`) VALUES
(8, NULL, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1,\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_import\":1,\"supplier_edit\":1,\"supplier_delete\":1,\"customer_all\":1,\"customer_add\":1,\"customer_import\":1,\"customer_edit\":1,\"customer_delete\":1,\"customer_group\":1,\"customer_report\":1,\"supplier_report\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":1,\"categories\":1,\"brand\":1,\"units\":1,\"variant\":1,\"warranties\":1,\"selling_price_group\":1,\"generate_barcode\":1,\"product_settings\":1,\"stock_report\":1,\"stock_in_out_report\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1,\"purchase_settings\":1,\"purchase_statements\":1,\"purchase_sale_report\":1,\"pro_purchase_report\":1,\"purchase_payment_report\":1}', '{\"adjustment_all\":1,\"adjustment_add_from_location\":1,\"adjustment_add_from_warehouse\":1,\"adjustment_delete\":1,\"stock_adjustment_report\":1}', '{\"view_expense\":1,\"add_expense\":1,\"edit_expense\":1,\"delete_expense\":1,\"expense_category\":1,\"category_wise_expense\":1,\"expanse_report\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"pos_sale_settings\":1,\"create_add_sale\":1,\"view_add_sale\":1,\"edit_add_sale\":1,\"delete_add_sale\":1,\"add_sale_settings\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_sale_screen\":1,\"edit_discount_pos_screen\":1,\"shipment_access\":1,\"view_product_cost_is_sale_screed\":1,\"view_own_sale\":1,\"return_access\":1,\"discounts\":1,\"sale_statements\":1,\"sale_return_statements\":1,\"pro_sale_report\":1,\"sale_payment_report\":1,\"c_register_report\":1,\"sale_representative_report\":1}', '{\"register_view\":1,\"register_close\":1,\"another_register_close\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"customer_report\":1,\"supplier_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"c_register_report\":1,\"sale_representative_report\":1,\"payroll_report\":1,\"payroll_payment_report\":1,\"attendance_report\":1,\"production_report\":1,\"financial_report\":1}', '{\"tax\":1,\"branch\":1,\"warehouse\":1,\"g_settings\":1,\"p_settings\":1,\"inv_sc\":1,\"inv_lay\":1,\"barcode_settings\":1,\"cash_counters\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"hrm_dashboard\":1,\"leave_type\":1,\"leave_assign\":1,\"shift\":1,\"attendance\":1,\"view_allowance_and_deduction\":1,\"payroll\":1,\"holiday\":1,\"department\":1,\"designation\":1,\"payroll_report\":1,\"payroll_payment_report\":1,\"attendance_report\":1}', '{\"assign_todo\":1,\"work_space\":1,\"memo\":1,\"msg\":1}', '{\"process_view\":1,\"process_add\":1,\"process_edit\":1,\"process_delete\":1,\"production_view\":1,\"production_add\":1,\"production_edit\":1,\"production_delete\":1,\"manuf_settings\":1,\"manuf_report\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', '{\"today_summery\":1,\"communication\":1}', 1, '2021-01-26 10:45:14', '2021-01-26 10:45:14'),
(32, 41, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1,\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_import\":1,\"supplier_edit\":1,\"supplier_delete\":1,\"customer_all\":1,\"customer_add\":1,\"customer_import\":1,\"customer_edit\":1,\"customer_delete\":1,\"customer_group\":1,\"customer_report\":1,\"supplier_report\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":1,\"categories\":1,\"brand\":1,\"units\":1,\"variant\":1,\"warranties\":1,\"selling_price_group\":1,\"generate_barcode\":1,\"product_settings\":1,\"stock_report\":1,\"stock_in_out_report\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1,\"purchase_settings\":1,\"purchase_statements\":1,\"purchase_sale_report\":1,\"pro_purchase_report\":1,\"purchase_payment_report\":1}', '{\"adjustment_all\":1,\"adjustment_add_from_location\":1,\"adjustment_add_from_warehouse\":1,\"adjustment_delete\":1,\"stock_adjustment_report\":1}', '{\"view_expense\":1,\"add_expense\":1,\"edit_expense\":1,\"delete_expense\":1,\"expense_category\":1,\"category_wise_expense\":1,\"expanse_report\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"pos_sale_settings\":1,\"create_add_sale\":1,\"view_add_sale\":1,\"edit_add_sale\":1,\"delete_add_sale\":1,\"add_sale_settings\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_sale_screen\":1,\"edit_discount_pos_screen\":1,\"shipment_access\":1,\"view_product_cost_is_sale_screed\":1,\"view_own_sale\":0,\"return_access\":1,\"discounts\":1,\"sale_statements\":1,\"sale_return_statements\":1,\"pro_sale_report\":1,\"sale_payment_report\":1,\"c_register_report\":1,\"sale_representative_report\":1}', '{\"register_view\":1,\"register_close\":1,\"another_register_close\":0}', '{\"tax_report\":1,\"production_report\":0}', '{\"tax\":1,\"branch\":1,\"warehouse\":1,\"g_settings\":1,\"p_settings\":1,\"inv_sc\":1,\"inv_lay\":1,\"barcode_settings\":1,\"cash_counters\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"hrm_dashboard\":1,\"leave_type\":1,\"leave_assign\":1,\"shift\":1,\"attendance\":1,\"view_allowance_and_deduction\":1,\"payroll\":1,\"holiday\":1,\"department\":1,\"designation\":1,\"payroll_report\":1,\"payroll_payment_report\":1,\"attendance_report\":1}', '{\"assign_todo\":1,\"work_space\":1,\"memo\":1,\"msg\":1}', '{\"process_view\":1,\"process_add\":1,\"process_edit\":1,\"process_delete\":1,\"production_view\":1,\"production_add\":1,\"production_edit\":1,\"production_delete\":1,\"manuf_settings\":1,\"manuf_report\":1}', '{\"proj_view\":0,\"proj_create\":0,\"proj_edit\":0,\"proj_delete\":0}', '{\"ripe_add_invo\":0,\"ripe_edit_invo\":0,\"ripe_view_invo\":0,\"ripe_delete_invo\":0,\"change_invo_status\":0,\"ripe_jop_sheet_status\":0,\"ripe_jop_sheet_add\":0,\"ripe_jop_sheet_edit\":0,\"ripe_jop_sheet_delete\":0,\"ripe_only_assinged_job_sheet\":0,\"ripe_view_all_job_sheet\":0}', '{\"superadmin_access_pack_subscrip\":0}', '{\"e_com_sync_pro_cate\":0,\"e_com_sync_pro\":0,\"e_com_sync_order\":0,\"e_com_map_tax_rate\":0}', '{\"today_summery\":1,\"communication\":1}', 0, '2022-03-31 13:04:55', '2022-09-02 12:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` bigint(20) NOT NULL,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `order_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `redeem_point` decimal(22,2) NOT NULL DEFAULT '0.00',
  `redeem_point_rate` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_status` tinyint(4) DEFAULT NULL,
  `delivered_to` mediumtext COLLATE utf8mb4_unicode_ci,
  `sale_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `order_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_payable_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_return_available` tinyint(1) NOT NULL DEFAULT '0',
  `ex_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=exchangeed,1=exchanged',
  `sale_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `sale_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=final;2=draft;3=challan;4=quatation;5=hold;6=suspended',
  `is_fixed_challen` tinyint(1) NOT NULL DEFAULT '0',
  `date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_pay` decimal(22,2) NOT NULL DEFAULT '0.00',
  `previous_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `all_total_payable` decimal(22,2) NOT NULL DEFAULT '0.00',
  `previous_due_paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `customer_running_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_by` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=add_sale;2=pos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `branch_id`, `customer_id`, `sale_account_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount_type`, `order_discount`, `order_discount_amount`, `redeem_point`, `redeem_point_rate`, `shipment_details`, `shipment_address`, `shipment_charge`, `shipment_status`, `delivered_to`, `sale_note`, `order_tax_percent`, `order_tax_amount`, `total_payable_amount`, `paid`, `change_amount`, `due`, `is_return_available`, `ex_status`, `sale_return_amount`, `sale_return_due`, `payment_note`, `admin_id`, `status`, `is_fixed_challen`, `date`, `time`, `report_date`, `month`, `year`, `attachment`, `gross_pay`, `previous_due`, `all_total_payable`, `previous_due_paid`, `customer_running_balance`, `created_by`, `created_at`, `updated_at`) VALUES
(14, 'MC00014', NULL, NULL, 31, NULL, NULL, 3, '170300.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '170300.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '10-03-2022', '07:11:07 pm', '2022-03-10 13:11:07', 'March', '2022', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2022-03-10 13:11:07', '2022-03-10 13:11:07'),
(44, 'MC00044', NULL, NULL, 31, NULL, NULL, 1, '130.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '130.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '22-05-2022', '02:47:10 pm', '2022-05-22 08:47:10', 'May', '2022', NULL, '0.00', '0.00', '130.00', '0.00', '0.00', 1, '2022-05-22 08:47:10', '2022-05-22 08:47:10'),
(62, 'MC00045', NULL, 11, 31, NULL, NULL, 1, '450.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '450.00', '450.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '12-09-2022', '06:24:23 pm', '2022-09-12 12:24:23', 'September', '2022', NULL, '450.00', '0.00', '450.00', '0.00', '0.00', 1, '2022-09-12 12:24:23', '2022-09-12 12:24:23'),
(63, 'MC00063', NULL, 11, 31, NULL, NULL, 1, '57750.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '57750.00', '0.00', '0.00', '57750.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '12-09-2022', '07:26:20 pm', '2022-09-12 13:26:20', 'September', '2022', NULL, '0.00', '0.00', '57750.00', '0.00', '0.00', 1, '2022-09-12 13:26:20', '2022-09-12 13:26:20'),
(64, 'MC00064', NULL, 11, 31, NULL, NULL, 3, '58550.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '58550.00', '0.00', '0.00', '58550.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '13-09-2022', '01:16:28 pm', '2022-09-13 07:16:28', 'September', '2022', NULL, '0.00', '57750.00', '116300.00', '0.00', '0.00', 1, '2022-09-13 07:16:28', '2022-09-13 07:16:28'),
(65, 'MC00065', NULL, 11, 31, NULL, NULL, 3, '58550.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '58550.00', '0.00', '0.00', '58550.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '13-09-2022', '01:16:43 pm', '2022-09-13 07:16:43', 'September', '2022', NULL, '0.00', '57750.00', '116300.00', '0.00', '0.00', 1, '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(66, 'MC00066', NULL, 11, 31, NULL, NULL, 3, '58550.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '58550.00', '0.00', '0.00', '58550.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '13-09-2022', '01:17:41 pm', '2022-09-13 07:17:41', 'September', '2022', NULL, '0.00', '57750.00', '116300.00', '0.00', '0.00', 1, '2022-09-13 07:17:41', '2022-09-13 07:17:41'),
(67, 'MC00067', NULL, 11, 31, NULL, NULL, 2, '800.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '800.00', '0.00', '0.00', '800.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '13-09-2022', '01:23:00 pm', '2022-09-13 07:23:00', 'September', '2022', NULL, '0.00', '0.00', '0.00', '0.00', '234200.00', 2, '2022-09-13 07:23:00', '2022-09-13 07:23:01'),
(68, 'MC00068', NULL, 11, 31, NULL, NULL, 1, '57750.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '57750.00', '0.00', '0.00', '57750.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '13-09-2022', '01:27:37 pm', '2022-09-13 07:27:37', 'September', '2022', NULL, '0.00', '234200.00', '291950.00', '0.00', '234200.00', 1, '2022-09-13 07:27:37', '2022-09-13 07:27:37'),
(69, 'SDC01100069', 1, 11, 195, NULL, NULL, 1, '120.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '120.00', '110.00', '0.00', '10.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 1, '13-09-2022', '07:02:24 pm', '2022-09-13 13:02:24', 'September', '2022', NULL, '0.00', '0.00', '120.00', '0.00', '234200.00', 1, '2022-09-13 13:02:24', '2022-09-13 13:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_on` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=sale_invoice_due;2=customer_due',
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=sale_due;2=return_due',
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_payments`
--

INSERT INTO `sale_payments` (`id`, `branch_id`, `invoice_id`, `sale_id`, `sale_return_id`, `customer_payment_id`, `customer_id`, `account_id`, `pay_mode`, `payment_method_id`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(61, NULL, 'SPV00001', 62, NULL, NULL, 11, 30, NULL, 3, '450.00', 1, 1, NULL, '12-09-2022', '06:24:23 pm', 'September', '2022', '2022-09-12 12:24:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-09-12 12:24:23', '2022-09-12 12:24:23'),
(62, 1, 'SPV00062', 69, NULL, 1, 11, 192, NULL, 3, '110.00', 1, 1, NULL, '13-09-2022', '07:02:50 pm', 'September', '2022', '2022-09-13 13:02:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2022-09-13 13:02:50', '2022-09-13 13:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `sale_products`
--

CREATE TABLE `sale_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'this_col_for_invoice_profit_report',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `ex_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `ex_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no_exchanged,1=prepare_to_exchange,2=exchanged',
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stock_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_products`
--

INSERT INTO `sale_products` (`id`, `sale_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_discount_type`, `unit_discount`, `unit_discount_amount`, `unit_tax_percent`, `unit_tax_amount`, `unit_cost_inc_tax`, `unit_price_exc_tax`, `unit_price_inc_tax`, `subtotal`, `description`, `ex_quantity`, `ex_status`, `delete_in_update`, `created_at`, `updated_at`, `stock_branch_id`, `stock_warehouse_id`) VALUES
(2, 62, 379, 1, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '450.00', '450.00', '450.00', NULL, '0.00', 0, 0, '2022-09-12 12:24:23', '2022-09-12 12:24:23', NULL, NULL),
(3, 63, 378, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2750.00', '46200.00', '55000.00', '57750.00', '57750.00', NULL, '0.00', 0, 0, '2022-09-12 13:26:20', '2022-09-12 13:26:20', NULL, NULL),
(4, 64, 379, 2, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:28', '2022-09-13 07:16:28', NULL, NULL),
(5, 64, 379, 1, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '450.00', '450.00', '450.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:28', '2022-09-13 07:16:28', NULL, NULL),
(6, 64, 378, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2750.00', '46200.00', '55000.00', '57750.00', '57750.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:28', '2022-09-13 07:16:28', NULL, NULL),
(7, 65, 379, 2, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:43', '2022-09-13 07:16:43', NULL, NULL),
(8, 65, 379, 1, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '450.00', '450.00', '450.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:43', '2022-09-13 07:16:43', NULL, NULL),
(9, 65, 378, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2750.00', '46200.00', '55000.00', '57750.00', '57750.00', NULL, '0.00', 0, 0, '2022-09-13 07:16:43', '2022-09-13 07:16:43', NULL, NULL),
(10, 66, 379, 2, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2022-09-13 07:17:41', '2022-09-13 07:17:41', NULL, NULL),
(11, 66, 379, 1, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '450.00', '450.00', '450.00', NULL, '0.00', 0, 0, '2022-09-13 07:17:41', '2022-09-13 07:17:41', NULL, NULL),
(12, 66, 378, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2750.00', '46200.00', '55000.00', '57750.00', '57750.00', NULL, '0.00', 0, 0, '2022-09-13 07:17:41', '2022-09-13 07:17:41', NULL, NULL),
(13, 67, 379, 1, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '450.00', '450.00', '450.00', NULL, '0.00', 0, 0, '2022-09-13 07:23:00', '2022-09-13 07:23:00', NULL, NULL),
(14, 67, 379, 2, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '300.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2022-09-13 07:23:00', '2022-09-13 07:23:00', NULL, NULL),
(15, 68, 378, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2750.00', '46200.00', '55000.00', '57750.00', '57750.00', NULL, '0.00', 0, 0, '2022-09-13 07:27:37', '2022-09-13 07:27:37', NULL, NULL),
(16, 69, 380, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '120.00', '120.00', '120.00', NULL, '0.00', 0, 0, '2022-09-13 13:02:24', '2022-09-13 13:02:24', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT '0',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `return_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_due_pay` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `return_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_products`
--

CREATE TABLE `sale_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sold_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `short_menus`
--

CREATE TABLE `short_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `short_menus`
--

INSERT INTO `short_menus` (`id`, `url`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'product.categories.index', 'Categories', 'fas fa-th-large', NULL, NULL),
(2, 'product.subcategories.index', 'SubCategories', 'fas fa-code-branch', NULL, NULL),
(3, 'product.brands.index', 'Brands', 'fas fa-band-aid', NULL, NULL),
(4, 'products.all.product', 'Product List', 'fas fa-sitemap', NULL, NULL),
(5, 'products.add.view', 'Add Product', 'fas fa-plus-circle', NULL, NULL),
(6, 'product.variants.index', 'Variants', 'fas fa-align-center', NULL, NULL),
(7, 'product.import.create', 'Import Products', 'fas fa-file-import', NULL, NULL),
(8, 'product.selling.price.groups.index', 'Price Group', 'fas fa-layer-group', NULL, NULL),
(9, 'barcode.index', 'G.Barcode', 'fas fa-barcode', NULL, NULL),
(10, 'product.warranties.index', 'Warranties ', 'fas fa-shield-alt', NULL, NULL),
(11, 'contacts.supplier.index', 'Suppliers', 'fas fa-address-card', NULL, NULL),
(12, 'contacts.suppliers.import.create', 'Import Suppliers', 'fas fa-file-import', NULL, NULL),
(13, 'contacts.customer.index', 'Customers', 'far fa-address-card', NULL, NULL),
(14, 'contacts.customers.import.create', 'Import Customers', 'fas fa-file-upload', NULL, NULL),
(15, 'purchases.create', 'Add Purchase', 'fas fa-shopping-cart', NULL, NULL),
(16, 'purchases.index_v2', 'Purchase List', 'fas fa-list', NULL, NULL),
(17, 'purchases.returns.index', 'Purchase Return', 'fas fa-undo', NULL, NULL),
(18, 'sales.create', 'Add Sale', 'fas fa-cart-plus', NULL, NULL),
(19, 'sales.index2', 'Add Sale List', 'fas fa-tasks', NULL, NULL),
(20, 'sales.pos.create', 'POS', 'fas fa-cash-register', NULL, NULL),
(21, 'sales.pos.list', 'POS List', 'fas fa-tasks', NULL, NULL),
(22, 'sales.drafts', 'Draft List', 'fas fa-drafting-compass', NULL, NULL),
(23, 'sales.quotations', 'Quotation List', 'fas fa-quote-right', NULL, NULL),
(24, 'sales.returns.index', 'Sale Returns', 'fas fa-undo', NULL, NULL),
(25, 'sales.shipments', 'Shipments', 'fas fa-shipping-fast', NULL, NULL),
(26, 'expanses.create', 'Add Expense', 'fas fa-plus-square', NULL, NULL),
(27, 'expanses.index', 'Expense List', 'far fa-list-alt', NULL, NULL),
(28, 'expanses.categories.index', 'Ex. Categories', 'fas fa-cubes', NULL, NULL),
(29, 'users.create', 'Add User', 'fas fa-user-plus', NULL, NULL),
(30, 'users.index', 'User List', 'fas fa-list-ol', NULL, NULL),
(31, 'users.role.create', 'Add Role', 'fas fa-plus-circle', NULL, NULL),
(32, 'users.role.index', 'Role List', 'fas fa-th-list', NULL, NULL),
(33, 'accounting.banks.index', 'Bank', 'fas fa-university', NULL, NULL),
(34, 'accounting.types.index', 'Account Types', 'fas fa-th', NULL, NULL),
(35, 'accounting.accounts.index', 'Accounts', 'fas fa-th', NULL, NULL),
(36, 'accounting.assets.index', 'Assets', 'fas fa-luggage-cart', NULL, NULL),
(37, 'accounting.balance.sheet', 'Balance Sheet', 'fas fa-balance-scale', NULL, NULL),
(38, 'accounting.trial.balance', 'Trial Balance', 'fas fa-balance-scale-right', NULL, NULL),
(39, 'accounting.cash.flow', 'Cash Flow', 'fas fa-money-bill-wave', NULL, NULL),
(40, 'settings.general.index', 'General Settings', 'fas fa-cogs', NULL, NULL),
(41, 'settings.taxes.index', 'Taxes', 'fas fa-percentage', NULL, NULL),
(42, 'invoices.schemas.index', 'Inv. Schemas', 'fas fa-file-invoice-dollar', NULL, NULL),
(43, 'invoices.layouts.index', 'Inv. Layouts', 'fas fa-file-invoice', NULL, NULL),
(44, 'settings.barcode.index', 'Barcode Settings', 'fas fa-barcode', NULL, NULL),
(45, 'settings.cash.counter.index', 'Cash Counter', 'fas fa-store', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `short_menu_users`
--

CREATE TABLE `short_menu_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `short_menu_users`
--

INSERT INTO `short_menu_users` (`id`, `short_menu_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(244, 1, 2, 0, '2021-10-07 06:49:41', '2021-10-07 06:49:53'),
(245, 2, 2, 0, '2021-10-07 06:49:42', '2021-10-07 06:49:53'),
(246, 3, 2, 0, '2021-10-07 06:49:44', '2021-10-07 06:49:53'),
(247, 4, 2, 0, '2021-10-07 06:49:45', '2021-10-07 06:49:53'),
(248, 26, 2, 0, '2021-10-07 06:49:48', '2021-10-07 06:49:53'),
(249, 18, 2, 0, '2021-10-07 06:49:52', '2021-10-07 06:49:53'),
(250, 19, 2, 0, '2021-10-07 06:49:53', '2021-10-07 06:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_adjustment_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT '0',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `recovered_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_products`
--

CREATE TABLE `stock_adjustment_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_recovers`
--

CREATE TABLE `stock_adjustment_recovers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recovered_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` int(11) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `shipping_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_purchase` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_less` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `contact_id`, `name`, `business_name`, `phone`, `alternative_phone`, `alternate_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_purchase`, `total_paid`, `total_less`, `total_purchase_due`, `total_return`, `total_purchase_return_due`, `status`, `prefix`, `created_at`, `updated_at`) VALUES
(92, 'S-0001', 'Supplier A', NULL, '018000000', '018000000', NULL, '018000000', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '47500.00', '300.00', '0.00', '47200.00', '0.00', '0.00', 1, 'S1', '2022-09-12 06:09:44', '2022-09-13 13:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=purchase;2=purchase_payment;3=opening_balance;4=direct_payment',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening',
  `date` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voucher_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `amount_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'debit/credit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_ledgers`
--

INSERT INTO `supplier_ledgers` (`id`, `branch_id`, `supplier_id`, `purchase_id`, `purchase_return_id`, `purchase_payment_id`, `supplier_payment_id`, `row_type`, `amount`, `date`, `report_date`, `created_at`, `updated_at`, `voucher_type`, `debit`, `credit`, `running_balance`, `amount_type`) VALUES
(222, NULL, 92, NULL, NULL, NULL, NULL, 1, '0.00', '2022-09-12 12:09:44', '2022-09-12 06:09:44', '2022-09-12 06:09:44', '2022-09-12 06:09:44', '0', '0.00', '0.00', '0.00', 'credit'),
(224, NULL, 92, 55, NULL, NULL, NULL, 1, '46200.00', '2022-09-12 18:02:13', '2022-09-12 12:02:13', '2022-09-12 12:02:13', '2022-09-12 12:02:13', '1', '0.00', '46200.00', '0.00', 'credit'),
(225, NULL, 92, 56, NULL, NULL, NULL, 1, '300.00', '2022-09-12 18:23:57', '2022-09-12 12:23:57', '2022-09-12 12:23:57', '2022-09-12 12:23:57', '1', '0.00', '300.00', '0.00', 'credit'),
(226, 1, 92, 57, NULL, NULL, NULL, 1, '1000.00', '2022-09-13 18:42:33', '2022-09-13 12:42:33', '2022-09-13 12:42:33', '2022-09-13 12:42:33', '1', '0.00', '1000.00', '0.00', 'credit'),
(227, 1, 92, NULL, NULL, NULL, 1, 1, '300.00', '2022-09-13 19:20:06', '2022-09-13 13:20:06', '2022-09-13 13:20:06', '2022-09-13 13:20:06', '5', '300.00', '0.00', '0.00', 'debit');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_opening_balances`
--

CREATE TABLE `supplier_opening_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `is_show_again` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_opening_balances`
--

INSERT INTO `supplier_opening_balances` (`id`, `branch_id`, `supplier_id`, `amount`, `report_date`, `created_by_id`, `is_show_again`, `created_at`, `updated_at`) VALUES
(4, NULL, 92, '0.00', NULL, 2, 1, '2022-09-12 06:09:44', '2022-09-12 06:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payments`
--

CREATE TABLE `supplier_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `less_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=purchase_payment;2=purchase_return_payment',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payments`
--

INSERT INTO `supplier_payments` (`id`, `voucher_no`, `reference`, `branch_id`, `supplier_id`, `account_id`, `paid_amount`, `less_amount`, `report_date`, `type`, `pay_mode`, `payment_method_id`, `date`, `time`, `month`, `year`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'SPV00001', NULL, 1, 92, 192, '300.00', '10.00', '2022-09-13 13:20:06', 1, NULL, 3, '13-09-2022', '07:20:06 pm', 'September', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-13 13:20:06', '2022-09-13 13:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payment_invoices`
--

CREATE TABLE `supplier_payment_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=purchase_due;2=purchase_return_due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payment_invoices`
--

INSERT INTO `supplier_payment_invoices` (`id`, `supplier_payment_id`, `purchase_id`, `supplier_return_id`, `paid_amount`, `created_at`, `updated_at`, `type`) VALUES
(1, 1, 57, NULL, '300.00', '2022-09-13 13:20:06', '2022-09-13 13:20:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `label_qty` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_products`
--

INSERT INTO `supplier_products` (`id`, `supplier_id`, `product_id`, `product_variant_id`, `label_qty`, `created_at`, `updated_at`) VALUES
(4, 92, 378, NULL, 1, '2022-09-12 06:09:59', '2022-09-12 12:02:12'),
(5, 92, 379, 1, 1, '2022-09-12 12:23:57', '2022-09-12 12:23:57'),
(6, 92, 380, NULL, 10, '2022-09-13 12:42:33', '2022-09-13 12:42:33');

-- --------------------------------------------------------

--
-- Table structure for table `sv_devices`
--

CREATE TABLE `sv_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sv_devices`
--

INSERT INTO `sv_devices` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Mobile', 'Mobile servicing', '2021-04-15 04:30:14', '2021-04-15 04:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `sv_device_models`
--

CREATE TABLE `sv_device_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checklist` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_job_sheets`
--

CREATE TABLE `sv_job_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `service_type` tinyint(4) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `checklist` text COLLATE utf8mb4_unicode_ci,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8mb4_unicode_ci COMMENT 'Product Configuration',
  `Condition` text COLLATE utf8mb4_unicode_ci COMMENT 'Condition Of The Product',
  `customer_report` text COLLATE utf8mb4_unicode_ci COMMENT 'Problem Reported By The Customer',
  `technician_comment` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_notification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_job_sheets_parts`
--

CREATE TABLE `sv_job_sheets_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_sheet_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_status`
--

CREATE TABLE `sv_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#262b26',
  `sort_order` bigint(20) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `sms_template` mediumtext COLLATE utf8mb4_unicode_ci,
  `mail_subject` mediumtext COLLATE utf8mb4_unicode_ci,
  `mail_body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sv_status`
--

INSERT INTO `sv_status` (`id`, `name`, `color`, `sort_order`, `is_completed`, `sms_template`, `mail_subject`, `mail_body`, `created_at`, `updated_at`) VALUES
(1, 'Completed', '#40e25b', 0, 1, 'SMS Template', 'Eamil Subject', 'Eamil Body', '2021-04-14 09:44:33', '2021-04-15 04:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `tax_percent`, `tax_name`, `created_at`, `updated_at`) VALUES
(1, '5.00', 'Tax@5%', '2020-11-02 05:21:19', '2020-11-02 05:24:07'),
(2, '10.00', 'Tax@10%', '2020-11-02 05:24:42', '2020-11-02 05:24:42'),
(3, '15.00', 'Tax@15%', '2020-11-02 05:24:55', '2020-11-02 05:29:34'),
(5, '50.00', 'Tax@50%', '2021-01-31 10:01:08', '2021-01-31 10:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `test_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `test_value`, `text`, `created_at`, `updated_at`) VALUES
(5, '0', NULL, NULL, NULL),
(6, '0', NULL, NULL, NULL),
(7, '0', '\n\n', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Asia/Dhaka', NULL, NULL),
(2, 'Asia/Kalkata', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `todo_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`id`, `task`, `todo_id`, `priority`, `status`, `due_date`, `description`, `branch_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(7, 'Task-2', '2021/6214', 'Urgent', 'Complated', '2021-07-07 18:00:00', 'D-S', NULL, 2, '2021-07-07 18:00:00', '2021-09-12 10:16:49'),
(8, 'Create a data base for our new project.', '2021/9211', 'Medium', 'Complated', '2021-07-07 18:00:00', 'Create a data base for our new project.', NULL, 2, '2021-07-07 18:00:00', '2021-09-06 07:26:23'),
(14, 'ff', '2021/3531', 'Low', 'Complated', '2021-01-08 18:00:00', NULL, NULL, 2, '2021-09-10 18:00:00', '2022-01-03 11:59:50'),
(15, 'Edit All Sale', '01228131', 'Low', 'New', '2021-12-06 18:00:00', NULL, NULL, 2, '2022-01-02 18:00:00', '2022-01-03 12:07:56'),
(16, 'Edit All Sale', '01226733', 'Low', 'New', '2022-01-02 18:00:00', NULL, NULL, 2, '2022-01-02 18:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `todo_users`
--

CREATE TABLE `todo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `todo_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `todo_users`
--

INSERT INTO `todo_users` (`id`, `todo_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 0, NULL, '2021-09-12 10:16:49'),
(3, 8, 2, 0, NULL, '2021-09-06 07:26:23'),
(23, 14, 2, 0, NULL, NULL),
(24, 15, 2, 0, NULL, '2022-01-03 12:07:56'),
(25, 16, 2, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_branch_to_branches`
--

CREATE TABLE `transfer_stock_branch_to_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_item` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_stock_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `expense_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `receive_status` tinyint(4) NOT NULL DEFAULT '1',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `receiver_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_branch_to_branch_products`
--

CREATE TABLE `transfer_stock_branch_to_branch_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_branches`
--

CREATE TABLE `transfer_stock_to_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pending;2=partial;3=completed',
  `warehouse_id` bigint(20) UNSIGNED NOT NULL COMMENT 'form_warehouse',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'to_branch',
  `total_item` decimal(8,2) NOT NULL,
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `additional_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `receiver_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_branch_products`
--

CREATE TABLE `transfer_stock_to_branch_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(22,2) NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_warehouses`
--

CREATE TABLE `transfer_stock_to_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pending;2=partial;3=completed',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'form_branch',
  `warehouse_id` bigint(20) UNSIGNED NOT NULL COMMENT 'to_warehouse',
  `total_item` decimal(8,2) NOT NULL,
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `additional_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `receiver_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_warehouse_products`
--

CREATE TABLE `transfer_stock_to_warehouse_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(22,2) NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dimension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code_name`, `dimension`, `created_at`, `updated_at`) VALUES
(3, 'Piece', 'PC', '1 Per piece', '2020-11-02 04:57:56', '2020-11-02 04:57:56'),
(4, 'Kilogram', 'KG', '100 Kilogram = 1 KG', '2020-11-03 00:41:16', '2020-11-03 00:41:16'),
(5, 'Dozon', 'DZ', '12 Pieces = 1 DZ', '2020-11-03 00:42:06', '2020-12-30 00:26:39'),
(7, 'Gram', 'GM', '1', '2020-12-30 03:13:06', '2020-12-30 03:13:18'),
(8, 'Ton', 'TN', NULL, '2021-01-19 04:27:58', '2021-01-19 04:27:58'),
(9, 'Pound', 'PND', NULL, '2021-01-19 04:29:11', '2021-01-19 04:29:11'),
(10, 'Unit', 'UT', NULL, '2021-07-15 06:08:10', '2021-07-15 06:08:10'),
(11, 'Item', 'ITM', NULL, '2021-07-15 06:53:29', '2021-07-15 06:53:29'),
(13, 'Liter', '1', NULL, '2021-11-18 12:32:46', '2021-11-18 12:32:46'),
(14, 'Box', 'BX', NULL, '2021-12-07 05:32:47', '2021-12-07 05:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `action` tinyint(4) DEFAULT NULL,
  `subject_type` int(11) DEFAULT NULL,
  `descriptions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_activity_logs`
--

INSERT INTO `user_activity_logs` (`id`, `branch_id`, `user_id`, `date`, `report_date`, `action`, `subject_type`, `descriptions`, `created_at`, `updated_at`) VALUES
(1043, NULL, 2, '12-09-2022', '2022-09-12 07:27:08', 1, 26, 'Name : Test Product, P.Code(SKU) : SD7439483, Cost.inc Tax : 300.00, Price.Exc Tax : 350, ', '2022-09-12 07:27:08', '2022-09-12 07:27:08'),
(1044, NULL, 2, '12-09-2022', '2022-09-12 07:27:43', 2, 26, 'Name : Test Product, P.Code(SKU) : SD7439483, Cost.inc Tax : 300.00, Price.Exc Tax : 350.00, ', '2022-09-12 07:27:43', '2022-09-12 07:27:43'),
(1045, NULL, 2, '12-09-2022', '2022-09-12 07:34:14', 5, 19, 'Username : superadmin, ', '2022-09-12 07:34:14', '2022-09-12 07:34:14'),
(1046, NULL, 2, '12-09-2022', '2022-09-12 12:01:36', 4, 18, 'Username : superadmin, ', '2022-09-12 12:01:36', '2022-09-12 12:01:36'),
(1047, NULL, 2, '12-09-2022', '2022-09-12 12:01:54', 1, 1, 'Name : Test Product, Phone : 0122544555, Customer ID : C-0001, Balance Due : 0, ', '2022-09-12 12:01:54', '2022-09-12 12:01:54'),
(1048, NULL, 2, '12-09-2022', '2022-09-12 12:02:13', 1, 4, 'Date : 12-09-2022, P.Invoice ID : PI00001, Total Purchase Amount : 46200.00, Paid : 0.00, Due : 46200.00, ', '2022-09-12 12:02:13', '2022-09-12 12:02:13'),
(1049, NULL, 2, '12-09-2022', '2022-09-12 12:23:57', 1, 4, 'Date : 12-09-2022, P.Invoice ID : PI00056, Total Purchase Amount : 300.00, Paid : 0.00, Due : 300.00, ', '2022-09-12 12:23:57', '2022-09-12 12:23:57'),
(1050, NULL, 2, '12-09-2022', '2022-09-12 12:24:23', 1, 7, 'Date : 12-09-2022, Invoice ID : MC00045, Total Payable Amount : 450.00, Paid : 450.00, Due : 0.00, ', '2022-09-12 12:24:23', '2022-09-12 12:24:23'),
(1051, NULL, 2, '12-09-2022', '2022-09-12 13:26:07', 2, 1, 'Name : Test Product, Phone : 0122544555, Customer ID : C-0001, Balance Due : 0.00, ', '2022-09-12 13:26:07', '2022-09-12 13:26:07'),
(1052, NULL, 2, '12-09-2022', '2022-09-12 13:26:20', 1, 7, 'Date : 12-09-2022, Invoice ID : MC00063, Total Payable Amount : 57750.00, Paid : 0.00, Due : 57750.00, ', '2022-09-12 13:26:20', '2022-09-12 13:26:20'),
(1053, NULL, 2, '13-09-2022', '2022-09-13 04:26:29', 4, 18, 'Username : superadmin, ', '2022-09-13 04:26:29', '2022-09-13 04:26:29'),
(1054, NULL, 2, '13-09-2022', '2022-09-13 07:16:28', 1, 7, 'Date : 13-09-2022, Invoice ID : MC00064, Total Payable Amount : 58550.00, Paid : 0.00, Due : 58550.00, ', '2022-09-13 07:16:28', '2022-09-13 07:16:28'),
(1055, NULL, 2, '13-09-2022', '2022-09-13 07:16:43', 1, 7, 'Date : 13-09-2022, Invoice ID : MC00065, Total Payable Amount : 58550.00, Paid : 0.00, Due : 58550.00, ', '2022-09-13 07:16:43', '2022-09-13 07:16:43'),
(1056, NULL, 2, '13-09-2022', '2022-09-13 07:17:41', 1, 7, 'Date : 13-09-2022, Invoice ID : MC00066, Total Payable Amount : 58550.00, Paid : 0.00, Due : 58550.00, ', '2022-09-13 07:17:41', '2022-09-13 07:17:41'),
(1057, NULL, 2, '13-09-2022', '2022-09-13 07:23:01', 1, 7, 'Date : 13-09-2022, Invoice ID : MC00067, Total Payable Amount : 800.00, Paid : 0.00, Due : 800.00, ', '2022-09-13 07:23:01', '2022-09-13 07:23:01'),
(1058, NULL, 2, '13-09-2022', '2022-09-13 07:27:37', 1, 7, 'Date : 13-09-2022, Invoice ID : MC00068, Total Payable Amount : 57750.00, Paid : 0.00, Due : 57750.00, ', '2022-09-13 07:27:37', '2022-09-13 07:27:37'),
(1059, NULL, 2, '13-09-2022', '2022-09-13 12:41:15', 1, 26, 'Name : BL2, P.Code(SKU) : SD8349452, Cost.inc Tax : 100.00, Price.Exc Tax : 120, ', '2022-09-13 12:41:15', '2022-09-13 12:41:15'),
(1060, NULL, 2, '13-09-2022', '2022-09-13 12:41:28', 5, 19, 'Username : superadmin, ', '2022-09-13 12:41:28', '2022-09-13 12:41:28'),
(1061, 1, 3, '13-09-2022', '2022-09-13 12:41:34', 4, 18, 'Username : business2, ', '2022-09-13 12:41:34', '2022-09-13 12:41:34'),
(1062, 1, 3, '13-09-2022', '2022-09-13 12:42:33', 1, 4, 'Date : 13-09-2022, P.Invoice ID : PI00057, Total Purchase Amount : 1000.00, Paid : 0.00, Due : 1000.00, ', '2022-09-13 12:42:33', '2022-09-13 12:42:33'),
(1063, 1, 3, '13-09-2022', '2022-09-13 13:02:10', 2, 1, 'Name : New Customer - Business Location 2, Phone : 0122544555, Customer ID : C-0001, Balance Due : 234200.00, ', '2022-09-13 13:02:10', '2022-09-13 13:02:10'),
(1064, 1, 3, '13-09-2022', '2022-09-13 13:02:24', 1, 7, 'Date : 13-09-2022, Invoice ID : SDC01100069, Total Payable Amount : 120.00, Paid : 0.00, Due : 120.00, ', '2022-09-13 13:02:24', '2022-09-13 13:02:24'),
(1065, 1, 3, '13-09-2022', '2022-09-13 13:02:50', 1, 27, 'Date : 13-09-2022, Voucher : CPV00001, AGS : N/A, Customer : New Customer - Business Location 2, Phn No : 0122544555, Type : Cash, Paid : 110.00, ', '2022-09-13 13:02:50', '2022-09-13 13:02:50'),
(1066, 1, 3, '13-09-2022', '2022-09-13 13:20:06', 1, 28, 'Date : 13-09-2022, Voucher : SPV00001, AGP : N/A, Supplier : Supplier A, Phn No : 018000000, Type : Cash, Paid : 300.00, ', '2022-09-13 13:20:06', '2022-09-13 13:20:06'),
(1067, NULL, 2, '18-09-2022', '2022-09-18 11:22:50', 4, 18, 'Username : superadmin, ', '2022-09-18 11:22:50', '2022-09-18 11:22:50'),
(1068, NULL, 2, '21-09-2022', '2022-09-21 06:56:16', 4, 18, 'Username : superadmin, ', '2022-09-21 06:56:16', '2022-09-21 06:56:16'),
(1069, NULL, 2, '21-09-2022', '2022-09-21 06:56:59', 2, 26, 'Name : BL2, P.Code(SKU) : SD8349452, Cost.inc Tax : 100.00, Price.Exc Tax : 120.00, ', '2022-09-21 06:56:59', '2022-09-21 06:56:59'),
(1070, NULL, 2, '21-09-2022', '2022-09-21 07:34:21', 4, 18, 'Username : superadmin, ', '2022-09-21 07:34:21', '2022-09-21 07:34:21'),
(1071, NULL, 2, '22-10-2022', '2022-10-22 07:03:01', 4, 18, 'Username : superadmin, ', '2022-10-22 07:03:01', '2022-10-22 07:03:01');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `branch_id`, `warehouse_name`, `warehouse_code`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Business 2 Warehouse', 'BW1', '0182544442', 'GenuinePos Warehouse', '2022-09-06 06:54:07', '2022-09-06 06:54:07'),
(3, NULL, 'Global W', 'PC', '0122544555', 'Global W', '2022-09-13 12:41:50', '2022-09-13 12:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_branches`
--

CREATE TABLE `warehouse_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_branches`
--

INSERT INTO `warehouse_branches` (`id`, `branch_id`, `warehouse_id`, `is_global`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 0, 0, '2022-09-13 12:41:50', '2022-09-13 12:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=warranty;2=guaranty ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warranties`
--

INSERT INTO `warranties` (`id`, `name`, `duration`, `duration_type`, `description`, `type`, `created_at`, `updated_at`) VALUES
(19, '1 Year warranty', '1', 'Years', NULL, 1, '2021-08-12 07:25:51', '2021-08-31 10:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ws_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `estimated_hours` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `branch_id`, `ws_id`, `name`, `priority`, `status`, `start_date`, `end_date`, `admin_id`, `description`, `estimated_hours`, `created_at`, `updated_at`) VALUES
(1, NULL, '2022/5267', 'HRM Software Maintenance', 'Low', 'New', '2022-03-08 18:00:00', '2022-03-30 18:00:00', 2, NULL, '120', '2022-03-08 18:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workspace_attachments`
--

CREATE TABLE `workspace_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_tasks`
--

CREATE TABLE `workspace_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NOT NULL,
  `task_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workspace_tasks`
--

INSERT INTO `workspace_tasks` (`id`, `workspace_id`, `task_name`, `user_id`, `deadline`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(1, 1, 'Create Database', 2, NULL, 'Complated', 'Low', NULL, '2022-03-09 11:50:45'),
(2, 1, 'Make a valuable design', 2, NULL, 'Complated', 'High', NULL, '2022-03-09 11:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_users`
--

CREATE TABLE `workspace_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workspace_users`
--

INSERT INTO `workspace_users` (`id`, `workspace_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xyz`
--

CREATE TABLE `xyz` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_bank_id_foreign` (`bank_id`),
  ADD KEY `accounts_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `account_branches`
--
ALTER TABLE `account_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_branches_branch_id_foreign` (`branch_id`),
  ADD KEY `account_branches_account_id_foreign` (`account_id`);

--
-- Indexes for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_ledgers_account_id_foreign` (`account_id`),
  ADD KEY `account_ledgers_expense_id_foreign` (`expense_id`),
  ADD KEY `account_ledgers_expense_payment_id_foreign` (`expense_payment_id`),
  ADD KEY `account_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `account_ledgers_sale_payment_id_foreign` (`sale_payment_id`),
  ADD KEY `account_ledgers_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `account_ledgers_purchase_id_foreign` (`purchase_id`),
  ADD KEY `account_ledgers_purchase_payment_id_foreign` (`purchase_payment_id`),
  ADD KEY `account_ledgers_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `account_ledgers_adjustment_id_foreign` (`adjustment_id`),
  ADD KEY `account_ledgers_payroll_id_foreign` (`payroll_id`),
  ADD KEY `account_ledgers_payroll_payment_id_foreign` (`payroll_payment_id`),
  ADD KEY `account_ledgers_production_id_foreign` (`production_id`),
  ADD KEY `account_ledgers_loan_id_foreign` (`loan_id`),
  ADD KEY `account_ledgers_loan_payment_id_foreign` (`loan_payment_id`),
  ADD KEY `account_ledgers_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `account_ledgers_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `account_ledgers_branch_id_foreign` (`branch_id`),
  ADD KEY `account_ledgers_stock_adjustment_recover_id_foreign` (`stock_adjustment_recover_id`),
  ADD KEY `account_ledgers_contra_credit_id_foreign` (`contra_credit_id`),
  ADD KEY `account_ledgers_contra_debit_id_foreign` (`contra_debit_id`);

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_and_users_email_unique` (`email`),
  ADD KEY `admin_and_users_role_id_foreign` (`role_id`),
  ADD KEY `admin_and_users_role_permission_id_foreign` (`role_permission_id`),
  ADD KEY `admin_and_users_branch_id_foreign` (`branch_id`),
  ADD KEY `admin_and_users_department_id_foreign` (`department_id`),
  ADD KEY `admin_and_users_designation_id_foreign` (`designation_id`),
  ADD KEY `admin_and_users_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_and_user_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allowance_employees_user_id_foreign` (`user_id`),
  ADD KEY `allowance_employees_allowance_id_foreign` (`allowance_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assets_type_id_foreign` (`type_id`),
  ADD KEY `assets_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `asset_types`
--
ALTER TABLE `asset_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_payment_methods_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `branch_payment_methods_account_id_foreign` (`account_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bulk_variant_children_bulk_variant_id_foreign` (`bulk_variant_id`);

--
-- Indexes for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_counters_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_flows_account_id_foreign` (`account_id`),
  ADD KEY `cash_flows_sender_account_id_foreign` (`sender_account_id`),
  ADD KEY `cash_flows_receiver_account_id_foreign` (`receiver_account_id`),
  ADD KEY `cash_flows_purchase_payment_id_foreign` (`purchase_payment_id`),
  ADD KEY `cash_flows_sale_payment_id_foreign` (`sale_payment_id`),
  ADD KEY `cash_flows_expanse_payment_id_foreign` (`expanse_payment_id`),
  ADD KEY `cash_flows_money_receipt_id_foreign` (`money_receipt_id`),
  ADD KEY `cash_flows_payroll_id_foreign` (`payroll_id`),
  ADD KEY `cash_flows_payroll_payment_id_foreign` (`payroll_payment_id`),
  ADD KEY `cash_flows_loan_id_foreign` (`loan_id`),
  ADD KEY `cash_flows_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `cash_flows_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `cash_flows_loan_payment_id_foreign` (`loan_payment_id`);

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_registers_branch_id_foreign` (`branch_id`),
  ADD KEY `cash_registers_admin_id_foreign` (`admin_id`),
  ADD KEY `cash_registers_sale_account_id_foreign` (`sale_account_id`),
  ADD KEY `cash_registers_cash_counter_id_foreign` (`cash_counter_id`);

--
-- Indexes for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_register_transactions_cash_register_id_foreign` (`cash_register_id`),
  ADD KEY `cash_register_transactions_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_category_id_foreign` (`parent_category_id`);

--
-- Indexes for table `combo_products`
--
ALTER TABLE `combo_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combo_products_product_id_foreign` (`product_id`),
  ADD KEY `combo_products_combo_product_id_foreign` (`combo_product_id`),
  ADD KEY `combo_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `contras`
--
ALTER TABLE `contras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contras_branch_id_foreign` (`branch_id`),
  ADD KEY `contras_receiver_account_id_foreign` (`receiver_account_id`),
  ADD KEY `contras_sender_account_id_foreign` (`sender_account_id`),
  ADD KEY `contras_user_id_foreign` (`user_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_customer_group_id_foreign` (`customer_group_id`);

--
-- Indexes for table `customer_credit_limits`
--
ALTER TABLE `customer_credit_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_credit_limits_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_credit_limits_branch_id_foreign` (`branch_id`),
  ADD KEY `customer_credit_limits_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_ledgers_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `customer_ledgers_sale_payment_id_foreign` (`sale_payment_id`),
  ADD KEY `customer_ledgers_money_receipt_id_foreign` (`money_receipt_id`),
  ADD KEY `customer_ledgers_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `customer_ledgers_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `customer_ledgers_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `customer_opening_balances`
--
ALTER TABLE `customer_opening_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_opening_balances_branch_id_foreign` (`branch_id`),
  ADD KEY `customer_opening_balances_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_opening_balances_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `customer_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_payments_account_id_foreign` (`account_id`),
  ADD KEY `customer_payments_admin_id_foreign` (`admin_id`),
  ADD KEY `customer_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payment_invoices_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `customer_payment_invoices_sale_id_foreign` (`sale_id`),
  ADD KEY `customer_payment_invoices_sale_return_id_foreign` (`sale_return_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_branch_id_foreign` (`branch_id`),
  ADD KEY `discounts_brand_id_foreign` (`brand_id`),
  ADD KEY `discounts_category_id_foreign` (`category_id`),
  ADD KEY `discounts_price_group_id_foreign` (`price_group_id`);

--
-- Indexes for table `discount_products`
--
ALTER TABLE `discount_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_products_discount_id_foreign` (`discount_id`),
  ADD KEY `discount_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `expanses`
--
ALTER TABLE `expanses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expanses_branch_id_foreign` (`branch_id`),
  ADD KEY `expanses_expense_account_id_foreign` (`expense_account_id`),
  ADD KEY `expanses_transfer_branch_to_branch_id_foreign` (`transfer_branch_to_branch_id`);

--
-- Indexes for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expanse_payments_expanse_id_foreign` (`expanse_id`),
  ADD KEY `expanse_payments_account_id_foreign` (`account_id`),
  ADD KEY `expanse_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_descriptions_expense_category_id_foreign` (`expense_category_id`),
  ADD KEY `expense_descriptions_expense_id_foreign` (`expense_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_allowance_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `hrm_department`
--
ALTER TABLE `hrm_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_holidays_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_leaves_employee_id_foreign` (`employee_id`),
  ADD KEY `hrm_leaves_leave_id_foreign` (`leave_id`);

--
-- Indexes for table `hrm_leavetypes`
--
ALTER TABLE `hrm_leavetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payrolls_user_id_foreign` (`user_id`),
  ADD KEY `hrm_payrolls_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_allowances_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_deductions_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_payments_payroll_id_foreign` (`payroll_id`),
  ADD KEY `hrm_payroll_payments_account_id_foreign` (`account_id`),
  ADD KEY `hrm_payroll_payments_admin_id_foreign` (`admin_id`),
  ADD KEY `hrm_payroll_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_schemas`
--
ALTER TABLE `invoice_schemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_loan_company_id_foreign` (`loan_company_id`),
  ADD KEY `loans_account_id_foreign` (`account_id`),
  ADD KEY `loans_created_user_id_foreign` (`created_user_id`),
  ADD KEY `loans_branch_id_foreign` (`branch_id`),
  ADD KEY `loans_expense_id_foreign` (`expense_id`),
  ADD KEY `loans_purchase_id_foreign` (`purchase_id`),
  ADD KEY `loans_loan_account_id_foreign` (`loan_account_id`);

--
-- Indexes for table `loan_companies`
--
ALTER TABLE `loan_companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_companies_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payments_company_id_foreign` (`company_id`),
  ADD KEY `loan_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `loan_payments_user_id_foreign` (`user_id`),
  ADD KEY `loan_payments_account_id_foreign` (`account_id`),
  ADD KEY `loan_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payment_distributions_loan_payment_id_foreign` (`loan_payment_id`),
  ADD KEY `loan_payment_distributions_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `memos`
--
ALTER TABLE `memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memos_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `memo_users`
--
ALTER TABLE `memo_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memo_users_memo_id_foreign` (`memo_id`),
  ADD KEY `memo_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_branch_id_foreign` (`branch_id`),
  ADD KEY `messages_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `money_receipts_customer_id_foreign` (`customer_id`),
  ADD KEY `money_receipts_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_settings_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `payment_method_settings_branch_id_foreign` (`branch_id`),
  ADD KEY `payment_method_settings_account_id_foreign` (`account_id`);

--
-- Indexes for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_short_menu_users_short_menu_id_foreign` (`short_menu_id`),
  ADD KEY `pos_short_menu_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `price_groups`
--
ALTER TABLE `price_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_group_products`
--
ALTER TABLE `price_group_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_group_products_price_group_id_foreign` (`price_group_id`),
  ADD KEY `price_group_products_product_id_foreign` (`product_id`),
  ADD KEY `price_group_products_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processes_product_id_foreign` (`product_id`),
  ADD KEY `processes_variant_id_foreign` (`variant_id`),
  ADD KEY `processes_unit_id_foreign` (`unit_id`),
  ADD KEY `processes_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_ingredients_process_id_foreign` (`process_id`),
  ADD KEY `process_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `process_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `process_ingredients_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `productions_branch_id_foreign` (`branch_id`),
  ADD KEY `productions_product_id_foreign` (`product_id`),
  ADD KEY `productions_variant_id_foreign` (`variant_id`),
  ADD KEY `productions_unit_id_foreign` (`unit_id`),
  ADD KEY `productions_stock_warehouse_id_foreign` (`stock_warehouse_id`),
  ADD KEY `productions_stock_branch_id_foreign` (`stock_branch_id`),
  ADD KEY `productions_tax_id_foreign` (`tax_id`),
  ADD KEY `productions_production_account_id_foreign` (`production_account_id`);

--
-- Indexes for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `production_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `production_ingredients_unit_id_foreign` (`unit_id`),
  ADD KEY `production_ingredients_production_id_foreign` (`production_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_parent_category_id_foreign` (`parent_category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_tax_id_foreign` (`tax_id`),
  ADD KEY `products_warranty_id_foreign` (`warranty_id`);

--
-- Indexes for table `product_branches`
--
ALTER TABLE `product_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_branches_product_id_foreign` (`product_id`),
  ADD KEY `product_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_branch_variants_product_branch_id_foreign` (`product_branch_id`),
  ADD KEY `product_branch_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_branch_variants_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_opening_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `product_opening_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_opening_stocks_product_id_foreign` (`product_id`),
  ADD KEY `product_opening_stocks_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouses_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_warehouses_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouse_variants_product_warehouse_id_foreign` (`product_warehouse_id`),
  ADD KEY `product_warehouse_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_warehouse_variants_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchases_branch_id_foreign` (`branch_id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchases_purchase_account_id_foreign` (`purchase_account_id`);

--
-- Indexes for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_order_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_product_receives_order_product_id_foreign` (`order_product_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_payments_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_payments_account_id_foreign` (`account_id`),
  ADD KEY `purchase_payments_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `purchase_payments_supplier_return_id_foreign` (`supplier_return_id`),
  ADD KEY `purchase_payments_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `purchase_payments_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_products_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `purchase_products_product_order_product_id_foreign` (`product_order_product_id`),
  ADD KEY `purchase_products_opening_stock_id_foreign` (`opening_stock_id`),
  ADD KEY `purchase_products_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_products_production_id_foreign` (`production_id`),
  ADD KEY `purchase_products_sale_return_product_id_foreign` (`sale_return_product_id`),
  ADD KEY `purchase_products_transfer_branch_to_branch_product_id_foreign` (`transfer_branch_to_branch_product_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_returns_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_returns_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_returns_purchase_return_account_id_foreign` (`purchase_return_account_id`);

--
-- Indexes for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_products_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_products_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `purchase_return_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_return_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchase_sale_product_chains`
--
ALTER TABLE `purchase_sale_product_chains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_sale_product_chains_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `purchase_sale_product_chains_sale_product_id_foreign` (`sale_product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_branch_id_foreign` (`branch_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_sale_account_id_foreign` (`sale_account_id`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_payments_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `sale_payments_account_id_foreign` (`account_id`),
  ADD KEY `sale_payments_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `sale_payments_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `sale_payments_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_payments_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_products_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_products_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `sale_products_stock_branch_id_foreign` (`stock_branch_id`),
  ADD KEY `sale_products_stock_warehouse_id_foreign` (`stock_warehouse_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_returns_branch_id_foreign` (`branch_id`),
  ADD KEY `sale_returns_sale_return_account_id_foreign` (`sale_return_account_id`),
  ADD KEY `sale_returns_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_products_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_products_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `sale_return_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_return_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `short_menus`
--
ALTER TABLE `short_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short_menu_users_short_menu_id_foreign` (`short_menu_id`),
  ADD KEY `short_menu_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_adjustments_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_adjustments_admin_id_foreign` (`admin_id`),
  ADD KEY `stock_adjustments_stock_adjustment_account_id_foreign` (`stock_adjustment_account_id`);

--
-- Indexes for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_products_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_products_product_id_foreign` (`product_id`),
  ADD KEY `stock_adjustment_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `stock_adjustment_recovers`
--
ALTER TABLE `stock_adjustment_recovers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_recovers_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_recovers_account_id_foreign` (`account_id`),
  ADD KEY `stock_adjustment_recovers_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_ledgers_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_ledgers_purchase_id_foreign` (`purchase_id`),
  ADD KEY `supplier_ledgers_purchase_payment_id_foreign` (`purchase_payment_id`),
  ADD KEY `supplier_ledgers_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `supplier_ledgers_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `supplier_ledgers_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `supplier_opening_balances`
--
ALTER TABLE `supplier_opening_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_opening_balances_branch_id_foreign` (`branch_id`),
  ADD KEY `supplier_opening_balances_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_opening_balances_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `supplier_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_payments_account_id_foreign` (`account_id`),
  ADD KEY `supplier_payments_admin_id_foreign` (`admin_id`),
  ADD KEY `supplier_payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_payment_invoices_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `supplier_payment_invoices_purchase_id_foreign` (`purchase_id`),
  ADD KEY `supplier_payment_invoices_supplier_return_id_foreign` (`supplier_return_id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_products_product_id_foreign` (`product_id`),
  ADD KEY `supplier_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `sv_devices`
--
ALTER TABLE `sv_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_device_models_brand_id_foreign` (`brand_id`),
  ADD KEY `sv_device_models_device_id_foreign` (`device_id`);

--
-- Indexes for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_job_sheets_customer_id_foreign` (`customer_id`),
  ADD KEY `sv_job_sheets_branch_id_foreign` (`branch_id`),
  ADD KEY `sv_job_sheets_user_id_foreign` (`user_id`),
  ADD KEY `sv_job_sheets_brand_id_foreign` (`brand_id`),
  ADD KEY `sv_job_sheets_device_id_foreign` (`device_id`),
  ADD KEY `sv_job_sheets_model_id_foreign` (`model_id`),
  ADD KEY `sv_job_sheets_status_id_foreign` (`status_id`);

--
-- Indexes for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_job_sheets_parts_job_sheet_id_foreign` (`job_sheet_id`),
  ADD KEY `sv_job_sheets_parts_product_id_foreign` (`product_id`),
  ADD KEY `sv_job_sheets_parts_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `sv_status`
--
ALTER TABLE `sv_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todos_branch_id_foreign` (`branch_id`),
  ADD KEY `todos_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_users_todo_id_foreign` (`todo_id`),
  ADD KEY `todo_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `transfer_stock_branch_to_branches`
--
ALTER TABLE `transfer_stock_branch_to_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_branch_to_branches_sender_branch_id_foreign` (`sender_branch_id`),
  ADD KEY `transfer_stock_branch_to_branches_sender_warehouse_id_foreign` (`sender_warehouse_id`),
  ADD KEY `transfer_stock_branch_to_branches_receiver_branch_id_foreign` (`receiver_branch_id`),
  ADD KEY `transfer_stock_branch_to_branches_expense_account_id_foreign` (`expense_account_id`),
  ADD KEY `transfer_stock_branch_to_branches_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `transfer_stock_branch_to_branches_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `transfer_stock_branch_to_branches_receiver_warehouse_id_foreign` (`receiver_warehouse_id`);

--
-- Indexes for table `transfer_stock_branch_to_branch_products`
--
ALTER TABLE `transfer_stock_branch_to_branch_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_branch_to_branch_products_transfer_id_foreign` (`transfer_id`),
  ADD KEY `transfer_stock_branch_to_branch_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_branch_to_branch_products_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_branches_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `transfer_stock_to_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_branch_products_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `transfer_stock_to_branch_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_to_branch_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_warehouses_branch_id_foreign` (`branch_id`),
  ADD KEY `transfer_stock_to_warehouses_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_warehouse_products_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `transfer_stock_to_warehouse_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_to_warehouse_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activity_logs_branch_id_foreign` (`branch_id`),
  ADD KEY `user_activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `warehouse_branches`
--
ALTER TABLE `warehouse_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_branches_branch_id_foreign` (`branch_id`),
  ADD KEY `warehouse_branches_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspaces_admin_id_foreign` (`admin_id`),
  ADD KEY `workspaces_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_attachments_workspace_id_foreign` (`workspace_id`);

--
-- Indexes for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_tasks_workspace_id_foreign` (`workspace_id`),
  ADD KEY `workspace_tasks_user_id_foreign` (`user_id`);

--
-- Indexes for table `workspace_users`
--
ALTER TABLE `workspace_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_users_workspace_id_foreign` (`workspace_id`),
  ADD KEY `workspace_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `xyz`
--
ALTER TABLE `xyz`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `account_branches`
--
ALTER TABLE `account_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=882;

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `combo_products`
--
ALTER TABLE `combo_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contras`
--
ALTER TABLE `contras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_credit_limits`
--
ALTER TABLE `customer_credit_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `customer_opening_balances`
--
ALTER TABLE `customer_opening_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_products`
--
ALTER TABLE `discount_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_department`
--
ALTER TABLE `hrm_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_leavetypes`
--
ALTER TABLE `hrm_leavetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_schemas`
--
ALTER TABLE `invoice_schemas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_companies`
--
ALTER TABLE `loan_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memos`
--
ALTER TABLE `memos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memo_users`
--
ALTER TABLE `memo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `price_groups`
--
ALTER TABLE `price_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `price_group_products`
--
ALTER TABLE `price_group_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=381;

--
-- AUTO_INCREMENT for table `product_branches`
--
ALTER TABLE `product_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=984;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_sale_product_chains`
--
ALTER TABLE `purchase_sale_product_chains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_menus`
--
ALTER TABLE `short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustment_recovers`
--
ALTER TABLE `stock_adjustment_recovers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `supplier_opening_balances`
--
ALTER TABLE `supplier_opening_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sv_devices`
--
ALTER TABLE `sv_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sv_status`
--
ALTER TABLE `sv_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `todo_users`
--
ALTER TABLE `todo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transfer_stock_branch_to_branches`
--
ALTER TABLE `transfer_stock_branch_to_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_branch_to_branch_products`
--
ALTER TABLE `transfer_stock_branch_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1072;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouse_branches`
--
ALTER TABLE `warehouse_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workspace_users`
--
ALTER TABLE `workspace_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xyz`
--
ALTER TABLE `xyz`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_branches`
--
ALTER TABLE `account_branches`
  ADD CONSTRAINT `account_branches_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  ADD CONSTRAINT `account_ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_adjustment_id_foreign` FOREIGN KEY (`adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_contra_credit_id_foreign` FOREIGN KEY (`contra_credit_id`) REFERENCES `contras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_contra_debit_id_foreign` FOREIGN KEY (`contra_debit_id`) REFERENCES `contras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_expense_payment_id_foreign` FOREIGN KEY (`expense_payment_id`) REFERENCES `expanse_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_payroll_payment_id_foreign` FOREIGN KEY (`payroll_payment_id`) REFERENCES `hrm_payroll_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_stock_adjustment_recover_id_foreign` FOREIGN KEY (`stock_adjustment_recover_id`) REFERENCES `stock_adjustment_recovers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  ADD CONSTRAINT `admin_and_users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_and_users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hrm_department` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `hrm_designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_role_permission_id_foreign` FOREIGN KEY (`role_permission_id`) REFERENCES `role_permissions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `hrm_shifts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  ADD CONSTRAINT `admin_and_user_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD CONSTRAINT `allowance_employees_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowance` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allowance_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `asset_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  ADD CONSTRAINT `branch_payment_methods_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_payment_methods_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  ADD CONSTRAINT `bulk_variant_children_bulk_variant_id_foreign` FOREIGN KEY (`bulk_variant_id`) REFERENCES `bulk_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD CONSTRAINT `cash_counters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD CONSTRAINT `cash_flows_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_expanse_payment_id_foreign` FOREIGN KEY (`expanse_payment_id`) REFERENCES `expanse_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_payment_id_foreign` FOREIGN KEY (`payroll_payment_id`) REFERENCES `hrm_payroll_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_receiver_account_id_foreign` FOREIGN KEY (`receiver_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sender_account_id_foreign` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD CONSTRAINT `cash_registers_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_cash_counter_id_foreign` FOREIGN KEY (`cash_counter_id`) REFERENCES `cash_counters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_registers_sale_account_id_foreign` FOREIGN KEY (`sale_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD CONSTRAINT `cash_register_transactions_cash_register_id_foreign` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_register_transactions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `combo_products`
--
ALTER TABLE `combo_products`
  ADD CONSTRAINT `combo_products_combo_product_id_foreign` FOREIGN KEY (`combo_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contras`
--
ALTER TABLE `contras`
  ADD CONSTRAINT `contras_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contras_receiver_account_id_foreign` FOREIGN KEY (`receiver_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contras_sender_account_id_foreign` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contras_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_credit_limits`
--
ALTER TABLE `customer_credit_limits`
  ADD CONSTRAINT `customer_credit_limits_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_credit_limits_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_credit_limits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD CONSTRAINT `customer_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_opening_balances`
--
ALTER TABLE `customer_opening_balances`
  ADD CONSTRAINT `customer_opening_balances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_opening_balances_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_opening_balances_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  ADD CONSTRAINT `customer_payment_invoices_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payment_invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payment_invoices_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_price_group_id_foreign` FOREIGN KEY (`price_group_id`) REFERENCES `price_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discount_products`
--
ALTER TABLE `discount_products`
  ADD CONSTRAINT `discount_products_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expanses`
--
ALTER TABLE `expanses`
  ADD CONSTRAINT `expanses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expanses_expense_account_id_foreign` FOREIGN KEY (`expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expanses_transfer_branch_to_branch_id_foreign` FOREIGN KEY (`transfer_branch_to_branch_id`) REFERENCES `transfer_stock_branch_to_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  ADD CONSTRAINT `expanse_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expanse_payments_expanse_id_foreign` FOREIGN KEY (`expanse_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expanse_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  ADD CONSTRAINT `expense_descriptions_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expanse_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_descriptions_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  ADD CONSTRAINT `hrm_allowance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD CONSTRAINT `hrm_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  ADD CONSTRAINT `hrm_holidays_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD CONSTRAINT `hrm_leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_leaves_leave_id_foreign` FOREIGN KEY (`leave_id`) REFERENCES `hrm_leavetypes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD CONSTRAINT `hrm_payrolls_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD CONSTRAINT `hrm_payroll_allowances_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD CONSTRAINT `hrm_payroll_deductions_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  ADD CONSTRAINT `hrm_payroll_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payroll_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_payroll_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payroll_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_created_user_id_foreign` FOREIGN KEY (`created_user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_loan_account_id_foreign` FOREIGN KEY (`loan_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_loan_company_id_foreign` FOREIGN KEY (`loan_company_id`) REFERENCES `loan_companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_companies`
--
ALTER TABLE `loan_companies`
  ADD CONSTRAINT `loan_companies_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `loan_companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD CONSTRAINT `loan_payment_distributions_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payment_distributions_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memos`
--
ALTER TABLE `memos`
  ADD CONSTRAINT `memos_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memo_users`
--
ALTER TABLE `memo_users`
  ADD CONSTRAINT `memo_users_memo_id_foreign` FOREIGN KEY (`memo_id`) REFERENCES `memos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `memo_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD CONSTRAINT `money_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `money_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  ADD CONSTRAINT `payment_method_settings_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_method_settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_method_settings_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  ADD CONSTRAINT `pos_short_menu_users_short_menu_id_foreign` FOREIGN KEY (`short_menu_id`) REFERENCES `short_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_short_menu_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `price_group_products`
--
ALTER TABLE `price_group_products`
  ADD CONSTRAINT `price_group_products_price_group_id_foreign` FOREIGN KEY (`price_group_id`) REFERENCES `price_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_group_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_group_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `processes`
--
ALTER TABLE `processes`
  ADD CONSTRAINT `processes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD CONSTRAINT `process_ingredients_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_production_account_id_foreign` FOREIGN KEY (`production_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_stock_branch_id_foreign` FOREIGN KEY (`stock_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_stock_warehouse_id_foreign` FOREIGN KEY (`stock_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD CONSTRAINT `production_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_warranty_id_foreign` FOREIGN KEY (`warranty_id`) REFERENCES `warranties` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_branches`
--
ALTER TABLE `product_branches`
  ADD CONSTRAINT `product_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  ADD CONSTRAINT `product_branch_variants_product_branch_id_foreign` FOREIGN KEY (`product_branch_id`) REFERENCES `product_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branch_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branch_variants_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD CONSTRAINT `product_opening_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  ADD CONSTRAINT `product_warehouses_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  ADD CONSTRAINT `product_warehouse_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouse_variants_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouse_variants_product_warehouse_id_foreign` FOREIGN KEY (`product_warehouse_id`) REFERENCES `product_warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_purchase_account_id_foreign` FOREIGN KEY (`purchase_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD CONSTRAINT `purchase_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  ADD CONSTRAINT `purchase_order_product_receives_order_product_id_foreign` FOREIGN KEY (`order_product_id`) REFERENCES `purchase_order_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD CONSTRAINT `purchase_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_return_id_foreign` FOREIGN KEY (`supplier_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_opening_stock_id_foreign` FOREIGN KEY (`opening_stock_id`) REFERENCES `product_opening_stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_order_product_id_foreign` FOREIGN KEY (`product_order_product_id`) REFERENCES `purchase_order_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_sale_return_product_id_foreign` FOREIGN KEY (`sale_return_product_id`) REFERENCES `sale_return_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_transfer_branch_to_branch_product_id_foreign` FOREIGN KEY (`transfer_branch_to_branch_product_id`) REFERENCES `transfer_stock_branch_to_branch_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_purchase_return_account_id_foreign` FOREIGN KEY (`purchase_return_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD CONSTRAINT `purchase_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_sale_product_chains`
--
ALTER TABLE `purchase_sale_product_chains`
  ADD CONSTRAINT `purchase_sale_product_chains_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_sale_product_chains_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_sale_account_id_foreign` FOREIGN KEY (`sale_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD CONSTRAINT `sale_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_stock_branch_id_foreign` FOREIGN KEY (`stock_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_stock_warehouse_id_foreign` FOREIGN KEY (`stock_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD CONSTRAINT `sale_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_sale_return_account_id_foreign` FOREIGN KEY (`sale_return_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD CONSTRAINT `sale_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  ADD CONSTRAINT `short_menu_users_short_menu_id_foreign` FOREIGN KEY (`short_menu_id`) REFERENCES `short_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `short_menu_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_stock_adjustment_account_id_foreign` FOREIGN KEY (`stock_adjustment_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD CONSTRAINT `stock_adjustment_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment_recovers`
--
ALTER TABLE `stock_adjustment_recovers`
  ADD CONSTRAINT `stock_adjustment_recovers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_recovers_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustment_recovers_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD CONSTRAINT `supplier_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_opening_balances`
--
ALTER TABLE `supplier_opening_balances`
  ADD CONSTRAINT `supplier_opening_balances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_opening_balances_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_opening_balances_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD CONSTRAINT `supplier_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  ADD CONSTRAINT `supplier_payment_invoices_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payment_invoices_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payment_invoices_supplier_return_id_foreign` FOREIGN KEY (`supplier_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  ADD CONSTRAINT `sv_device_models_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_device_models_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `sv_devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  ADD CONSTRAINT `sv_job_sheets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `sv_devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `sv_device_models` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `sv_status` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sv_job_sheets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  ADD CONSTRAINT `sv_job_sheets_parts_job_sheet_id_foreign` FOREIGN KEY (`job_sheet_id`) REFERENCES `sv_job_sheets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_parts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_parts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todos_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD CONSTRAINT `todo_users_todo_id_foreign` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todo_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_branch_to_branches`
--
ALTER TABLE `transfer_stock_branch_to_branches`
  ADD CONSTRAINT `transfer_stock_branch_to_branches_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_expense_account_id_foreign` FOREIGN KEY (`expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_receiver_branch_id_foreign` FOREIGN KEY (`receiver_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_receiver_warehouse_id_foreign` FOREIGN KEY (`receiver_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_sender_branch_id_foreign` FOREIGN KEY (`sender_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_branch_to_branches_sender_warehouse_id_foreign` FOREIGN KEY (`sender_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_branch_to_branch_products`
--
ALTER TABLE `transfer_stock_branch_to_branch_products`
  ADD CONSTRAINT `transfer_stock_branch_to_branch_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_branch_to_branch_products_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `transfer_stock_branch_to_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_branch_to_branch_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  ADD CONSTRAINT `transfer_stock_to_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branches_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  ADD CONSTRAINT `transfer_stock_to_branch_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branch_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branch_products_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stock_to_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  ADD CONSTRAINT `transfer_stock_to_warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stock_to_warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_branches`
--
ALTER TABLE `warehouse_branches`
  ADD CONSTRAINT `warehouse_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_branches_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `workspaces_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspaces_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  ADD CONSTRAINT `workspace_attachments_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  ADD CONSTRAINT `workspace_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_tasks_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_users`
--
ALTER TABLE `workspace_users`
  ADD CONSTRAINT `workspace_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_users_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
