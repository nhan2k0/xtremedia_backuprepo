<?php
	session_start();
	
	define('IN_MEDIA', true);
	include("../../includes/config.php");

//	require('password.php');
	
	if(!empty($_GET['logout'])){
		$_SESSION = array();
		session_destroy();
		$target_url = 'index.php';
	
	}else if(!empty($_POST['submit'])){
	
		$input_username = trim($_POST['username']);
		$input_password = trim($_POST['password']);

		$name = $input_username;
		$password = md5($input_password);
		$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."' AND user_password = '".$password."' AND (user_level = 3)");
		if ($mysql->num_rows($q)) {
			$r = $mysql->fetch_array($q);
			$_SESSION['SMILETAG_LOGGED'] = true;
			$target_url = 'admin.php?show=messages';
		}

		else{
			$_SESSION['SMILETAG_LOGIN_ERROR'] = true;
			$target_url = 'index.php';
		}
	}
	
	header("Location: http://" . $_SERVER['HTTP_HOST']
                     . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
                     . "/" . $target_url);
?>