<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
function db_getDbCollation($db) {
	global $mysql;
	$return = $mysql->fetch_array($mysql->query("SHOW VARIABLES LIKE 'collation_database'"));
	$return = $return[1];
	return $return;
}
if ($_POST['submit']) {
	if (!$_POST['export_structure'] && !$_POST['export_data']) exit();
	$match = $mysql->fetch_array($mysql->query("SELECT VERSION()"));
	$match = explode('.', $match[0]);
	define('MYSQL_VERSION',(int) sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
	unset($match);
	
	define('NOW',gmmktime());
	if (MYSQL_VERSION >= 40102)	define('ENGINE_KEYWORD','ENGINE');
	else define('ENGINE_KEYWORD','TYPE');
	
	$q_tb = $mysql->query("SHOW TABLES LIKE '".$tb_prefix."%'");
	$sql = 
		"-- --------------------------------------------------------\n".
		"-- XTREMEDIA DATABASE BACKUP\n".
		"-- DATE : ".date('d m Y',NOW)."\n".
		"-- Created by RedPhoenix89\n".
		"-- --------------------------------------------------------\n\n";
	while ($r_tb = $mysql->fetch_array($q_tb)) {
		$table_name = $r_tb[0];
		
		$q_cl = $mysql->query("SHOW COLUMNS FROM `$table_name`");
		$str = 
			"-- --------------------------------------------------------\n".
			"-- TABLE $table_name\n".
			"-- --------------------------------------------------------\n\n";
		if ($_POST['drop_table']) $str .= "DROP TABLE IF EXISTS `$table_name`;\n";
		$str .= "CREATE TABLE `$table_name` (\n";
		$pri_arr = array();
		$key_arr = array();
		$cl_name = '';
		$cl_size = $mysql->num_rows($q_cl);
		while ($r_cl = $mysql->fetch_array($q_cl)) {
			extract($r_cl);
			$str .= "  `". $Field ."` ". $Type;
			$cl_name .= "`". $Field ."`, ";
			if ($Null && !$Default) $str .= ' default NULL';
			elseif (isset($Default)) $str .= " NOT NULL default '". $Default ."'";
			elseif (!$Null) $str .= " NOT NULL";
			if ($Extra) $str .= " auto_increment";
			$str .= ",\n";
			if ($Key == 'PRI') $pri_arr[] = $Field;
			elseif ($Key == 'MUL') $key_arr[] = $Field;
		}
		unset($Field, $Type, $Null, $Default, $Extra);
		
		if (count($pri_arr)) {
			$str .= " PRIMARY KEY (";
			for ($i=0;$i<count($pri_arr);$i++)	$str .= "`$pri_arr[$i]`,";
			$str = substr($str,0,-1);
			$str .= "),\n";
		}
		if (count($key_arr)) for ($i=0;$i<count($key_arr);$i++)	$str .= " KEY `$key_arr[$i]` (`$key_arr[$i]`),\n";
		
		$str = substr($str,0,-2);
		$r_status = $mysql->fetch_array($mysql->query("SHOW TABLE STATUS LIKE '$table_name'"));
		extract($r_status);
		if (ENGINE_KEYWORD == 'ENGINE') $Type = $Engine;
		$str .= "\n) ".ENGINE_KEYWORD."=".$Type;
		
		if ($Auto_increment) $str .= " AUTO_INCREMENT=".$Auto_increment;
		if (MYSQL_VERSION >= 40102) {
			$collation = db_getDbCollation($db_name);
			if (strpos($collation, '_'))
				$str .= ' DEFAULT CHARACTER SET '.substr($collation, 0, strpos($collation, '_')).' COLLATE '.$collation;
			else
				$str .= ' DEFAULT CHARACTER SET '.$collation;
		}
		if ($Comment) $str .= " COMMENT='".addslashes($Comment)."'";
		
		$str .= ";\n\n";
		if (!$_POST['export_structure']) $str = '';
		if ($_POST['export_data']) {
			$cl_name = substr($cl_name,0,-2);
			$query = $mysql->query("SELECT * FROM `$table_name`");
			if ($mysql->num_rows($query)) {
				$str .= 
					"-- --------------------------------------------------------\n".
					"-- TABLE $table_name's DATA\n".
					"-- --------------------------------------------------------\n\n";
				$si = "INSERT INTO `$table_name` ($cl_name) VALUES ";
				while ($r = $mysql->fetch_array($query)) {
					$si .= "(";
					for ($i=0;$i<$cl_size;$i++) $si .= "'".addslashes(stripslashes($r[$i]))."',";
					$si = substr($si,0,-1);
					$si .= "),";
				}
				$si = substr($si,0,-1);
				$si = str_replace(
					array("\n","\t","\r","'),('"),
					array("\\n","\\t","\\r","'),\n('"),
					$si);
				
				$si .= ";\n";
			}
			$str .= $si;
		}
		$str .= "\n\n";
		$sql .= $str;
		unset($si,$str);
	}
	$sql = gzencode($sql,9);
	$size = strlen($sql);
	header("Content-disposition: attachment; filename=MediaDB_".date('d_m_y',NOW).".sql.gz");
	header('Content-type: application/gzip; charset=UTF-8');
	header("Content-Length: $size");
	echo $sql;
	exit();
}
else {
	echo "<form method=post>".
		"<input class=checkbox type=checkbox name=export_structure value=1 checked> Backup cấu trúc<br>".
		"<input class=checkbox type=checkbox name=export_data value=1 checked> Backup dữ liệu<br>".
		"<input class=checkbox type=checkbox name=drop_table value=1 checked> Thêm DROP TABLE IF EXISTS<br>".
		"<input type=submit name=submit class=submit value=Backup>".
		"</form>";
}
?>