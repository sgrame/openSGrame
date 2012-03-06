-- ----------------------------------------------------- --
-- Mail module                                           --
--                                                       --
-- Provides the mail templating engine                   --
-- ----------------------------------------------------- --


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------


--
-- Table structure for table `mail_template`
--

CREATE TABLE IF NOT EXISTS `mail_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realm` varchar(64) NOT NULL,
  `language` varchar(12) NOT NULL,
  `description` varchar(256) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mail_template`
--

INSERT INTO `mail_template` 
    (`realm`, `language`, `description`, `subject`, `body`, `replacements`, `ci`, `cd`, `cr`) 
VALUES
    ('user:new_invited', 'en', 'Welcome (new user created by an administrator)', 'An administrator created an account for you at {site:name}', '{user:name},\r\n\r\nA site administrator at {site:name} has created an account for you. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0, NOW(), NULL),
    ('user:new_pending', 'en', 'Welcome (signup waiting for approval)', 'Account details for {user:name} at {site:name} (pending admin approval)', '{user:name},\r\n\r\nThank you for registering at {site:name}. \r\n\r\nYour application for an account is currently pending approval. Once it has been approved, you will receive another e-mail containing information about how to log in, set your password, and other details.\r\n\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username', 0,  NOW(), NULL),
    ('user:new_welcome', 'en', 'Welcome (no approval required)', 'Account details for {user:name} at {site:name}', '{user:name},\r\n\r\nThank you for registering at {site:name}. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password\r\n\r\n--  {site:name} team', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0,  NOW(), NULL),
    ('user:new_activated', 'en', 'Account activation', 'Account details for {user:name} at {site:name} (approved)', '{user:name},\r\n\r\nYour account at {site:name} has been activated.\r\n\r\nYou may now log in by clicking this link or copying and pasting it into your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {url:login} in the future using:\r\n\r\nusername: {user:name}\r\npassword: Your password', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL', 0,  NOW(), NULL),
    ('user:password_recovery', 'en', 'Password recovery', 'Replacement login information for {user:name} at {site:name}', '{user:name},\r\n\r\nA request to reset the password for your account has been made at {site:name}.\r\n\r\nYou may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{url:one-time-login}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password. It expires after one day and nothing will happen if it''s not used.\r\n\r\n--  {site:name} team \r\n    {url:site}', 'site:name|Website name\r\nuser:name|Username\r\nurl:one-time-login|One time login URL\r\nurl:login|Login URL\r\nurl:site|Website URL', 0, NOW(), NULL)
;


-- --------------------------------------------------------
