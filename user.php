<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");

if ($_POST['email'] && $_POST['change_info']) {
	$warn = '';
	$change_pwd = false;
	$email = trim(urldecode($_POST['email']));
	$oldpwd = stripslashes(trim(urldecode($_POST['oldpwd'])));
	$newpwd = stripslashes(trim(urldecode($_POST['newpwd'])));
	$sex = ($_POST['sex'])?$_POST['sex']:1;
	
	$hide_sex = ($_POST['hide_sex'] == 'true')?1:0;
	$hide_email = ($_POST['hide_email'] == 'true')?1:0;
	$hide_info = $hide_sex.$hide_email;
	
	$hide_info = bindec($hide_info);
	
	if (!m_check_email($email)) $warn .= "Email không hợp lệ<br>";
	if ($oldpwd && $newpwd) {
		$change_pwd = true;
		//$oldpwd = $oldpwd;
		$newpwd = md5($newpwd);
		$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_id = '".$_SESSION['user_id']."' AND user_password = '".md5($oldpwd)."'");
		if (!$mysql->num_rows($q)) {
			if (m_check_random_str($oldpwd,15))
				$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_id = '".$_SESSION['user_id']."' AND (user_new_password = '".$oldpwd."' AND user_new_password != '')");
			if (!$mysql->num_rows($q))	
				$warn .= "Mật khẩu cũ không chính xác.<br>";
		}
		/*
		$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_id = '".$_SESSION['user_id']."' AND ( user_password = '".md5($oldpwd)."' OR (user_new_password = '".$oldpwd."' AND user_new_password != ''))");
		if ($mysql->num_rows($q)) {
			$mysql->query("UPDATE ".$tb_prefix."user SET user_password = '".$newpwd."', user_new_password = '' WHERE user_id = '".$_SESSION['user_id']."'");
		}
		else $warn .= "Mật khẩu cũ không chính xác.<br>";
		*/
	}
	if ($warn) echo "<center><b>Lỗi</b> : <br>".$warn."</center>";
	else {
		$mysql->query("UPDATE ".$tb_prefix."user SET user_hide_info = '".$hide_info."', user_email = '".$email."', user_sex = '".$sex."' WHERE user_id = '".$_SESSION['user_id']."'");
		if ($change_pwd) {
			$mysql->query("UPDATE ".$tb_prefix."user SET user_lastvisit = '".NOW."', user_identifier = '', user_timeout = '', user_ip = '', user_online = 0 WHERE user_id = '".$_SESSION['user_id']."'");
			m_setcookie('INFO', '', false);
			session_destroy();
		}
		else echo "<center><b>Đã sửa xong thông tin của bạn.</center>";
	}
	exit();
}
elseif ($_POST['email'] && $_POST['forgot']) {
	$warn = '';
	$email = trim(urldecode($_POST['email']));
	$q = $mysql->query("SELECT user_name FROM ".$tb_prefix."user WHERE user_email = '".$email."'");
	if ($mysql->num_rows($q)) {
		$r = $mysql->fetch_array($q);
		$user_name = $r['user_name'];
		$new_password = m_random_str(15);
		$web_email = m_get_config('web_email');
		$title = $webTitle." : Mat khau moi";
		$header = m_build_mail_header($email,$web_email);
		$content = "Chao <b>".$user_name."</b>,<br>".
			"Mat khau moi cua ban : <b>".$new_password."</b> <br>".
			"Ban nho doi mat khau lai ngay sau khi dang nhap.<br>".
			"<a href='".$mainURL."'><b>".$webTitle."</b></a>";
		if ( mail($email,$title,$content,$header) ) {
			$mysql->query("UPDATE ".$tb_prefix."user SET user_new_password = '".$new_password."' WHERE user_name = '".$user_name."'");
		}
		else $warn .= "Host không hỗ trợ Mail";
	}
	else $warn .= "Không có email này";
	if ($warn) echo "<b>Lỗi : </b><br>".$warn;
	else echo "Mật khẩu mới sẽ được gởi đến email của bạn trong vài phút nữa.<br>Bạn nhớ đổi mật khẩu lại ngay sau khi đăng nhập";
	exit();
}
if ($value[0] == 'User' && is_numeric($value[1])) {
	$u_id = $value[1];
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_id = '".$u_id."'");
	if (!$mysql->num_rows($q)) {
		echo "<center><b>Không có thành viên này</b></center>";
		exit();
	}
	$main = $tpl->get_tpl('user_info');
	$r = $mysql->fetch_array($q);
	$name = $r['user_name'];
	
	$hide_info = m_get_data('USER',$u_id,'user_hide_info');
	
	$sex = $r['user_sex'];
	$sex = ($sex == 1)?"Nam":"Nữ";
	
	$sex = (!$hide_info['hide_sex'])?$sex:'Dấu thông tin';
	
	if (!$hide_info['hide_email']) {
		$email = $r['user_email'];
		if (strstr($email,'@yahoo.com')) {
			$e = split('@yahoo.com',$email);
			$email = "<a href=ymsgr:sendIM?".$e[0]."><img src='http://opi.yahoo.com/online?u=".$e[0]."'></a> ".$email;
		}
	}
	else $email = 'Dấu thông tin';
	
	$level = $r['user_level'];
	switch ($level) {
		case 1	:	$level = "Member"; break;
		case 2	:	$level = "Moderator"; break;
		case 3	:	$level = "Admin"; break;
	}
	
	$main = $tpl->assign_vars($main,
		array(
			'user.NAME'	=>	$name,
			'user.EMAIL'	=>	$email,
			'user.LEVEL'	=>	$level,
			'user.SEX'	=>	$sex,
		)
	);
	$tpl->parse_tpl($main);
}
elseif ($value[0] == 'List_User') {
	$m_per_page = 20;
	if (!$value[1]) $value[1] = 1;
	$limit = ($value[1]-1)*$m_per_page;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user ORDER BY user_name ASC LIMIT ".$limit.",".$m_per_page);
	$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(user_id) FROM ".$tb_prefix."user"));
	$tt = $tt[0];
	if ($mysql->num_rows($q)) {
		
		$main = $tpl->get_tpl('list_user');
		$t['row'] = $tpl->get_block_from_str($main,'list_row',1);
		
		$html = '';
		while ($r = $mysql->fetch_array($q)) {
			static $i = 0;
			$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
			$id = $r['user_id'];
			$name = $r['user_name'];
			
			$hide_info = m_get_data('USER',$u_id,'user_hide_info');
			
			$email = (!$hide_info['hide_email'])?$r['user_email']:'Dấu thông tin';
			$html .= $tpl->assign_vars($t['row'],
				array(
					'user.NAME'	=>	$name,
					'user.URL'	=>	'#User,'.$id,
					'user.EMAIL'	=>	$email,
					'user.CLASS'	=>	$class,
				)
			);
			$i++;
		}
		$class = (fmod($i,2) == 0)?'m_list':'m_list_2';
		$main = $tpl->assign_vars($main,
			array(
				'CLASS' => $class,
				'TOTAL'	=> $tt,
				'VIEW_PAGES' => m_viewpages($tt,$m_per_page,$value[1]),
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
elseif ($value[0] == 'Change_Info' && $isLoggedIn) {
	$html = $tpl->get_tpl('user_change_info');
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_id = '".$_SESSION['user_id']."'");
	$r = $mysql->fetch_array($q);
	
	$hide_info = m_get_data('USER',$_SESSION['user_id'],'user_hide_info');
	$u_sex =
		"<input name=u_sex type=radio class=checkbox value=1".(($r['user_sex'] == 1)?' checked':'')."> Nam &nbsp; ".
		"<input name=u_sex type=radio class=checkbox value=2".(($r['user_sex'] == 2)?' checked':'')."> Nữ";
	$html = $tpl->assign_vars($html,
		array(
			'user.EMAIL'	=>	$r['user_email'],
			'user.SEX'		=>	$u_sex,
			'user.HIDE_EMAIL'	=>	($hide_info['hide_email'])?' checked':'',
			'user.HIDE_SEX'	=>	($hide_info['hide_sex'])?' checked':'',
		)
	);
	$tpl->parse_tpl($html);
}
elseif ($value[0] == 'Forgot_Password' && !$isLoggedIn) {
	$html = $tpl->get_tpl('forgot_password');
	$tpl->parse_tpl($html);
}
?>