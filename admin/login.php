<?php
define('IN_MEDIA', true);
include("../includes/config.php");
if (isset($_POST["submit"])) {
	$name = $_POST['name'];
	$password = md5(stripslashes($_POST['password']));
	$q = $mysql->query("SELECT user_id FROM ".$tb_prefix."user WHERE user_name = '".$name."' AND user_password = '".$password."' AND (user_level = 2 OR user_level = 3)");
	if ($mysql->num_rows($q)) {
		$r = $mysql->fetch_array($q);
		$_SESSION['admin_id'] = $r['user_id'];
	}
	header("Location: ./");
}
?>
