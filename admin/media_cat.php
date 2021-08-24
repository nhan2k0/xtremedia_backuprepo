<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=cat&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'cat_name',
			'name'	=>	'Tên thể loại',
			'type'	=>	'free'
		),
		'order'	=> array(
			'table'	=>	'cat_order',
			'name'	=>	'Thứ tự',
			'type'	=>	'number',
			'can_be_empty'	=>	true,
		),
		'sub'	=> array(
			'table'	=>	'sub_id',
			'name'	=>	'Cấp độ của chuyên mục',
			'type'	=>	'function::acp_maincat::number'
		),
	);
##################################################
# ADD MEDIA CAT
##################################################
if ($mode == 'add') {
	acp_check_permission('add_cat');
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'cat'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm thể loại',$inp_arr,$error_arr);
}
##################################################
# EDIT MEDIA CAT
##################################################
if ($mode == 'edit') {
	
	acp_check_permission('edit_cat');
	
	if ($cat_id) {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_id = '$cat_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'cat','cat_id','cat_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa thể loại',$inp_arr,$error_arr);
	}
	else {
		if ($_POST['sbm']) {
			$z = array_keys($_POST);
			$q = $mysql->query("SELECT cat_id FROM ".$tb_prefix."cat");
			for ($i=0;$i<$mysql->num_rows($q);$i++) {
				$id = split('o',$z[$i]);
				$od = ${$z[$i]};
				$mysql->query("UPDATE ".$tb_prefix."cat SET cat_order = '$od' WHERE cat_id = '".$id[1]."'");
			}
		}
		echo "<script>function check_del(id) {".
		"if (confirm('Bạn có muốn xóa thể loại này không ?')) location='?act=cat&mode=del&cat_id='+id;".
		"return false;}</script>";
		echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form method=post>";
		echo "<tr><td align=center class=title width=5%>STT</td><td class=title style='border-right:0'>Tên thể loại</td></tr>";
		$cat_query = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE (sub_id IS NULL OR sub_id = 0) ORDER BY cat_order ASC");
		while ($cat = $mysql->fetch_array($cat_query)) {
			echo "<tr align=center><td colspan=2 class=cat_title>".$cat['cat_title']."</td></tr>";
			$iz = $cat['cat_order'];
			echo "<tr><td align=center class=fr><input onclick=this.select() type=text name='o".$cat['cat_id']."' value=$iz size=2 style='text-align:center'></td><td class=fr_2><a href=# onclick=check_del(".$cat['cat_id'].")>Xóa</a> - <a href='$link&cat_id=".$cat['cat_id']."'><b>".$cat['cat_name']."</b></a></td></tr>";
			$sub_query = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE sub_id = '".$cat['cat_id']."' ORDER BY cat_order ASC");
			if ($mysql->num_rows($sub_query)) echo "<tr><td class=fr_2>&nbsp;</td><td class=fr><table width=100% cellpadding=2 cellspacing=0 class=border>";
			while ($sub = $mysql->fetch_array($sub_query)) {
				$s_o = $sub['cat_order'];
				echo "<tr><td align=center class=fr width=5%><input onclick=this.select() type=text name='o".$sub['cat_id']."' value=$s_o size=2 style='text-align:center'></td><td class=fr_2><a href=# onclick=check_del(".$sub['cat_id'].")>Xóa</a> - <a href='$link&cat_id=".$sub['cat_id']."'><b>".$sub['cat_name']."</b></a></td></tr>";
			}
			if ($mysql->num_rows($sub_query)) echo "</table></td></tr>";
		}
		echo '<tr><td colspan="2" align="center"><input type="submit" name="sbm" class=submit value="Sửa thứ tự"></td></tr>';
		echo '</form></table>';
	}
	
}
##################################################
# DELETE MEDIA CAT
##################################################
if ($mode == 'del') {
	acp_check_permission('del_cat');
	if ($cat_id) {
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."cat WHERE cat_id = '".$cat_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa thể loại này không ??????<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>