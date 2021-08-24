<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
include('includes/config.php');
include('includes/functions.php');

if ($value[0] == 'Detail_File' && is_numeric($value[1])) {
	$file_id = $value[1];
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."file WHERE file_id = '".$file_id."'");
	if (!$mysql->num_rows($q)) {
		echo "<center><b>Không có File này</b></center>";
		exit();
	}
	$main = $tpl->get_tpl('Detail_File');
	$r = $mysql->fetch_array($q);
	$title = $r['file_title'];
	$file_info = m_text_tidy($r['file_info']);
	$file_url = ($r['file_is_local'])?$mediaFolder.'/'.$r['file_url']:$r['file_url'];
	$main = $tpl->assign_vars($main,
		array(
			'file.ID'	=>	$file_id,
			'file.TITLE'	=>	$tieude,
			'file.CAT'	=>	$r['file_cat'],
			'file.INFO'	=>	$file_info,
			'file.URL'	=>	$file_url,
			'file.POSTER'	=>	m_get_data('USER',$r['file_poster']),
			'file.TYPE'	=>	$r['file_type'],
			'song.DOWNLOADED'	=>	$r['file_downloaded'],
		)
	);
	$tpl->parse_tpl($main);
	
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."news where news_id > '" .$news_id. "' ORDER BY news_id DESC LIMIT 10");
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(news_id) FROM ".$tb_prefix."news"));
	$tt = $tt[0];
	if ($mysql->num_rows($q)) {
		
		$main = $tpl->get_tpl('list_detail_news');
		$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$id = $r['news_id'];
			$tieude = $r['news_tieude'];
			$datepost = $r['news_datepost'];
			$html .= $tpl->assign_vars($t['row'],
				array(
					'news.TITLE'	=>	$tieude,
					'news.URL'	=>	'#Detail_News,'.$id,
					'news.DATEPOST'	=>	$datepost,
					'news.CLASS'	=>	$class,
				)
			);
			$i++;
		}
		$main = $tpl->assign_vars($main,
			array(
				'NAME_ML'	=> "Các tin mới hơn",
				)
		);
		
		$main = $tpl->assign_blocks_content($main,array(
				'list'	=>	$html,
			)
		);
		
		$tpl->parse_tpl($main);
	}
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."news where news_id < '" .$news_id. "' ORDER BY news_id DESC LIMIT 10");
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(news_id) FROM ".$tb_prefix."news"));
	$tt = $tt[0];
	if ($mysql->num_rows($q)) {
		
		$main = $tpl->get_tpl('list_detail_news');
		$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$id = $r['news_id'];
			$tieude = $r['news_tieude'];
			$datepost = $r['news_datepost'];
			$html .= $tpl->assign_vars($t['row'],
				array(
					'news.TITLE'	=>	$tieude,
					'news.URL'	=>	'#Detail_News,'.$id,
					'news.DATEPOST'	=>	$datepost,
					'news.CLASS'	=>	$class,
				)
			);
			$i++;
		}
		$main = $tpl->assign_vars($main,
			array(
				'NAME_ML'	=> "Các tin củ hơn",
			)
		);
		
		$main = $tpl->assign_blocks_content($main,array(
				'list'	=>	$html,
			)
		);
		
		$tpl->parse_tpl($main);
	}
	
}
elseif ($value[0] == 'List_News') {
	$m_per_page = 10;
	if (!$value[2]) $value[2] = 1;
	$limit = ($value[2]-1)*$m_per_page;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."news ORDER BY news_id DESC LIMIT ".$limit.",$m_per_page");
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(news_id) FROM ".$tb_prefix."news"));
	$tt = $tt[0];
	if ($mysql->num_rows($q)) {
		
		$main = $tpl->get_tpl('list_news');
		$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$id = $r['news_id'];
			$tieude = $r['news_tieude'];
			$hinhminhhoa = $r['news_img'];
			$noidung = getwords(strip_tags(m_text_tidy($r['news_noidung'])),100).'...'; 
			$html .= $tpl->assign_vars($t['row'],
				array(
					'news.TITLE'	=>	$tieude,
					'news.URL'	=>	'#Detail_News,'.$id,
					'news.IMAGES'	=>	$hinhminhhoa,
					'news.CONTENT'  =>  $noidung,
					'news.CLASS'	=>	$class,
				)
			);
			$i++;
		}
		$main = $tpl->assign_vars($main,
			array(
				'TOTAL'	=> $tt,
				'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$value[2]),
			)
		);
		
		$main = $tpl->assign_blocks_content($main,array(
				'list'	=>	$html,
			)
		);
		
		$tpl->parse_tpl($main);
	}
	else echo "<center><b>Không có dữ liệu trong mục này.</b></center";
}

?>