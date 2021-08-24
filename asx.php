<?php
define('IN_MEDIA',true);
include('includes/config.php');
include('includes/functions.php');

if (!strstr(USER_AGENT,'Media-Player')) die();
//header("Cache-Control: private");
//header("Pragma: public");
header("Content-Type: video/x-ms-wmv; charset=utf-8");

$id = $_GET['id'];
$type = $_GET['type'];
$asx = '<asx Version="3.0">'.
	'<Title>'.$webTitle.'</Title>'.
	'<Param Name="Encoding" Value="UTF-8" />';
	
if ($intro_song = m_get_config('intro_song')) {
	$asx .= '<entry><Ref Href="'.((m_get_config('intro_song_is_local'))?$mediaFolder.'/'.$intro_song:$intro_song).'" /></entry>';
}
	
if ($type == 1) {
	$r = $mysql->fetch_array($mysql->query("SELECT m_url, m_title, m_singer, m_is_local FROM ".$tb_prefix."data WHERE m_id = ".$id));
	$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
	
	$asx .= '<repeat><entry>'.
		'<Title>'.$r['m_title'].'</Title>'.
		'<Author>'.m_get_data('SINGER',$r['m_singer']).'</Author>'.
		'<Copyright>'.$webTitle.'</Copyright>'.
		'<Ref Href="'.$url.'" />'.
	'</entry></repeat>';
}
elseif ($type == 2) {
	$q = $mysql->query("SELECT m_url, m_title, m_is_local FROM ".$tb_prefix."data WHERE m_singer = '".$id."' AND (m_type = 1 OR m_type = 3)");
	$singer_name = m_get_data('SINGER',$id);
	while ($r = $mysql->fetch_array($q)) {
		$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
		$asx .= '<entry>'.
			'<Title>'.$r['m_title'].'</Title>'.
			'<Author>'.$singer_name.'</Author>'.
			'<Copyright>'.$webTitle.'</Copyright>'.
			'<Ref Href="'.$url.'" />'.
		'</entry>';
	}
}
elseif ($type == 3) {
	$q = $mysql->query("SELECT m_url, m_title, m_singer, m_is_local FROM ".$tb_prefix."data WHERE m_album = '".$id."' AND (m_type = 1 OR m_type = 3)");
	while ($r = $mysql->fetch_array($q)) {
		$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
		$asx .= '<entry>'.
			'<Title>'.$r['m_title'].'</Title>'.
			'<Author>'.m_get_data('SINGER',$r['m_singer']).'</Author>'.
			'<Copyright>'.$webTitle.'</Copyright>'.
			'<Ref Href="'.$url.'" />'.
		'</entry>';
	}
}
elseif ($type == 4) {
	$isLoggedIn = m_checkLogin();
	//if (!$isLoggedIn) exit();
	$pl_q = $mysql->query("SELECT playlist_contents FROM ".$tb_prefix."playlist WHERE playlist_id = '".$id."'");
	if ($mysql->num_rows($pl_q)) {
		$pl_r = $mysql->fetch_array($pl_q);
		$playlist = $pl_r['playlist_contents'];
		$q = $mysql->query("SELECT m_url, m_title, m_singer, m_is_local FROM ".$tb_prefix."data WHERE m_id IN (".$playlist.")");
		while ($r = $mysql->fetch_array($q)) {
			$url = ($r['m_is_local'])?$mediaFolder.'/'.$r['m_url']:$r['m_url'];
			$asx .= '<entry>'.
				'<Title>'.$r['m_title'].'</Title>'.
				'<Author>'.m_get_data('SINGER',$r['m_singer']).'</Author>'.
				'<Copyright>'.$webTitle.'</Copyright>'.
				'<Ref Href="'.$url.'" />'.
			'</entry>';
		}
	}
	
}
$asx .= '</asx>';
echo $asx;
?>