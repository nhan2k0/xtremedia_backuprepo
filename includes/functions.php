<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");

include('includes/user_online.php');
include('includes/counter.php');
include('includes/functions_boxes.php');

function getwords($str,$num)
{
	$limit = $num - 1 ;
    $str_tmp = '';
    //explode -- Split a string by string
    $arrstr = explode(" ", $str);
    if ( count($arrstr) <= $num ) { return $str; }
    if (!empty($arrstr))
    {
        for ( $j=0; $j< count($arrstr) ; $j++)    
        {
            $str_tmp .= " " . $arrstr[$j];
            if ($j == $limit) 
            {
                break;
            }
        }
    }
    return $str_tmp.'...';
}

function m_emotions_array() {
	return array(
		6 => '>:D<',		18 => '#:-S',				36 => '<:-P',		42 => ':-SS',
		48 => '<):)',		50 => '3:-O',				51 => ':(|)',		53 => '@};-',
		55 => '**==',		56 => '(~~)',				58 => '*-:)',		63 => '[-O<',
		67 => ':)>-',		77 => '^:)^',				106 => ':-??',		25 => 'O:)',
		26 => ':-B',		28 => 'I-)',				29 => '8-|',		30 => 'L-)',
		31 => ':-&',		32 => ':-$',				33 => '[-(',		34 => ':O)',
		35 => '8-}',		7 => ':-/',					37 => '(:|',		38 => '=P~',
		39 => ':-?',		40 => '#-O',				41 => '=D>',		9 => ':">',
		43 => '@-)',		44 => ':^O',				45 => ':-W',		46 => ':-<',
		47 => '>:P',		11 => array(':*',':-*'),	49 => ':@)',		12 => '=((',
		13 => ':-O',		52 => '~:>',				16 => 'B-)',		54 => '%%-',
		17 => ':-S',		5 => ';;)',					57 => '~O)',		19 => '>:)',
		59 => '8-X',		60 => '=:)',				61 => '>-)',		62 => ':-L',
		20 => ':((',		64 => '$-)',				65 => ':-"',		66 => 'B-(',
		21 => ':))',		68 => '[-X',				69 => '\:D/',		70 => '>:/',
		71 => ';))',		72 => 'O->',				73 => 'O=>',		74 => 'O-+',
		75 => '(%)',		76 => ':-@',				23 => '/:)',		78 => ':-J',
		79 => '(*)',		100 => ':)]',				101 => ':-C',		102 => '~X(',
		103 => ':-H',		104 => ':-T',				105 => '8->',		24 => '=))',
		107 => '%-(',		108 => ':O3',				1 => array(':)',':-)'),		2 => array(':(',':-('),
		3 => array(';)',';-)'),		22 => array(':|',':-|'),		14 => array('X(','X-('),		15 => array(':>',':->'),
		8 => array(':X',':-X'),		4 => array(':D',':-D'),		27 => '=;',		10 => array(':P',':-P'),
	);
}

function m_emotions_replace($s) {
	$emotions = m_emotions_array();

	foreach ($emotions as $a => $b) {
		$x = array();
		if (is_array($b)) {
			for ($i=0;$i<count($b);$i++) {
				$b[$i] = m_htmlchars($b[$i]);
				$x[] = $b[$i];
				$v = strtolower($b[$i]);
				if ($v != $b[$i]) $x[] = $v;
				//array_push($x,$b[$i],strtolower($b[$i]));
			}
		}
		else {
			$b = m_htmlchars($b);
			$x[] = $b;
			$v = strtolower($b);
			if ($v != $b) $x[] = $v;
			//$x = array($b,strtolower($b));
		}
		$p = '';
		for ($u=0;$u<strlen($x[0]);$u++) {
			$ord = ord($x[0][$u]);
			if ($ord < 65 && $ord > 90) $p .= '&#'.$ord.';';
			else $p .= $x[0][$u];
		}
		$s = str_replace($x,'<img src=emoticons/'.$a.'.gif>',$s);
	}
	return $s;
}

function m_encode($id) {
	$salt = m_get_config('download_salt');
	$m = strtoupper(substr(md5($salt.$id),6,16));
	return 'RP89-'.$m;
}

function m_get_tt($exp = '') {
	global $mysql, $tb_prefix;
	$q = "SELECT COUNT(m_id) FROM ".$tb_prefix."data";
	$q .= ($exp)?" WHERE ".$exp:'';
	$tt = $mysql->fetch_array($mysql->query($q));
	return $tt[0];
}


function m_get_data($type,$v,$field = '') {
	global $mysql, $tb_prefix, $cached, $value;
	if ($type == 'CAT') {
		if (!$field) $field = 'cat_name';
		if (!$cached['cat'][$v]) {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."cat WHERE cat_id = '".$v."'"));
			$cached['cat'][$v] = $r[$field];
		}
		return $cached['cat'][$v];
	}
	if ($type == 'NEWSCAT') {
		if (!$field) $field = 'cat_name';
		if (!$cached['cat'][$v]) {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."news_cat WHERE cat_id = '".$v."'"));
			$cached['cat'][$v] = $r[$field];
		}
		return $cached['cat'][$v];
	}
	elseif ($type == 'PLAYLIST') {
		if (!$cached['playlist']) {
			$r = $mysql->fetch_array($mysql->query("SELECT playlist_contents FROM ".$tb_prefix."playlist WHERE playlist_id = '".$v."'"));
			$cached['playlist'] = $r['playlist_contents'];
		}
		return $cached['playlist'];
	}
	elseif ($type == 'USER') {
		if (!$field) $field = 'user_name';
		if (!$cached['user']['user_'.$v][$field]) {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."user WHERE user_id = '".$v."'"));
			if ($field == 'user_name') $r[$field] = m_unhtmlchars($r[$field]);
			$cached['user']['user_'.$v][$field] = $r[$field];
		}
		if ($field == 'user_hide_info') {
			$hide_list = array('hide_sex','hide_email');
			$len = count($hide_list);
			if (m_check_level($_SESSION['user_id']) == 3 && $value[0] != 'Change_Info')
				$hide_info = '';
			else {
				$hide_info = $cached['user']['user_'.$v]['user_hide_info'];
				$hide_info = decbin($hide_info);
			}
			
			while (strlen($hide_info) < $len) $hide_info = '0'.$hide_info;
			for ($i=0;$i<$len;$i++) $arr[$hide_list[$i]] = $hide_info[$i];
			return $arr;
			
		}
		return $cached['user']['user_'.$v][$field];
	}
	elseif ($type == 'SONG') {
		if (!$field) $field = 'm_title';
		if (!$cached['song']['song_'.$v][$field]) {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."data WHERE m_id = '".$v."'"));
			$cached['song']['song_'.$v][$field] = $r[$field];
		}
		return $cached['song']['song_'.$v][$field];
	}
	elseif ($type == 'ALBUM') {
		if (!$field) $field = 'album_name';
		if (!$cached['album']['album_'.$v][$field]) {
			if ($v == 0 && $field == 'album_name') return "Đang cập nhật";
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."album WHERE album_id = '".$v."'"));
			$cached['album']['album_'.$v][$field] = $r[$field];
		}
		return $cached['album']['album_'.$v][$field];
	}
	elseif ($type == 'SINGER') {
		if (!$field) $field = 'singer_name';
		if ($field == 'singer_name') {
			$c_name =& $cached['singer']['singer_'.$v][$field];
			if (!$c_name) {
				if ($v == -1) $c_name = "Chưa biết (VN)";
				elseif ($v == -2) $c_name = "Chưa biết (QT)";
				//elseif ($v == -3) $c_name = "Chưa biết (Band)";
				else {
					$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."singer WHERE singer_id = '".$v."'"));
					$c_name = $r[$field];
				}
			}
			return $c_name;
		}
		elseif ($field == 'singer_img') {
			$c_img =& $cached['singer']['singer_'.$v][$field];
			if (!$c_img) {
				if ($id == -1) $c_img = "{TPL_LINK}/img/unknown_vn.gif";
				elseif ($id == -2) $c_img = "{TPL_LINK}/img/unknown_fr.gif";
				//elseif ($id == -3) $c_img = "{TPL_LINK}/img/unknown_band.gif";
				else {
					$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."singer WHERE singer_id = '".$v."'"));
					if (!$r[$field]) $c_img = "{TPL_LINK}/img/no_singer.gif";
					else $c_img = $r[$field];
				}
			}
			return $c_img;
		}
		else {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."singer WHERE singer_id = '".$v."'"));
			return $r[$field];
		}
	}
}

function m_resize_img(&$w,&$h,$max_w,$max_h) {
	$ratio = $w/$h;
	$w = ($w>$max_w)?$max_w:$w;
	$h = ($w>$max_w)?round($w*$ratio):$h;
	$h = ($h>$max_h)?$max_h:$h;
	$w = ($h>$max_h)?round($h*$ratio):$w;
}

function m_get_img($type,$img) {
	if ($type == 'Album') {
		if (!$img) $img = "{TPL_LINK}/img/no_album.gif";
	}
	elseif ($type == 'Singer') {
		if (!$img) $img = "{TPL_LINK}/img/no_singer.gif";
	}
	elseif ($type == 'News') {
		if (!$img) $img = "{TPL_LINK}/img/no_news.png";
	}
	return $img;
}


function m_viewpages($ttrow,$n,$pg){
	global $value, $tpl;
	$total = ceil($ttrow/$n);
	if ($total <= 1) return '';
	$v_f = 3;
	$v_a = 2;
	$v_l = 3;
	$max_pages = $v_f + $v_a + $v_l + 5;
	
	$z_1 = $z_2 = $z_3 = false;
	
	if (in_array($value[0],array('Top_Download','Top_Play','Home','Ebooks','Files'))) {
		$link = '#'.$value[0];
		$pg = ($value[1])?$value[1]:1;
	}
	elseif ($value[0] == 'Search' || $value[0] == 'Quick_Search') {
		$link = '#'.$value[0].','.$value[1].','.$value[2];
	}
	elseif (in_array($value[0],array('List_Album','List_User'))) {
		$link = '#'.$value[0];
	}
	else
		$link = '#'.$value[0].','.$value[1];
	
	
	$html = $tpl->get_box('view_pages');
	$block = $tpl->get_block_from_str($html,'page_block');
	$t = $tpl->auto_get_block($block);
	$block = '';
	$pgt = $pg-1;
	if ($pg != 1)
		$block .= $tpl->assign_vars($t['first_previous_page'],
			array(
				'page.F_LINK'	=>	$link,
				'page.P_LINK'	=>	$link.",".$pgt,
			)
		);
	for($m = 1; $m <= $total; $m++) {
		if ($total > $max_pages) {
			if (($m > $v_f) && (($m < $pg - $v_a) || ($m > $pg + $v_a)) && ($m < $total - $v_l + 1)) {
				if (!$z_1 && ($m > $v_f)) {
					$block .= "...";
					$z_1 = true;
				}
				elseif (!$z_2 && ($m > $pg + $v_a)) {
					$block .= "...";
					$z_2 = true;
				}
				continue;
			}
		}	
		if($m == $pg)
			$block .= $tpl->assign_vars($t['current_page'],
				array(
					'page.NUMBER'	=>	$m,
				)
			);
		else
			$block .= $tpl->assign_vars($t['page_number'],
				array(
					'page.NUMBER'	=>	$m,
					'page.LINK'	=>	$link.",".$m,
				)
			);
	}
	$pgs = $pg + 1;
	if ($pg != $total)
		$block .= $tpl->assign_vars($t['last_next_page'],
			array(
				'page.L_LINK'	=>	$link.",".$total,
				'page.N_LINK'	=>	$link.",".$pgs,
			)
		);
	$html = $tpl->assign_blocks_content($html,
			array(
				'page_block'	=>	$block,
			)
		);
	return $html;
}

function m_blog($r) {
	global $mainURL,$tpl;
	$html = $tpl->get_tpl('blog');
	$html = $tpl->assign_vars($html,
		array(
			'TPL_LINK'	=>	$mainURL.'/{TPL_LINK}',
			'song.TITLE'	=>	$r['m_title'],
			'song.URL'	=>	$mainURL.'/#Play,'.$r['m_id'],
			'singer.URL'	=>	$mainURL.'/#Singer,'.$r['m_singer'],
			'singer.NAME'	=>	m_get_data('SINGER',$r['m_singer']),
		)
	);
	$html = htmlspecialchars($html);
	return $html;
}


function m_info_tb($r,$show_singer = false) {
	global $mysql,$mainURL,$tpl,$tb_prefix;
	$t['info'] = $tpl->get_tpl('play_info');
	$singer_img = m_get_data('SINGER',$r['m_singer'],'singer_img');
	$singer_img = m_get_img('Singer',$singer_img);

	$singer_type = m_get_data('SINGER',$r['m_singer'],'singer_type');
	$singer_is_member = ($singer_type == '9')?'Thành viên tự thể hiện':'';
	
	$total_comment = $mysql->fetch_array($mysql->query("SELECT COUNT(comment_media_id) FROM ".$tb_prefix."comment WHERE comment_media_id = '".$r['m_id']."'"));
	$total_comment = $total_comment[0];
/////////////////////////////
	$d_w = 300;
	$d_h = 68;
	if ($r['m_type'] != 1) {
		$d_w = ($r['m_width'])?$r['m_width']:400;
		$d_h = ($r['m_height'])?$r['m_height']:360;
	}
	$d_w = $d_w + 70;
	$d_h = $d_h + 170;	
/////////////////////////////
	$lyric = ($r['m_lyric'])?m_text_tidy($r['m_lyric']):'';
	if ($lyric) $t['info'] = $tpl->assign_vars($t['info'],array
		(
			'LYRIC'	=>	m_emotions_replace($lyric),
		)
	);
	else $t['info'] = $tpl->unset_block($t['info'],array('lyric'));
/////////////////////////////	
	// NOT SHOW ADD_TO_PLAYLIST
	$m_type = $r['m_type'];
	if ($m_type == '2') $t['info'] = $tpl->unset_block($t['info'],array('AddToPlayList'));

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

	$rater_stars_img = 	"<img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star1.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star2.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star3.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star4.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star5.".gif\">";
/////////////////////////////

	$html = $tpl->assign_vars($t['info'],
		array(
			'song.TITLE'	=>	$r['m_title'],
			'song.VIEWED'	=>	$r['m_viewed'] + 1,
			'song.DOWNLOADED'	=>	$r['m_downloaded'],
			'song.ID'		=>	$r['m_id'],
			'song.POSTER'		=>	m_get_data('USER',$r['m_poster']),
			'singer.URL'	=>	'#Singer,'.$r['m_singer'],
			'singer.NAME'	=>	m_get_data('SINGER',$r['m_singer']),
			'singer.IMG'	=>	$singer_img,
			'album.URL'		=>	'#Album,'.$r['m_album'],
			'cat.URL'		=>	'#List,'.$r['m_cat'],
			'cat.NAME'		=>	m_get_data('CAT',$r['m_cat']),
			'user.URL'		=>	'#User,'.$r['m_poster'],
			'user.URL'		=>	'#User,'.$r['m_poster'],
			'comment.TOTAL'		=>	$total_comment,
			'album.NAME'	=>	m_get_data('ALBUM',$r['m_album']),
			'blog.CONTENT'	=>	m_blog($r),
			'MEDIA_LINK'	=>	$mainURL.'/#Play,'.$r['m_id'],
			'DOWNLOAD_LINK'	=>	'#Download,'.$r['m_id'].','.m_encode($r['m_id']),
	//		'ID_CODE'		=>	m_encode($r['m_id']),
			'WEB_URL'		=>	$mainURL,
			'RATE.STAR'	=>	$rater_stars_img." ( ".$r['m_rating_total']." Rates )",
			'MEDIA_URL'		=>	$mainURL.'/asx.php?type=1&id='.$r['m_id'],
			'MEMBER_THE_HIEN'		=>	$singer_is_member,
			'WIDTH.POPUP'		=>	$d_w,
			'HEIGHT.POPUP'	=>	$d_h,
			)
	);
	return $html;
}

function m_info_tb_notmusic($r,$show_singer = false) {
	global $mysql,$mainURL,$tpl,$tb_prefix;
	$t['info'] = $tpl->get_tpl('play_info_notmusic');
	
	$total_comment = $mysql->fetch_array($mysql->query("SELECT COUNT(comment_media_id) FROM ".$tb_prefix."comment WHERE comment_media_id = '".$r['m_id']."'"));
	$total_comment = $total_comment[0];
	switch ($r['m_type']) {
	//		case 1 : $media_type_img = 'music'; break;
	//		case 2 : $media_type_img = 'flash'; break;
	//		case 3 : $media_type_img = 'movie'; break;
			case 4 : $media_type_img = 'ebook'; $media_type_title = 'EBOOKS'; break;
			case 5 : $media_type_img = 'application'; $media_type_title = 'APPLICATIONS'; break;
			case 6 : $media_type_img = 'archive'; $media_type_title = 'ARCHIVES'; break;
		}
	$media_type_img = $media_type_img.'_big.png';

	$info = ($r['m_lyric'])?m_text_tidy($r['m_lyric']):'';
	if ($info) $t['info'] = $tpl->assign_vars($t['info'],array
		(
			'INFO'	=>	m_emotions_replace($info),
		)
	);
	else $t['info'] = $tpl->unset_block($t['info'],array('info'));

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

	$rater_stars_img = 	"<img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star1.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star2.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star3.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star4.".gif\">"
						." <img src=\"templates/".$_SESSION['current_tpl']."/img/rate/".$star5.".gif\">";
/////////////////////////////

	$html = $tpl->assign_vars($t['info'],
		array(
			'file.TITLE'	=>	$r['m_title'],
			'file.ID'		=>	$r['m_id'],
			'file.VIEWED'	=>	$r['m_viewed'] + 1,
			'file.DOWNLOADED'	=>	$r['m_downloaded'],
			'file.ID'		=>	$r['m_id'],
			'file.POSTER'		=>	m_get_data('USER',$r['m_poster']),
			'cat.URL'		=>	'#List,'.$r['m_cat'],
			'cat.NAME'		=>	m_get_data('CAT',$r['m_cat']),
			'user.URL'		=>	'#User,'.$r['m_poster'],
			'user.URL'		=>	'#User,'.$r['m_poster'],
			'comment.TOTAL'		=>	$total_comment,
			'MEDIA_LINK'	=>	$mainURL.'/#Play,'.$r['m_id'],
			'DOWNLOAD_LINK'	=>	'#Download,'.$r['m_id'].','.m_encode($r['m_id']),
			'RATE.STAR'	=>	$rater_stars_img." ( ".$r['m_rating_total']." Rates )",
			'WEB_URL'		=>	$mainURL,
			'MEDIA_TYPE.TITLE'	=>	$media_type_title,
			'MEDIA_TYPE.IMG'	=>	$media_type_img,
			'INFO'	=>	$info,
		)
	);
	return $html;
}

function play_album($r) {
	global $mysql,$tb_prefix,$tpl;
	
	$t['album'] = $tpl->get_tpl('play_album');
	$album_img = m_get_img('Album',$r['album_img']);
	$id = $r['album_id'];
	$album_info = m_text_tidy($r['album_info']);
	$arr = array(
		'type'	=>	3,
		'm_type'	=>	3,
		'd_w'	=>	300,
		'd_h'	=>	300,
		'id'	=>	$id,
	);
	
	$html = $tpl->assign_vars($t['album'],array
		(
			'album.NAME'	=>	$r['album_name'],
			'album.IMG'		=>	$album_img,
			'album.URL'	=>	'#Album,'.$id,
			'album.INFO'	=>	$album_info,
			'singer.URL'	=>	'#Singer,'.$r['album_singer'],
			'singer.NAME'	=>	m_get_data('SINGER',$r['album_singer']),
		)
	);
	
	$html = $tpl->assign_blocks_content($html,
	 	array(
			'player'	=>	m_player($arr),
		)
	);
	
	$tpl->parse_tpl($html);
}

function play_singer($r) {
	global $mysql,$tpl;
	$lyric = ($r['m_lyric'])?str_replace("\n",'<br>',$r['m_lyric']):'Chưa có lời.';
	$html = $tpl->get_tpl('play_singer');
	$singer_img = m_get_img('Singer',$r['singer_img']);
	$singer_info = ($r['singer_info'])?$r['singer_info']:'Chưa có';
	$singer_info = m_text_tidy($singer_info);
	$id = $r['singer_id'];
	$arr = array(
		'type'	=>	2,
		'm_type'	=>	3,
		'd_w'	=>	300,
		'd_h'	=>	300,
		'id'	=>	$id,
	);
	
	$html = $tpl->assign_vars($html,array
		(
			'singer.URL'	=>	'#Singer,'.$id,
			'singer.NAME'	=>	$r['singer_name'],
			'singer.IMG'	=>	$singer_img,
			'singer.INFO'	=>	$singer_info,
		)
	);
	
	$html = $tpl->assign_blocks_content($html,
	 	array(
			'player'	=>	m_player($arr),
		)
	);
	
	$tpl->parse_tpl($html);
}

function play_playlist($id) {
	global $mysql,$tpl;
	$html = $tpl->get_tpl('play_playlist');
	
	$arr = array(
		'type'	=>	4,
		'm_type'	=>	3,
		'd_w'	=>	300,
		'd_h'	=>	300,
		'id'	=>	$id,
	);
	
	$html = $tpl->assign_blocks_content($html,
	 	array(
			'player'	=>	m_player($arr),
		)
	);
	
	$tpl->parse_tpl($html);
}

function m_player($arr) {
	global $tpl;
	extract($arr);
	if (!$url) $url = 'asx.php?type='.$type.'&id='.$id;
	if ($m_type == 1) {
		$player = $tpl->get_tpl('players/mp3');
	}
	elseif ($m_type == 2) {
		$player = $tpl->get_tpl('players/swf');
	}
	elseif ($m_type == 3) {
		$player = $tpl->get_tpl('players/wmv');
	}
	$player = $tpl->assign_vars($player,array
		(
			'WIDTH'		=>	$d_w,
			'HEIGHT'	=>	$d_h,
			'URL'		=>	$url,
		)
	);
	return $player;
}

function m_play($r) {
	global $mysql,$tb_prefix,$tpl,$mediaFolder;
	$media_arr = array(
		'1',
		'2',
		'3',
	);
	$media_type = $r['m_type'];
	$lyric = ($r['m_lyric'])?m_text_tidy($r['m_lyric']):'';
	
	if (in_array($media_type,$media_arr)) $html = $tpl->get_tpl('play');
	else $html = $tpl->get_tpl('play_notmusic');
	
	$id = $r['m_id'];
	if ($lyric) $html = $tpl->assign_vars($html,array
		(
			'LYRIC'	=>	m_emotions_replace($lyric),
		)
	);
	else $html = $tpl->unset_block($html,array('lyric'));
	
	if (in_array($media_type,$media_arr)) {
		$html = $tpl->assign_vars($html,array
			(
				'MEDIA_INFO'	=>	m_info_tb($r,1),
			)
		);
	}
	else {
		$html = $tpl->assign_vars($html,array
			(
				'MEDIA_INFO'	=>	m_info_tb_notmusic($r,1),
			)
		);
	}
	$arr = array(
		'type'	=>	1,
		'm_type'	=>	$r['m_type'],
		'd_w'	=>	350,
		'd_h'	=>	68,
		'id'	=>	$id,
	);
	if ($r['m_type'] != 1) {
		$arr['d_w'] = ($r['m_width'])?$r['m_width']:350;
		$arr['d_h'] = ($r['m_height'])?$r['m_height']:350;
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

function m_cut_string($str,$len,$more){
	if ($str=='' || $str==NULL) return $str;
	if (is_array($str)) return $str;
	$str = trim($str);
	if (strlen($str) <= $len) return $str;
	$str = substr($str,0,$len);
	if ($str != '') {
		if (!substr_count($str," ")) {
			if ($more) $str .= " ...";
			return $str;
		}
		while(strlen($str) && ($str[strlen($str)-1] != " ")) $str = substr($str,0,-1);
		$str = substr($str,0,-1);
		if ($more) $str .= " ...";
	}
    return $str;
}

function utf8_to_ascii($str) {
	$chars = array(
		'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
		'e' =>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
		'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
		'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
		'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
		'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
		'd'	=>	array('đ','Đ'),
	);
	foreach ($chars as $key => $arr) 
		foreach ($arr as $val)
			$str = str_replace($val,$key,$str);
	return $str;
}
function m_random_str($len = 5) {
	$s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	mt_srand ((double)microtime() * 1000000);
	$unique_id = '';
	for ($i=0;$i< $len;$i++)
		$unique_id .= substr($s, (mt_rand()%(strlen($s))), 1);
	return $unique_id;
}

function m_check_random_str($str,$len = 5) {
	if (!ereg('^([A-Za-z0-9]){'.$len.'}$',$str)) return false;
	return true;
}

function m_check_email($email) {
	if (strlen($email) == 0) return false;
	if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) return true;
	return false;
}

function m_checkLogin(){
	global $mysql, $tb_prefix;
	$time_exit = NOW;
	$mysql->query("UPDATE ".$tb_prefix."user SET user_lastvisit = user_timeout, user_identifier = '', user_timeout = '', user_ip = '', user_online = 0 WHERE user_timeout < ".$time_exit." AND user_timeout > 0");
	
	if ($_COOKIE['USER']) {
		$identifier = $_COOKIE['USER'];
		$q = $mysql->query("SELECT user_identifier, user_id FROM ".$tb_prefix."user WHERE user_online = 1 AND user_ip = '".IP."' AND user_identifier = '".$identifier."'");
		if ($mysql->num_rows($q)) {
			$r = $mysql->fetch_array($q);
			$_SESSION['user_id'] = $r['user_id'];
			$mysql->query("UPDATE ".$tb_prefix."user SET user_timeout = ".(NOW + 2*60*60)." WHERE user_id = '".$_SESSION['user_id']."'");
			$return = true;
		}
		else $return = false;
	}
	else $return = false;

	if ($return == false) {
		if ($_COOKIE['INFO']) m_setcookie('INFO', '', false);
		unset($_SESSION['user_id']);
	}
	return $return;
}

function m_user_level($id) {
	global $mysql, $tb_prefix;
	$r = $mysql->fetch_array($mysql->query("SELECT user_level FROM ".$tb_prefix."user WHERE user_id = '".$id."'"));
	$level = $r['user_level'];
	switch ($level) {
		case 1 : $level = 'Member';	break;
		case 2 : $level = 'Moderator';	break;
		case 3 : $level = 'Admin';	break;
	}
	return $level;
}

function m_check_level($id) {
	global $mysql, $tb_prefix, $cached;
	if (!$cached['user']['user_'.$id]['m_level']) {
		$r = $mysql->fetch_array($mysql->query("SELECT user_level FROM ".$tb_prefix."user WHERE user_id = '".$id."'"));
		if ($r['user_level'] == 2 || $r['user_level'] == 3) $cached['user']['user_'.$id]['m_level'] = $r['user_level'];
		else $cached['user']['user_'.$id]['m_level'] = false;
	}
	return $cached['user']['user_'.$id]['m_level'];
}

function m_unhtmlchars($str) {
	return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;', '&#92;', '&#39'), array('<', '>', '"', '&', chr(92), chr(39)), $str);
}

function m_htmlchars($str) {
	return str_replace(
		array('&', '<', '>', '"', chr(92), chr(39)),
		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),
		$str
	);
}

function m_encode_quotes($s) {
	return str_replace(
		array('"', chr(39)),
		array('&quot;', '&#39'),
		$s
	);
}

function m_decode_quotes($s) {
	return str_replace(
		array('&quot;', '&#39'),
		array('"', chr(39)),
		$s
	);
}

function m_build_mail_header($to_email, $from_email) {
	$CRLF = "\n";
	$headers = 'MIME-Version: 1.0'.$CRLF;
	$headers .= 'Content-Type: text/html; charset=UTF-8'.$CRLF;
	$headers .= 'Date: ' . gmdate('D, d M Y H:i:s Z', NOW) . $CRLF;
	$headers .= 'From: <'. $from_email .'>'. $CRLF;
	$headers .= 'Reply-To: <'. $from_email .'>'. $CRLF;
	$headers .= 'Auto-Submitted: auto-generated'. $CRLF;
	$headers .= 'Return-Path: <'. $from_email .'>'. $CRLF;
	$headers .= 'X-Sender: <'. $from_email .'>'. $CRLF; 
	$headers .= 'X-Priority: 3'. $CRLF;
	$headers .= 'X-MSMail-Priority: Normal'. $CRLF;
	$headers .= 'X-MimeOLE: Produced By xtreMedia'. $CRLF;
	$headers .= 'X-Mailer: PHP/ '. phpversion() . $CRLF;
	return $headers;
}
/*
function m_text_tidy($txt = "", $htmlchars = false) {
	if ($htmlchars) $txt = htmlspecialchars($txt);
	$txt = str_replace("\n","<br>",$txt);
	$txt = str_replace("\t","&nbsp;&nbsp;",$txt);
	//$txt = preg_replace( "/\\n/"    , "<br>"           , $txt );
	$txt = preg_replace( "/  /" , " &nbsp;"      , $txt );
	//$txt = preg_replace( "/\t/"    , "&nbsp;&nbsp;" , $txt );
	return $txt;
}
*/

function m_text_tidy( $string ) {
        $string = str_replace ( '&amp;', '&', $string );
        $string = str_replace ( "&#039;", "'", $string );
        $string = str_replace ( '&quot;', '"', $string );
        $string = str_replace ( '&lt;', '<', $string );
        $string = str_replace ( '&gt;', '>', $string );
       
        return $string;
}


?>