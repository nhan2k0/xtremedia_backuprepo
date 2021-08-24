<?php
define('IN_MEDIA',true);
include('../includes/config.php');
mysql_query("ALTER TABLE `".$tb_prefix."data` CHANGE `m_viewed_month` `m_viewed_month` INT( 10 ) NOT NULL ,
CHANGE `m_downloaded_month` `m_downloaded_month` INT( 10 ) NOT NULL");
mysql_query("UPDATE ".$tb_prefix."data SET m_viewed_month = m_viewed, m_downloaded_month = m_downloaded");
mysql_query("ALTER TABLE `media_user` CHANGE `user_id` `user_id` INT( 5 ) NOT NULL AUTO_INCREMENT");

echo "UPDATE COMPLETE !!!";
?>