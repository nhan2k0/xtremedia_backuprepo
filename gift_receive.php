<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if ($value[1]) {
	$gift_id = $value[1];
	$q = $mysql->query("SELECT gift_id, gift_media_id, gift_sender_name, gift_recip_name, gift_message, gift_time FROM ".$tb_prefix."gift WHERE gift_id = '".$gift_id."'");
	if ($mysql->num_rows($q)) {
		$r = $mysql->fetch_array($q);
		if ($gift_id == $r['gift_id']) {
			$message = m_text_tidy($r['gift_message']);
			$main = $tpl->get_tpl('gift_receive');
			$main = $tpl->assign_vars($main,
				array(
					'gift.MESSAGE'	=>	$message,
					'gift.SENDER'	=>	$r['gift_sender_name'],
					'gift.RECIP'	=>	$r['gift_recip_name'],
					'gift.TIME'		=>	strftime('%H:%M:%S - %d-%m-%Y',$r['gift_time']),
				)
			);
			$m_r = $mysql->fetch_array($mysql->query("SELECT m_id, m_type, m_is_local, m_width, m_height FROM ".$tb_prefix."data WHERE m_id = '".$r['gift_media_id']."'"));
			$arr = array(
				'type'	=>	1,
				'm_type'	=>	$m_r['m_type'],
				'd_w'	=>	300,
				'd_h'	=>	300,
				'id'	=>	$m_r['m_id'],
			);
			
			if ($m_r['m_type'] == 2) {
				$arr['d_w'] = ($m_r['m_width'])?$m_r['m_width']:425;
				$arr['d_h'] = ($m_r['m_height'])?$m_r['m_height']:350;
				$mediaFolder = m_get_config('server_url').'/'.m_get_config('server_folder');
				$arr['url'] = ($m_r['m_is_local'])?$mediaFolder.'/'.$m_r['m_url']:$m_r['m_url'];
			}
			elseif ($m_r['m_type'] == 3) {
				$arr['d_w'] = ($m_r['m_width'])?$m_r['m_width']:300;
				$arr['d_h'] = ($m_r['m_height'])?$m_r['m_height']:300;
			}
			
			$main = $tpl->assign_blocks_content($main,
				array(
					'player'	=>	m_player($arr),
				)
			);
			$tpl->parse_tpl($main);
			exit();
		}
	}
}
echo "<center><b>Mã số quà tặng sai</b></center>";
?>