<?php
/*====================================*\
|| ################################## ||
|| # XtreMedia                   	# ||
|| # ------------------------------	# ||
|| # Coded by REDPHOENIX89			# ||
|| # Y!m : RedPhoenix89				# ||
|| ################################## ||
\*====================================*/
if (!defined('IN_MEDIA')) die("Hacking attempt");
$tpl =& new Template;
$main = $tpl->get_tpl('download');

$isLoggedIn = m_checkLogin();




if (is_numeric($value[1]) && $value[2]) {
	$r = $mysql->fetch_array($mysql->query("SELECT m_title, m_url, m_is_local, m_public, m_more_url FROM ".$tb_prefix."data WHERE m_id = '".$value[1]."'"));
	
	if (!$isLoggedIn && m_get_config('must_login_to_download') && !$r['m_public'] ) {
		$main = $tpl->unset_block($main,array('more_links'));
		$main = $tpl->assign_vars($main,
			array(
				'song.TITLE'	=>	$r['m_title'],
				'INFO.DOWNLOAD'	=>	'Bạn cần phải đăng nhập để có thể Download',
				'LINK.DOWNLOAD'	=>	'#No Link',
				'IMG.DOWNLOAD'	=>	'stop.png',
			)
		);
		$tpl->parse_tpl($main);
		exit();
	}
	if ($value[2] == m_encode($value[1]) || $r['m_public'] ) {
		if ($r) {
			$mysql->query("UPDATE ".$tb_prefix."data SET m_downloaded = m_downloaded + 1, m_downloaded_month = m_downloaded_month + 1 WHERE m_id = '".$value[1]."'");
			$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
			//$url_t = substr($url,0,30)."...";

			$more_url = ($r['m_more_url'])?m_text_tidy($r['m_more_url']):'';
			if ($more_url) $main = $tpl->assign_vars($main,array
				(
					'MORE.LINKS'	=>	m_emotions_replace($more_url),
				)
			);
			else $main = $tpl->unset_block($main,array('more_links'));

			$main = $tpl->assign_vars($main,
				array(
					'song.TITLE'	=>	$r['m_title'],
					'INFO.DOWNLOAD'	=>	' Link Download Here',
					'LINK.DOWNLOAD'	=>	$url,
					'IMG.DOWNLOAD'	=>	'down.png',
				)
			);
			
		}
	}
	else { die('www'); }
}
else { die('www'); }

// -------------------

$tpl->parse_tpl($main);

?>