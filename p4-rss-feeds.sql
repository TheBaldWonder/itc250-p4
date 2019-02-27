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
  `Title` varchar(255) DEFAULT '',
  `Description` text,
  PRIMARY KEY (`FeedID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `p4_feeds_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `p4_categories` (`CategoryID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert rss feeds
INSERT INTO `p4_feeds` (`FeedID`, `CategoryID`, `Title`, `Description`) VALUES
(1, 1, 'Computer Training', ''),
(2, 1, 'Job Opportunities', ''),
(4, 2, 'Data Science', ''),
(5, 2, 'Artificial Intelligence', ''),
(6, 2, 'Cyber Security', ''),
(7, 3, 'Seattle', ''),
(8, 3, 'Washington', ''),
(9, 3, 'United States', '')
;

DROP TABLE IF EXISTS `p4_feedURLs`;
CREATE TABLE `p4_feedURLs` (
  `UrlID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FeedID` int(10) unsigned DEFAULT '0',
  `FeedURL` varchar(255) DEFAULT '',
  PRIMARY KEY (`UrlID`),
  KEY `FeedID` (`FeedID`),
  CONSTRAINT `p4_feedURLs_ibfk_1` FOREIGN KEY (`FeedID`) REFERENCES `p4_feeds` (`FeedID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert feed URLs
INSERT INTO `p4_feedURLs` (`UrlID`, `FeedID`, `FeedURL`) VALUES
(1, 1, 'https://www.thecrazyprogrammer.com/feed'),
(2, 2, 'https://www.python.org/jobs/feed/rss/'),
(4, 4, 'https://www.datasciencecentral.com/activity/log/list?fmt=rss'),
(5, 5, 'https://www.aitrends.com/feed'),
(6, 6, 'https://www.darkreading.com/rss/all.xml'),
(7, 7, 'http://thescoop.seattle.gov/feed/'),
(8, 8, 'https://agr.wa.gov/wsdaNewsRss.xml'),
(9, 9, 'https://www.usa.gov/rss/updates.xml')
;
