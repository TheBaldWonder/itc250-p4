SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `p4_categories`;
CREATE TABLE `p4_categories` (
  `CategoryID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Category` varchar(255) DEFAULT '',
  PRIMARY KEY (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `p4_categories` (`CategoryID`, `Category`) VALUES
(1,	'Resources For Developers'),
(2,	'News For Developers'),
(3,	'Government');

DROP TABLE IF EXISTS `p4_feeds`;
CREATE TABLE `p4_feeds` (
  `FeedID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CategoryID` int(10) unsigned DEFAULT '0',
  `FeedURL` varchar(255) DEFAULT '',
  `Title` varchar(255) DEFAULT '',
  `Description` text,
  PRIMARY KEY (`FeedID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `p4_feeds_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `p4_categories` (`CategoryID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert rss feeds
-- INSERT INTO `p4_feeds` (`FeedID`, `CategoryID`, `FeedURL`, `Title`, `Description`) VALUES
