<?
if (!defined('IN_MEDIA')) die("Hacking attempt");
function box_user_online($file_tpl = 'user_online') {
	global $mysql, $tb_prefix, $tpl;
	$html = $tpl->get_box('user_online');
	
	$c_user_online = $tpl->get_block_from_str($html,'user_online');
	$c = $tpl->auto_get_block($c_user_online);
	/*
	$c_mem = $tpl->get_block_from_str($c_user_online,'member',1);
	$c_mod = $tpl->get_block_from_str($c_user_online,'mod',1);
	$c_admin = $tpl->get_block_from_str($c_user_online,'admin',1);
	$c_this = $tpl->get_block_from_str($c_user_online,'this',1);
	*/
	$timeout = 60*30;
	$total = 0;	$member = 0; $guest = 0;
	$current_time = NOW;
	$time_exit = $current_time - $timeout;
	$mysql->query("DELETE FROM ".$tb_prefix."online WHERE timestamp < ".$time_exit);
	$guests = $mysql->num_rows($mysql->query("SELECT DISTINCT sid FROM ".$tb_prefix."online WHERE sid != ''"));
	
	$member_sql = $mysql->query("SELECT user_id, user_name, user_level FROM ".$tb_prefix."user WHERE user_online = 1 AND user_timeout > ".$time_exit." ORDER BY user_timeout DESC");
	$members = $mysql->num_rows($member_sql);
	$total = $guests + $members;
	
	while($r = $mysql->fetch_array($member_sql)){
		$id = $r['user_id'];
		$level = $r['user_level'];
		$name = $r['user_name'];
		if ($id == $_SESSION['user_id']) $s = $c['this'];
		elseif ($level == 1) $s = $c['member'];
		elseif ($level == 2) $s = $c['mod'];
		elseif ($level == 3) $s = $c['admin'];
		$online_list .= $tpl->assign_vars($s,
			array(
				'user.ID'	=>	$id,
				'user.NAME'	=>	$name,
				'user.URL'	=>	'#User,'.$id,
			)
		);
	}
	$html = $tpl->assign_vars($html,
			array(
				'TOTAL'		=>	$total,
				'GUESTS'	=>	$guests,
				'MEMBERS'	=>	$members,
			)
		);
	
	$html = $tpl->assign_blocks_content($html,
		array(
			'user_online'	=>	$online_list,
		)
	);
	return $html;
}
?>