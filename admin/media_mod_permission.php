<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}

$mod_permission = acp_get_mod_permission();

$permission_list = array(
	'add_cat'	=>	'Thêm Thể loại',
	'edit_cat'	=>	'Quản lý & Sửa Thể loại',
	'del_cat'	=>	'Xóa Thể loại',
	'add_media'	=>	'Thêm Media',
	'edit_media'	=>	'Quản lý & Sửa Media',
	'del_media'	=>	'Xóa Media',
	'add_singer'	=>	'Thêm Ca sỹ',
	'edit_singer'	=>	'Quản lý & Sửa Ca sỹ',
	'del_singer'	=>	'Xóa Ca sỹ',
	'add_album'	=>	'Thêm Album',
	'edit_album'	=>	'Quản lý & Sửa Album',
	'del_album'	=>	'Xóa Album',
	'add_user'	=>	'Thêm User',
	'edit_user'	=>	'Quản lý & Sửa User',
	'del_user'	=>	'Xóa User',
	'add_link'	=>	'Thêm Liên kết',
	'edit_link'	=>	'Quản lý & Sửa Liên kết',
	'del_link'	=>	'Xóa Liên kết',
	'add_template'	=>	'Thêm Giao diện',
	'edit_template'	=>	'Quản lý & Sửa Giao diện',
	'del_template'	=>	'Xóa Giao diện',
	'edit_request'	=>	'Quản lý Request',
);

if (!$_POST['submit']) {
?>
<form method=post>
<table class=border cellpadding=2 cellspacing=0 width=95%>
<tr><td colspan=2 class=title align=center>Phân quyền cho MOD</td></tr>
<?php
foreach ($permission_list as $name => $desc) {
?>
<tr>
	<td class=fr width=30%><b><?=$desc?></b></td>
	<td class=fr_2><input type=radio class=checkbox value=1 name=<?=$name?><?=(($mod_permission[$name])?' checked':'')?>> Đúng <input type=radio class=checkbox value=0 name=<?=$name?><?=((!$mod_permission[$name])?' checked':'')?>> Sai </td>
</tr>
<?php
}
?>
<tr><td class=fr colspan=2 align=center><input type=submit name=submit class=submit value=Submit></td></tr>
</table>
</form>
<?php
}
else {
	$per = '';
	foreach ($permission_list as $name => $desc) {
		$v = $_POST[$name];
		if ($v == '') $v = 0;
		$per .= $v;
	}
	$per = bindec($per);
	$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$per."' WHERE config_name = 'mod_permission'");
	echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=$link'>";
}
?>