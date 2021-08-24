<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
include("class_mysql.php");
$mysql =& new mysql;
$mysql->connect($db_host,$db_user,$db_pass,$db_name);
?>