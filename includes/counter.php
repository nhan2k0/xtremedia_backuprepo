<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
function m_counter() {
	global $mysql, $tb_prefix;
	$time = NOW;
	$seconds = 60 * 60;
	$mysql->query("DELETE FROM ".$tb_prefix."counter WHERE time < ".$time." OR sid = ''");
	$q = $mysql->query("SELECT ip FROM ".$tb_prefix."counter WHERE ip='".IP."' AND sid='".SID."' AND time > ".$time);
	$total_visit = m_get_config('total_visit');
	if (!$mysql->num_rows($q)) {
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = config_value + 1 WHERE config_name = 'total_visit'");
		$mysql->query("INSERT INTO ".$tb_prefix."counter VALUES ('".IP."','".SID."','".($time+$seconds)."')");
		$total_visit += 1;
	}
	else $mysql->query("UPDATE ".$tb_prefix."counter SET time = '".($time+$seconds)."' WHERE ip = '".IP."' AND sid = '".SID."'");
	return $total_visit;
}
?>