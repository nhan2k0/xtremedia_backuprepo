<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
class HTMLForm {
	var $error_color = array(
		'empty'		=>	'#FCB222',
		'number'	=>	'#7EBA01',
		'>0'		=>	'#47A2CB',
		'>=0'		=>	'#585CFE',
		'url'		=>	'#202020',
	);
	function createSQL($config_arr,$inp_arr) {
		if ($config_arr[0] == 'INSERT') {
			foreach ($inp_arr as $key=>$arr) {
				if (!$arr['table']) continue;
				$s1 .= '`'.$arr['table'].'`,';
				if ($arr['type'] == 'hidden_value')	$s2 .= '\"'.$arr['value'].'\",';
				else $s2 .= '\'$'.$key.'\',';
			}
			$s1 = substr($s1,0,-1);
			$s2 = substr($s2,0,-1);
			$sql = "INSERT INTO ".$config_arr[1]." (".$s1.") VALUES (".$s2.")";
		}
		elseif ($config_arr[0] == 'UPDATE') {
			foreach ($inp_arr as $key=>$arr) {
				global $$key;
				if (!$arr['table']) continue;
				if ($arr['update_if_true'] && !eval('return ('.$arr['update_if_true'].');')) continue;
				
				if ($arr['type'] == 'hidden_value' && !$arr['change_on_update']) continue;
				if ($arr['type'] == 'hidden_value')	$s1 .= $arr['table'].' = \''.$arr['value'].'\', ';
				else $s1 .= $arr['table'].' = \"$'.$key.'\", ';
			}
			$s1 = substr($s1,0,-2);
			if ($config_arr[2] && $config_arr[3]) $sql = "UPDATE ".$config_arr[1]." SET ".$s1." WHERE ".$config_arr[2]." = '\$".$config_arr[3]."'";
			else $sql = "UPDATE ".$config_arr[1]." SET ".$s1."";
		}
		return $sql;
	}
	function createTableArray($inp_arr,$field_arr) {
		$keys = array_keys($inp_arr);
		$tb_arr = array();
		for ($i=0;$i<count($keys);$i++)
			$tb_arr[$keys[$i]] = $field_arr[$i];
		return $tb_arr;
	}
	function getWarnString($error_arr) {
		if (!$error_arr) return;
		if (in_array('empty',$error_arr)) $warn = "<b style='color:".$this->error_color['empty']."'>*</b> : Chưa nhập dữ liệu<br>";
		if (in_array('number',$error_arr)) $warn .= "<b style='color:".$this->error_color['number']."'>*</b> : Dữ liệu phải là số<br>";
		if (in_array('>0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>0']."'>*</b> : Dữ liệu phải lớn hơn 0<br>";
		if (in_array('>=0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>=0']."'>*</b> : Dữ liệu phải lớn hơn hoặc bằng 0<br>";
		if (in_array('url',$error_arr)) $warn .= "<b style='color:".$this->error_color['url']."'>*</b> : Dữ liệu phải là URL<br>";
		return substr($warn,0,-4);
	}
	function checkForm($inp_arr) {
		
		foreach ($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
		}
		foreach ($inp_arr as $key=>$arr) {
			if (!$$key && $arr['can_be_empty']) continue;
			if ($arr['type'] == 'hidden_value') continue;
			if ($arr['check_if_true'] && !eval('return ('.$arr['check_if_true'].');')) continue;
			
			$$key = htmlspecialchars($_POST[$key]);
			if ($$key == '' && !$arr['can_be_empty']) $error_arr[$key] = 'empty';
			if (ereg("^function::*::*",$arr['type'])) { $z = split('::',$arr['type']); $type = $z[1]; }
			else $type = $arr['type'];
			if (!$error_arr[$key]) {
				if ($type == 'number' && !is_numeric($$key)) $error_arr[$key] = 'number';
				elseif ($type == 'number' && $arr['>0'] && $$key <= 0 ) $error_arr[$key] = '>0';
				elseif ($type == 'number' && $arr['>=0'] && $$key < 0 ) $error_arr[$key] = '>=0';
				elseif ($type == 'url' && !ereg("[http|mms|ftp|rtsp]://[a-z0-9_-]+\.[a-z0-9_-]+",$$key)) $error_arr[$key] = 'url';
			}
		}
		return $error_arr;
	}
	function createForm($title,$inp_arr,$error_arr) {
		global $warn;
		echo "<form method=post>".
		"<table class=border cellpadding=2 cellspacing=0 width=90%>".
		"<tr><td colspan=2 class=title align=center>$title</td></tr>";
		if ($warn) echo "<tr><td class=fr><b>Lỗi</b></td><td class=fr_2>$warn</td></tr>";
		
		foreach($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
			if ($arr['always_empty']) $$key = '';
			if (ereg("^function::*::*",$arr['type'])) {
				$ex_arr = split('::',$arr['type']);
				$str = $ex_arr[1]($$key);
				$type = 'function';
			}
			else $type = $arr['type'];
			echo "<tr><td class=fr width=30%><b>".$arr['name']."</b>".(($arr['desc'])?"<br>".$arr['desc']:'')."</td><td class=fr_2>";
			$value = ($$key != '')?m_unhtmlchars(stripslashes($$key)):'';
			switch ($type) {
				case 'number' : echo "<input type=text name=".$key." size=10 value=\"".$value."\">"; break;
				case 'free' : echo "<input type=text name=".$key." size=50 value=\"".$value."\">"; break;
				case 'password' : echo "<input type=password name=".$key." size=50 value=\"".$value."\">"; break;
				case 'url' : echo "<input type=text name=".$key." size=50 value=\"".$value."\">"; break;
				case 'function' : echo $str; break;
				case 'text' : echo "<textarea rows=8 cols=70 name=".$key.">".$value."</textarea>"; break;
				case 'checkbox'	:	echo "<input value=1".(($arr['checked'])?' checked':'')." type=checkbox class=checkbox name=".$key.">"; break;
			}
			if ($error_arr[$key]) {
				echo ' ';
				switch ($error_arr[$key]) {
					case 'empty'	:	echo "<b style='color:".$this->error_color['empty']."'>*</b>";	break;
					case 'number'	:	echo "<b style='color:".$this->error_color['number']."'>*</b>";	break;
					case '>0'		:	echo "<b style='color:".$this->error_color['>0']."'>*</b>";		break;
					case '>=0'		:	echo "<b style='color:".$this->error_color['>=0']."'>*</b>";	break;
					case 'url'		:	echo "<b style='color:".$this->error_color['url']."'>*</b>";	break;
				}
			}
			echo "</td></tr>";
		}
		
		echo "<tr><td class=fr colspan=2 align=center><input type=submit name=submit class=submit value=Submit></td></tr>";
		echo "</table></form>";
	}
}
?>