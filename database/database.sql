SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/* create article */
CREATE TABLE IF NOT EXISTS article (
  id int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  freq varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  priority float NOT NULL,
  lastmod datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
