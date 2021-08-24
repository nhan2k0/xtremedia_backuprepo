<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
$m_per_page = 25;
if (($value[0] == 'Search' || $value[0] == 'Quick_Search') && isset($value[1],$value[2])) {
	if (!$value[3]) $value[3] = 1;
	$limit = ($value[3]-1)*$m_per_page;
	$fields = "m_id, m_title, m_singer, m_cat, m_type, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric";
	
	$kw = strtolower(utf8_to_ascii(urldecode($value[2])));
	$s_type = $value[1];
	$value[2] = urldecode($value[2]);
	$q = '';
	if ($s_type == 1) {
		if ($value[0] == 'Search') {
			$q = "SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_title_ascii LIKE '%".$kw."%' ORDER BY m_title ASC, m_title_ascii ASC LIMIT ".$limit.",".$m_per_page;
			$tt = m_get_tt("m_title LIKE '%".$kw."%'");
		}
		else {
			if ($value[2] == "0-9") {
				$q = "SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_title_ascii RLIKE '^[0-9]' ORDER BY m_title ASC, m_title_ascii ASC LIMIT ".$limit.",".$m_per_page;
				$tt = m_get_tt("m_title_ascii RLIKE '^[0-9]'");
			}
			else {
				$q = "SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_title_ascii LIKE '".$value[2]."%' ORDER BY m_title ASC, m_title_ascii ASC LIMIT ".$limit.",".$m_per_page;
				$tt = m_get_tt("m_title_ascii LIKE '".$value[2]."%'");
			}
		}
	}
	elseif ($s_type == 2) {
		$q = "SELECT singer_name,singer_img,singer_id FROM ".$tb_prefix."singer WHERE singer_name_ascii LIKE '%".$kw."%' ORDER BY singer_name ASC LIMIT ".$limit.",".$m_per_page;
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(singer_id) FROM ".$tb_prefix."singer WHERE singer_name_ascii LIKE '%".$kw."%'"));
		$tt = $tt[0];
	}
	elseif ($s_type == 3) {
		$q = "SELECT album_name,album_img,album_id,album_singer FROM ".$tb_prefix."album WHERE album_name_ascii LIKE '%".$kw."%' ORDER BY album_name ASC LIMIT ".$limit.",".$m_per_page;
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) FROM ".$tb_prefix."album WHERE album_name_ascii LIKE '%".$kw."%'"));
		$tt = $tt[0];
	}
	if ($q) $q = $mysql->query($q);
	
	if ($mysql->num_rows($q)) {
		$cat_tit = 'Kết quả tìm kiếm';
		
		if ($s_type == 1)
			$file = 'search_song';
		elseif ($s_type == 2)
			$file = 'search_singer';
		elseif ($s_type == 3)
			$file = 'search_album';
			
		$z = $tpl->get_tpl($file);
		$t['row'] = $tpl->get_block_from_str($z,'list_row',1);
	
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			
			if ($s_type == 1) {
				$lyric = ($r['m_lyric'])?"<img src='{TPL_LINK}/img/media/ok.gif'>":'';
				$singer = m_get_data('SINGER',$r['m_singer']);
				switch ($r['m_type']) {
					case 1 : $media_type = 'music'; break;
					case 2 : $media_type = 'flash'; break;
					case 3 : $media_type = 'movie'; break;
				}
				$media_type = "<img src='{TPL_LINK}/img/media/type/".$media_type.".gif'>";
				$html .= $tpl->assign_vars($t['row'],
					array(
						'song.CLASS' => $class,
						'song.TYPE' => $media_type,
						'song.ID' => $r['m_id'],
						'song.URL' => '#Play,'.$r['m_id'],
						'song.TITLE' => $r['m_title'],
						'song.VIEWED' => $r['m_viewed'],
						'song.DOWNLOADED' => $r['m_downloaded'],
						'song.LYRIC' => $lyric,
						'singer.NAME' => $singer,
						'singer.URL' => '#Singer,'.$r['m_singer'],
					)
				);
			}
			elseif ($s_type == 2) {
				$singer_img = ($r['singer_img'])?$r['singer_img']:"{TPL_LINK}/img/no_singer.gif";
				$html .= $tpl->assign_vars($t['row'],
					array(
						'singer.CLASS' => $class,
						'singer.IMG' => $singer_img,
						'singer.NAME' => $r['singer_name'],
						'singer.URL' => '#Singer,'.$r['singer_id'],
					)
				);
			}
			elseif ($s_type == 3) {
				$album_img = m_get_img('Album',$r['album_img']);
				$html .= $tpl->assign_vars($t['row'],
					array(
						'album.CLASS'	=>	$class,
						'album.URL'		=>	'#Album,'.$r['album_id'],
						'album.IMG'		=>	$album_img,
						'album.NAME'	=>	$r['album_name'],
						'singer.NAME'	=>	m_get_data('SINGER',$r['album_singer']),
						'singer.URL'	=>	'#Singer,'.$r['album_singer'],
					)
				);
			}
			$i++;
		}
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		$z = $tpl->assign_vars($z,
			array(
				'CLASS' => $class,
				'CAT_TITLE' => $cat_tit,
				'TOTAL'	=> $tt,
				'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$value[3]),
			)
		);
		
		$z = $tpl->assign_blocks_content($z,array(
				'list'	=>	$html,
			)
		);
		
		$tpl->parse_tpl($z);
	}
	else echo "<center><b>Không có dữ liệu trong mục này.</b></center";
}
?>