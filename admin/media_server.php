<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
if ($mode == 'change') {
	/*
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE config_name IN ('server_address','server_username','server_password','server_port','server_folder')");
	while ($r = $mysql->fetch_array($q)) {
		$$r['config_name'] = $r['config_value'];
	}
	if (!$server_port) $server_port = 21;
	$connect_id = ftp_connect($server_address,$server_port);
	$login_result = ftp_login($connect_id, $server_username, $server_password);
	ftp_pasv($connect_id, true);
	if (!$connect_id || !$login_result) {
		echo "Kết nối với <b>".$server_address."</b> thất bại.".
		exit();
	}
	$old_dir = $server_folder;
	$new_dir = m_random_str(10);
	if ($old_dir) {
		if (ftp_rename($connect_id, $old_dir, $new_dir)) {
			echo "Sửa tên thư mục cũ <b>".$old_dir."</b> thành tên mới là <b>".$new_dir."</b><br>";
			$result= mysql_query("UPDATE ".$tb_prefix."config SET config_value = '".$new_dir."' WHERE config_name ='server_folder'");
			echo "<meta http-equiv='Refresh' content='3; URL=?act=server'>";
		}
		else {
			echo "Lỗi khi sửa tên thư mục <b>".$old_dir."</b>";
		}
	}
	else {
		if (ftp_mkdir($connect_id, $new_dir)) {
			echo "Đã tạo thư mục <b>".$new_dir."</b><br>";
			$result= mysql_query("UPDATE ".$tb_prefix."config SET config_value = '".$new_dir."' WHERE config_name ='server_folder'");
			echo "<meta http-equiv='Refresh' content='3; URL=?act=server'>";
		}
		else {
			echo "Lỗi khi tạo thư mục <b>".$new_dir."</b>";
		}
	}
	ftp_close($connect_id);
	*/
}
else {
	if (!$_POST['submit']) {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE config_name IN ('server_address','server_username','server_password','server_port','server_folder','server_url')");
		while ($r = $mysql->fetch_array($q)) {
			$$r['config_name'] = $r['config_value'];
		}
	?>
	<form method="post">
	<table cellspacing="0" width="90%" class=border>
		<tr><td colspan=2 align=center class=title><b>Config Sever Media</b></td></tr>
		<?php /*
		<tr><td class=fr width="30%">Tên server || IP server :</td><td class=fr_2><input name="s_address" size="32" value="<?=$server_address?>"></td></tr>
		<tr><td class=fr>Username FTP :</td><td class=fr_2><input name="s_username" size="32" value="<?=$server_username?>"></td></tr>
		<tr><td class=fr>Password FTP :</td><td class=fr_2><input type="password" name="s_password" size="32" value="<?=$server_password?>"></td></tr>
		<tr><td class=fr>Port FTP<br>( thường là 21 ):</td><td class=fr_2><input name="s_port" size="32" value="<?=$server_port?>"></td></tr>
		<tr><td class=fr>Folder chứa nhạc :</td><td class=fr_2><b><?=$server_folder?></b> ---> <a href="<?=$link?>&mode=change"><b>Đổi thư mục</b></a></td></tr>*/ ?>
		<tr><td class=fr>Folder chứa nhạc :</td><td class=fr_2><input name="s_folder" size="32" value="<?=$server_folder?>"></td></tr>
		<tr><td class=fr>Link Host chứa nhạc :</td><td class=fr_2><input name="s_url" size="50" value="<?=$server_url?>"></td></tr>
		<tr><td colspan="2" align="center" class=fr><input type="submit" value="Sửa Sever" name=submit class=submit></td></tr>
		<tr><td colspan=2>
		Link nhạc có dạng như sau : http://link_host_chua_nhac/folder_nhac/link_nhac.wma<br>
		Bạn upload link_nhac.wma lên folder_nhac<br>
		Ở đây là : Play Link nhạc : <b><?=$server_url?>/<?=$server_folder?>/linknhac.wma</b>
		</td></tr>
	</td></tr>
	</table>
	</form>
	<?php
	}
	else {
		/*
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_address."' WHERE config_name = 'server_address'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_username."' WHERE config_name = 'server_username'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_password."' WHERE config_name = 'server_password'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_port."' WHERE config_name = 'server_port'");
		*/
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_url."' WHERE config_name = 'server_url'");
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$s_folder."' WHERE config_name = 'server_folder'");
		echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=$link'>";
	}
}
?>