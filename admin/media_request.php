<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

acp_check_permission('edit_request');

$view_url = 'index.php?act=request&mode=view';

$inp_arr = array(
		'title_request'	=> array(
			'table'	=>	'request_title',
			'name'	=>	'Tên bản nhạc',
			'type'	=>	'free'
		),
		'request_singer'	=> array(
			'table'	=>	'request_singer',
			'name'	=>	'Tên Ca sĩ',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'request_author'	=> array(
			'table'	=>	'request_author',
			'name'	=>	'Tên Nhạc sĩ',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'request_date'	=>	array(
			'table'	=>	'request_date',
			'name'	=>	'Ngày gửi Request',
			'type'	=>	'free',
		),
		'request_ip'	=>	array(
			'table'	=>	'request_ip',
			'name'	=>	'IP người gửi Request',
			'type'	=>	'free',
		),
		'request_email'	=>	array(
			'table'	=>	'request_email',
			'name'	=>	'Email',
			'type'	=>	'free',
		),
		'request_ym'	=>	array(
			'table'	=>	'request_ym',
			'name'	=>	'YM',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'request_info'	=>	array(
			'table'	=>	'request_info',
			'name'	=>	'Thông tin thêm',
			'type'	=>	'text',
			'can_be_empty'	=> true,
		),
		'request_admin'	=>	array(
			'table'	=>	'request_admin',
			'name'	=>	'Ghi chú của Admin',
			'type'	=>	'text',
			'can_be_empty'	=> true,
		),

	);
##################################################
# EDIT SINGER
##################################################
if ($mode == 'view') {
	if ($request_del_id) {
//		acp_check_permission('edit_request');
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."request WHERE request_id = '".$request_del_id."'");
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
//			acp_check_permission('del_request');
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."request WHERE request_id IN (".$in_sql.")");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($request_id) {
//		acp_check_permission('edit_request');
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."request WHERE request_id = '$request_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['request_admin']['value'] = strtolower(utf8_to_ascii($singer));

				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'request','request_id','request_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$view_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Xem thông tin bản nhạc được Yêu Cầu',$inp_arr,$error_arr);
	}
	else {
//		acp_check_permission('edit_request');
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."request ORDER BY request_title ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->fetch_array($mysql->query("SELECT COUNT(request_id) FROM ".$tb_prefix."request"));
		$tt = $tt[0];
		if ($tt) {
			echo "ID của Request cần <b>xem</b>: <input id=request_id size=20> <input type=button onclick='window.location.href = \"".$link."&request_id=\"+document.getElementById(\"request_id\").value;' value=Xem><br><br>";
			echo "ID của Request cần <b>xóa</b>: <input id=request_del_id size=20> <input type=button onclick='window.location.href = \"".$link."&request_del_id=\"+document.getElementById(\"request_del_id\").value;' value=Xóa><br><br>";
			
			echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form name=media_list method=post action=$link onSubmit=\"return check_checkbox();\">";
			echo "	<tr align=center>
						<td width=3%>
							<input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall>
						</td>
						<td class=title width=40%>
							Tên Media
						</td>
						<td class=title width=25%>
							Ca sĩ
						</td>
						<td class=title width=20%>
							Nhạc sĩ
						</td>
						<td class=title width=15%>
							Email
						</td>
					</tr>";
			while ($r = $mysql->fetch_array($q)) {
				$id = $r['request_id'];
				$title = $r['request_title'];
				$singer = $r['request_singer'];
				$email = $r['request_author'];
				$ym = $r['request_email'];
				echo "<tr>
						<td>
							<input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id>
						</td>
						<td class=fr>
							<b><a href=?act=request&mode=view&request_id=".$id.">".$title."</a></b>
						</td>
						<td class=fr_2 align=center>
							".$singer."
						</td>
						<td class=fr_2 align=center>
							".$email."
						</td>
						<td class=fr_2 align=center>
							".$ym."
						</td>
						</tr>";
			}
			echo "<tr><td colspan=5>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
			echo '<tr><td colspan=5 align="center">Với những Request đã chọn : '.
				'<select name=selected_option><option value=del>Xóa</option>'.
				'<input type="submit" name="do" class=submit value="Thực hiện"></td></tr>';
			echo '</form></table>';
		}
		else echo "Hiện tại chưa có ai yêu cầu nhạc";
}
}
?>