SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/* create article */
CREATE TABLE IF NOT EXISTS `article` (
  `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `freq` varchar(10) NOT NULL,
  `priority` char(3) NOT NULL DEFAULT '0.5',
  `lastmod` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `article` (`id`, `freq`, `priority`, `lastmod`) VALUES
(1, 'daily', '0.5', '2020-01-01 00:00:00'),
(2, 'daily', '0.5', '2020-01-01 00:00:00'),
(3, 'daily', '0.5', '2020-01-01 00:00:00'),
(4, 'daily', '0.5', '2020-01-01 00:00:00'),
(5, 'daily', '0.5', '2020-01-01 00:00:00'),
(6, 'daily', '0.5', '2020-01-01 00:00:00'),
(7, 'daily', '0.5', '2020-01-01 00:00:00'),
(8, 'daily', '0.5', '2020-01-01 00:00:00'),
(9, 'daily', '0.5', '2020-01-01 00:00:00'),
(10, 'daily', '0.5', '2020-01-01 00:00:00'),
(11, 'daily', '0.5', '2020-01-01 00:00:00'),
(12, 'daily', '0.5', '2020-01-01 00:00:00'),
(13, 'daily', '0.5', '2020-01-01 00:00:00'),
(14, 'daily', '0.5', '2020-01-01 00:00:00'),
(15, 'daily', '0.5', '2020-01-01 00:00:00'),
(16, 'daily', '0.5', '2020-01-01 00:00:00'),
(17, 'daily', '0.5', '2020-01-01 00:00:00'),
(18, 'daily', '0.5', '2020-01-01 00:00:00');
