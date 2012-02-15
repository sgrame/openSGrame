--
-- openSGrame::core
--

-- Table structure for table `variable`
CREATE TABLE IF NOT EXISTS `variable` (
    `name` varchar(128) NOT NULL COMMENT 'The variable key name',
    `value` longblob NOT NULL COMMENT 'The variable value (serialized data)',
    PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- Dumping data for table `variable`
INSERT INTO `variable` 
    (`name`, `value`) 
VALUES
    ('site_name', 0x733a31303a226f70656e534772616d65223b),
    ('site_email', 0x733a31353a22696e666f40736772616d652e636f6d223b);
