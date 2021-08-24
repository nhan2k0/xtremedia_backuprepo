<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=tpl&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'tpl_fname',
			'name'	=>	'Tên giao diện',
			'type'	=>	'free'
		),
		'short_name'	=> array(
			'table'	=>	'tpl_sname',
			'name'	=>	'Tên thư mục chứa giao diện',
			'type'	=>	'free'
		),
		'order'	=> array(
			'table'	=>	'tpl_order',
			'name'	=>	'Thứ tự giao diện',
			'type'	=>	'number'
		),
	);
##################################################
# ADD
##################################################
if ($mode == 'add') {
	acp_check_permission('add_template');
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'tpl'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm giao diện',$inp_arr,$error_arr);
}
##################################################
# EDIT
##################################################
if ($mode == 'edit') {
	acp_check_permission('edit_template');
	if (!$tpl_id) {
		if ($_POST['sbm']) {
			$z = array_keys($_POST);
			$q = $mysql->query("SELECT tpl_id FROM ".$tb_prefix."tpl");
			for ($i=0;$i<$mysql->num_rows($q);$i++) {
				$id = split('o',$z[$i]);
				$ord = ${$z[$i]};
				$mysql->query("UPDATE ".$tb_prefix."tpl SET tpl_order = '$ord' WHERE tpl_id = '".$id[1]."'");
			}
		}
		echo "<script>function check_del(id) {".
		"if (confirm('Bạn có muốn xóa giao diện này không ?')) location='?act=tpl&mode=del&tpl_id='+id;".
		"return false;}</script>";
		echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form method=post>";
		echo "<tr><td align=center class=title width=5%>STT</td><td class=title style='border-right:0'>Tên giao diện</td></tr>";
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."tpl ORDER BY tpl_order ASC");
		while ($r = $mysql->fetch_array($q)) {
			//echo "<tr align=center><td colspan=2>".$r['full_name']."</td></tr>";
			echo "<tr><td align=center class=fr><input onclick=this.select() type=text name='o".$r['tpl_id']."' value=".$r['tpl_order']." size=2 style='text-align:center'></td><td class=fr_2><a href=# onclick=check_del(".$r['tpl_id'].")>Xóa</a> - <a href=?act=tpl&mode=set_default&tpl_id=".$r['tpl_id'].">Mặc định</a> - <a href='$link&tpl_id=".$r['tpl_id']."'><b>".$r['tpl_fname']."</b></a></td></tr>";
		}
		echo '<tr><td colspan="2" align="center"><input type="submit" name="sbm" class=submit value="Sửa thứ tự"></td></tr>';
		echo '</form></table>';
	}
	else {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."tpl WHERE tpl_id = '$tpl_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'tpl','tpl_id','tpl_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa giao diện',$inp_arr,$error_arr);
	}
}
if ($mode == 'set_default' && is_numeric($tpl_id)) {
	acp_check_permission('edit_template');
	$name = $mysql->fetch_array($mysql->query("SELECT tpl_sname FROM ".$tb_prefix."tpl WHERE tpl_id = '$tpl_id'"));
	if ($name) {
		$mysql->query("UPDATE ".$tb_prefix."config SET config_value = '".$name[0]."' WHERE config_name = 'default_tpl'");
		echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
	}
	else echo "Lỗi.";
	
}
##################################################
# DELETE
##################################################
if ($mode == 'del') {
	acp_check_permission('del_template');
	if ($tpl_id) {
		if ($_POST['submit'] && is_numeric($tpl_id) && $act=='tpl' && $mode == 'del') {
			$mysql->query("DELETE FROM ".$tb_prefix."tpl WHERE tpl_id = $tpl_id");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">
		Bạn có muốn xóa giao diện này không ??????<br>
		<input value="Có" name=submit type=submit class=submit>
		</form>
<?
	}
}
?>