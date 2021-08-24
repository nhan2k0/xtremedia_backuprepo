<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
if ($isLoggedIn) {
	echo "<center><b>Bạn đã đăng nhập</b></center>";
	exit();
}
if ($_POST['reg']) {
	$warn = '';
	$name = m_htmlchars(stripslashes(trim(urldecode($_POST['name']))));
	$pwd = md5(stripslashes(urldecode($_POST['pwd'])));
	$email = stripslashes(trim(urldecode($_POST['email'])));
	$sex = ($_POST['sex'])?$_POST['sex']:1;
	
	if ($mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."'"))) $warn .= "Tài khoản này đã có người sử dụng<br>";
	
	if (!m_check_email($email)) $warn .= "Email không hợp lệ<br>";
	elseif ($mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_email = '".$email."'"))) $warn .= "Email này đã có người sử dụng<br>";
	
	if ($warn) echo "<b>Lỗi</b> : <br>".$warn;
	else {
		$playlist_id = m_random_str(20);
		$mysql->query("INSERT INTO ".$tb_prefix."user (user_name,user_password,user_email,user_sex,user_regdate,user_playlist_id) VALUES ('".$name."','".$pwd."','".$email."','".$sex."',NOW(),'".$playlist_id."')");
	}
	exit();
}
$main = $tpl->get_tpl('register');
$tpl->parse_tpl($main);
?>