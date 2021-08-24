<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
class Template {
	var $ext = ".html";
	var $cache_tpl = array();
	function get_tpl($filename,$blockname = '',$c = false) {
		$full_link = "templates/".$_SESSION['current_tpl']."/".$filename.$this->ext;
		if (!file_exists($full_link)) {
			$default_tpl = m_get_config('default_tpl');
			die("Không tìm thấy file : <b>".$full_link."</b><br><a href='?reset_tpl=1'>Trở về giao diện mặc định</a>");
		}

		if ($this->cache_tpl['file_'.$filename]) $file_content = $this->cache_tpl['file_'.$filename];
		else {
			$this->cache_tpl['file_'.$filename] = $file_content = file_get_contents($full_link);
		}
		return $file_content;
	}
	function get_block_from_str($str,$block = '',$c = false) {
		
		if (!$this->cache_tpl['block_'.$block]) {
			preg_replace('#<!-- '.(($c)?'\#':'').'BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- '.(($c)?'\#':'').'END '.$block.' -->#se','$s = stripslashes("\1");',$str);
			if ($s != $str)	$str = $s;
			else $str = '';
			$this->cache_tpl['block_'.$block] = $str;
		}
		return $this->cache_tpl['block_'.$block];
	}
	function get_box($filename) {
		$full_link = "templates/".$_SESSION['current_tpl']."/boxes/".$filename.$this->ext;
		if (!file_exists($full_link)) {
			$default_tpl = m_get_config('default_tpl');
			die("Không tìm thấy file : <b>".$full_link."</b><br><a href='?reset_tpl=1'>Trở về giao diện mặc định</a>");
		}
		if ($this->cache_tpl['box_'.$filename]) $file_content = $this->cache_tpl['box_'.$filename];
		else {
			$file_content = file_get_contents($full_link);
			$this->cache_tpl['box_'.$filename] = $file_content;
		}
		return $file_content;
	}
	
	function auto_get_block($str) {
		preg_match_all('#<!-- \#BEGIN (.*?) -->[\r\n]*(.*?)[\r\n]*<!-- \#END (.*?) -->#s', $str, $arr, PREG_PATTERN_ORDER);
		$a = array();
		for ($i=0; $i<count($arr[0]); $i++) {
			$a[$arr[1][$i]] = $arr[0][$i];
		}
		return $a;
	}

	function unset_block($code,$arr,$c = false) {
		foreach ($arr as $block) {
			$code = preg_replace('#<!-- '.(($c)?'\#':'').'BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- '.(($c)?'\#':'').'END '.$block.' -->#s', '', $code);
		}
		return $code;
	}
	function assign_vars($code,$arr) {
		foreach ($arr as $block => $val) {
			//do {
				$code = str_replace('{'.$block.'}',$val,$code);
			//}
			//while (strpos('{'.$block.'}',$code));
		}
		return $code;
	}
	function assign_vars2($code,$arr) {
		foreach ($arr as $block => $val) {
			//do {
				$code = str_replace('{'.$block.'}', $val,$code);
			//}
			//while (strpos('{'.$block.'}',$code));
		}
		$code = str_replace("\"","\\\"",$code);
		return $code;
	}
	function assign_blocks_content($code,$arr) {
		foreach ($arr as $block => $val) {
			$code = preg_replace('#<!-- BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- END '.$block.' -->#s', $val, $code);
		}
		return $code;
	}
	function parse_box($func,$exp = '') {
		$exp = trim(stripslashes($exp));
		if ($exp) $code = eval("return ".$func."(".$exp.");");
		else $code = eval("return ".$func."();");

		return $code;
	}
	
	function parse_tpl($code) {
		global $webTitle;
		$code = preg_replace('#<!-- BOX (.*?)\((.*?)\) -->#se', '$this->parse_box("\\1","\\2");', $code);
		$code = str_replace('{TPL_LINK}', "templates/".$_SESSION['current_tpl'], $code);
		$code = str_replace('{WEB_TITLE}', $webTitle, $code);
		$code = str_replace('{WEB_URL}', m_get_config('web_url'), $code);
		$code = preg_replace('#<!-- BEGIN (.*?) -->[\r\n]*(.*?)[\r\n]*<!-- END (.*?) -->#s', '\\2', $code);
		echo $code;
	}
}
?>