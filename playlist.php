<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");

if ($_POST['reloadPlaylist'] && $isLoggedIn) {
	$add_id = (int)$add_id;
	$remove_id = (int)$remove_id;
	$playlist_id = m_get_data('USER',$_SESSION['user_id'],'user_playlist_id');
	if ($add_id || $remove_id) {
		$q = $mysql->query("SELECT playlist_contents FROM ".$tb_prefix."playlist WHERE playlist_id = '".$playlist_id."'");
		if (!$mysql->num_rows($q)) {
			if ($add_id) {
				$cached['playlist'] = $add_id;
				$mysql->query("INSERT INTO ".$tb_prefix."playlist (playlist_id,playlist_contents) VALUES ('".$playlist_id."','".$add_id."')");
			}
		}
		else {
			$r = $mysql->fetch_array($q);
			$playlist = $r['playlist_contents'];
			if ($remove_id) {
				if ($playlist === $remove_id) {
					$mysql->query("DELETE FROM ".$tb_prefix."playlist WHERE playlist_contents = '".$remove_id."' AND playlist_id = '".$playlist_id."'");
					$cached['playlist'] = '';
				}
				else {
					$z = split(',',$playlist);
					
					if (in_array($remove_id,$z)) {
						unset($z[array_search($remove_id,$z)]);
						$str = implode(',',$z);
						if (!$str) {
							$mysql->query("DELETE FROM ".$tb_prefix."playlist WHERE playlist_contents = '".$remove_id."' AND playlist_id = '".$playlist_id."'");
							$cached['playlist'] = '';
						}
						else {
							$mysql->query("UPDATE ".$tb_prefix."playlist SET playlist_contents = '".$str."' WHERE playlist_id = '".$playlist_id."'");
							$cached['playlist'] = $str;
						}
					}
				}
			}
			elseif ($add_id) {
				$z = split(',',$playlist);
				if (!in_array($add_id,$z)) {
					$mysql->query("UPDATE ".$tb_prefix."playlist SET playlist_contents = CONCAT('".$add_id.",',playlist_contents)");
					$cached['playlist'] = $playlist.','.$add_id;
				}
				else $cached['playlist'] = $playlist;
			}
		}
	}
	$html = box_playlist(1);
	$tpl->parse_tpl($html);
	exit();
}
if ($value[0] == 'Play_Playlist') {
	$playlist_id = m_get_data('USER',$_SESSION['user_id'],'user_playlist_id');
	$q = $mysql->query("SELECT playlist_id FROM ".$tb_prefix."playlist WHERE playlist_id = '".$playlist_id."'");
	if ($mysql->num_rows($q)) {
		play_playlist($playlist_id);
	}
	else echo("<b><center>Playlist rỗng.</center></b>");
	exit();
}
?>