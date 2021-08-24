<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
$error_arr = array();
//--------------FORUM------------------------
$config_arr = array(
	'site_off'	=>
		array(
			'name'	=>	'site_off',
			'desc'	=>	'Đóng cửa Website',
			'type'	=>	'true_false',
		),
	'site_off_announcement'	=>
		array(
			'name'	=>	'site_off_announcement',
			'desc'	=>	'Thông báo Đóng cửa Website',
			'type'	=>	'text',
		),
);

if ($submit && $_POST) {
	$list = array_keys($_POST);
	$ok = true;
	for ($i=0;$i<count($list);$i++) {
		$key = $list[$i];
		$vl = addslashes($_POST[$key]);
		if ($key == 'web_url') 
			if ($vl[strlen($vl)-1] == '/') $vl = substr($vl,0,-1);
		if ($key == 'announcement' && $vl == '<br>') $vl = '';
		if ($key == 'submit') continue;
		if (!array_key_exists($key,$config_arr)) continue;
		$arr = $config_arr[$r['config_name']];
		if ($check[0] == 'number' && !is_numeric($vl)) { $ok = false; $error_arr[] = $key; }
		if ($ok) $mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$vl."' WHERE config_name = '".$key."'");
	}
	if ($ok) {
		echo "Đã nhập xong dữ liệu<meta http-equiv='refresh' content='0;url=$link'>";
		exit();
	}
}

//--------------------------------------------
echo "<form method=post>".
	"<table class=border cellpadding=2 cellspacing=0 width=90%>".
	"<tr><td colspan=2 class=title align=center>Configures</td></tr>";
$q = $mysql->query("SELECT * FROM ".$tb_prefix."config ORDER BY config_name ASC");
while ($r = $mysql->fetch_array($q)) {
	if (!$submit && !count($error_arr)) $vl = stripslashes($r['config_value']);
	else $vl = stripslashes($_POST[$r['config_name']]);
	if (array_key_exists($r['config_name'],$config_arr)) {
		$arr = $config_arr[$r['config_name']];
		if (in_array($r['config_name'],$error_arr)) $symbol = "<font style='color:red'>*</font> ";
		else $symbol = '';
		echo "<tr><td class=fr><b>".$arr['desc']."</b> : </td><td class=fr_2>";
		if (!$arr['type'] || $arr['type'] == 'number' || $arr['type'] == 'free') echo "<input name=".$r['config_name']." size=50 value='".$vl."'>";
		elseif ($arr['type'] == 'text') echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../js/openwysiwyg/wysiwyg.js\"></script><textarea cols=60 rows=10 id=".$r['config_name']." name=".$r['config_name'].">".$vl."</textarea>"."<script language=\"JavaScript\">generate_wysiwyg('".$r['config_name']."');</script>";
		elseif ($arr['type'] == 'true_false')
			echo "<input type=radio name=".$r['config_name']." value=1".(($r['config_value'] == 1)?' checked':'')."> Đúng <input type=radio name=".$r['config_name']." value=0".(($r['config_value'] == 0)?' checked':'')."> Sai";
		if ($arr['type'] == 'number' && in_array($r['config_name'],$error_arr)) echo " Dữ liệu phải là số.";
		echo "</td></tr>";
	}
}
echo "<tr><td colspan=2 align=center><input class=submit name=submit type=submit value=Submit> <input type=reset class=submit value='Reset'></td></tr>";
echo "</table></form>";
?>