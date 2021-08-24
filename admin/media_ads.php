<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=ads&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'ads_web',
			'name'	=>	'Tên Web',
			'type'	=>	'free'
		),
		'url'	=> array(
			'table'	=>	'ads_url',
			'name'	=>	'Link',
			'type'	=>	'free'
		),
		'logo'	=> array(
			'table'	=>	'ads_img',
			'name'	=>	'Logo',
			'type'	=>	'free'
		),
	);
##################################################
# ADD ADS
##################################################
if ($mode == 'add') {
	acp_check_permission('add_link');
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'ads'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm Liên kết',$inp_arr,$error_arr);
}
##################################################
# EDIT ADS
##################################################
if ($mode == 'edit') {
	acp_check_permission('edit_link');
	if ($ads_id) {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."ads WHERE ads_id = '$ads_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'ads','ads_id','ads_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa thể loại',$inp_arr,$error_arr);
	}
	else {
		echo "<script>function check_del(id) {".
		"if (confirm('Bạn có muốn xóa liên kết này không ?')) location='?act=ads&mode=del&ads_id='+id;".
		"return false;}</script>";
		echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form method=post>";
		echo "<tr><td align=left width=40% class=title>Web</td><td class=title width=40%>URL</td><td align=center class=title>Logo</td></tr>";
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."ads ORDER BY ads_id ASC");
		while ($r = $mysql->fetch_array($q)) {
			echo "<tr align=center class=fr><td><a href=?act=ads&mode=del&ads_id=".$r['ads_id']."'>DEL</a> - <a href=\"$link&ads_id=".$r['ads_id']."\"><b>".$r['ads_web']."</b></a></td><td class=fr_2><a href=\"".$r['ads_url']."\" target=_blank><b>".$r['ads_url']."</b></a></td><td class=fr><img src=\"".$r['ads_img']."\" width=160 height=80></td></tr>";
		}
		echo '<tr><td colspan="2" align="center"><input type="submit" name="sbm" class=submit value="Sửa thứ tự"></td></tr>';
		echo '</form></table>';
	}
	
}
##################################################
# DELETE ADS
##################################################
if ($mode == 'del') {
	acp_check_permission('del_link');
	if ($ads_id) {
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."ads WHERE ads_id = '".$ads_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa Liên kết này không ??????<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>