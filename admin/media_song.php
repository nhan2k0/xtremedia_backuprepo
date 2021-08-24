<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=song&mode=edit';

$inp_arr = array(
		'title'		=> array(
			'table'	=>	'm_title',
			'name'	=>	'Tiêu đề',
			'type'	=>	'free',
		),
		'singer'	=> array(
			'table'	=>	'm_singer',
			'name'	=>	'Trình bày',
			'type'	=>	'function::acp_singer::number',
		),
		'new_singer'	=>	array(
			'name'	=>	'Nhập nhanh ca sỹ',
			'type'	=>	'function::acp_quick_add_singer_form::free',
			'desc'	=>	'Nếu chưa có ca sỹ này, Web sẽ tự động tạo ( nếu ca sỹ này đã có thì không cần chọn loại ca sỹ )',
			'can_be_empty'	=>	true,
		),
		'album'		=> array(
			'table'	=>	'm_album',
			'name'	=>	'Album',
			'type'	=>	'function::acp_album_list::number',
		),
		'cat'		=> array(
			'table'	=>	'm_cat',
			'name'	=>	'Thể loại',
			'type'	=>	'function::acp_cat::number'
		),
		'type_media'	=> array(
			'table'	=>	'm_type',
			'name'	=>	'Loại',
			'type'	=>	'hidden_value',
			'change_on_update'	=>	true,
		),
		'width'		=> array(
			'table'	=>	'm_width',
			'name'	=>	'Rộng',
			'type'	=>	'number',
			'desc'	=>	'Để trống --> kích thước mặc định',
			'change_on_update'	=>	true,
			'>0'	=>	true,
			'can_be_empty'	=>	true,
		),
		'height'	=> array(
			'table'	=>	'm_height',
			'name'	=>	'Cao',
			'type'	=>	'number',
			'desc'	=>	'Để trống --> kích thước mặc định',
			'change_on_update'	=>	true,
			'>0'	=>	true,
			'can_be_empty'	=>	true,
		),
		'url'		=> array(
			'table'	=>	'm_url',
			'name'	=>	'Link',
			'type'	=>	'free',
		),
		'local_url'	=> array(
			'table'	=>	'm_is_local',
			'name'	=>	'Local URL',
			'type'	=>	'checkbox',
			'checked'	=>	false,
			'can_be_empty'	=>	true,
		),
		'lyric'			=> array(
			'table'		=>	'm_lyric',
			'name'		=>	'Lời',
			'type'		=>	'text',
			'can_be_empty'	=>	1
		),
		'date'		=>	array(
			'table'	=>	'm_date',
			'type'	=>	'hidden_value',
			'value'	=>	date("Y-m-d",NOW),
		),
		'poster'		=>	array(
			'table'	=>	'm_poster',
			'type'	=>	'hidden_value',
			'value'	=>	$_SESSION['admin_id'],
		),
		'title_ascii'	=>	array(
			'table'	=>	'm_title_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
);

##################################################
# ADD MEDIA
##################################################
if ($mode == 'add') {
	acp_check_permission('add_media');
	
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$inp_arr['title_ascii']['value'] = strtolower(utf8_to_ascii($title));
			$inp_arr['type_media']['value'] = acp_type($url);
			if ($new_singer && $singer_type) {
				$singer = acp_quick_add_singer($new_singer,$singer_type);
			}
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'data'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm Media',$inp_arr,$error_arr);
}
elseif ($mode == 'multi_add') {
	acp_check_permission('add_media');
	include('media_multi_song.php');
}
##################################################
# EDIT MEDIA
##################################################
if ($mode == 'edit') {
	if ($m_del_id) {
		acp_check_permission('del_media');
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."data WHERE m_id = '".$m_del_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?><form method="post">Bạn có muốn xóa không ??????<br><input value="Có" name=submit type=submit class=submit></form><?
	}
	elseif ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('Lỗi');
		if ($_POST['selected_option'] == 'del') {
			acp_check_permission('del_media');
			
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."data WHERE m_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		acp_check_permission('edit_media');
		
		if ($_POST['selected_option'] == 'multi_edit') {
			$arr = implode(',',$arr);
			header("Location: ./?act=song_multi_edit&id=".$arr);
		}
		elseif ($_POST['selected_option'] == 'normal') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."data SET m_is_broken = 0 WHERE m_id IN (".$in_sql.")");
			echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		exit();
	}
	elseif ($m_id) {
		acp_check_permission('edit_media');
		
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_id = '$m_id'");
			if (!$mysql->num_rows($q)) {
				echo "Không có bài hát này";
				exit();
			}
			$r = $mysql->fetch_array($q);
				
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
			$inp_arr['local_url']['checked'] = $local_url;
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['title_ascii']['value'] = strtolower(utf8_to_ascii($title));
				$inp_arr['type_media']['value'] = acp_type($url);
				
				if ($new_singer && $singer_type) {
					$singer = acp_quick_add_singer($new_singer,$singer_type);
				}
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'data','m_id','m_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa Media',$inp_arr,$error_arr);
	}
	else {
		acp_check_permission('edit_media');
		
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		$search = urldecode($_GET['search']);
		$extra = (($search)?"m_title_ascii LIKE '%".$search."%' ":'');
		if ($show_broken) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data WHERE m_is_broken = 1 ".(($extra)?"AND ".$extra." ":'')."ORDER BY m_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			$tt = m_get_tt('m_is_broken = 1 '.(($extra)?"AND ".$extra." ":''));
			echo "<a href=?act=song&mode=edit><b>Danh sách toàn bộ Media</b></a><br><br>";
		}
		else {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."data ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY m_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			$tt = m_get_tt($extra);
			echo "<a href=".$link."&show_broken=1><b>Danh sách toàn bộ Media bị lỗi</b></a><br><br>";
		}
		if ($mysql->num_rows($q)) {
			if ($search) {
				$link2 = preg_replace("#&search=(.*)#si","",$link);
			}
			else $link2 = $link;
			echo "ID của Media cần <b>sửa</b>: <input id=m_id size=20> <input type=button onclick='window.location.href = \"".$link."&m_id=\"+document.getElementById(\"m_id\").value;' value=Sửa><br><br>";
			echo "ID của Media cần <b>xóa</b>: <input id=m_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&m_del_id=\"+document.getElementById(\"m_del_id\").value;' value=Xóa><br><br>";
			echo "Tìm Media : <input id=search size=20 value=\"".$search."\"> <input type=button onclick='window.location.href = \"".$link2."&search=\"+document.getElementById(\"search\").value;' value=Tìm><br><br>";
			echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
			echo "<tr align=center><td width=3%><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td><td class=title width=60%>Tên Media</td><td class=title>Ca sĩ</td><td class=title>Lỗi</td></tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['m_id'];
				$title = $r['m_title'];
				$singer = $r['m_singer'];
				$broken = ($r['m_is_broken'])?'<font color=red><b>X</b></font>':'';
				echo "<tr><td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td><td class=fr><a href='$link&m_id=".$id."'><b>".$title."</b></a></td><td class=fr_2 align=center><b><a href=?act=singer&mode=edit&singer_id=".$singer.">".m_get_data('SINGER',$singer)."</a></b></td><td align=center>".$broken."</td></tr>";
			}
			echo "<tr><td colspan=3>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=3 align="center">Với những Media đã chọn : '.
				'<select name=selected_option><option value=multi_edit>Sửa</option><option value=del>Xóa</option><option value=normal>Thôi báo Link hỏng</option></select>'.
				'<input type="submit" name="do" class=submit value="Thực hiện"></td></tr>';
			echo '</form></table>';
		}
		else echo "Không có Media nào";
	}
}
?>