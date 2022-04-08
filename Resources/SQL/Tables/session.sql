# CocoaMySQL dump
# Version 0.7b5
# http://cocoamysql.sourceforge.net
#
# Host: 127.0.0.1 (MySQL 5.0.37)
# Database: glueynotes
# Generation Time: 2007-07-25 17:43:28 -0700
# ************************************************************

# Dump of table session
# ------------------------------------------------------------

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uuid` char(36) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idx_session_id` (`uuid`),
  UNIQUE KEY `idx_userid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

