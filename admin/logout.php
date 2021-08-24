<?php
define('IN_MEDIA',true);
include('../includes/config.php');
unset($_SESSION['admin_id']);
session_destroy();
header("Location: ./");
?>