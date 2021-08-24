<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>XtreMedia Installation Wizard</title>
<link rel=stylesheet href='./templates/suse/style.css' type=text/css>
<style>
td {
	padding: 3px;
}
.input {
	width: 90%;
	border: 1px solid #999999;
}
a {
 	Font-size: 11px;
 	font-weight: bold;
 	color: red;
}
</style>
</head>
<body>
<div style="width:40%; position:absolute; display:block; left:30%; top:10%; background: #FFF;">
<?php
if (!isset($_POST['submit'])) {

	// -------------Lấy đường dẫn thư mục XtreMedia --------------
	$hostname = $_SERVER["HTTP_HOST"];
	$PHP_SELF = $_SERVER["PHP_SELF"];
	$leng = strlen($PHP_SELF);
	$num = strrpos($PHP_SELF,"/");
	$folder = substr_replace($PHP_SELF,"",$num,$leng);
	$web_url_auto = "http://".$hostname.$folder;
	//----------------------------------------------------------

echo <<<HTML
	<form name="setup" id="form3" method="post" action="install.php">
	<table width="100%" align="center" cellpadding="0" cellspacing="0" style="border: #AD1E1E 1px solid;">
      <tr>
        <td width="100%" colspan="2" align="center" class="headred_c" style="padding:0px;font-size:12px;">Cấu hình cài đặt Website</td>
        </tr>
      <tr>
        <td width="40%">MySQL Server:</td>
        <td width="60%"><input class="input" name="dbhost" type="text" value="localhost" /></td>
      </tr>
      <tr>
        <td>MySQL Database:</td>
        <td><input class="input" name="dbname" type="text" value="xtremedia" /></td>
      </tr>
      <tr>
        <td>MySQL Username:</td>
        <td><input class="input" name="dbuser" type="text" /></td>
      </tr>
      <tr>
        <td>MySQL Password:</td>
        <td><input class="input" name="dbpass" type="password" /></td>
      </tr>
      <tr>
        <td>MySQL Table Prefix:</td>
        <td><input class="input" name="tblprefix" type="text" value="media_" /></td>
      </tr>
      <tr>
        <td>Địa chỉ Website:</td>
        <td><input class="input" name="web_url" type="text" id="web_url" value="$web_url_auto" ></td>
      </tr>
      <tr>
        <td width="100%" colspan="2" align="center" class="headred_c" style="padding:0px;font-size:12px;">Tạo tài khoản Admin</td>
      </tr>
      <tr>
        <td>Tên đăng nhập:</td>
        <td><input class="input" name="a_name" type="text" id="a_name" value="admin" ></td>
      </tr>
      <tr>
        <td>Mật khẩu:</td>
        <td><input class="input" name="a_pass1" type="password" id="a_pass1" ></td>
      </tr>
      <tr>
        <td>Xác nhận Mật khẩu:</td>
        <td><input class="input" name="a_pass2" type="password" id="a_pass2" ></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input class="input" name="a_email" type="text" id="a_email"></td>
      </tr>
      <tr>
        <td>Giới tính:</td>
        <td>
    		<input name="a_sex" id="a_sex" type="radio" value="1" checked class=checkbox> Nam &nbsp;&nbsp;
    		<input name="a_sex" id="a_sex" type="radio" value="2" class=checkbox> Nữ
    	</td>
      </tr>
      <tr>
        <td colspan="2" style="padding:20px">
    		<div align="center">
          		<input type="submit" name="submit" value="Install" class="button_1" />
        	</div>
    	</td>
      </tr>
    </table>	
	  </form>

HTML;
}

if (isset($_POST['submit'])) {
	
	$db_host = $_POST['dbhost'];
	$db_name = $_POST['dbname'];
	$db_user = $_POST['dbuser'];
	$db_pass = $_POST['dbpass'];
	$tbl_prefix = $_POST['tblprefix'];
//	$site_name = $_POST['site_name'];
	$site_name = 'XtreMedia';
	$web_url = $_POST['web_url'];
	
	$a_name = $_POST['a_name'];
	$a_pass1 = $_POST['a_pass1'];
	$a_pass2 = $_POST['a_pass2'];
	$a_email = $_POST['a_email'];
	$a_sex = $_POST['a_sex'];
	function err_message($message) {
		echo "
			<table width=100% align=center cellpadding=0 cellspacing=1 style=\"border: #AD1E1E 1px solid;\">
				<tr>
						<td width=100% align=center class=headred_c style=\"padding:0px;font-size:12px;\">Lỗi trong quá trình cài đặt</td>
				</tr>
				<tr><td style=\"padding:10px\">".$message."</td></tr>
			</table>
					";
	}
	if (!isset($a_name) || $a_name =='') { err_message('Kiểm tra lại phần thông tin tạo Admin') ; exit(); }
	if (!isset($a_pass1) || $a_pass1 =='') { err_message('Kiểm tra lại phần thông tin tạo Admin') ; exit(); }
	if (!isset($a_email) || $a_email =='') { err_message('Kiểm tra lại phần thông tin tạo Admin') ; exit(); }
	if ($a_pass1 !== $a_pass2) { err_message('Hai lần nhập Mật khẩu admin không khớp') ; exit(); }
	
	$a_pass = md5($a_pass1);
	$time_now = date("Y-m-d",time());
	// -------------Lấy đường dẫn thư mục XtreMedia --------------
	$hostname = $_SERVER["HTTP_HOST"];
	$PHP_SELF = $_SERVER["PHP_SELF"];
	$leng = strlen($PHP_SELF);
	$num = strrpos($PHP_SELF,"/");
	$folder = substr_replace($PHP_SELF,"",$num,$leng);
	$web_url_auto = "http://".$hostname.$thu_muc_chinh;
	//----------------------------------------------------------

	echo "
		<table width=100% align=center cellpadding=0 cellspacing=1 style=\"border: #AD1E1E 1px solid;\">
				<tr>
					<td width=100% align=center class=headred_c style=\"padding:0px;font-size:12px;\">Install</td>
				</tr>
				<tr><td>
				";
	
	
	
	$db = @mysql_connect($db_host, $db_user, $db_pass);
	if ($db == false) {
		echo "Unable to connect to MySQL server. - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>"; 
		exit();
	}
	@mysql_query("SET NAMES utf8", $db);
	echo "MySQL connection...<b>OK</b><br>";

	// create the database if it doesn´t exist and select it
	echo "Creating Database...";
	$result = mysql_query("CREATE DATABASE IF NOT EXISTS ".$db_name);
	if (!result){ 
		echo "Unable to create database - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	if (!@mysql_select_db($db_name)) {
		echo "Unable to connect to MySQL server. - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>"; 
		exit();
	}

	// create the media_ads table
	echo "Creating table ".$tbl_prefix."ads ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."ads` (
		  `ads_id` int(2) NOT NULL auto_increment,
		  `ads_web` varchar(255) NOT NULL default '',
		  `ads_url` varchar(255) NOT NULL default '',
		  `ads_img` varchar(255) NOT NULL default '',
		  `ads_count` int(5) NOT NULL default '0',
		  PRIMARY KEY  (`ads_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=3 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// insert to media_ads
	mysql_query("INSERT INTO `".$tbl_prefix."ads` VALUES (1, 'DaoNgoc.Com - PhuQuocOnline.Net', 'http://www.daongoc.com', 'http://www.cknl.net/media/img/ad_daongoc.gif', 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."ads` VALUES (2, 'HVACR Media', 'http://media.hvacr.com.vn', 'http://www.cknl.net/media/img/ad_media_hvacr.gif', 0);");

	// create the media_album table
	echo "Creating table ".$tbl_prefix."album ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."album` (
		  `album_id` int(10) NOT NULL auto_increment,
		  `album_name` varchar(255) NOT NULL default '',
		  `album_name_ascii` varchar(255) NOT NULL default '',
		  `album_singer` varchar(50) NOT NULL default '',
		  `album_img` varchar(255) NOT NULL default '',
		  `album_info` text NOT NULL,
		  `album_viewed` int(10) NOT NULL default '0',
		  PRIMARY KEY  (`album_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_cat table
	echo "Creating table ".$tbl_prefix."cat ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."cat` (
		  `cat_id` int(3) NOT NULL auto_increment,
		  `m_title_ascii` varchar(120) NOT NULL default '',
		  `cat_name` varchar(120) NOT NULL default '',
		  `cat_type` varchar(3) NOT NULL default '',
		  `cat_order` int(3) NOT NULL default '0',
		  `sub_id` int(3) default NULL,
		  PRIMARY KEY  (`cat_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=11 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (1, '', 'Music', '', 1, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (2, '', 'Video', '', 2, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (3, '', 'Download', '', 3, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (4, '', 'Viá»‡t Nam', '', 1, 1);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (5, '', 'NÆ°á»›c NgoÃ i', '', 2, 1);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (6, '', 'Funny Clip', '', 1, 2);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (7, '', 'Xem Phim Online', '', 2, 2);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (8, '', 'HÃ²a Táº¥u - Äá»™c Táº¥u', '', 3, 1);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (9, '', 'Ebooks', '', 1, 3);");
	mysql_query("INSERT INTO `".$tbl_prefix."cat` VALUES (10, '', 'Softwares', '', 2, 3);");



	// create the media_comment table
	echo "Creating table ".$tbl_prefix."comment ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."comment` (
		  `comment_id` int(5) NOT NULL auto_increment,
		  `comment_media_id` int(5) NOT NULL default '0',
		  `comment_poster` varchar(5) NOT NULL default '',
		  `comment_content` text NOT NULL,
		  `comment_time` varchar(12) NOT NULL default '',
		  PRIMARY KEY  (`comment_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_config table
	echo "Creating table ".$tbl_prefix."comment ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."config` (
		  `config_name` varchar(50) NOT NULL default '',
		  `config_value` text NOT NULL,
		  PRIMARY KEY  (`config_name`)
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// Insert to media_config Table
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('default_tpl', 'suse');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('total_visit', '1');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('announcement', '');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('server_address', '');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('web_title', '".$site_name."');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('web_url', '".$web_url."');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('server_folder', 'data_nhac00001');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('server_url', '".$web_url."_data');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('current_month', '5');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('web_email', '".$a_email."');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('download_salt', '124w5x');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('must_login_to_download', '0');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('must_login_to_play', '0');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('must_login_to_rate', '0');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('media_per_page', '30');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('mod_permission', '4193281');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('intro_song', '');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('intro_song_is_local', '0');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('site_off', '0');");
	mysql_query("INSERT INTO `".$tbl_prefix."config` VALUES ('site_off_announcement', 'Website tam thoi khong truy cap duoc');");


	// create the media_counter table
	echo "Creating table ".$tbl_prefix."counter ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."counter` (
		  `ip` varchar(15) NOT NULL default '',
		  `sid` varchar(32) NOT NULL default '',
		  `time` varchar(12) NOT NULL default '0'
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_data table
	echo "Creating table ".$tbl_prefix."data ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."data` (
		  `m_id` int(10) NOT NULL auto_increment,
		  `m_title` varchar(120) NOT NULL default '',
		  `m_title_ascii` varchar(120) NOT NULL default '',
		  `m_singer` int(5) NOT NULL default '0',
		  `m_album` int(5) NOT NULL default '0',
		  `m_cat` varchar(120) NOT NULL default '',
		  `m_url` varchar(250) NOT NULL default '',
		  `m_more_url` text,
		  `m_poster` varchar(5) NOT NULL default '',
		  `m_is_local` tinyint(1) NOT NULL default '0',
		  `m_public` tinyint(1) NOT NULL default '0',
		  `m_lyric` text,
		  `m_type` int(1) NOT NULL default '0',
		  `m_width` int(3) default NULL,
		  `m_height` int(3) default NULL,
		  `m_viewed` int(10) NOT NULL default '0',
		  `m_viewed_month` int(10) NOT NULL default '0',
		  `m_downloaded` int(5) NOT NULL default '0',
		  `m_downloaded_month` int(10) NOT NULL default '0',
		  `m_date` date NOT NULL default '0000-00-00',
		  `m_time` int(50) NOT NULL default '0',
		  `m_is_broken` tinyint(1) NOT NULL default '0',
		  `m_rating` int(11) NOT NULL default '0',
		  `m_rating_total` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`m_id`),
		  KEY `m_title` (`m_title`)
		) ENGINE=MyISAM  AUTO_INCREMENT=7 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}
	
	// INSERT some sample media file
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (1, 'Aloha', 'aloha', -2, 0, '5', 'http://files.myopera.com/blackmoon/files/Aloha_Cool.wma', NULL, '1', 0, 0, '', 1, 0, 0, 1, 1, 1, 0, '".$time_now."', ".time().", 0, 0, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (2, 'Anh Pháº£i LÃ m Sao', 'anh phai lam sao', -1, 0, '4', 'mms://210.245.24.10/Music/NhacTre/DanTruong_AnhPhaiLamSao/wma/01_DanTruong_AnhPhaiLamSao_NhatTrung.wma', NULL, '1', 0, 0, NULL, 1, NULL, NULL, 2, 1, 1, 1, '".$time_now."', ".time().", 0, 0, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (3, 'Good Dog, Bad Dog', 'good dog, bad dog', -2, 0, '6', 'http://media1.clip.vn/mediaserver/tuanna/294.flv', NULL, '1', 0, 0, '', 2, 420, 340, 2, 2, 2, 2, '".$time_now."', ".time().", 0, 0, 0);");
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (4, 'Lá»¡ Tay Cháº¡m Ngá»±c Con GÃ¡i', 'lo tay cham nguc con gai', -1, 0, '9', 'http://files.myopera.com/blackmoon/files/LoTayChamNgucConGai_TrangHa.pdf', NULL, '1', 0, 0, '', 4, 0, 0, 2, 2, 2, 2, '".$time_now."', ".time().", 0, 0, 0);");	
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (5, 'ÂµTorrent 1.6.1', 'âµtorrent 1.6.1', -1, 0, '10', 'http://download.utorrent.com/1.6.1/utorrent.exe', NULL, '1', 0, 0, '&lt;font size=&quot;4&quot;&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;ÂµTorrent&lt;/span&gt;&lt;/font&gt; is an efficient and feature rich BitTorrent client for Windows sporting a very small footprint. It was designed to use as little cpu, memory and space as possible while offering all the functionality expected from advanced clients', 5, 0, 0, 2, 2, 1, 1, '".$time_now."', ".time().", 0, 0, 0);");	
	mysql_query("INSERT INTO `".$tbl_prefix."data` VALUES (6, 'Alien V.S Predator', 'alien v.s predator', -2, 0, '7', 'mms://203.162.168.211/vnntv/phim/phimgiatuong/cuochienduoichanthapco.wmv', NULL, '1', 0, 0, '', 3, 420, 340, 2, 2, 2, 2, '".$time_now."', ".time().", 0, 0, 0);");
	
	
	// create the media_gift table
	echo "Creating table ".$tbl_prefix."gift ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."gift` (
		  `gift_id` varchar(20) NOT NULL default '',
		  `gift_media_id` int(5) NOT NULL default '0',
		  `gift_sender_id` int(5) NOT NULL default '0',
		  `gift_sender_name` varchar(100) NOT NULL default '',
		  `gift_sender_email` varchar(100) NOT NULL default '',
		  `gift_recip_name` varchar(100) NOT NULL default '',
		  `gift_recip_email` varchar(100) NOT NULL default '',
		  `gift_message` text NOT NULL,
		  `gift_time` varchar(12) NOT NULL default '',
		  PRIMARY KEY  (`gift_id`)
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_manage table
	echo "Creating table ".$tbl_prefix."manage ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."manage` (
		  `manage_type` varchar(25) NOT NULL default '',
		  `manage_user` varchar(5) NOT NULL default '',
		  `manage_media` varchar(5) NOT NULL default '',
		  `manage_timeout` varchar(12) NOT NULL default ''
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_news_cat table
	echo "Creating table ".$tbl_prefix."news_cat ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."news_cat` (
	  `cat_id` int(3) NOT NULL auto_increment,
	  `m_title_ascii` varchar(120) NOT NULL default '',
	  `cat_name` varchar(120) NOT NULL default '',
	  `cat_type` varchar(3) NOT NULL default '',
	  `cat_order` int(3) NOT NULL default '0',
	  `sub_id` int(3) default NULL,
	  PRIMARY KEY  (`cat_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=7 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	mysql_query("INSERT INTO `media_news_cat` VALUES (1, '', 'Tin Tá»©c', '', 1, 0);");
	mysql_query("INSERT INTO `media_news_cat` VALUES (2, '', 'Ã‚m Nháº¡c', '', 1, 1);");
	mysql_query("INSERT INTO `media_news_cat` VALUES (3, '', 'Äiá»‡n áº¢nh', '', 2, 1);");
	mysql_query("INSERT INTO `media_news_cat` VALUES (4, '', 'Truyá»‡n', '', 2, 0);");
	mysql_query("INSERT INTO `media_news_cat` VALUES (5, '', 'Chicken Soup', '', 1, 4);");
	mysql_query("INSERT INTO `media_news_cat` VALUES (6, '', 'Truyá»‡n CÆ°á»i', '', 2, 4);");

	// create the media_news table
	echo "Creating table ".$tbl_prefix."news ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."news` (
		  `news_id` int(10) NOT NULL auto_increment,
		  `news_cat` varchar(120) NOT NULL default '0',
		  `news_tieude` mediumtext NOT NULL,
		  `news_tieude_ascii` mediumtext NOT NULL,
		  `news_img` text NOT NULL,
		  `news_infoanh` text NOT NULL,
		  `news_noidung` text NOT NULL,
		  `news_noidung_ascii` text NOT NULL,
		  `news_from` varchar(50) NOT NULL default '',
		  `news_datepost` varchar(32) NOT NULL default '',
		  PRIMARY KEY  (`news_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}
	// Insert Sample News

	mysql_query("INSERT INTO `".$tbl_prefix."news` VALUES (1, '2', 'Rock khÃ´ng cÃ³ tuá»•i!', 'rock khong co tuoi!', 'http://www.tuoitre.com.vn/Tianyon/ImageView.aspx?ThumbnailID=195811', 'Iggy Pop trong buá»•i diá»…n nhÃ¢n dá»‹p sinh nháº­t 60 tuá»•i cá»§a Ã´ng', '&lt;span style=&quot;font-weight: bold;&quot;&gt;TTO - Trong vÃ i thÃ¡ng tá»›i, nhiá»u ban nháº¡c rock huyá»n thoáº¡i - nay Ä‘Ã£ á»Ÿ tuá»•i xáº¿ chiá»u - nhÆ° The Police, The Who, Slyâ€¦ sáº½ tÃ¡i há»£p trong má»™t Ä‘Ãªm diá»…n vÃ  chá»©ng minh ráº±ng tÃ¬nh yÃªu rock trong há» khÃ´ng há» giÃ  Ä‘i theo nÄƒm thÃ¡ng.&lt;/span&gt;&lt;br&gt;&lt;br&gt;CÃ¡c ban nháº¡c Family Stone, Iggy Pop, The Stooges vÃ  nhá»¯ng thÃ nh viÃªn cÃ²n láº¡i cá»§a The Doors cÅ©ng sáº½ xuáº¥t hiá»‡n. Phil Collins cÅ©ng sáº½ cÃ³ máº·t vá»›i ban nháº¡c Genesis nÄƒm xÆ°a cá»§a mÃ¬nh (nhÆ°ng khÃ´ng cÃ³ Peter Gabriel).&lt;br&gt;&lt;br&gt;Giles Green - phÃ³ chá»§ tá»‹ch hÃ£ng Ä‘Ä©a Sanctuary, má»™t hÃ£ng Ä‘Ä©a â€œchuyÃªn trá»‹â€ cÃ¡c sá»± kiá»‡n Ã¢m nháº¡c kinh Ä‘iá»ƒn - cho biáº¿t: â€œHá» váº«n khao khÃ¡t Ä‘Æ°á»£c chÆ¡i nháº¡c, cÅ©ng nhÆ° cÃ¡c fan váº«n khao khÃ¡t Ä‘Æ°á»£c xem há» trÃ¬nh diá»…n. CÃ¡c huyá»n thoáº¡i Ã¢m nháº¡c má»™t thá»i Ä‘á»u cÃ³ thá»ƒ quay láº¡i; cÃ³ má»™t lÆ°á»£ng khÃ¡n giáº£ váº«n chá» Ä‘á»£i há» Ä‘á»ƒ gáº·p láº¡i tuá»•i tráº» cá»§a mÃ¬nh vÃ  sá»‘ng láº¡i má»™t thá»i Ä‘Ã£ quaâ€.&lt;br&gt;&lt;br&gt;Trong khi Ä‘Ã³, Rolling Stones - nhá»¯ng hÃ²n Ä‘Ã¡ lÄƒn chÆ°a bao giá» má»‡t má»i - cÅ©ng sáº¯p lÃªn Ä‘Æ°á»ng cho tour diá»…n chÃ¢u Ã‚u mang tÃªn Bigger Bang. Tour diá»…n sáº½ báº¯t Ä‘áº§u vÃ o 5-6 táº¡i Bá»‰ vÃ  láº§n Ä‘áº§u tiÃªn sau 9 nÄƒm, Rolling Stones má»›i trá»Ÿ láº¡i diá»…n á»Ÿ Ba Lan. Trong khi hÃ ng loáº¡t cÃ¡c ban nháº¡c cÃ¹ng thá»i Ä‘Ã£ giáº£i tÃ¡n, cho Ä‘áº¿n giá», Rolling Stones váº«n sÃ¡t cÃ¡nh bÃªn nhau.&lt;br&gt;&lt;br&gt;Tuy nhiÃªn, nhá»¯ng dáº¥u hiá»‡u Ä‘Ã¡ng má»«ng cho má»™t cuá»™c tÃ¡i há»£p cá»§a cÃ¡c ban nháº¡c Ä‘Ã£ khiáº¿n ngÆ°á»i yÃªu nháº¡c kháº¥p khá»Ÿi, Ä‘áº·c biá»‡t lÃ  sá»± kiá»‡n báº¥t ngá» Ä‘áº§u nÄƒm nay - khi ban nháº¡c The Police tuyÃªn bá»‘ hÃ²a giáº£i sau hÆ¡n 20 nÄƒm chia cáº¯t.&lt;br&gt;', 'tto - trong vai thang toi, nhieu ban nhac rock huyen thoai - nay da o tuoi xe chieu - nhu the police, the who, slyâ€¦ se tai hop trong mot dem dien va chung minh rang tinh yeu rock trong ho khong he gia di theo nam thang.cac ban nhac family stone, iggy pop, the stooges va nhung thanh vien con lai cua the doors cung se xuat hien. phil collins cung se co mat voi ban nhac genesis nam xua cua minh (nhung khong co peter gabriel).giles green - pho chu tich hang dia sanctuary, mot hang dia â€œchuyen triâ€ cac su kien am nhac kinh dien - cho biet: â€œho van khao khat duoc choi nhac, cung nhu cac fan van khao khat duoc xem ho trinh dien. cac huyen thoai am nhac mot thoi deu co the quay lai; co mot luong khan gia van cho doi ho de gap lai tuoi tre cua minh va song lai mot thoi da quaâ€.trong khi do, rolling stones - nhung hon da lan chua bao gio met moi - cung sap len duong cho tour dien chau au mang ten bigger bang. tour dien se bat dau vao 5-6 tai bi va lan dau tien sau 9 nam, rolling stones moi tro lai dien o ba lan. trong khi hang loat cac ban nhac cung thoi da giai tan, cho den gio, rolling stones van sat canh ben nhau.tuy nhien, nhung dau hieu dang mung cho mot cuoc tai hop cua cac ban nhac da khien nguoi yeu nhac khap khoi, dac biet la su kien bat ngo dau nam nay - khi ban nhac the police tuyen bo hoa giai sau hon 20 nam chia cat.', 'TTO', '28-05-2007');");


	// create the media_online table
	echo "Creating table ".$tbl_prefix."online ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."online` (
		  `timestamp` varchar(15) NOT NULL default '0',
		  `ip` varchar(15) NOT NULL default '',
		  `sid` varchar(32) NOT NULL default '',
		  KEY `timestamp` (`timestamp`),
		  KEY `ip` (`ip`)
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_playlist table
	echo "Creating table ".$tbl_prefix."playlist ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."playlist` (
		  `playlist_id` varchar(20) NOT NULL default '',
		  `playlist_contents` varchar(255) NOT NULL default ''
		) ENGINE=MyISAM ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_request table
	echo "Creating table ".$tbl_prefix."request ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."request` (
		  `request_id` int(10) NOT NULL auto_increment,
		  `request_title` varchar(255) NOT NULL default '',
		  `request_singer` varchar(255) NOT NULL default '',
		  `request_author` varchar(255) NOT NULL default '',
		  `request_info` text NOT NULL,
		  `request_ym` varchar(100) NOT NULL default '',
		  `request_email` varchar(100) NOT NULL default '',
		  `request_ip` varchar(15) NOT NULL default '',
		  `request_date` varchar(12) NOT NULL default '',
		  `request_admin` text NOT NULL,
		  PRIMARY KEY  (`request_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_singer table
	echo "Creating table ".$tbl_prefix."singer ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."singer` (
		  `singer_id` int(10) NOT NULL auto_increment,
		  `singer_name` varchar(255) NOT NULL default '',
		  `singer_name_ascii` varchar(255) NOT NULL default '',
		  `singer_img` varchar(255) NOT NULL default '',
		  `singer_info` text NOT NULL,
		  `singer_type` char(1) NOT NULL default '',
		  PRIMARY KEY  (`singer_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}

	// create the media_tpl table
	echo "Creating table ".$tbl_prefix."tpl ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."tpl` (
		  `tpl_id` int(3) NOT NULL auto_increment,
		  `tpl_sname` varchar(20) NOT NULL default '',
		  `tpl_fname` varchar(255) NOT NULL default '',
		  `tpl_order` int(3) NOT NULL default '0',
		  PRIMARY KEY  (`tpl_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}
	// Insert tpl
	mysql_query("INSERT INTO `".$tbl_prefix."tpl` VALUES (1, 'suse', 'suse', 1);");

	// create the media_user table
	echo "Creating table ".$tbl_prefix."user ...";
	if (!mysql_query("
		CREATE TABLE `".$tbl_prefix."user` (
		  `user_id` int(5) NOT NULL auto_increment,
		  `user_name` varchar(50) NOT NULL default '',
		  `user_password` varchar(50) NOT NULL default '',
		  `user_new_password` varchar(15) NOT NULL default '',
		  `user_email` varchar(100) default NULL,
		  `user_sex` char(1) NOT NULL default '0',
		  `user_hide_info` varchar(32) NOT NULL default '',
		  `user_regdate` varchar(12) NOT NULL default '',
		  `user_level` tinyint(1) NOT NULL default '1',
		  `user_playlist_id` varchar(20) NOT NULL default '',
		  `user_online` tinyint(1) NOT NULL default '0',
		  `user_ip` varchar(15) NOT NULL default '',
		  `user_identifier` varchar(32) default NULL,
		  `user_timeout` varchar(12) default NULL,
		  `user_lastvisit` varchar(12) NOT NULL default '',
		  PRIMARY KEY  (`user_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
		")) {
		echo "Unable to create table - ".mysql_error()."<br><br><a href=\"javascript:history.back()\">Go back</a> to change the settings.</td></tr></table>";
		exit();
	}
	else {echo "<b>OK</b><br>";}
	// Insert Admin
	mysql_query("INSERT INTO `".$tbl_prefix."user` VALUES (1, '".$a_name."', '".$a_pass."', '', '".$a_email."', '".$a_sex."', '0', '".$time_now."', 3, 'RIh1IwhGzUpPDl6YG1FK', 0, '', '', '', '1179837503');");


	echo "<br><div align=center style=\"font-size:14px; font-weight:bold; margin:10px\">Quá trình cài đặt đã hoàn tất !!!</div>";
	echo "</td></tr></table>";}
?>

</div>
</body>
</html>