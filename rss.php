<?php

define('IN_MEDIA',true);
include('includes/config.php');
include('includes/functions.php');

$web_description = "Nghe và Download miễn phí: nhạc, phim, ebook, softs...";
$limit = '30'; // Số lượng media trong RSS


$web_name = m_get_config('web_title');
$web_url = m_get_config('web_url');
$admin_email = m_get_config('web_email');
$last_built = date('D, d M Y H:i:s O');
$copy_year = "COPYRIGHT".date("Y");
header('Content-type: text/xml; charset="utf-8"', true);
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "\n<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "	<title>$web_name</title>\n";
echo "	<link>$web_url</link>\n";
echo "	<description>$web_description</description>\n";
echo "	<copyright>$copy_year (C) $web_name</copyright>\n";
echo "	<generator>$web_name</generator>\n";
echo "	<language>vi-vn</language>\n";
echo "	<lastBuildDate>$last_built</lastBuildDate>\n";
echo "	<managingEditor> $admin_email </managingEditor>\n";
echo "	<webMaster> $admin_email </webMaster>\n";
echo "	<ttl>60</ttl>\n\n";
echo "	<image>\n";
echo "		<title>$web_name</title>\n";
echo "		<url>".$web_url."/img/yes.gif</url>\n"; //dia chi logo 
echo "		<link>$web_url</link>\n";
echo "		<width>32</width>\n";
echo "		<height>32</height>\n";
echo "		<description>$web_name</description>\n";
echo "	</image>\n\n";

$fields = "m_id, m_title, m_singer, m_type, m_date, m_time, IF(m_lyric = '' OR m_lyric IS NULL,0,1) m_lyric";
$q = "SELECT ".$fields." FROM ".$tb_prefix."data ORDER BY m_id DESC LIMIT ".$limit." ";

if ($q) $q = $mysql->query($q);
if ($mysql->num_rows($q)) {
	while ($r = $mysql->fetch_array($q)) {
		$m_id = $r['m_id'];
		
		$singer = m_get_data('SINGER',$r['m_singer']);
		$singer_type = m_get_data('SINGER',$r['m_singer'],'singer_type');
		$singer_is_member = ($singer_type == '9')?' (Thành viên tự thể hiện)':'';
	//	$lyric = getwords(strip_tags(m_text_tidy(m_get_data('SONG',$m_id,'m_lyric'))),40);

//		date_default_timezone_set('GMT');
		$m_time = date('D, d M Y H:i:s O', $r['m_time']);
		if ($r['m_time'] =='0') { 
			$date_n = split('-',$r['m_date']); // m_date dang 2007-02-19
			$m_time = mktime(0,0,0,$date_n[1],$date_n[2],$date_n[0]);
			$m_time = date('D, d M Y H:i:s O', $m_time);
			
		}

		switch ($r['m_type']) {
			case 1 : $media_type = 'Music'; $singer = m_get_data('SINGER',$r['m_singer']); break;
			case 2 : $media_type = 'Flash'; $singer = m_get_data('SINGER',$r['m_singer']); break;
			case 3 : $media_type = 'Movie'; $singer = m_get_data('SINGER',$r['m_singer']); break;
			case 4 : $media_type = 'Ebook'; $singer = "EBOOK"; break;
			case 5 : $media_type = 'Application'; $singer = "Application"; break;
			case 6 : $media_type = 'Archive'; $singer = "Archive"; break;
		}

		echo "		<item>\n"; 
		echo "			<title>".$media_type." || ".strip_tags($r['m_title'])."</title>\n"; 
		echo "			<link>".$web_url."/#Play,".$m_id."</link>\n"; 
		echo "			<description>".$media_type." || ".$singer.$singer_is_member." || Cập nhật ngày: ".$m_time."</description>\n"; 
		echo "			<guid>".$web_url."/#Play,".$m_id."</guid>\n"; 
		echo "			<pubDate>".$m_time."</pubDate>\n";
		echo "		</item>\n";

	}
}
else echo " ";
?>
	</channel>
</rss>