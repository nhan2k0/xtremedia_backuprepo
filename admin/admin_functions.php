<?
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");

function admin_viewpages($ttrow,$n,$pg){
	global $link;
	$link = preg_replace("#&pg=([0-9]{1,})#si","",$link);
	$html="<table width=100% valign=bottom cellpadding=2 cellspacing=2>";
	$html.="<tr><td align=center>";
	$pgt = $pg-1;
	if ($pg<>1) $html.="<a class=pagelink href=$link onfocus=this.blur() title ='Xem trang đầu'><b>&laquo;&laquo;</b></a> <a class=pagelink href=$link&pg=$pgt onfocus=this.blur() title='Xem trang trước'><b>&laquo;</b></a> ";
	for($l = 0; $l < $ttrow/$n; $l++) {
		$m = $l+1;
		if($m == $pg) $html .= "<a onfocus=this.blur() class=pagecurrent>$m</a> ";
		else $html .= "<a onfocus=this.blur() href=$link&pg=$m title='Xem trang $m' class=pagelink>$m</a> ";
	}
	$pgs = $pg+1;
	if ($pg<>$m) $html.="<a class=pagelink href=$link&pg=$pgs onfocus=this.blur() title='Xem trang kế tiếp'><b>&raquo;</b></a> <a class=pagelink href=$link&pg=$m onfocus=this.blur() title='Xem trang cuối'><b>&raquo;&raquo;</b></a> ";
	$html.="</td></tr></table>";
	return $html;
}
function acp_quick_add_singer_form() {
	$html = "<input name=new_singer size=50> &nbsp; <select name=singer_type>".
		"<option value=1 selected>Ca sỹ & Ban nhạc VN</option>".
		"<option value=2>Ca sỹ & Ban nhạc QT</option>".
		//"<option value=3>Ban nhạc</option>".
	"</select>";
	return $html;
}

function acp_quick_add_singer($new_singer,$singer_type) {
	global $mysql, $tb_prefix;
	//$new_singer = stripslashes($new_singer);
	$q = $mysql->query("SELECT singer_id FROM ".$tb_prefix."singer WHERE singer_name = '".$new_singer."'");
	if ($mysql->num_rows($q)) {
		$r = $mysql->fetch_array($q);
		$singer = $r[0];
	}
	else {
		$mysql->query("INSERT INTO ".$tb_prefix."singer (singer_name,singer_name_ascii,singer_type) VALUES ('".$new_singer."','".strtolower(utf8_to_ascii($new_singer))."','".$singer_type."')");
		$singer = $mysql->insert_id();
	}
	return $singer;
}
function acp_album_list($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."album ORDER BY album_name ASC");
	$html = "<select name=album>";
	if ($add) $html .= "<option value=dont_edit".(($id == 0)?" selected":'').">Không sửa</option>";
	$html .= "<option value=0".(($id == 0 && !$add)?" selected":'').">Chưa biết</option>";
	while ($r = $mysql->fetch_array($q)) {
		$html .= "<option value=".$r['album_id'].(($id == $r['album_id'])?" selected":'').">".$r['album_name']."</option>";
	}
	$html .= "</select>";
	return $html;
}
function acp_singer($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$id = (int)$id;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."singer ORDER BY singer_name ASC");
	$html = "<select name=singer>";
	if ($add) $html .= "<option value=dont_edit".(($id == 0)?" selected":'').">Không sửa</option>";
	$html .= "<option value=-1".((($id == -1 || $id == 0) && !$add)?" selected":'').">Chưa biết (VN)</option>";
	$html .= "<option value=-2".(($id == -2 && !$add)?" selected":'').">Chưa biết (QT)</option>";
	//$html .= "<option value=-3".(($id == -3 && !$add)?" selected":'').">Chưa biết (Band)</option>";
	while ($r = $mysql->fetch_array($q)) {
		$html .= "<option value=".$r['singer_id'].(($id == $r['singer_id'])?" selected":'').">".$r['singer_name']."</option>";
	}
	$html .= "</select>";
	return $html;
}
function acp_singer_type($i) {
	$html = "<select name=singer_type>".
		"<option value=1".(($i==1)?' selected':'').">Ca sỹ trong nước</option>".
		"<option value=2".(($i==2)?' selected':'').">Ca sỹ nước ngoài</option>".
		//"<option value=3".(($i==3)?' selected':'').">Ban nhạc</option>".
	"</select>";
	return $html;
}
function acp_cat($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat ORDER BY sub_id ASC");
	while ($r = $mysql->fetch_array($q)) {
		//echo $r['cat_id'].'-'.$r['sub_id'].'<br>';
		if (!$r['sub_id']) $arr[$r['cat_id']][0] = $r['cat_name'];
		elseif ($arr[$r['sub_id']][0]) $arr[$r['sub_id']][] = array($r['cat_id'],$r['cat_name']);
	}
	$html = "<select name=cat>";
	if ($add) $html .= "<option value=dont_edit".(($id == 0)?" selected":'').">Không sửa</option>";
	if ($arr) 
		foreach ($arr as $key => $val) {
			$html .= "<optgroup label='".$val[0]."'>";
			for ($i=1;$i<count($val);$i++) {
				$html .= "<option value=".$val[$i][0].(($id == $val[$i][0])?" selected":'').">".$val[$i][1]."</option>";
			}
			$html .= "</optgroup>";
		}
	$html .= "</select>";
	return $html;
}

function acp_user_level($lv) {
	$html = "<select name=level>".
		"<option value=1".(($lv==1)?' selected':'').">Member</option>".
		"<option value=2".(($lv==2)?' selected':'').">Moderator</option>".
		"<option value=3".(($lv==3)?' selected':'').">Admin</option>".
	"</select>";
	return $html;
}

function acp_user_sex($s) {
	$html = "<select name=sex>".
		"<option value=1".(($s==1)?' selected':'').">Nam</option>".
		"<option value=2".(($s==2)?' selected':'').">Nữ</option>".
	"</select>";
	return $html;
}

function acp_type(&$url) {
	$t_url = strtolower($url);
	$ext = explode('.',$t_url);
	$ext = $ext[count($ext)-1];
	$ext = explode('?',$ext);
	$ext = $ext[0];
	$movie_arr = array(
		'wmv',
		'avi',
		'asf',
		'mpg',
		'mpe',
		'mpeg',
		'asx',
		'm1v',
		'mp2',
		'mpa',
		'ifo',
		'vob',
	);
	
	$extra_swf_arr = array(
		'www.dailymotion.com',
		'www.metacafe.com',
	);
	
	for ($i=0;$i<count($extra_swf_arr);$i++){
		if (preg_match("#^http://".$extra_swf_arr[$i]."/(.*?)#s",$url)) {
			$type = 2;
			break;
		}
	}
	
	$is_youtube = (preg_match("#http://www.youtube.com/watch\?v=(.*?)#s",$url));
	$is_googleVideo = (preg_match("#http://video.google.com/videoplay\?docid=(.*?)#s",$url));
	
	if ($ext == 'swf' || $is_youtube || $is_googleVideo ) $type = 2;
	elseif (in_array($ext,$movie_arr)) $type = 3;
	elseif (!$type) $type = 1;
	
	if ($is_youtube) {
		$url = str_replace('watch?v=','v/',$url);
	}
	elseif ($is_googleVideo) {
		$url = str_replace('videoplay?docid=','googleplayer.swf?docId=',$url);
	}
	return $type;
}

function acp_maincat($id) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE sub_id = 0 OR sub_id IS NULL ORDER BY cat_order ASC");
	$html = "<select name=sub>";
	$html .= "<option value=0>- Mục chính -</option>";
	while ($r = $mysql->fetch_array($q)) {
		$html .= "<option value=".$r['cat_id'].(($r['cat_id'] == $id)?" selected":"").">".$r['cat_name']."</option>";
	}
	$html .= "</select>";
	return $html;
}

function acp_get_mod_permission() {
	global $mysql, $tb_prefix;
	
	$permission_list = array(
		'add_cat',		'edit_cat',
		'del_cat',		'add_media',
		'edit_media',		'del_media',
		'add_singer',		'edit_singer',
		'del_singer',		'add_album',
		'edit_album',		'del_album',
		'add_user',		'edit_user',
		'del_user',		'add_link',
		'edit_link',		'del_link',
		'add_template',		'edit_template',
		'del_template',
	);
	
	$per = m_get_config('mod_permission');
	$per = decbin($per);
	$len = count($permission_list);
	while (strlen($per) < $len) $per = '0'.$per;
	
	for ($i=0;$i<$len;$i++) $arr[$permission_list[$i]] = $per[$i];
	return $arr;
	
}

function acp_check_permission($t) {
	global $level,$mod_permission;
	if ($level == 2 && !$mod_permission[$t]) die('<center>Bạn không được quyền vào trang này !</center>');
}
?>