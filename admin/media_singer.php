<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=singer&mode=edit';

$inp_arr = array(
		'singer'	=> array(
			'table'	=>	'singer_name',
			'name'	=>	'Tên ca sỹ',
			'type'	=>	'free'
		),
		'img'	=> array(
			'table'	=>	'singer_img',
			'name'	=>	'Hình ca sỹ',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'singer_type'	=>	array(
			'table'	=>	'singer_type',
			'name'	=>	'Loại',
			'type'	=>	'function::acp_singer_type::number',
		),
		'singer_info'	=>	array(
			'table'	=>	'singer_info',
			'name'	=>	'Thông tin',
			'type'	=>	'text',
			'can_be_empty'	=>	true,
		),
		'singer_name_ascii'	=>	array(
			'table'	=>	'singer_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
	);
##################################################
# ADD SINGER
##################################################
if ($mode == 'add') {
	if ($level == 2 && !$mod_permission['add_singer']) echo 'Bạn không được quyền vào trang này';
	else {
		if ($_POST['submit']) {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['singer_name_ascii']['value'] = strtolower(utf8_to_ascii($album));
				$sql = $form->createSQL(array('INSERT',$tb_prefix.'singer'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
	
		$form->createForm('Thêm Ca sỹ',$inp_arr,$error_arr);
	}
}
##################################################
# EDIT SINGER
##################################################
if ($mode == 'edit') {
	if ($singer_del_id) {
		acp_check_permission('del_singer');
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."singer WHERE singer_id = '".$singer_del_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa không ??????<br><input value="Có" name=submit type=submit class=submit></form>
		<?
	}
	elseif ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Lỗi');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_singer');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."singer WHERE singer_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($singer_id) {
		acp_check_permission('edit_singer');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer WHERE singer_id = '$singer_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['singer_name_ascii']['value'] = strtolower(utf8_to_ascii($singer));

				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'singer','singer_id','singer_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa ca sỹ',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_singer');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer ORDER BY singer_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(singer_id) FROM ".$tb_prefix."singer"));
		$tt = $tt[0];
		if ($tt) {
			echo "ID của ca sỹ cần <b>sửa</b>: <input id=singer_id size=20> <input type=button onclick='window.location.href = \"".$link."&singer_id=\"+document.getElementById(\"singer_id\").value;' value=Sửa><br><br>";
			echo "ID của ca sỹ cần <b>xóa</b>: <input id=singer_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&singer_del_id=\"+document.getElementById(\"singer_del_id\").value;' value=Xóa><br><br>";
			
			echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
			echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Tên Ca sỹ</td><td class=title>Ảnh</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['singer_id'];
				$singer = $r['singer_name'];
				$img = ($r['singer_img'])?"<img src=".$r['singer_img']." width=50 height=50>":'';
				echo "<tr><td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td><td class=fr><b><a href=?act=singer&mode=edit&singer_id=".$id.">".$singer."</a></b></td><td class=fr_2 align=center>".$img."</td></tr>";
			}
			echo "<tr><td colspan=3>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=3 align="center">Với những ca sỹ đã chọn : '.
				'<select name=selected_option><option value=del>Xóa</option>'.
				'<input type="submit" name="do" class=submit value="Thực hiện"></td></tr>';
			echo '</form></table>';
		}
		else echo "Không có ca sỹ nào";
	}
}
?>