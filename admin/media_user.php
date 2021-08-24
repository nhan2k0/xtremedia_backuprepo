<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=user&mode=edit';

$inp_arr = array(
		'name'		=> array(
			'table'	=>	'user_name',
			'name'	=>	'Username',
			'type'	=>	'free',
		),
		'email'	=> array(
			'table'	=>	'user_email',
			'name'	=>	'Email',
			'type'	=>	'free',
		),
		'password'	=> array(
			'table'	=>	'user_password',
			'name'	=>	'Mật khẩu',
			'type'	=>	'password',
			'always_empty'	=>	true,
			'update_if_true'	=>	'trim($password) != ""',
			'can_be_empty'	=>	true,
		),
		'level'	=> array(
			'table'	=>	'user_level',
			'name'	=>	'Phân quyền',
			'type'	=>	'function::acp_user_level::number',
		),
		'sex'	=> array(
			'table'	=>	'user_sex',
			'name'	=>	'Giới tính',
			'type'	=>	'function::acp_user_sex::number',
		),
		'date'		=>	array(
			'table'	=>	'user_regdate',
			'type'	=>	'hidden_value',
			'value'	=>	date("Y-m-d",NOW),
			'change_on_update'	=>	true,
		),
		'playlist_id'		=>	array(
			'table'	=>	'user_playlist_id',
			'type'	=>	'hidden_value',
			'value'	=>	m_random_str(20),
			'change_on_update'	=>	false,
		),
);

##################################################
# ADD USER
##################################################
if ($mode == 'add') {
	acp_check_permission('add_user');
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$name = m_htmlchars(stripslashes(trim(urldecode($_POST['name']))));
			$password = md5(stripslashes($_POST['password']));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'user'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm User',$inp_arr,$error_arr);
}
##################################################
# EDIT USER
##################################################
if ($mode == 'edit') {
	if ($us_del_id) {
		acp_check_permission('del_user');
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE usre_id = ".$us_del_id);
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">
		Bạn có muốn xóa không ??????<br>
		<input value="Có" name=submit type=submit class=submit>
		</form>
		<?
	}
	elseif ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Lỗi');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_user');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE user_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($us_id) {
		acp_check_permission('edit_user');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_id = '$us_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = (($r[$arr['table']]));
			
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				if ($_POST['password']) $password = md5(stripslashes($_POST['password']));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'user','user_id','us_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa User',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_user');
		
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$search = trim(urldecode($_GET['search']));
		$extra = (($search)?"user_name LIKE '%".$search."%' ":'');
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."user ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY user_name ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->num_rows($mysql->query("SELECT user_id FROM ".$tb_prefix."user".(($extra)?" WHERE ".$extra:'')));
		
		if ($tt) {
			if ($search) {
				$link2 = preg_replace("#&search=(.*)#si","",$link);
			}
			else $link2 = $link;
			
			echo "ID của User cần <b>sửa</b>: <input id=us_id size=20> <input type=button onclick='window.location.href = \"".$link."&us_id=\"+document.getElementById(\"us_id\").value;' value=Sửa><br><br>";
			echo "ID của User cần <b>xóa</b>: <input id=us_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&us_del_id=\"+document.getElementById(\"us_del_id\").value;' value=Xóa><br><br>";
			echo "Tìm User : <input id=search size=20 value=\"".$search."\"> <input type=button onclick='window.location.href = \"".$link2."&search=\"+document.getElementById(\"search\").value;' value=Tìm><br><br>";
			echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
			echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Username</td><td class=title>Quyền</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['user_id'];
				$name = m_unhtmlchars($r['user_name']);
				$level = m_user_level($id);
				echo "<tr><td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td><td class=fr><a href='$link&us_id=".$id."'><b>".$name."</b></a></td><td class=fr_2 align=center>".$level."</td></tr>";
			}
			echo "<tr><td colspan=3>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=3 align="center">Với những User đã chọn : '.
				'<select name=selected_option><option value=del>Xóa</option></select>'.
				'<input type="submit" name="do" class=submit value="Thực hiện"></td></tr>';
			echo '</form></table>';
		}
		else echo "Không có User nào";
	}
	
}
?>