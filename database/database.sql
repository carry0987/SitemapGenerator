SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/* create article */
CREATE TABLE IF NOT EXISTS article (
  id int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  freq varchar(10) NOT NULL,
  priority char(3) NOT NULL DEFAULT '0.5',
  lastmod datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
