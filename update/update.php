<?php
define('IN_MEDIA',true);
include('../includes/config.php');
include('../includes/functions.php');

$mysql_arr = array(
"ALTER TABLE `media_album` ADD `album_name_ascii` VARCHAR( 255 ) NOT NULL AFTER `album_name` ;",
"ALTER TABLE `media_config` CHANGE `config_value` `config_value` TEXT NOT NULL",
"CREATE TABLE `media_counter` (
  `ip` varchar(15) NOT NULL default '',
  `sid` varchar(32) NOT NULL default '',
  `time` varchar(12) NOT NULL default '0'
) ENGINE=MyISAM;",
"ALTER TABLE `media_data` ADD `m_poster` VARCHAR( 5 ) NOT NULL AFTER `m_url` ;",
"ALTER TABLE `media_data` ADD `m_viewed_month` INT( 10 ) AFTER `m_viewed` ;",
"ALTER TABLE `media_data` ADD `m_downloaded_month` INT( 10 ) AFTER `m_downloaded` ;",

"ALTER TABLE `media_singer` ADD `singer_name_ascii` VARCHAR( 255 ) NOT NULL AFTER `singer_name` ;",
"ALTER TABLE `media_singer` ADD `singer_info` TEXT NOT NULL ,
ADD `singer_type` VARCHAR( 1 ) NOT NULL ;",
"CREATE TABLE `media_online` (
  `timestamp` varchar(15) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `sid` varchar(32) NOT NULL default '',
  KEY `timestamp` (`timestamp`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM;",
"CREATE TABLE `media_user` (
  `user_id` tinyint(5) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `user_password` varchar(50) NOT NULL default '',
  `user_new_password` varchar(15) NOT NULL default '',
  `user_email` varchar(100) default NULL,
  `user_sex` char(1) NOT NULL default '0',
  `user_regdate` varchar(12) NOT NULL default '',
  `user_level` tinyint(1) default NULL,
  `user_playlist_id` varchar(20) NOT NULL default '',
  `user_online` tinyint(1) NOT NULL default '0',
  `user_ip` varchar(15) NOT NULL default '',
  `user_identifier` varchar(32) default NULL,
  `user_timeout` varchar(12) default NULL,
  `user_lastvisit` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;",
"INSERT INTO `media_user` (`user_id`, `user_name`, `user_password`, `user_new_password`, `user_email`, `user_sex`, `user_regdate`, `user_level`, `user_playlist_id`, `user_online`, `user_ip`, `user_identifier`, `user_timeout`, `user_lastvisit`) VALUES 
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'redphoenix289@yahoo.com', '2', '2006-12-20', 3, 'nI8RG0eJt3pilGZ8HFjy', 0, '', '', '', '1168172386');",
"INSERT INTO `media_config` (`config_name`, `config_value`) VALUES 
('total_visit', '100'),
('announcement', ''),
('web_title', 'XtreMedia'),
('web_url', ''),
('server_url', ''),
('server_folder', ''),
('current_month',1),
('web_email','admin@admin.com'),
('download_salt','16-06-89');",
"UPDATE media_config SET config_value = 'funnycolors' WHERE config_name = 'default_tpl'",
"UPDATE media_singer SET singer_type = 1",
"UPDATE media_data SET m_poster = 1",
"CREATE TABLE `media_ads` (
  `ads_id` int(2) NOT NULL auto_increment,
  `ads_web` varchar(255) NOT NULL default '',
  `ads_url` varchar(255) NOT NULL default '',
  `ads_img` varchar(255) NOT NULL default '',
  `ads_count` int(5) NOT NULL default '0',
  PRIMARY KEY  (`ads_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;",
"CREATE TABLE `media_gift` (
  `gift_id` varchar(20) NOT NULL default '',
  `gift_media_id` int(5) NOT NULL default '0',
  `gift_sender_id` int(5) NOT NULL default '0',
  `gift_sender_name` varchar(100) NOT NULL default '',
  `gift_sender_email` varchar(100) NOT NULL default '',
  `gift_recip_name` varchar(100) NOT NULL default '',
  `gift_recip_email` varchar(100) NOT NULL default '',
  `gift_message` text NOT NULL,
  `gift_time` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`gift_id`)
) ENGINE=MyISAM;",
"CREATE TABLE `media_playlist` (
  `playlist_id` varchar(20) NOT NULL default '',
  `playlist_contents` varchar(255) NOT NULL default ''
) ENGINE=MyISAM;",

);
foreach ($mysql_arr as $vl) {
	$mysql->query($vl);
}
echo "UPDATE COMPLETE !!!";
?>