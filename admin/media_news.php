<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level=='') die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
$inp_arr = array(
		'tieude'	=> array(
			'table'	=>	'news_tieude',
			'name'	=>	'Tiêu đề',
			'type'	=>	'free'
		),
		'cat'		=> array(
			'table'	=>	'news_cat',
			'name'	=>	'Thể loại',
			'type'	=>	'function::acp_news_cat::number'
		),
		'img'	=> array(
			'table'	=>	'news_img',
			'name'	=>	'Hình minh họa',
			'type'	=>	'free',
			'can_be_empty'	=>	1
		),
		'infoanh'	=> array(
			'table'	=>	'news_infoanh',
			'name'	=>	'Thông tin ảnh',
			'type'	=>	'free',
			'can_be_empty'	=>	1
		),
		'noidung'	=> array(
			'table'	=>	'news_noidung',
			'name'	=>	'Nội dung thông tin',
			'type'		=>	'text',
		),
		'nguon'	=> array(
			'table'	=>	'news_from',
			'name'	=>	'Lấy thông tin từ',
			'type'	=>	'free',
			'can_be_empty'	=>	1
		),
		'datepost'	=>	array(
			'table'	=>	'news_datepost',
			'type'	=>	'hidden_value',
			'value'	=>	date("d-m-Y",NOW),
		),
		'tieude_ascii'	=>	array(
			'table'	=>	'news_tieude_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),

		'noidung_ascii'	=>	array(
			'table'	=>	'news_noidung_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),


	);
##################################################
# ADD news
##################################################
if ($mode == 'add') {
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$inp_arr['tieude_ascii']['value'] = strtolower(utf8_to_ascii($tieude));
			$inp_arr['noidung_ascii']['value'] = strtolower(utf8_to_ascii(htmlspecialchars(strip_tags(m_text_tidy($noidung)))));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'news'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm tin tức',$inp_arr,$error_arr);
}
##################################################
# EDIT news
##################################################
if ($mode == 'edit') {
	if ($news_id) {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_id = '$news_id'");
			$r = $mysql->fetch_array($q);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['tieude_ascii']['value'] = strtolower(utf8_to_ascii($tieude));
				$inp_arr['noidung_ascii']['value'] = strtolower(utf8_to_ascii(htmlspecialchars(strip_tags(m_text_tidy($noidung)))));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'news','news_id','news_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=$link'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa thể loại',$inp_arr,$error_arr);
	}
	else {
		$m_per_page = 12;
		if (!$pg) $pg = 1;

		if ($cat_id) {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."news WHERE news_cat=".$cat_id." ORDER BY news_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->num_rows($mysql->query("SELECT news_id FROM ".$tb_prefix."news WHERE news_cat=".$cat_id." "));
		}
		else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."news ORDER BY news_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = $mysql->num_rows($mysql->query("SELECT news_id FROM ".$tb_prefix."news"));
		}

		if ($tt) {
				echo "<script>function check_del(id) {".
				"if (confirm('Bạn có muốn xóa tin tức này không ?')) location='?act=news&mode=del&news_id='+id;".
				"return false;}</script>";
				echo "<table width=90% align=center cellpadding=2 cellspacing=0 class=border><form method=post>";
				echo "	<tr>
							<td align=center width=10% class=title>DEL</td>
							<td width=30% class=title>Tiêu đề</td>
							<td class=title width=40%>Trích dẫn</td>
							<td class=title>Chuyên Mục</td>
						</tr>";
				while ($r = $mysql->fetch_array($q)) {
					echo "<tr class=fr>
							<td align=center ><a href=?act=news&mode=del&news_id=".$r['news_id']."'>DEL</a></td>
							<td><a href=\"$link&news_id=".$r['news_id']."\"><b>".$r['news_tieude']."</b></a></td>
							<td class=fr_2>".getwords(strip_tags(m_text_tidy($r['news_noidung'])),20)."</td>
							<td class=fr><a href=?act=news&mode=edit&cat_id=".$r['news_cat'].">  ".m_get_data('NEWSCAT',$r['news_cat'],'cat_name')."</a></td>
						</tr>";
				}
				echo "<tr><td colspan=4>".admin_viewpages($tt,$m_per_page,$pg)."</td></tr>";
				echo '<tr><td colspan="4" align="center"><input type="submit" name="sbm" class=submit value="Sửa thứ tự"></td></tr>';
				echo '</form></table>';
			}
		else echo "Không có tin tức nào";
		}
}
##################################################
# DELETE news
##################################################
if ($mode == 'del') {
	if ($news_id) {
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."news WHERE news_id = '".$news_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=?act=news&mode=edit'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa tin tức này không ??????<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>