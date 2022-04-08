# CocoaMySQL dump
# Version 0.7b5
# http://cocoamysql.sourceforge.net
#
# Host: 127.0.0.1 (MySQL 5.0.37)
# Database: glueynotes
# Generation Time: 2007-07-21 11:51:20 -0700
# ************************************************************

# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_name` varchar(10) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `accept_terms` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

