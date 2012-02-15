--
-- openSGrame::mail Module
--


--
-- Table structure for table `mail_template`
--

CREATE TABLE IF NOT EXISTS `mail_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realm` varchar(64) NOT NULL,
  `language` varchar(12) NOT NULL,
  `subject` varchar(256) DEFAULT NULL,
  `body` longtext,
  `replacements` text,
  `ci` int(11) unsigned NOT NULL,
  `cd` datetime NOT NULL,
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `realm` (`realm`),
  KEY `language` (`language`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

