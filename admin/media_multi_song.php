<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
$total_links = 20;
if (!$_POST['submit']) {
?>
<script>
var total = <?=$total_links?>;
function check_local(status){
	for(i=1;i<=total;i++)
		document.getElementById("local_url_"+i).checked=status;
}
</script>
<form method=post>
<table class=border cellpadding=2 cellspacing=0 width=95%>
<tr><td colspan=2 class=title align=center>Thêm nhiều Media</td></tr>
<tr>
	<td class=fr width=30%><b>Trình bày</b></td>
	<td class=fr_2><?=acp_singer()?></td>
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
	<td class=fr_2><?=acp_album_list()?></td>
</tr>
<tr>
	<td class=fr width=30%><b>Thể loại</b></td>
	<td class=fr_2><?=acp_cat()?></td>
</tr>

<tr>
	<td class=fr width=30%><b>Local URL</b></td>
	<td class=fr_2><input value="Check All" type="button" onClick="check_local(true)"> <input value="Uncheck All" type="button" onClick="check_local(false)"></td>
</tr>

<?php
for ($i=1;$i<=$total_links;$i++) {
?>
<tr>
<td class=fr width=30%>Bài <?=$i?><br>Link</td>
<td class=fr_2><input type=text name=title[<?=$i?>] size=50 value=""><br><input type=text name=url[<?=$i?>] size=50 value=""><input value=1 type=checkbox class=checkbox id=local_url_<?=$i?> name=local_url[<?=$i?>]><b>Local URL</b></td>
</tr>
<?php
}
?>
<tr><td class=fr colspan=2 align=center><input type=submit name=submit class=submit value=Submit></td></tr>
</table>
</form>
<?php
}
else {
	if ($new_singer && $singer_type) {
		$singer = acp_quick_add_singer($new_singer,$singer_type);
	}
	
	$t_singer = $singer;
	$t_album = $album;
	$t_cat = $cat;
	for ($i=0;$i<=$total_links;$i++) {
		$t_url = stripslashes($_POST['url'][$i]);
		$t_type = acp_type($t_url);
		$t_title = $_POST['title'][$i];
		$t_title_ascii = strtolower(utf8_to_ascii($t_title));
		$t_local = $_POST['local_url'][$i];
		if ($t_url && $t_title) {
			$mysql->query("INSERT INTO ".$tb_prefix."data (m_singer,m_album,m_cat,m_url,m_type,m_title,m_title_ascii,m_is_local,m_poster) VALUES ('".$t_singer."','".$t_album."','".$t_cat."','".$t_url."','".$t_type."','".$t_title."','".$t_title_ascii."','".$t_local."','".$_SESSION['admin_id']."')");
		}
	}
	echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
}
?>