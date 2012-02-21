--
-- openSGrame::core
--

-- Table structure for table `log`
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message` text,
  `uri` varchar(256) DEFAULT NULL,
  `module` varchar(64) DEFAULT NULL,
  `controller` varchar(64) DEFAULT NULL,
  `action` varchar(64) DEFAULT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`),
  KEY `cd` (`cd`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- Table structure for table `session`
CREATE TABLE IF NOT EXISTS `session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- Table structure for table `variable`
CREATE TABLE IF NOT EXISTS `variable` (
    `name` varchar(128) NOT NULL COMMENT 'The variable key name',
    `value` longblob NOT NULL COMMENT 'The variable value (serialized data)',
    PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Dumping data for table `variable`
INSERT INTO `variable` 
    (`name`, `value`) 
VALUES
    ('site_name', 0x733a31303a226f70656e534772616d65223b),
    ('site_email', 0x733a31353a22696e666f40736772616d652e636f6d223b),
    ('site_languages', 0x613a323a7b693a303b733a323a22656e223b693a313b733a323a226e6c223b7d),
    ('site_languages_default', 0x733a323a22656e223b);
