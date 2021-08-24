<?php
define('IN_MEDIA',true);
include('includes/config.php');
include('includes/functions.php');
include('includes/class_template.php');
$isLoggedIn = m_checkLogin();
if (!$isLoggedIn) die("<center><b>Bạn chưa đăng nhập</b></center>");
$tpl =& new Template;

if ($_POST['gift'] && $_POST['media_id']) {
	$warn = '';
	$media_id = $_POST['media_id'];
	$sender_id = $_SESSION['user_id'];
	$sender_name = m_htmlchars(stripslashes(trim(urldecode($_POST['sender_name']))));
	$recip_name = m_htmlchars(stripslashes(trim(urldecode($_POST['recip_name']))));
	$sender_email = stripslashes(trim(urldecode($_POST['sender_email'])));
	$recip_email = stripslashes(trim(urldecode($_POST['recip_email'])));
	$message = substr(stripslashes(trim(urldecode($_POST['message']))),0,255);
	if ($sender_name && $recip_name && $sender_email && $recip_email && $message) {
		if (!m_check_email($sender_email)) $warn = "Email của bạn không hợp lệ";
		elseif (!m_check_email($recip_email)) $warn = "Email người nhận không hợp lệ";
		else {
			$q = $mysql->query("SELECT gift_id FROM ".$tb_prefix."gift WHERE gift_sender_id = '".$sender_id."' AND gift_recip_email = '".$recip_email."' AND gift_media_id = '".$media_id."' AND gift_message = '".$message."'");
			$r = $mysql->fetch_array($q);
			if (!$mysql->num_rows($q)) {
				$gift_id = m_random_str(20);
				$title = $webTitle." : Tang nhac";
				$header = m_build_mail_header($recip_email,$sender_email);
				$link = $mainURL."/#Gift,".$gift_id;
				$time = NOW;
				$web_link = "<a href='".$mainURL."' target=_blank><b>".$webTitle."</b></a>";
				$content = "Chao <b>".$recip_name."</b>,<br>".
					$sender_name." da goi cho ban mot ca khuc tai ".$web_link.".<br>".
					"De nghe ca khuc, ban chi can nhan vao duong dan ben duoi :<br>".
					"<a href='".$link."' target='_blank'>".$link."</a><br>".
					"hoac ban cung co the vao trang Web cua chung toi va su dung ma so <b>".$gift_id."</b> de nhan duoc thiep.<br>".
					$web_link;
				
				if ( mail($recip_email,$title,$content,$header) ) {
					$mysql->query("INSERT INTO ".$tb_prefix."gift VALUES ('".$gift_id."','".$media_id."','".$sender_id."','".$sender_name."','".$sender_email."','".$recip_name."','".$recip_email."','".$message."','".$time."')");
					echo "Đã gởi đi. Bạn có thể xem quà tặng với mã <b>".$gift_id."</b>";
				}
				else $warn = "Host không hỗ trợ Mail";
			}
			else {
				echo "Đã gởi đi. Bạn có thể xem quà tặng với mã số <b>".$r['gift_id']."</b>";
			}
		}
	}
	else $warn = "Bạn chưa nhập đủ thông tin";
	if ($warn) echo "<b>Lỗi :</b> ".$warn;
	exit();
}
elseif ($value[0] == 'Gift' && $value[1]) {
	
}
else {
	$id = $_GET['id'];
	$main = $tpl->get_tpl('gift');
	$main = $tpl->assign_vars($main,
		array(
			'song.ID'	=>	$id,
		)
	);
	$tpl->parse_tpl($main);
}
?>