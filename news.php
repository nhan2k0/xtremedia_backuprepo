<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");

if ($value[0] == 'Detail_News' && is_numeric($value[1])) {
	$news_id = $value[1];
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_id = '".$news_id."'");
	if (!$mysql->num_rows($q)) {
		echo "<center><b>Không có mục tin tức này</b></center>";
		exit();
	}
	$main = $tpl->get_tpl('Detail_News');
	$r = $mysql->fetch_array($q);
	$tieude = $r['news_tieude'];
//	$hinhminhhoa = $r['news_img'];
	$hinhminhhoa = m_get_img('News',$r['news_img']);
	$thongtinanh = $r['news_infoanh'];
	$noidung = m_text_tidy($r['news_noidung']);
	$nguontu = $r['news_from'];
	$datepost = $r['news_datepost'];
	$main = $tpl->assign_vars($main,
		array(
			'news.TITLE'	=>	$tieude,
			'news.IMAGES'	=>	$hinhminhhoa,
			'news.IMAGESINFO'	=>	$thongtinanh,
			'news.CONTENT'	=>	$noidung,
			'news.FROM'	=>	$nguontu,
			'news.DATEPOST'	=>	$datepost,
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

	function news_get_tt($exp = '') {
		global $mysql, $tb_prefix;
		$q = "SELECT COUNT(news_id) FROM ".$tb_prefix."news";
		$q .= ($exp)?" WHERE ".$exp:'';
		$tt = $mysql->fetch_array($mysql->query($q));
		return $tt[0];
	}

	if (!$value[2]) $value[2] = 1;
	$limit = ($value[2]-1)*$m_per_page;
	if (!$value[1]) {
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."news ORDER BY news_id DESC LIMIT ".$limit.",$m_per_page");
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(news_id) FROM ".$tb_prefix."news"));
	$tt = $tt[0];
	}
	else {
		$check = $mysql->fetch_array($mysql->query("SELECT sub_id FROM ".$tb_prefix."news_cat WHERE cat_id = '$value[1]'"));
		if (!is_null($check['sub_id']) && $check['sub_id'] != 0) {
			$q = "SELECT * FROM ".$tb_prefix."news WHERE news_cat = ".$value[1]." ORDER BY news_id DESC LIMIT ".$limit.",$m_per_page";
			$q = $mysql->query($q);
			$tt = news_get_tt("news_cat = ".$value[1]." ");
		}
		else {
			$list_q = $mysql->query("SELECT cat_id FROM ".$tb_prefix."news_cat WHERE sub_id = ".$value[1]." ");
			$in_sql = '';
			while ($list_r = $mysql->fetch_array($list_q)) $in_sql .= "'".$list_r['cat_id']."',";
			$in_sql = substr($in_sql,0,-1);
			if (!$in_sql) $in_sql = -1;
			$q = "SELECT * FROM ".$tb_prefix."news WHERE news_cat IN ($in_sql) ORDER BY news_id DESC LIMIT ".$limit.",$m_per_page";
			$q = $mysql->query($q);
			$tt = news_get_tt("news_cat IN (".$in_sql.")");
		}
	}
	
	if ($mysql->num_rows($q)) {
		
		$main = $tpl->get_tpl('list_news');
		$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$id = $r['news_id'];
			$tieude = $r['news_tieude'];
			//$hinhminhhoa = $r['news_img'];
			$hinhminhhoa = m_get_img('News',$r['news_img']);
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