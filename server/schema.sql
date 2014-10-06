DROP SCHEMA IF EXISTS `24hhh_db`;
CREATE SCHEMA `24hhh_db`;
USE `24hhh_db`;

# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.38)
# Database: horseshoehell
# Generation Time: 2014-10-06 21:39:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table rating
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rating`;

CREATE TABLE `rating` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL DEFAULT '',
  `points` int(11) NOT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;

INSERT INTO `rating` (`id`, `name`, `points`, `updated`)
VALUES
  (2,'5.1',10,NULL),
  (3,'5.2',20,NULL),
  (4,'5.3',30,NULL),
  (5,'5.4',40,NULL),
  (6,'5.5',50,NULL),
  (7,'5.6',60,NULL),
  (8,'5.7',70,NULL),
  (9,'5.7+',80,NULL),
  (10,'5.8',90,NULL),
  (11,'5.8+',100,NULL),
  (12,'5.9-',110,NULL),
  (13,'5.9',120,NULL),
  (14,'5.9+',130,NULL),
  (15,'5.10a',140,NULL),
  (16,'5.10b',160,NULL),
  (17,'5.10c',180,NULL),
  (18,'5.10d',200,NULL),
  (19,'5.11a',220,NULL),
  (20,'5.11b',250,NULL),
  (21,'5.11c',280,NULL),
  (22,'5.11d',310,NULL),
  (23,'5.12a',350,NULL),
  (24,'5.12b',390,NULL),
  (25,'5.12c',440,NULL),
  (26,'5.12d',490,NULL),
  (27,'5.13a',550,NULL),
  (28,'5.13b',610,NULL),
  (29,'5.13c',670,NULL),
  (30,'5.13d',730,NULL),
  (31,'5.14a',790,NULL),
  (32,'5.14b',860,NULL),
  (33,'5.14c',930,NULL),
  (34,'5.14d',1000,NULL),
  (35,'5.15a',1070,NULL),
  (36,'5.15b',1150,NULL),
  (37,'5.15c',1230,NULL),
  (38,'5.15d',1310,NULL);

/*!40000 ALTER TABLE `rating` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table route
# ------------------------------------------------------------

DROP TABLE IF EXISTS `route`;

CREATE TABLE `route` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(11) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `wall` int(4) NOT NULL,
  `rating` int(4) NOT NULL,
  `trad` tinyint(1) NOT NULL,
  `height` int(4) NOT NULL,
  `safety_rating` enum('','PG','R','X') NOT NULL,
  `first_year` int(4) NOT NULL,
  `description` text,
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wall`;

CREATE TABLE `wall` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `side` enum('west','east') NOT NULL DEFAULT 'west',
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `wall` WRITE;
/*!40000 ALTER TABLE `wall` DISABLE KEYS */;

INSERT INTO `wall` (`id`, `name`, `side`, `created`, `updated`)
VALUES
  (1,'Crackhouse Alley','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (2,'Confederate Cracks','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (3,'Ren and Stimpy','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (4,'Prophecy Wall','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (5,'Titanic Boulder','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (6,'Doomsday Wall','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (7,'North Forty - Cooridor Area','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (8,'North Forty - Lavender Eye Area','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (9,'North Forty - Circus Wall','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (10,'North Forty - Crimp Scampi Area','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (11,'North Forty - Groovy Area','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (12,'North Forty - Kindergarten Boulder','west','2014-10-02 21:36:45','2014-10-02 21:37:01'),
  (13,'North Forty - Land Beyond','west','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (14,'Goat Cave','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (15,'Mullet Buttress','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (16,'Land of the Lost','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (17,'Middle East','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (18,'Magoo Rock','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (19,'Roman Wall','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (20,'Cliffs of Insanity','east','2014-10-02 21:36:45','2014-10-02 21:36:45'),
  (21,'Far East','east','2014-10-02 21:36:45','2014-10-02 21:36:45');

/*!40000 ALTER TABLE `wall` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
