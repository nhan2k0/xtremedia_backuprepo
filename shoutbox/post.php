<?php
	session_start();

	require_once('lib/domit/xml_domit_lite_include.php');
	require_once('lib/St_XmlParser.class.php');	
	require_once('lib/St_ConfigManager.class.php');
	require_once('lib/St_FileDao.class.php');
	require_once('lib/St_PersistenceManager.class.php');
	require_once('lib/St_RuleProcessor.class.php');
	require_once('lib/St_InputProcessor.class.php');
	require_once('lib/St_PostManager.class.php');


define('IN_MEDIA',true);
	require_once('../includes/config.php');

function m_checkLogin(){
	global $mysql, $tb_prefix;
	$time_exit = NOW;
	$mysql->query("UPDATE ".$tb_prefix."user SET user_lastvisit = user_timeout, user_identifier = '', user_timeout = '', user_ip = '', user_online = 0 WHERE user_timeout < ".$time_exit." AND user_timeout > 0");
	
	if ($_COOKIE['USER']) {
		$identifier = $_COOKIE['USER'];
		$q = $mysql->query("SELECT user_identifier, user_id FROM ".$tb_prefix."user WHERE user_online = 1 AND user_ip = '".IP."' AND user_identifier = '".$identifier."'");
		if ($mysql->num_rows($q)) {
			$r = $mysql->fetch_array($q);
			$_SESSION['user_id'] = $r['user_id'];
			$mysql->query("UPDATE ".$tb_prefix."user SET user_timeout = ".(NOW + 2*60*60)." WHERE user_id = '".$_SESSION['user_id']."'");
			$return = true;
		}
		else $return = false;
	}
	else $return = false;

	if ($return == false) {
		if ($_COOKIE['INFO']) m_setcookie('INFO', '', false);
		unset($_SESSION['user_id']);
	}
	return $return;
}
$isLoggedIn = m_checkLogin();

function m_unhtmlchars($str) {
	return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;', '&#92;', '&#39'), array('<', '>', '"', '&', chr(92), chr(39)), $str);
}

function m_get_data($type,$v,$field = '') {
	global $mysql, $tb_prefix, $cached, $value;

	if ($type == 'USER') {
		if (!$field) $field = 'user_name';
		if (!$cached['user']['user_'.$v][$field]) {
			$r = $mysql->fetch_array($mysql->query("SELECT ".$field." FROM ".$tb_prefix."user WHERE user_id = '".$v."'"));
			if ($field == 'user_name') $r[$field] = m_unhtmlchars($r[$field]);
			$cached['user']['user_'.$v][$field] = $r[$field];
		}
		return $cached['user']['user_'.$v][$field];
	}
}

if ($isLoggedIn) {
	$username = m_get_data('USER',$_SESSION['user_id']);
	$HttpRequest['name'] = $username;
}
else {
	$username = trim($_POST['name']);
	$HttpRequest['name'] = "{".$username."}";

}
	

	$HttpRequest['url'] =  trim($_POST['mail_or_url']);
	$HttpRequest['message'] = trim($_POST['message']);
	
	$postManager =& new St_PostManager();
	$errorMessage = null;
	
	if(empty($HttpRequest['name']) or empty($HttpRequest['message'])){
		$errorMessage = "Name and Message is required!";
	}
	elseif( $HttpRequest['name'] == "{Name}" OR $HttpRequest['message'] == "Message" ){
		$errorMessage = "Name and Message is required!";
	}		
	else{
		if($postManager->doPost($HttpRequest) == false){
			$errorMessage = $postManager->getErrorMessage();
		}
	}
	
	if(empty($errorMessage)){
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/view.php');
	}else{
		echo '<center>';
		echo $errorMessage;
		echo '</center>';
		include "view.php";
	}

?>