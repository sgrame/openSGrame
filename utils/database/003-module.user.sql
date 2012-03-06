-- ----------------------------------------------------- --
-- User module                                           --
--                                                       --
-- Provides users, groups, roles and permissions.        --
-- ----------------------------------------------------- --


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------



--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(32) NOT NULL,
  `password_salt` varchar(32) NOT NULL,
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `blocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `picture_id` int(11) DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `locked` (`locked`),
  KEY `blocked` (`blocked`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` 
    (`id`, `username`, `email`, `password`, `password_salt`, `locked`, `blocked`, `picture_id`, `last_access`, `last_login`, `created`, `ci`, `cd`, `cr`) 
VALUES
    (0, 'system', 'system@opensgrame.org', 'fsgqgfwf dshgdfvqwv', 'xbghgfdbvgwgr', 0, 0, NULL, NULL, NULL,  NOW(), 1,  NOW(), NULL),
    (1, 'admin', 'peter@serial-graphics.be', 'fdbb9d89b8ae2c385cbb375b72c0c929', '5Jd1fuKKMExJuGdjLCAHL50EGhJ0fHem', 0, 0, NULL, NULL, NULL,  NOW(), 1,  NOW(), NULL)
;

-- --------------------------------------------------------

--
-- Table structure for table `user_action`
--

CREATE TABLE IF NOT EXISTS `user_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `action` varchar(64) NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_expire` datetime DEFAULT NULL,
  `ci` int(11) unsigned NOT NULL,
  `cd` datetime NOT NULL,
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `token` (`uuid`),
  KEY `used` (`used`),
  KEY `cr` (`cr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` 
    (`id`, `name`, `ci`, `cd`, `cr`) 
VALUES
    (1, 'system', 0,  NOW(), NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` 
    (`id`, `user_id`, `group_id`, `ci`, `cd`, `cr`) 
VALUES
    (1, 1, 1, 0,  NOW(), NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_roles`
--

CREATE TABLE IF NOT EXISTS `user_group_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `module` varchar(128) DEFAULT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `module` (`module`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` 
    (`name`, `module`, `ci`, `cd`, `cr`) 
VALUES
    ('administer system configuration', 'system:admin', 0,  NOW(), NULL),
    ('view system reports', 'system:admin', 0,  NOW(), NULL),
    ('edit profile', 'user', 0,  NOW(), NULL),
    ('cancel own profile', 'user', 0,  NOW(), NULL),
    ('register', 'user', 0,  NOW(), NULL),
    ('administer all users', 'user:admin', 0,  NOW(), NULL),
    ('administer users of same group', 'user:admin', 0,  NOW(), NULL),
    ('administer users of the system', 'user:admin', 0,  NOW(), NULL),
    ('administer roles', 'user:admin', 0,  NOW(), NULL),
    ('administer groups', 'user:admin', 0, NOW(), NULL),
    ('administer permissions', 'user:admin', 0, NOW(), NULL),
    ('administer configuration', 'user:admin', 0, NOW(), NULL),
    ('administer users', 'user:admin', 0, NOW(), NULL),
    ('administer', 'system:admin', 0, NOW(), NULL)
;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `locked` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Locked roles can''t be edited trough the UI',
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `cr` (`cr`),
  KEY `locked` (`locked`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` 
    (`id`, `name`, `locked`, `ci`, `cd`, `cr`) 
VALUES
    (1, 'everyone', 1, 0, NOW(), NULL),
    (2, 'registered', 1, 0, NOW(), NULL),
    (3, 'admin', 1, 0, NOW(), NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_role_permissions`
--

CREATE TABLE IF NOT EXISTS `user_role_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  `ci` int(11) unsigned NOT NULL DEFAULT '0',
  `cd` datetime DEFAULT '0000-00-00 00:00:00',
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


