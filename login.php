<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if ($isLoggedIn) {
	echo "<center><b>Bạn đã đăng nhập</b></center>";
	exit();
}
if ($_POST['login']) {
	$warn = '';
	$name = m_htmlchars(stripslashes(trim(urldecode($_POST['name']))));
	$pwd = stripslashes(urldecode($_POST['pwd']));
	$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."' AND user_password = '".md5($pwd)."'");
	if (!$mysql->num_rows($q)) {
		if (m_check_random_str($pwd,15))
			$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."' AND (user_new_password = '".$pwd."' AND user_new_password != '')");
		if (!$mysql->num_rows($q))	
			$warn .= "Username hoặc mật khẩu không chính xác<br>";
	}
	if ($warn) echo "<b>Lỗi</b> : <br>".$warn;
	else {
		$r = $mysql->fetch_array($q);
		$_SESSION['user_id'] = $r['user_id'];
		$salt = 'Nothing';
		$identifier = md5($salt.IP.$r['user_id'].$salt);
		$timeout = NOW + 2*60*60;
		m_setcookie('USER', $identifier);
		$mysql->query("DELETE FROM ".$tb_prefix."online WHERE sid = '".SID."'");
		$mysql->query("UPDATE ".$tb_prefix."user SET user_online = 1, user_ip = '".IP."', user_identifier = '".$identifier."', user_timeout = '".$timeout."' WHERE user_id = '".$r['user_id']."'");
	}
	exit();
}
else {
	$main = $tpl->get_tpl('login');
	$tpl->parse_tpl($main);
}
?>