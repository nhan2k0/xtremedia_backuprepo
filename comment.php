<?php
define('IN_MEDIA',true);
include('includes/config.php');
include('includes/functions.php');
include('includes/class_template.php');
$isLoggedIn = m_checkLogin();
if (!$isLoggedIn && !$_POST['showcomment']) die("<center><b>Bạn chưa đăng nhập</b></center>");
$tpl =& new Template;

if ($_POST['showcomment'] && $_POST['media_id']) {
	$id = (int)$_POST['media_id'];
	
	$main = $tpl->get_tpl('comment');
	
	if (!$isLoggedIn) {
		$main = $tpl->assign_blocks_content($main,
			array(
				'write_comment'	=>	'<div><b><center>Bạn cần đăng nhập mới có thể viết cảm nhận</center></b></div>',
			)
		);
		//$main = $tpl->unset_block($main,array('write_comment'));
	}
	
	$main = $tpl->assign_vars($main,
		array(
			'media.ID'	=>	$id,
		)
	);
	
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."comment WHERE comment_media_id = '".$id."' ORDER BY comment_time ASC");
	if ($mysql->num_rows($q)) {
		$comment_block = $tpl->get_block_from_str($main,'comment_block');
		$comment = $tpl->get_block_from_str($comment_block,'comment',1);
		
		$html = '';
		$unset = false;
		$i = 0;
		while ($r = $mysql->fetch_array($q)) {
			$i++;
			if (!m_check_level($_SESSION['user_id']) && !$unset) {
				$comment = $tpl->unset_block($comment,array('functions'),1);
				$unset = true;
			}
			
			$class = (fmod($i,2) == 0)?'comment_1':'comment_2';
			
			$content = m_emotions_replace(m_text_tidy($r['comment_content'],1));
			
			$html .= $tpl->assign_vars($comment,
				array(
					'comment.CLASS'		=>	$class,
					'comment.POSTER'	=>	m_get_data('USER',$r['comment_poster']),
					'comment.POSTER_URL'	=>	'#User,'.$r['comment_poster'],
					'comment.TIME'		=>	date('d-m-Y',$r['comment_time']),
					'comment.CONTENT'	=>	$content,
					'comment.ID'	=>	$r['comment_id'],
				)
			);
		}
		
	}
	else $html = 'Chưa có cảm nhận nào !';
	
	$main = $tpl->assign_blocks_content($main,
		array(
			'comment_block'	=>	$html,
		)
	);
	
	$main = $tpl->assign_vars($main,
		array(
			'song.ID'	=>	$id,
		)
	);
	$tpl->parse_tpl($main);
	exit();
}
elseif ($_POST['comment'] && $_POST['media_id'] && $_POST['comment_content']) {
	$warn = '';
	$media_id = (int)$_POST['media_id'];
	$comment_content = substr(stripslashes(trim(urldecode($_POST['comment_content']))),0,255);
	if ($comment_content) {
		//$q = $mysql->query("SELECT comment_id FROM ".$tb_prefix."comment WHERE comment_media_id = '".$media_id."' AND comment_content = '".$comment_content."' AND comment_poster = '".$_SESSION['user_id']."'");
		$mysql->query("DELETE FROM ".$tb_prefix."manage WHERE manage_timeout < '".NOW."'");
		$q = $mysql->query("SELECT manage_timeout FROM ".$tb_prefix."manage WHERE manage_type = 'Comment' AND manage_user = '".$_SESSION['user_id']."' AND manage_media = '".$media_id."' AND manage_timeout >= '".NOW."'");
		//$r = $mysql->fetch_array($q);
		if (!$mysql->num_rows($q) || m_check_level($_SESSION['user_id'])) {
			$mysql->query("INSERT INTO ".$tb_prefix."comment (comment_media_id,comment_poster,comment_content,comment_time) VALUES ('".$media_id."','".$_SESSION['user_id']."','".$comment_content."','".NOW."')");
			if (!m_check_level($_SESSION['user_id'])) $mysql->query("INSERT INTO ".$tb_prefix."manage VALUES ('Comment','".$_SESSION['user_id']."','".$media_id."','".(NOW + 60*2)."')");
			//echo "OK";
		}
		else {
			$r = $mysql->fetch_array($q);
			$warn = "Bạn cần chờ thêm <b>".($r['manage_timeout'] - NOW)."</b> giây nữa mới có thể viết tiếp cảm nhận";
		}
	}
	else $warn = "Bạn chưa nhập cảm nhận";
	if ($warn) echo "<b>Lỗi :</b> ".$warn;
	else echo "OK";
	exit();
}
elseif ($_POST['delete'] && is_numeric($_POST['comment_id']) && is_numeric($_POST['media_id'])) {
	$comment_id = (int)$_POST['comment_id'];
	$media_id = (int)$_POST['media_id'];
	if (m_check_level($_SESSION['user_id'])) {
		$mysql->query("DELETE FROM ".$tb_prefix."comment WHERE comment_media_id = '".$media_id."' AND comment_id = '".$comment_id."'");
		echo 'OK';
		//echo "<b>Đã xóa cảm nhận thành công. Nhấn F5 để Refresh.</b>";
	}
	
	//header("Location: comment.php?id=".$media_id);
}
?>