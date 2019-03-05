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
(3, 1, 'Meet Ups', ''),
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
(2, 1, 'https://www.sitepoint.com/feed '),
(3, 1, 'https://www.raywenderlich.com/feed'),
(4, 1, 'https://www.blog.jooq.org/feed'),
(5, 1, 'https://www.css-tricks.com/feed'),
(6, 1, 'https://www.codingalpha.com/feed'),
(7, 1, 'https://tympanus.net/codrops/feed'),
(8, 1, 'https://alistapart.com/site/rss'),
(9, 1, 'https://catonmat.net/feed'),
(10, 1, 'https://blog.codepen.io/feed '),
(11, 1, 'https://davidwalsh.name/feed'),
(12, 2, 'https://www.python.org/jobs/feed/rss/'),
(13, 2, 'https://www.seattleindies.org/feed/'),
(14, 2, 'https://aws.amazon.com/new/feed/'),
(15, 2, 'https://feeds.feedburner.com/wtia'),
(16, 4, 'https://www.datasciencecentral.com/activity/log/list?fmt=rss'),
(17, 4, 'http://www.datatau.com/rss'),
(18, 4, 'https://www.reddit.com/r/datascience/.rss'),
(19, 4, 'https://news.google.com/rss/search?cf=all&pz=1&q=data+science&hl=en-US&gl=US&ceid=US:en'),
(20, 4, 'https://www.analyticsvidhya.com/feed/'),
(21, 4, 'http://blog.yhat.com/rss.xml'),
(22, 4, 'http://dataplusscience.com/RSSfeed.xml'),
(23, 4, 'http://blog.nycdatascience.com/feed'),
(24, 4, 'https://www.kdnuggets.com/feed'),
(25, 4, 'https://www.datacamp.com/community/rss.xml'),
(26, 4, 'http://101.datascience.community/feed'),
(27, 5, 'https://www.aitrends.com/feed'),
(28, 5, 'https://www.sciencedaily.com/rss/computers_math/artificial_intelligence.xml'),
(29, 5, 'http://machinelearningmastery.com/blog/feed/'),
(30, 5, 'http://news.mit.edu/rss/topic/artificial-intelligence2'),
(31, 5, 'https://reddit.com/r/artificial/.rss'),
(32, 5, 'http://feeds.feedburner.com/AIInTheNews'),
(33, 5, 'http://mlweekly.com/issues.rss'),
(34, 5, 'https://machinelearnings.co/feed'),
(35, 5, 'https://www.artificial-intelligence.blog/news?format=RSS'),
(36, 5, 'https://news.google.com/rss/search?cf=all&pz=1&q=artificial+intelligence&hl=en-US&gl=US&ceid=US:en'),
(37, 5, 'https://chatbotsmagazine.com/feed'),
(38, 5, 'https://blogs.technet.microsoft.com/machinelearning/feed/'),
(39, 6, 'https://www.darkreading.com/rss/all.xml'),
(40, 6, 'https://schneier.com/blog/atom.xml'),
(41, 6, 'https://threatpost.com/feed'),
(42, 6, 'http://nakedsecurity.sophos.com/feed'),
(43, 6, 'http://blogs.quickheal.com/feed'),
(44, 6, 'http://feeds.feedburner.com/GoogleOnlineSecurityBlog'),
(45, 6, 'https://grahamcluley.com/feed'),
(46, 6, 'https://www.infosecurity-magazine.com/rss/news/'),
(47, 6, 'http://csoonline.com/index.rss'),
(48, 7, 'http://thescoop.seattle.gov/feed/'),
(49, 7, 'http://artbeat.seattle.gov/category/artbeat/feed/'),
(50, 7, 'http://ocr.seattle.gov/feed/'),
(51, 7, 'http://buildingconnections.seattle.gov/feed/'),
(52, 7, 'https://news.seattle.gov/category/news-release/municipal-court/feed/'),
(53, 7, 'http://bottomline.seattle.gov/feed/'),
(54, 7, 'http://education.seattle.gov/feed/'),
(55, 7, 'http://filmandmusic.seattle.gov/feed/'),
(56, 7, 'http://fireline.seattle.gov/feed/'),
(57, 7, 'https://housing.seattle.gov/feed/'),
(58, 7, 'http://welcoming.seattle.gov/category/oira/feed/'),
(59, 7, 'http://techtalk.seattle.gov/feed/'),
(60, 7, 'http://frontporch.seattle.gov/feed/'),
(61, 7, 'http://parkways.seattle.gov/feed/'),
(62, 7, 'http://spdblotter.seattle.gov/feed/'),
(63, 7, 'http://greenspace.seattle.gov/feed/'),
(64, 7, 'http://sdotblog.seattle.gov/feed/'),
(65, 8, 'https://agr.wa.gov/wsdaNewsRss.xml'),
(66, 8, 'https://mil.wa.gov/blog/news/feed'),
(67, 8, 'http://dfi.wa.gov/news/press-releases.xml'),
(68, 8, 'http://dfi.wa.gov/news/consumer-alerts.xml'),
(69, 8, 'http://www.wsgc.wa.gov/rss.xml'),
(70, 8, 'http://www.governor.wa.gov/rss/news'),
(71, 8, 'http://wainsurance.blogspot.com/feeds/posts/default'),
(72, 8, 'http://www.ltgov.wa.gov/feed/'),
(73, 8, 'https://www.pdc.wa.gov/engage/news/rss.xml'),
(74, 8, 'http://www.housedemocrats.wa.gov/news/feed/rss/'),
(75, 8, 'http://houserepublicans.wa.gov/feed/'),
(76, 8, 'http://www.dva.wa.gov/rss.xml'),
(77, 9, 'https://www.usa.gov/rss/updates.xml')
;
