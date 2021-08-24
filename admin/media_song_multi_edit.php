<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if (!$_GET['id']) die('ERROR');
$id = $_GET['id'];
if (!$_POST['submit']) {
?>
<form method=post>
<table class=border cellpadding=2 cellspacing=0 width=95%>
<tr><td colspan=2 class=title align=center>Sửa nhiều Media</td></tr>
<tr>
	<td class=fr width=30%><b>Các bài hát sẽ sửa</b></td>
	<td class=fr_2>
	<?php
	$in_sql = $id;
	$q = $mysql->query("SELECT m_title FROM ".$tb_prefix."data WHERE m_id IN (".$in_sql.")");
	while ($r = $mysql->fetch_array($q)) {
		echo '+ <b>'.$r['m_title'].'</b><br>';
	}
	?>
	</td>
</tr>
<tr>
	<td class=fr width=30%><b>Trình bày</b></td>
	<td class=fr_2><?=acp_singer(NULL,1)?></td>
</tr>
<tr>
	<td class=fr width=30%>
		<b>Nhập nhanh ca sỹ</b>
		<br>Nếu chưa có ca sỹ này, Web sẽ tự động tạo ( nếu ca sỹ này đã có thì không cần chọn loại ca sỹ )</td>
	<td class=fr_2>
		<input name=new_singer size=50> &nbsp;
		<select name=singer_type>
			<option value=1>Ca sỹ & Ban nhạc VN</option>
			<option value=2>Ca sỹ & Ban nhạc QT</option>
		</select>
	</td>
</tr>
<tr>
	<td class=fr width=30%><b>Album</b></td>
	<td class=fr_2><?=acp_album_list(NULL,1)?></td>
</tr>
<tr>
	<td class=fr width=30%><b>Thể loại</b></td>
	<td class=fr_2><?=acp_cat(NULL,1)?></td>
</tr>

<tr><td class=fr colspan=2 align=center><input type=submit name=submit class=submit value="Sửa"></td></tr>
</table>
</form>
<?php
}
else {
	if ($_POST['new_singer']) {
		$q = $mysql->query("SELECT singer_id FROM ".$tb_prefix."singer WHERE singer_name = '".$new_singer."' LIMIT 1");
		if (!$mysql->num_rows($q) && $new_singer && $singer_type) {
			$singer = acp_quick_add_singer($new_singer,$singer_type);
		}
	}
	else $singer = $_POST['singer'];
	$in_sql = $id;
	$t_singer = $singer;
	$t_album = $album;
	$t_cat = $cat;
	$sql = '';
	if ($t_singer != 'dont_edit') $sql .= "m_singer = '".$t_singer."',";
	if ($t_album != 'dont_edit') $sql .= "m_album = '".$t_album."',";
	if ($t_cat != 'dont_edit') $sql .= "m_cat = '".$t_cat."',";
	$sql = substr($sql,0,-1);
	if ($sql) $mysql->query("UPDATE ".$tb_prefix."data SET ".$sql." WHERE m_id IN (".$in_sql.")");
	echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=$link'>";
}
?>