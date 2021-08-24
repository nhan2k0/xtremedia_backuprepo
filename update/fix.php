<?php
define('IN_MEDIA',true);
include('../includes/config.php');
include('../includes/functions.php');

$q = $mysql->query("SELECT album_id,album_name FROM media_album");
while ($r = $mysql->fetch_array($q)) {
	$mysql->query("UPDATE media_album SET album_name_ascii = '".addslashes(strtolower(utf8_to_ascii($r['album_name'])))."' WHERE album_id = '".$r['album_id']."'");
}

$q = $mysql->query("SELECT singer_id,singer_name FROM media_singer");
while ($r = $mysql->fetch_array($q)) {
	$mysql->query("UPDATE media_singer SET singer_name_ascii = '".addslashes(strtolower(utf8_to_ascii($r['singer_name'])))."' WHERE singer_id = '".$r['singer_id']."'");
}
/*
$q = $mysql->query("SELECT m_title FROM media_data");
while ($r = $mysql->fetch_array($q)) {
	$mysql->query("UPDATE media_data SET m_title_ascii = '".strtolower(utf8_to_ascii($r['m_title']))."' WHERE m_title = '".$r['m_title']."'");
}
/*
$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user");
while ($r = $mysql->fetch_array($q)) {
	echo $playlist_id = m_random_str(20)."<br>";
	$mysql->query("UPDATE ".$tb_prefix."user SET user_playlist_id = '".$playlist_id."' WHERE user_id = '".$r['user_id']."'");
}
*/
echo "FIX COMPLETE !!!";
?>