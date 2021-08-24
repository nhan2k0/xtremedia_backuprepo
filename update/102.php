<?php
define('IN_MEDIA',true);
include('../includes/config.php');

$mysql->query("CREATE TABLE `media_comment` (
  `comment_id` int(5) NOT NULL auto_increment,
  `comment_media_id` int(5) NOT NULL default '0',
  `comment_poster` varchar(5) NOT NULL default '',
  `comment_content` text NOT NULL,
  `comment_time` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;");

$mysql->query("UPDATE media_singer SET singer_type = 2 WHERE singer_type = 3");
$mysql->query("INSERT INTO `media_config` ( `config_name` , `config_value` ) VALUES ('must_login_to_download', '1');");
$mysql->query("INSERT INTO `media_config` ( `config_name` , `config_value` ) VALUES ('must_login_to_play', '1');");
$mysql->query("INSERT INTO `media_config` ( `config_name` , `config_value` ) VALUES ('media_per_page', '25');");
$mysql->query("ALTER TABLE `media_user` ADD `user_hide_info` VARCHAR( 32 ) NOT NULL AFTER `user_sex` ;");

echo "UPDATE COMPLETE !!!";
?>