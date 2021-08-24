<?php
define('IN_MEDIA',true);
include('../includes/config.php');

$mysql->query("CREATE TABLE `".$tb_prefix."manage` (
  `manage_type` varchar(25) NOT NULL default '',
  `manage_user` varchar(5) NOT NULL default '',
  `manage_media` varchar(5) NOT NULL default '',
  `manage_timeout` varchar(12) NOT NULL default ''
) ENGINE=MyISAM;");

$mysql->query("INSERT INTO `".$tb_prefix."config` (`config_name`, `config_value`) VALUES ('intro_song', ''),('intro_song_is_local', '0');");
$mysql->query("ALTER TABLE `media_user` CHANGE `user_level` `user_level` TINYINT( 1 ) NOT NULL DEFAULT '1'");
if (!$mysql->num_rows($mysql->query("SELECT config_value FROM ".$tb_prefix."config "))) {
	$mysql->query("INSERT INTO `".$tb_prefix."config` (`config_name`, `config_value`) VALUES ('mod_permission', '0');");
}
echo "UPDATE COMPLETE !!!";
?>