--
-- openSGrame::user Module
--


-- START TABLE 
CREATE TABLE IF NOT EXISTS `START` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`ci` int(11) unsigned NOT NULL DEFAULT 0,
	`cd` datetime DEFAULT '0000-00-00 00:00:00',
	`cr` int(11) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- START OWNER
CREATE TABLE IF NOT EXISTS `START_OWNER` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`owner_id` int(11) unsigned NOT NULL DEFAULT 0,
	`date_created` datetime DEFAULT '0000-00-00 00:00:00',
	`ci` int(11) unsigned NOT NULL DEFAULT 0,
	`cd` datetime DEFAULT '0000-00-00 00:00:00',
	`cr` int(11) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


-- Table structure for table `user`
CREATE TABLE IF NOT EXISTS `user` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(128) NOT NULL,
	`email` varchar(256) DEFAULT NULL,
	`password` varchar(32) NOT NULL,
	`password_salt` varchar(32) NOT NULL,
	`locked` tinyint(1) unsigned NOT NULL DEFAULT 0,
	`blocked` tinyint(1) unsigned NOT NULL DEFAULT 0,
	`ci` int(11) unsigned NOT NULL DEFAULT 0,
	`cd` datetime DEFAULT '0000-00-00 00:00:00',
	`cr` int(11) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `username` (`username`),
	KEY `locked` (`locked`),
	KEY `blocked` (`blocked`),
	KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- Create the system user
INSERT INTO `user` 
	(`id`, `username`, `email`, `password`, `password_salt`, `locked`, `blocked`, `ci`, `cd`, `cr`) 
VALUES
	('1', 'system', NULL, 'fsgqgfwf dshgdfvqwv', 'xbghgfdbvgwgr', 0, 0, 0, '1974-07-28 12:00:00', NULL)
;
UPDATE `user` SET id = 0 WHERE id = 1;

-- Create the admin user
INSERT INTO `user` 
	(`id`, `username`, `email`, `password`, `password_salt`, `locked`, `blocked`, `ci`, `cd`, `cr`) 
VALUES
	('1', 'admin', 'peter@serial-graphics.be', 'b16c6e8fc7bb03b168ea9502b26eca91', 'bwqJVt3KpsJV9DLWHV7hMNsYCo84V097', 0, 0, 0, '1974-07-28 12:00:00', NULL)
;


-- --------------------------------------------------------


-- Table structure for table `user_group`
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

-- Dumping data for table `user_group`
INSERT INTO `user_group` 
	(`id`, `name`, `ci`, `cd`, `cr`) 
VALUES
	(1, 'system', 0, '1974-07-28 12:00:00', NULL);


-- --------------------------------------------------------


-- Table structure for table `user_groups`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- Dumping data for table `user_groups`
INSERT INTO `user_groups` 
	(`id`, `user_id`, `group_id`, `ci`, `cd`, `cr`) 
VALUES
	(1, 1, 1, 0, '1974-07-28 12:00:00', NULL);


-- --------------------------------------------------------


-- Table structure for table `user_group_roles`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


-- Table structure for table `user_permission`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


-- Table structure for table `user_role`
CREATE TABLE IF NOT EXISTS `user_role` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(128) NOT NULL,
	`ci` int(11) unsigned NOT NULL DEFAULT '0',
	`cd` datetime DEFAULT '0000-00-00 00:00:00',
	`cr` int(11) unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `cr` (`cr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- Dumping data for table `user_role`
INSERT INTO `user_role` 
	(`id`, `name`, `ci`, `cd`, `cr`) 
VALUES
	(1, 'admin', 0, '1974-07-28 12:00:00', NULL);


-- --------------------------------------------------------


-- Table structure for table `user_roles`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- Dumping data for table `user_roles`
INSERT INTO `user_roles` 
	(`id`, `user_id`, `role_id`, `ci`, `cd`, `cr`) 
VALUES
	(1, 1, 1, 0, '1974-07-28 12:00:00', NULL);


-- --------------------------------------------------------


-- Table structure for table `user_role_permissions`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


-- Table structure for table `user_action`
CREATE TABLE IF NOT EXISTS `user_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `action` varchar(64) NOT NULL,
  `token` varchar(32) NOT NULL,
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_expire` datetime DEFAULT NULL,
  `ci` int(11) unsigned NOT NULL,
  `cd` datetime NOT NULL,
  `cr` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `token` (`token`),
  KEY `used` (`used`),
  KEY `cr` (`cr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------


-- Default user mail templates
INSERT INTO `mail_template` 
    (`realm`, `language`, `description`, `subject`, `body`, `replacements`, `ci`, `cd`, `cr`) 
VALUES
    ('user_new_invited', 'en', 'Welcome (new user created by an administrator)', 'An administrator created an account for you at {site:name}', '{user:name},\r\n\r\nA site administrator at {site:name} has created an account for you. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0, '1974-07-28 12:00:00', NULL),
    ('user_new_pending', 'en', 'Welcome (signup waiting for approval)', 'Account details for {user:name} at {site:name} (pending admin approval)', '{user:name},\r\n\r\nThank you for registering at {site:name}. \r\n\r\nYour application for an account is currently pending approval. Once it has been approved, you will receive another e-mail containing information about how to log in, set your password, and other details.\r\n\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username', 0, '1974-07-28 12:00:00', NULL),
    ('user_new_welcome', 'en', 'Welcome (no approval required)', 'Account details for {user:name} at {site:name}', '{user:name},\r\n\r\nThank you for registering at {site:name}. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0, '1974-07-28 12:00:00', NULL),
    ('user_new_activated', 'en', 'Account activation', 'Account details for {user:name} at {site:name} (approved)', '{user:name},\r\n\r\nYour account at {site:name} has been activated.\r\n\r\nYou may now log in by clicking this link or copying and pasting it into your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0, '1974-07-28 12:00:00', NULL),
    ('user_password_recovery', 'en', 'Password recovery', 'Replacement login information for {user:name} at {site:name}', '{user:name},\r\n\r\nA request to reset the password for your account has been made at {site:name}.\r\n\r\nYou may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password. It expires after one day and nothing will happen if it''s not used.\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0, '1974-07-28 12:00:00', NULL)
;

