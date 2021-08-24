<?php
/*
Các file thêm vào:
	request.php
	templates/[templates]/request.html
	
Các file cần edit:
	index.php
	media.js


*/

session_start();
if (!defined('IN_MEDIA')) die("Hacking attempt");
function isFloodPost(){
	$_SESSION['current_message'] = time();
	global $wait_request;
	$timeDiff_request = $_SESSION['current_message']-$_SESSION['prev_message'];

// Thiết lập thời gian chờ giữa các lần Request
	$floodInterval_request	= 45;
	$wait_request = $floodInterval_request - $timeDiff_request ;
	
	if($timeDiff_request <= $floodInterval_request){
		return true;
	}else {
		return false;
	}
}

if ($_POST['request']) {

	if (!isset($_SESSION['prev_message'])) { $_SESSION['prev_message'] = 0;}
	if (isFloodPost($_SESSION['prev_message'])) {
			echo '<p align="center"><img src=img/warning.gif width=100 height=100></p><p align="center"><b>Bạn cần phải chờ thêm '.$wait_request.' giây nữa để có thể gửi thêm một lời yêu cầu nhạc tiếp theo.</b></p>';
		
			//save it for future reference
			//$_SESSION['prev_message'] = time();
			exit();
	}

	$warn = '';
	$title_request = m_htmlchars(stripslashes(trim(urldecode($_POST['title_request']))));
	$singer_request = m_htmlchars(stripslashes(trim(urldecode($_POST['singer_request']))));
	$author_request = m_htmlchars(stripslashes(trim(urldecode($_POST['author_request']))));
	$info_request = htmlspecialchars(stripslashes(trim(urldecode($_POST['info_request']))));
	$ym_request = m_htmlchars(stripslashes(trim(urldecode($_POST['ym_request']))));
	$email = stripslashes(trim(urldecode($_POST['email_request'])));
	$ip = $_SERVER["REMOTE_ADDR"];
	$date = date("d-m-Y");
	if (!m_check_email($email)) $warn .= "Email không hợp lệ<br>";
	
	if ($warn) echo "<p align=\"center\"><img src=img/warning.gif width=100 height=100></p><p align=\"center\"><b>Lỗi</b> : <br>".$warn."</p>";
	else {
		$mysql->query("INSERT INTO ".$tb_prefix."request (request_title,request_singer,request_author,request_info,request_ym,request_email,request_ip,request_date) VALUES ('".$title_request."','".$singer_request."','".$author_request."','".$info_request."','".$ym_request."','".$email."','".$ip."','".$date."')");
		echo '<p align="center"><img src=img/music.gif width=100 height=100></p><p align="center"><b>Lời yêu cầu nhạc của bạn đã được gửi cho chúng tôi.<br /> Chúng tôi sẽ cố gắng đáp ứng yêu cầu của bạn trong thời gian sớm nhất.</b></p>';
	}
	$_SESSION['prev_message'] = time();
	exit();
}

$html = $tpl->get_tpl('request');
$tpl->parse_tpl($html);

?>