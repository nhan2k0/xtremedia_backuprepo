<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=album&mode=edit';

$inp_arr = array(
		'album'	=> array(
			'table'	=>	'album_name',
			'name'	=>	'Tên Album',
			'type'	=>	'free'
		),
		'singer'	=> array(
			'table'	=>	'album_singer',
			'name'	=>	'Trình bày',
			'type'	=>	'function::acp_singer::number',
		),
		'new_singer'	=>	array(
			'name'	=>	'Nhập nhanh ca sỹ',
			'type'	=>	'function::acp_quick_add_singer_form::free',
			'desc'	=>	'Nếu chưa có ca sỹ này, Web sẽ tự động tạo ( nếu ca sỹ này đã có thì không cần chọn loại ca sỹ )',
			'can_be_empty'	=>	true,
		),
		'img'	=> array(
			'table'	=>	'album_img',
			'name'	=>	'Hình Album',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'album_info'	=>	array(
			'table'	=>	'album_info',
			'name'	=>	'Thông tin',
			'type'	=>	'text',
			'can_be_empty'	=>	true,
		),
		'album_ascii'	=>	array(
			'table'	=>	'album_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
	);
##################################################
# ADD ALBUM
##################################################
if ($mode == 'add') {
	acp_check_permission('add_album');
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			if ($new_singer && $singer_type) {
				$singer = acp_quick_add_singer($new_singer,$singer_type);
			}
			$inp_arr['album_ascii']['value'] = strtolower(utf8_to_ascii($album));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'album'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm Album',$inp_arr,$error_arr);
}
##################################################
# EDIT ALBUM
##################################################
if ($mode == 'edit') {
	if ($album_del_id) {
		acp_check_permission('del_album');
		if ($_POST['submit']) {
				$mysql->query("DELETE FROM ".$tb_prefix."album WHERE album_id = '".$album_del_id."'");
				$mysql->query("UPDATE ".$tb_prefix."data SET m_album = '' WHERE m_album = '".$album_del_id."'");
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
			acp_check_permission('del_album');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."album WHERE album_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	
	elseif ($album_id) {
		acp_check_permission('edit_album');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."album WHERE album_id = '".$album_id."'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['album_ascii']['value'] = strtolower(utf8_to_ascii($album));
				
				if ($new_singer && $singer_type) {
					$singer = acp_quick_add_singer($new_singer,$singer_type);
				}
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'album','album_id','album_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa album',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_album');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."album ORDER BY album_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(album_id) FROM ".$tb_prefix."album"));
		$tt = $tt[0];
		if ($tt) {
			echo "ID của album cần <b>sửa</b>: <input id=album_id size=20> <input type=button onclick='window.location.href = \"".$link."&album_id=\"+document.getElementById(\"album_id\").value;' value=Sửa><br><br>";
			echo "ID của album cần <b>xóa</b>: <input id=album_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&album_del_id=\"+document.getElementById(\"album_del_id\").value;' value=Xóa><br><br>";
			
			echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
			echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Album</td><td class=title>Ảnh</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['album_id'];
				$album = $r['album_name'];
				$img = ($r['album_img'])?"<img src=".$r['album_img']." width=50 height=50>":'';
				echo "<tr><td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td><td class=fr><b><a href=?act=album&mode=edit&album_id=".$id.">".$album."</a></b></td><td class=fr_2 align=center>".$img."</td></tr>";
			}
			echo "<tr><td colspan=3>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=3 align="center">Với những Album đã chọn : '.
				'<select name=selected_option><option value=del>Xóa</option>'.
				'<input type="submit" name="do" class=submit value="Thực hiện"></td></tr>';
			echo '</form></table>';
		}
		else echo "Không có Album nào";
	}
}
?>
