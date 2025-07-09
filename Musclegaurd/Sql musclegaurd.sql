-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2024 at 09:34 AM
-- Server version: 10.5.10-MariaDB-cll-lve
-- PHP Version: 8.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luminary_gym_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `name`, `email`, `password`, `date`, `code`, `otp`, `status`) VALUES
(1, 'Dummy Account', 'dummy@gmail.com', '$2y$10$JrJBb8Su0fEwdticsP90UOyNHRI9oj7d2sOyUqD/YUTdcy64ZqfNi', '2024-01-17', 'c4ca4238a0b923820dcc509a6f75849b', 0, 0),
(3, 'Yazdan', 'yazdanshaikh11@gmail.com', '$2y$10$8xUeDQDemeBsFrbYs2kVSeWYs2LAIVs87VYdrFArZc4.P/SxPmSra', '2024-01-21', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0, 0),
(8, 'Saad Ansari', 'saad.ansari3631@gmail.com', '$2y$10$FCkDH9NsSARWiYWn9AqugeQvmcviOetlrcGFW8uQevNWnqG9JYH4S', '2024-01-23', 'c9f0f895fb98ab9159f51fd0297e236d', 0, 0),
(9, 'Sameer Shaikh', 'alisameer52718@gmail.com', '$2y$10$uvuwGmjTr9HXyJhAtR6Z..CM2iJOdVEWtBcM/ypNL5NqhycWm5th.', '2024-01-23', '45c48cce2e2d7fbdea1afc51c7c6ad26', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` bigint(20) UNSIGNED NOT NULL,
  `main` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `card` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `second_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `writter_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `qoute` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `name`, `card`, `card_description`, `description`, `second_description`, `writter_name`, `category`, `qoute`, `created_at`, `updated_at`) VALUES
(3, 'Nerko NFT is a cutting-edge platform', '1705767964.png', 'Nerko NFT website platform is a digital art haven where creativity and technology converge. It\'s a dynamic ecosystem empowering artists to tokenize their unique masterpieces. With cutting-edge blockchain technology, it offers a secure and transparent marketplace for collectors and creators. Discover the next wave of digital art on Nerko, where innovation and imagination unite.', 'The Al Hareem Center Website is a digital platform offering a rich blend of cultural, religious, and educational content, serving as a hub for Islamic knowledge and community engagement. Bag Emporium\'s ecommerce website offers a wide selection of stylish bags for every occasion. Explore their trendy collection and shop for the perfect bag to complement your style. Nerko NFT is a cutting-edge platform for digital art enthusiasts and collectors. Explore, buy, and trade unique non-fungible tokens, connecting creators with their global audience. Join the NFT revolution today. Civi Freelance Website is a dynamic online platform connecting talented freelancers with businesses seeking skilled professionals. Find top-notch freelancers or showcase your expertise for exciting projects.', 'Bag Emporium\'s ecommerce website offers a wide selection of stylish bags for every occasion. Explore their trendy collection and shop for the perfect bag to complement your style. Nerko NFT is a cutting-edge platform for digital art enthusiasts and collectors. Explore, buy, and trade unique non-fungible tokens, connecting creators with their global audience. Join the NFT revolution today. Civi Freelance Website is a dynamic online platform connecting talented freelancers with businesses seeking skilled professionals. Find top-notch freelancers or showcase your expertise for exciting projects.', 'Yazdan Shaikh', 'Technology', 'Your one-stop destination for seamless online shopping.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `size` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `shipment` int(11) NOT NULL DEFAULT 0,
  `charges` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `user_id`, `qty`, `size`, `color`, `price`, `discount`, `shipment`, `charges`, `date`, `time`, `order_id`, `code`, `status`, `reason`) VALUES
(3, 1, 1, 1, 'S', '#af1212', 10000, 20, 0, 1, '2024-01-19', '21:43:13', 1, 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 3, NULL),
(4, 1, 1, 2, 'S', '#af1212', 10000, 20, 0, 432, '2024-01-19', '22:14:04', 2, 'a87ff679a2f3e71d9181a67b7542122c', 0, NULL),
(5, 2, 1, 1, 'L', '#7d1c1c', 4234, 0, 0, 1, '2024-01-21', '02:18:07', 3, 'e4da3b7fbbce2345d7772b0674a318d5', 0, NULL),
(6, 5, 2, 2, 'M', '#2d9211', 10000, 10, 0, 1, '2024-01-21', '15:43:48', 4, '1679091c5a880faf6fb5e6087eb1b2dc', 0, NULL),
(7, 4, 2, 1, 'M', '#a41919', 5500, 20, 0, 1, '2024-01-21', '15:44:27', 5, '8f14e45fceea167a5a36dedd4bea2543', 0, NULL),
(8, 4, 3, 1, 'M', '#a41919', 5500, 20, 0, 1, '2024-01-21', '16:56:47', 6, 'c9f0f895fb98ab9159f51fd0297e236d', 0, NULL),
(9, 4, 1, 1, 'M', '#a41919', 5500, 20, 0, 1, '2024-01-22', '19:15:17', 7, '45c48cce2e2d7fbdea1afc51c7c6ad26', 0, NULL),
(10, 5, 1, 1, 'M', '#c41212', 10000, 10, 0, 1, '2024-01-22', '19:16:06', 8, 'd3d9446802a44259755d38e6d163e820', 0, NULL),
(11, 5, 1, 1, 'M', '#c41212', 10000, 10, 0, 1, '2024-01-22', '19:17:35', 9, '6512bd43d9caa6e02c990b0a82652dca', 0, NULL),
(12, 3, 5, 1, 'M', '#aeadad', 3500, 30, 1, 1, '2024-01-22', '20:56:23', 10, 'c20ad4d76fe97759aa27a0c99bff6710', 0, NULL),
(13, 3, 6, 3, 'S', '#171616', 3500, 30, 1, 1, '2024-01-23', '13:18:31', 11, 'c51ce410c124a10e0db5e4b97fc2af39', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `small` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `commission` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `parent_id`, `name`, `description`, `url`, `id`, `account_id`, `date`, `time`, `card`, `small`, `code`, `status`, `commission`) VALUES
(2, 1, 'yazdanshaikh11@gmail.com', NULL, 'yazdanshaikh11-gmail-com-1705511512', '1705511512', 1, '2024-01-17', '22:11:52', '1-1705511512-72688848.png', NULL, 'c81e728d9d4c2f636f067f89cc14862c', 0, 0),
(3, 0, 'Proteins', 'Proteins', 'proteins-1705687286', '1705687286', 1, '2024-01-19', '23:01:26', '1-1705687286-74565886.png', '1-1705687286-80466932.jpg', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0, 0),
(4, 0, 'Gym shoes', NULL, 'gym-shoes-1705687360', '1705687360', 1, '2024-01-19', '23:02:40', '1-1705687360-89206796.png', '1-1705687360-31297453.jpg', 'a87ff679a2f3e71d9181a67b7542122c', 0, 0),
(5, 0, 'Water bottle', NULL, 'water-bottle-1705687379', '1705687379', 1, '2024-01-19', '23:02:59', '1-1705687379-75097287.png', '1-1705687379-72408517.jpg', 'e4da3b7fbbce2345d7772b0674a318d5', 0, 0),
(6, 0, 'Leg Press Machine', NULL, 'leg-press-machine-1705687415', '1705687415', 1, '2024-01-19', '23:03:35', '1-1705687415-47730181.png', '1-1705687415-95213759.jpg', '1679091c5a880faf6fb5e6087eb1b2dc', 0, 0),
(7, 0, 'Barbells', NULL, 'barbells-1705687427', '1705687427', 1, '2024-01-19', '23:03:47', '1-1705687427-43509822.png', '1-1705687427-36130240.jpg', '8f14e45fceea167a5a36dedd4bea2543', 0, 0),
(8, 0, 'Dumbles', NULL, 'dumbles-1705687668', '1705687668', 1, '2024-01-19', '23:07:48', '1-1705687668-17971615.png', '1-1705687668-17621143.png', 'c9f0f895fb98ab9159f51fd0297e236d', 0, 0),
(10, 8, 'Steel Dumbles', 'Steel Dumbles', 'steel-dumbles-1705828028', '1705828028', 1, '2024-01-21', '14:07:08', '1-1705828028-25784990.png', '1-1705828028-23156307.png', 'd3d9446802a44259755d38e6d163e820', 0, 0),
(11, 7, 'Long Barbells', 'Long Barbells', 'long-barbells-1705828107', '1705828107', 1, '2024-01-21', '14:08:27', '1-1705828107-47218111.png', '1-1705828107-27533253.png', '6512bd43d9caa6e02c990b0a82652dca', 0, 0),
(12, 6, 'Steel Leg Press Machine', NULL, 'steel-leg-press-machine-1705828140', '1705828140', 1, '2024-01-21', '14:09:00', '1-1705828140-12769818.png', '1-1705828140-72908882.png', 'c20ad4d76fe97759aa27a0c99bff6710', 0, 0),
(13, 5, 'Fancy Water Bottles', NULL, 'fancy-water-bottles-1705828159', '1705828159', 1, '2024-01-21', '14:09:19', '1-1705828159-44366556.png', '1-1705828159-62637626.png', 'c51ce410c124a10e0db5e4b97fc2af39', 0, 0),
(14, 4, 'Sports Shoes', NULL, 'sports-shoes-1705828174', '1705828174', 1, '2024-01-21', '14:09:34', '1-1705828174-19407842.png', '1-1705828174-31417099.png', 'aab3238922bcc25a6f606eb525ffdc56', 0, 0),
(15, 3, 'Proteins Shak', NULL, 'proteins-shak-1705828193', '1705828193', 1, '2024-01-21', '14:09:53', '1-1705828193-90802160.png', '1-1705828193-89397033.png', '9bf31c7ff062936a96d3c8bd1f8f2ff3', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `name`, `country_id`, `state_id`, `date`, `time`, `card`, `account_id`, `code`, `status`) VALUES
(1, 'Karachi', 1, 1, '2024-01-17', '22:04:40', '1-1705511080-96417985.png', 1, 'c4ca4238a0b923820dcc509a6f75849b', 0),
(2, 'hyderabad', 1, 1, '2024-01-19', '22:40:27', '1-1705686027-61320197.png', 1, 'c81e728d9d4c2f636f067f89cc14862c', 0),
(3, 'Lahore', 1, 2, '2024-01-19', '22:40:40', '1-1705686040-91315639.png', 1, 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0),
(4, 'Islamabad', 1, 2, '2024-01-19', '22:40:50', '1-1705686050-35948319.png', 1, 'a87ff679a2f3e71d9181a67b7542122c', 0),
(5, 'Sukker', 1, 1, '2024-01-23', '00:08:37', '7-1705950517-80379160.jpeg', 7, 'e4da3b7fbbce2345d7772b0674a318d5', 0),
(6, 'Nawabshah', 1, 1, '2024-01-23', '13:15:36', '8-1705997736-81373276.jpg', 8, '1679091c5a880faf6fb5e6087eb1b2dc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `commission_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `commission` bigint(20) NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `name`, `email`, `phone`, `subject`, `message`, `date`, `time`, `code`) VALUES
(1, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '03493146998', 'asda', 'asdas', '2024-01-19', '01:02:21', 'c4ca4238a0b923820dcc509a6f75849b'),
(2, 'saad', 'saad.ansari3631@gmail.com', '12387123921', 'lorem', 'asjdkaskddjhcsdvnasd csdc asd asdlv sdlv lasd vasdlvas', '2024-01-23', '13:42:49', 'c81e728d9d4c2f636f067f89cc14862c');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `name`, `country_code`, `date`, `time`, `account_id`, `code`, `status`) VALUES
(1, 'Pakistan', '92', '2024-01-17', '22:03:46', 1, 'c4ca4238a0b923820dcc509a6f75849b', 0);

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `coupon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`coupon_id`, `coupon`, `discount`, `account_id`, `date`, `time`, `card`, `code`, `status`, `availability`) VALUES
(1, '8x66oXXr497693', 50, 1, '2024-01-19', '01:08:07', NULL, 'c4ca4238a0b923820dcc509a6f75849b', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `availability` int(11) NOT NULL DEFAULT 0
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

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `name`, `email`, `phone`, `country_id`, `state_id`, `city_id`, `address`, `note`, `coupon_id`, `date`, `time`, `user_id`, `code`, `status`) VALUES
(1, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'dsadasd', 1, '2024-01-19', '21:55:02', 1, 'c4ca4238a0b923820dcc509a6f75849b', 2),
(2, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'rewrw', 0, '2024-01-19', '22:14:58', 1, 'c81e728d9d4c2f636f067f89cc14862c', 0),
(3, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'weqwee', 0, '2024-01-21', '02:21:39', 1, 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0),
(4, 'Shayan Ali', 'shayanalishaikh1410@gamil.com', '03310378148', 1, 1, 2, 'unit 9 mir fazal town', 'ghfygkihi', 0, '2024-01-21', '15:44:02', 2, 'a87ff679a2f3e71d9181a67b7542122c', 0),
(5, 'Shayan Ali', 'shayanalishaikh1410@gamil.com', '03310378148', 1, 1, 2, 'unit 9 mir fazal town', 'fhjfjhyf', 0, '2024-01-21', '15:44:42', 2, 'e4da3b7fbbce2345d7772b0674a318d5', 0),
(6, 'yazdan', 'xitehub@gmail.com', '03493146998', 1, 1, 1, 'Suit no n2219 block 2', 'sddasdas', 0, '2024-01-21', '16:57:11', 3, '1679091c5a880faf6fb5e6087eb1b2dc', 0),
(7, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'trtr', 0, '2024-01-22', '19:15:25', 1, '8f14e45fceea167a5a36dedd4bea2543', 0),
(8, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'trtrtrter', 0, '2024-01-22', '19:16:36', 1, 'c9f0f895fb98ab9159f51fd0297e236d', 0),
(9, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', 'tyrytry', 0, '2024-01-22', '19:17:44', 1, '45c48cce2e2d7fbdea1afc51c7c6ad26', 0),
(10, 'Shayan Ali', 'shayanalishaikh1410@gamil.com', 'sdsa', 1, 1, 2, 'dwefewf', 'sdas', 0, '2024-01-22', '20:56:34', 5, 'd3d9446802a44259755d38e6d163e820', 0),
(11, 'saad', 'saad.ansari3631@gmail.com', '3123854550', 1, 1, 2, 'ajsdgasjdasj', 'dcsnj,asdbjkb bcsdyacguakus,aas', 0, '2024-01-23', '13:19:08', 6, '6512bd43d9caa6e02c990b0a82652dca', 0);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `sub_category_id` int(11) NOT NULL DEFAULT 0,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `price` bigint(20) NOT NULL,
  `colors` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `shipment` int(11) NOT NULL DEFAULT 0,
  `intra` int(11) NOT NULL DEFAULT 0,
  `inter` int(11) NOT NULL DEFAULT 0,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `shop_id` int(11) NOT NULL DEFAULT 0,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `approval` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `featured` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `id`, `brand_id`, `category_id`, `sub_category_id`, `name`, `size`, `description`, `specification`, `discount`, `price`, `colors`, `activity`, `note`, `keywords`, `sku`, `stock`, `shipment`, `intra`, `inter`, `url`, `date`, `time`, `vendor_id`, `account_id`, `shop_id`, `card`, `images`, `code`, `status`, `approval`, `block`, `availability`, `rating`, `views`, `featured`) VALUES
(3, '1705830270', 0, 7, 11, 'Series I Commercial Grade Urethane Dumbbells – American Barbell', '[{\"size\":\"S\",\"stock\":\"30\",\"sold\":0,\"status\":0},{\"size\":\"M\",\"stock\":\"30\",\"sold\":0,\"status\":0},{\"size\":\"L\",\"stock\":\"40\",\"sold\":0,\"status\":0}]', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"', 30, 3500, '[{\"name\":null,\"hex\":\"#171616\",\"status\":0},{\"name\":null,\"hex\":\"#aeadad\",\"status\":0},{\"name\":null,\"hex\":\"#16927a\",\"status\":0}]', '[{\"description\":\"Product Has Been Added On January 21 2024\",\"icon\":\"fa-solid fa-plus\",\"date\":\"21 January\",\"task\":\"Creation\"},{\"description\":\"Temperary Down For Approval From Administration On January 21 2024\",\"icon\":\"fa-solid fa-down-long\",\"date\":\"21 January\",\"task\":\"Temperary Down For Approval\"},{\"description\":\"Add Some Images On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Uploading\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Size (S) With Stock Of (30) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Add Size (M) With Stock Of (30) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Add Size (L) With Stock Of (40) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Product Go On Discount (30%) On January 21 2024\",\"icon\":\"fa-solid fa-percent\",\"date\":\"21 January\",\"task\":\"Go On Discount\"}]', NULL, 'Series I Commercial Grade Urethane Dumbbells – American Barbell', 'gSFTLY2k3513', 0, 1, 1, 1, 'series-i-commercial-grade-urethane-dumbbells-----american-barbell-1705830270', '2024-01-21', '14:44:30', 5, 0, 0, '5-1705830270-37728619.png', '[\"5-1705830359-61871455.png\",\"5-1705830359-18077444.jpg\",\"5-1705830359-48358020.jpg\"]', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0, 0, 0, 0, 4, 8, 0),
(4, '1705830764', 0, 7, 11, 'Damyuan Mens Lightweight Athletic Running Walking Gym Shoes Casual Sports Shoes', '[{\"size\":\"S\",\"stock\":\"128\",\"sold\":0,\"status\":0},{\"size\":\"M\",\"stock\":\"128\",\"sold\":0,\"status\":0},{\"size\":\"L\",\"stock\":\"128\",\"sold\":0,\"status\":0}]', '\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"', '\"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"', 20, 5500, '[{\"name\":null,\"hex\":\"#141414\",\"status\":0},{\"name\":null,\"hex\":\"#a41919\",\"status\":0},{\"name\":null,\"hex\":\"#2d867b\",\"status\":0}]', '[{\"description\":\"Product Has Been Added On January 21 2024\",\"icon\":\"fa-solid fa-plus\",\"date\":\"21 January\",\"task\":\"Creation\"},{\"description\":\"Temperary Down For Approval From Administration On January 21 2024\",\"icon\":\"fa-solid fa-down-long\",\"date\":\"21 January\",\"task\":\"Temperary Down For Approval\"},{\"description\":\"Add Some Images On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Uploading\"},{\"description\":\"Delete Images(5-1705830968-60913240.jpg) On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Delete\"},{\"description\":\"Add Some Images On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Uploading\"},{\"description\":\"Delete Images(5-1705830968-63527833.jpg) On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Delete\"},{\"description\":\"Add Some Images On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Uploading\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Size (S) With Stock Of (128) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Add Size (M) With Stock Of (128) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Add Size (L) With Stock Of (128) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Product Go On Discount (20%) On January 21 2024\",\"icon\":\"fa-solid fa-percent\",\"date\":\"21 January\",\"task\":\"Go On Discount\"}]', NULL, 'Damyuan Mens Lightweight Athletic Running Walking Gym Shoes Casual Sports Shoes', 'R5T8yiTl29220', 0, 0, 0, 0, 'damyuan-mens-lightweight-athletic-running-walking-gym-shoes-casual-sports-shoes-1705830764', '2024-01-21', '14:52:44', 5, 0, 0, '5-1705830764-80898766.png', '[\"5-1705830968-47867781.jpg\",\"5-1705830984-36651285.jpg\",\"5-1705830994-10025219.jpg\"]', 'a87ff679a2f3e71d9181a67b7542122c', 0, 0, 0, 0, 3, 15, 0),
(5, '1705831357', 0, 7, 11, 'Protein Shaker Bottle Sports 500 ML - Gym Mixer Bottle Leak-Proof', '[{\"size\":\"M\",\"stock\":\"128\",\"sold\":0,\"status\":0}]', 'On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.', 'On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.', 10, 10000, '[{\"name\":null,\"hex\":\"#1a1a1a\",\"status\":0},{\"name\":null,\"hex\":\"#2d9211\",\"status\":0},{\"name\":null,\"hex\":\"#c41212\",\"status\":0},{\"name\":null,\"hex\":\"#0400ff\",\"status\":0}]', '[{\"description\":\"Product Has Been Added On January 21 2024\",\"icon\":\"fa-solid fa-plus\",\"date\":\"21 January\",\"task\":\"Creation\"},{\"description\":\"Temperary Down For Approval From Administration On January 21 2024\",\"icon\":\"fa-solid fa-down-long\",\"date\":\"21 January\",\"task\":\"Temperary Down For Approval\"},{\"description\":\"Add Some Images On January 21 2024\",\"icon\":\"fa-regular fa-image\",\"date\":\"21 January\",\"task\":\"Image Uploading\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Color () On January 21 2024\",\"icon\":\"fa-solid fa-droplet\",\"date\":\"21 January\",\"task\":\"Color Creation\"},{\"description\":\"Add Size (M) With Stock Of (128) Piece On January 21 2024\",\"icon\":\"fa-solid fa-ruler\",\"date\":\"21 January\",\"task\":\"Size Creation\"},{\"description\":\"Product Go On Discount (10%) On January 21 2024\",\"icon\":\"fa-solid fa-percent\",\"date\":\"21 January\",\"task\":\"Go On Discount\"}]', NULL, 'Protein Shaker Bottle Sports 500 ML - Gym Mixer Bottle Leak-Proof', 'M0HzimJ042065', 0, 0, 0, 0, 'protein-shaker-bottle-sports-500-ml---gym-mixer-bottle-leak-proof-1705831357', '2024-01-21', '15:02:37', 5, 0, 0, '5-1705831357-32885874.png', '[\"5-1705831397-18689097.jpg\",\"5-1705831397-17424052.jpg\"]', 'e4da3b7fbbce2345d7772b0674a318d5', 0, 0, 0, 0, 5, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `professional`
--

CREATE TABLE `professional` (
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` int(11) NOT NULL,
  `experience` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quick` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `available` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `professional` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `quality` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `pdf` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `front` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `back` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `verified` int(11) NOT NULL DEFAULT 0,
  `approval` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0,
  `featured` int(11) NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `approval` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` int(11) NOT NULL DEFAULT 0,
  `type_id` int(11) NOT NULL DEFAULT 0,
  `condition` int(11) NOT NULL DEFAULT 0,
  `price` bigint(20) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `location` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bathroom` int(11) NOT NULL DEFAULT 0,
  `bedroom` int(11) NOT NULL DEFAULT 0,
  `area` int(11) NOT NULL DEFAULT 0,
  `area_unit` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `shop_id` int(11) NOT NULL DEFAULT 0,
  `card` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maps` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `approval` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `featured` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `professional_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `product_id`, `professional_id`, `user_id`, `rating`, `review`, `date`, `time`, `code`) VALUES
(1, 1, 0, 1, 3, 'vsdf', '2024-01-17', '22:24:49', 'c4ca4238a0b923820dcc509a6f75849b'),
(2, 1, 0, 1, 5, 'sqw', '2024-01-17', '22:26:07', 'c81e728d9d4c2f636f067f89cc14862c'),
(3, 1, 0, 1, 5, 'csc', '2024-01-17', '22:26:28', 'eccbc87e4b5ce2fe28308fd9f2a7baf3'),
(4, 5, 0, 2, 5, 'nice product', '2024-01-21', '15:43:37', 'a87ff679a2f3e71d9181a67b7542122c'),
(5, 4, 0, 3, 3, 'Good', '2024-01-21', '16:56:43', 'e4da3b7fbbce2345d7772b0674a318d5'),
(6, 3, 0, 3, 4, 'Amazing Dumbles', '2024-01-21', '17:11:22', '1679091c5a880faf6fb5e6087eb1b2dc');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `shop_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shop_overview` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stn` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ntn` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `member` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`shop_id`, `type_id`, `category_id`, `vendor_id`, `shop_name`, `shop_overview`, `business_email`, `business_phone`, `business_address`, `stn`, `ntn`, `logo`, `banner`, `date`, `time`, `code`, `commission`, `status`, `block`, `member`) VALUES
(3, 2, 4, 3, 'Gym Fitness', 'Welcome to Fitness Haven, a state-of-the-art gym designed to cater to every aspect of your fitness journey. Nestled in the heart of the city, our facility spans a generous 10,000 square feet, offering a perfect blend of modernity, comfort, and functionality.', 'yazdanshaikh@gmail.com', '+923493146997', 'karachi sindh pakistan', '12345', '123456', '3-1705689032-86494555.png', '3-1705689032-73084658.jpg', '2024-01-19', '23:30:32', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0, 0, 0, 103),
(4, 3, 6, 4, 'Golds Gym', 'After a vigorous workout, unwind in our wellness area, which includes a sauna, steam room, and a relaxation lounge. This tranquil space is the perfect escape to rejuvenate your body and mind. Our team of experienced personal trainers is available to provide customized workout plans and nutritional advice. Whether you\'re aiming for weight loss, muscle gain, or overall health improvement, our trainers will guide you every step of the way.', 'yazdan@gmail.com', '+923493146995', 'karachi sindh pakistan', '12345', '123456', '4-1705689712-14836894.png', '4-1705689712-26146046.jpg', '2024-01-19', '23:41:52', 'a87ff679a2f3e71d9181a67b7542122c', 0, 0, 0, 67),
(5, 2, 7, 5, 'Zenith Gym', 'At Zenith Gym, we believe in creating an inclusive and motivating environment. Our clean, well-maintained facility, coupled with a supportive community, makes us more than just a gym - we are your partner in the journey to a healthier, happier you. Join us and transform your fitness dreams into reality!', 'shaikh@gmail.com', '+923493146994', 'karachi sindh pakistan', '12345', '123456', '5-1705689976-79931644.png', '5-1705689977-74538595.jpg', '2024-01-19', '23:46:16', 'e4da3b7fbbce2345d7772b0674a318d5', 0, 0, 0, 33),
(6, 1, 4, 6, 'Luminary Gyms', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'luminarybytes@gmail.com', '03160308933', 'Unit 9 Latifabad', '12345', '12345', '6-1705833352-37660942.png', '6-1705833352-62303356.jpg', '2024-01-21', '15:35:52', '1679091c5a880faf6fb5e6087eb1b2dc', 0, 0, 0, 30),
(7, 3, 4, 7, 'dd', 'sss', 'a@gmail.com', '03123899880', 'hyderabad', '22', '22', '7-1705852955-90611171.jpg', '7-1705852955-11278814.jpg', '2024-01-21', '21:02:35', '8f14e45fceea167a5a36dedd4bea2543', 0, 0, 0, 200),
(8, 2, 8, 8, 'Sameer Gym', 'sfvsafsad', 'alisameer52718@gmail.com', '+923160306237', 'house # 3 fort area', '342142143214', '123413254415', '8-1705868281-59578319.jpg', '8-1705868281-68872241.jpg', '2024-01-22', '01:18:01', 'c9f0f895fb98ab9159f51fd0297e236d', 0, 0, 0, 10),
(9, 2, 8, 9, 'abc', 'hh', 'abc@mail.com', '03123899889', 'hyderabad', '22', '22', '9-1705922345-35283310.jpg', '9-1705922345-94122552.jpg', '2024-01-22', '16:19:05', '45c48cce2e2d7fbdea1afc51c7c6ad26', 0, 0, 0, 44),
(10, 3, 4, 10, 'Shahnoor', 'lorem ipsum', 'asasd@gmail.com', '03160234567', 'hyderabad', '321636521315786', '231263123671283', '10-1705999076-69841308.jpg', '10-1705999076-96713864.png', '2024-01-23', '13:37:56', 'd3d9446802a44259755d38e6d163e820', 0, 0, 0, 21);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `name`, `country_id`, `date`, `time`, `account_id`, `code`, `status`) VALUES
(1, 'Sindh', 1, '2024-01-17', '22:04:01', 1, 'c4ca4238a0b923820dcc509a6f75849b', 0),
(2, 'Punjab', 1, '2024-01-19', '22:39:04', 1, 'c81e728d9d4c2f636f067f89cc14862c', 0),
(5, 'Balochistan', 1, '2024-01-23', '13:09:23', 1, 'e4da3b7fbbce2345d7772b0674a318d5', 0),
(6, 'KPK', 1, '2024-01-26', '19:31:40', 8, '1679091c5a880faf6fb5e6087eb1b2dc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscriber`
--

CREATE TABLE `subscriber` (
  `subscriber_id` bigint(20) UNSIGNED NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriber`
--

INSERT INTO `subscriber` (`subscriber_id`, `email`, `status`, `date`, `time`, `code`) VALUES
(1, 'yazdn@gmail.com', 0, '2024-01-18', '22:59:05', 'c4ca4238a0b923820dcc509a6f75849b'),
(2, 'shaikh@gmail.com', 0, '2024-01-21', '14:13:25', 'c81e728d9d4c2f636f067f89cc14862c');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `otp` int(11) NOT NULL DEFAULT 0,
  `verified` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `phone`, `country_id`, `state_id`, `city_id`, `address`, `password`, `profile`, `date`, `time`, `code`, `status`, `otp`, `verified`) VALUES
(1, 'Yazdan Shaikh', 'yazdanshaikh11@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', '$2y$10$72FBluaUO1im0DBWXkHqGu8iWdAl88yEzcQ7ieQDz.tnLZ5deLk9a', NULL, '2024-01-17', '22:16:18', 'c4ca4238a0b923820dcc509a6f75849b', 0, 302161, 0),
(2, 'Shayan Ali', 'shayanalishaikh1410@gamil.com', '03310378148', 1, 1, 2, 'unit 9 mir fazal town', '$2y$10$Nj0wiOYoIRmZ81Cgvx3GCO7xOI.1RYOS2ZkpO29FPyfxMIlHK46vq', NULL, '2024-01-21', '15:40:57', 'c81e728d9d4c2f636f067f89cc14862c', 0, 0, 1),
(4, 'Yazdan Shaikh', 'bilalajmeri2124@gmail.com', '+923493146998', 1, 1, 1, 'karachi sindh pakistan\r\nkarachi sindh pakistan', '$2y$10$q1/IAkFuMC0jrGVpVI0ReuLeuJZd.WoqNm9bXo64emdoNOv93jQoS', NULL, '2024-01-22', '19:23:33', 'a87ff679a2f3e71d9181a67b7542122c', 0, 175311, 0),
(5, 'Shayan Ali', 'Luminarybytes@gmail.com', '03310378148', 1, 1, 2, 'dwefewf', '$2y$10$v40XhjxXl.c/Go5WwU9pqOGzNIGWt.Z0gJItvkkUbzOOX5Aa31aYm', NULL, '2024-01-22', '20:55:57', 'e4da3b7fbbce2345d7772b0674a318d5', 0, 0, 1),
(6, 'Saad', 'saad.ansari3631@gmail.com', '3123854550', 1, 1, 2, 'ajsdgasjdasj', '$2y$10$tE.k5jpka.bnrZw10BKmtua9KK4CjKMiVnHxu.FlymE6UA/WxwywS', NULL, '2024-01-23', '13:17:53', '1679091c5a880faf6fb5e6087eb1b2dc', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `intra` int(11) NOT NULL DEFAULT 1,
  `inter` int(11) NOT NULL DEFAULT 1,
  `profile` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `front` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `back` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `block` int(11) NOT NULL DEFAULT 0,
  `verified` int(11) NOT NULL DEFAULT 1,
  `approval` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) NOT NULL DEFAULT 0,
  `cheque` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `type`, `first_name`, `last_name`, `email`, `phone`, `country_id`, `state_id`, `city_id`, `address`, `cnic`, `password`, `bank`, `intra`, `inter`, `profile`, `front`, `back`, `date`, `time`, `code`, `otp`, `status`, `block`, `verified`, `approval`, `availability`, `cheque`) VALUES
(3, 0, 'Yazdan', 'Shaikh', 'yazdanshaikh@gmail.com', '+923493146997', 1, 1, 1, 'karachi sindh pakistan', '4130224690927', '$2y$10$zx7XKY2RYfuq/cwylwJf8eTfgiR8mprWWNuttg/1iaepEZwvMunTW', '{\"bank_id\":\"undefined\",\"iban\":\"undefined\",\"title\":\"undefined\"}', 1, 1, NULL, '3-1705689032-72054612.jpg', '3-1705689032-20682364.jpg', '2024-01-19', '23:30:31', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 0, 0, 0, 1, 1, 0, NULL),
(10, 0, 'Muhammad Saad', 'Ansari', 'saad.ansari3631@gmail.com', '03160306235', 1, 1, 2, 'Hyderabad, Pakistan', '4130470744916', '$2y$10$qbCdtMDvYQBO1yP8J1aXNeiLo35fNMssbXAjdbtNgT.56065s2yUK', '{\"bank_id\":\"undefined\",\"iban\":\"undefined\",\"title\":\"undefined\"}', 1, 1, NULL, '10-1705999076-18174236.jpg', '10-1705999076-22369954.png', '2024-01-23', '13:37:56', 'd3d9446802a44259755d38e6d163e820', 0, 0, 0, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wanted`
--

CREATE TABLE `wanted` (
  `wanted_id` bigint(20) UNSIGNED NOT NULL,
  `purpose` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `product_id`, `user_id`, `date`, `time`, `code`) VALUES
(9, 3, 2, '2024-01-21', '15:44:54', '45c48cce2e2d7fbdea1afc51c7c6ad26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`commission_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `professional`
--
ALTER TABLE `professional`
  ADD PRIMARY KEY (`professional_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `subscriber`
--
ALTER TABLE `subscriber`
  ADD PRIMARY KEY (`subscriber_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `wanted`
--
ALTER TABLE `wanted`
  ADD PRIMARY KEY (`wanted_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banner_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `commission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `professional`
--
ALTER TABLE `professional`
  MODIFY `professional_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shop_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscriber`
--
ALTER TABLE `subscriber`
  MODIFY `subscriber_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wanted`
--
ALTER TABLE `wanted`
  MODIFY `wanted_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
