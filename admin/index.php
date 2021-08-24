<?php
define('IN_MEDIA', true);
define('IN_MEDIA_ADMIN', true);
include("../includes/config.php");
include("../includes/functions.php");
include("../includes/class_form.php");

$level = m_check_level($_SESSION['admin_id']);

if ($act == 'backup' && $_POST['submit'] && $level == 3) {
	include("media_dbbackup.php");
	exit();
}
$form =& new HTMLForm;
?>
<html>
<head>
	<?php if ($act == 'left') echo '<base target="frame_content">'; ?>
	<title>.: <?=$webTitle?> CP :.</title>
	<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
	<link rel=stylesheet href="style.css" type=text/css>
</head>
<? if (!$level) { ?>
<form method="post" action="login.php">
<table width=31% align=center cellpadding=2 cellspacing=0 class=border bgcolor=white>
	<tr><td colspan=2 align=center class=title>Đăng nhập</td></tr>
	<tr><td width=48% class=fr>Tên đăng nhập</td><td width=52% class=fr_2><input name="name" type="text" size="20"></td></tr>
	<tr><td class=fr>Mật khẩu</td><td class=fr_2><input name="password" type="password" size="20"></td></tr>
	<tr><td class=fr colspan=2 align=center><input class="submit" type="submit" name="submit" value="Đăng nhập"></td></tr>
</table>
</form>
<?
	exit();
}
include("admin_functions.php");

if ($level == 2) $mod_permission = acp_get_mod_permission();
$link = 'index.php';
if ($_SERVER["QUERY_STRING"]) $link .= '?'.$_SERVER["QUERY_STRING"];
?>
<script src="../js/admin.js"></script><script src="../js/him.js"></script>
<?php
if ($act) echo '<table cellspacing="0" align="center" cellpadding="0" width="100%"><tr><td align="center" width="100%">';
switch($act){
	case "song":	include("media_song.php");break;
	case "song_multi_edit":	include("media_song_multi_edit.php");break;
	case "singer":	include("media_singer.php");break;
	case "cat":		include("media_cat.php");break;
	case "album":	include("media_album.php");break;
	case "tpl":		include("media_tpl.php");break;
	case "ads":		include("media_ads.php");break;
	case "user":	include("media_user.php");break;
	case "config":	include("media_configures.php");break;
	case "backup":	include("media_dbbackup.php");break;
	case "server":	include("media_server.php");break;
	case "mod_permission":	include("media_mod_permission.php");break;
	case "main"	:	echo "Welcome to XtreMedia CP"; break;
	case "header" :	echo "<div class=title><b>".$webTitle." Control Panel</b></div>";break;
	case "left"	:	include("left.php");break;
	default :
		echo '<script type="text/javascript">if (self.parent.frames.length != 0) self.parent.location.replace(document.location.href);</script>';
	?>
	<frameset cols="200,*" rows="*" id="mainFrameset">
		<frame src="index.php?act=left" name="frame_navigation" frameborder="0" noresize />
		<frameset rows="20,*">
			<frame src="index.php?act=header" frameborder="0" noresize />
			<frame src="index.php?act=main" name="frame_content" id="frame_content" frameborder="0" noresize />
		</frameset>
	</frameset>
	<?
		break;
}
if ($act) echo '</td></tr></table>';
?>
</html>