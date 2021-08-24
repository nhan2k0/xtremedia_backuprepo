<?php
/*====================================*\
|| ################################## ||
|| # Play in PopUp                 	# ||
|| # ZENE	- CKNL.NET				# ||
|| # ------------------------------	# ||
|| # XtreMedia - RedPhoenix89		# ||
|| ################################## ||
\*====================================*/
define('IN_MEDIA',true);

include('includes/config.php');
include('includes/functions.php');
include('includes/class_template.php');

$tpl =& new Template;

$isLoggedIn = m_checkLogin();

function m_info_p($r,$show_singer = false) {
	global $mysql,$mainURL,$tpl,$tb_prefix;
	$t['info'] = $tpl->get_tpl('play_popup_info');
	$isLoggedIn = m_checkLogin();
/////////////////////////////
///// SHOW RATING
	if ($r['m_rating_total'] =='0') $current_star = 0;
	else $rater_rating = $r['m_rating'] / $r['m_rating_total'];

	// Assign star image
	if ($rater_rating <= 0  ){$star1 = "none"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 0.5){$star1 = "half"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1  ){$star1 = "full"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1.5){$star1 = "full"; $star2 = "half"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2  ){$star1 = "full"; $star2 = "full"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2.5){$star1 = "full"; $star2 = "full"; $star3 = "half"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "half"; $star5 = "none";}
	if ($rater_rating >= 4  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "none";}
	if ($rater_rating >= 4.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "half";}
	if ($rater_rating >= 5  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "full";}

	$rater_stars_img = 	"<img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star1."_s.gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star2."_s.gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star3."_s.gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star4."_s.gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star5."_s.gif\">";
/////////////////////////////
	$html = $tpl->assign_vars($t['info'],
		array(
			'song.TITLE'	=>	$r['m_title'],
			'song.VIEWED'	=>	$r['m_viewed'] + 1,
			'song.DOWNLOADED'	=>	$r['m_downloaded'],
			'song.ID'		=>	$r['m_id'],
			'song.POSTER'		=>	m_get_data('USER',$r['m_poster']),
			'singer.URL'	=>	m_get_config('web_url').'/#Singer,'.$r['m_singer'],
			'singer.NAME'	=>	m_get_data('SINGER',$r['m_singer']),
			'album.URL'		=>	m_get_config('web_url').'/#Album,'.$r['m_album'],
			'cat.URL'		=>	m_get_config('web_url').'/#List,'.$r['m_cat'],
			'cat.NAME'		=>	m_get_data('CAT',$r['m_cat']),
			'user.URL'		=>	m_get_config('web_url').'/#User,'.$r['m_poster'],
			'album.NAME'	=>	m_get_data('ALBUM',$r['m_album']),
			'RATE.STAR'		=>	$rater_stars_img,
		)
	);
	return $html;
}

function m_play_p($r) {
	global $html, $mysql,$tb_prefix,$tpl,$mediaFolder;
	$lyric = ($r['m_lyric'])?m_text_tidy($r['m_lyric']):'';
	$html = $tpl->get_tpl('play_popup');
	$id = $r['m_id'];
	
	$html = $tpl->assign_vars($html,array
		(
			'MEDIA_INFO'	=>	m_info_p($r,1),
		)
	);
	
	$arr = array(
		'type'	=>	1,
		'm_type'	=>	$r['m_type'],
		'd_w'	=>	300,
		'd_h'	=>	68,
		'id'	=>	$id,
	);
	if ($r['m_type'] != 1) {
		$arr['d_w'] = ($r['m_width'])?$r['m_width']:400;
		$arr['d_h'] = ($r['m_height'])?$r['m_height']:360;
	}
	if ($r['m_type'] == 2) {
		$arr['url'] = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
		$t_url = $r['m_url'];
		$ext = explode('.',$t_url);
		$ext = $ext[count($ext)-1];
		$ext = explode('?',$ext);
		$ext = $ext[0];
		if ($ext == 'flv') {
			$arr['url'] = 'flvplayer.swf?autostart=true&showfsbutton=true&file=';
			$arr['url'] .= ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
		}
	}
	
	$html = $tpl->assign_blocks_content($html,
	 	array(
			'player'	=>	m_player($arr),
		)
	);
	
	$tpl->parse_tpl($html);
}

if (is_numeric($id)) {
	if (!$isLoggedIn && m_get_config('must_login_to_play')) {
		die("<b><center>Bạn cần đăng nhập mới có thể nghe được nhạc</center></b>");
	}
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_id = '$id'");
	if (!$mysql->num_rows($q)) {
		die("<center><b>Bài hát này không có thật.</b></center>");
	}
	$r = $mysql->fetch_array($q);
	$mysql->query("UPDATE ".$tb_prefix."data SET m_viewed = m_viewed + 1, m_viewed_month = m_viewed_month + 1 WHERE m_id = '$id'");
	m_play_p($r);
}
else exit("CKNL.NET");

?>