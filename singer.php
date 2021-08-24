<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if ($value[0] == 'Singer' && is_numeric($value[1])) {
	$ok = false;
	$singer_id = $value[1];
	$main = $tpl->get_tpl('singer_info');
	
	# SINGER
	$q = $mysql->query("SELECT singer_name, singer_info FROM ".$tb_prefix."singer WHERE singer_id = '".$singer_id."'");
	$r = $mysql->fetch_array($q);
	$singer_info = ($r['singer_info'])?$r['singer_info']:'Chưa có';
	$singer_info = m_text_tidy($singer_info);
	$main = $tpl->assign_vars($main,
			array(
				'singer.NAME'	=> $r['singer_name'],
				'singer.INFO'	=> $singer_info,
				'singer.IMG'	=> m_get_data('SINGER',$singer_id,'singer_img'),
				'singer.PLAY_URL'	=>	'#Play_Singer,'.$singer_id,
			)
	);
	
	# ALBUM
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."album WHERE album_singer = '".$singer_id."' ORDER BY album_name ASC");
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) FROM ".$tb_prefix."album WHERE album_singer = ".$singer_id));
	$tt = $tt[0];
	if ($tt) {
		$ok = true;
		$album_block = $tpl->get_block_from_str($main,'album_block');
		$t['row'] = $tpl->get_block_from_str($album_block,'album_list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			$singer = m_get_data('SINGER',$r['album_singer']);
			$album_img = m_get_img('Album',$r['album_img']);
			$html .= $tpl->assign_vars($t['row'],
				array(
					'album.CLASS'	=>	$class,
					'album.IMG'		=>	$album_img,
					'album.URL'		=>	'#Album,'.$r['album_id'],
					'album.NAME'	=>	$r['album_name'],
					'singer.NAME'	=>	$singer,
					'album.VIEWED'	=>	$r['album_viewed'],
				)
			);
			$i++;
		}
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		
		$album_block = $tpl->assign_blocks_content($album_block,array(
				'album_list'	=>	$html,
			)
		);
		$album_block = $tpl->assign_vars($album_block,
			array(
				'CLASS' => $class,
				'TOTAL'	=> $tt,
			)
		);
		$main = $tpl->assign_blocks_content($main,array(
				'album_block'	=>	$album_block,
			)
		);
	}
	else $main = $tpl->unset_block($main,array('album_block'));
	
	# SONG
	$m_per_page = m_get_config('media_per_page');
	if (!$value[2]) $value[2] = 1;
	$limit = ($value[2]-1)*$m_per_page;
	
	$fields = "m_id, m_title, m_singer, m_cat, m_type, m_viewed, m_downloaded, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric";
	$q = $mysql->query("SELECT ".$fields." FROM ".$tb_prefix."data WHERE m_singer = '".$singer_id."' ORDER BY m_title ASC LIMIT ".$limit.",".$m_per_page);
	$tt = m_get_tt("m_singer = '".$singer_id."'");
	if ($tt) {
		$ok = true;
		$song_block = $tpl->get_block_from_str($main,'song_block');
		$t['row'] = $tpl->get_block_from_str($song_block,'song_list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			
			$lyric = ($r['m_lyric'])?"<img src='{TPL_LINK}/img/media/ok.gif'>":'';
			
			$singer = m_get_data('SINGER',$r['m_singer']);
			switch ($r['m_type']) {
				case 1 : $media_type = 'music'; break;
				case 2 : $media_type = 'flash'; break;
				case 3 : $media_type = 'movie'; break;
			}
			$media_type = "<img src='{TPL_LINK}/img/media/type/$media_type.gif'>";
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
				)
			);
			$i++;
		}
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		
		$song_block = $tpl->assign_blocks_content($song_block,array(
				'song_list'	=>	$html,
			)
		);
		$song_block = $tpl->assign_vars($song_block,
			array(
				'CLASS' => $class,
				'TOTAL'	=> $tt,
				'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$value[2]),
			)
		);
		$main = $tpl->assign_blocks_content($main,array(
				'song_block'	=>	$song_block,
			)
		);
	}
	else $main = $tpl->unset_block($main,array('song_block'));
	if (!$ok) echo "<center><b>Không có dữ liệu</b></center>";
	$tpl->parse_tpl($main);
}
elseif ($value[0] == 'Play_Singer' && is_numeric($value[1])) {
	if (!$isLoggedIn && m_get_config('must_login_to_play')) {
		echo "<b><center>Bạn cần đăng nhập mới có thể nghe được nhạc</center></b>";
		exit();
	}
	$singer_id = $value[1];
	$q = $mysql->query("SELECT singer_name, singer_info, singer_id FROM ".$tb_prefix."singer WHERE singer_id = '".$singer_id."'");
	if (!$mysql->num_rows($q)) {
		ob_end_clean();
		echo "<center><b>Không có ca sỹ này.</b></center>";
		exit();
	}
	$r = $mysql->fetch_array($q);
	play_singer($r);
}
?>