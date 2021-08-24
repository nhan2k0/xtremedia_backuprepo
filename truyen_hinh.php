<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
$tpl =& new Template;
$chanel = $value[1];

if ($chanel){
	switch ($chanel) {
		case VTC1 : $chanel_url = 'mms://www.vtc.com.vn/VTC1_2812'; break;
		case VTC2 : $chanel_url = 'mms://www.vtc.com.vn/VTC2_2813'; break;
		case VTC3 : $chanel_url = 'mms://www.vtc.com.vn/VTC3_2814'; break;
		case VTC5 : $chanel_url = 'mms://www.vtc.com.vn/VTC5_2817'; break;
		case HTV7 : $chanel_url = 'mms://210.245.126.153/HTV7'; break;
		case HTV9 : $chanel_url = 'mms://www.vtc.com.vn/HTV9_2818'; break;
		case HTVCMUSIC : $chanel_url = 'mms://210.245.126.153/HTVCMUSIC'; break;
		case HTVCMOVIE : $chanel_url = 'mms://210.245.126.153/HTVCMOVIE'; break;
		case VTV1 : $chanel_url = 'mms://210.245.126.153/VTV1/'; break;
		case VTV2 : $chanel_url = 'mms://210.245.126.153/VTV2/'; break;
		case VTV3 : $chanel_url = 'mms://210.245.126.153/VTV3/'; break;
		case VTV4 : $chanel_url = 'mms://210.245.126.153/VTV4/'; break;
		case HTV : $chanel_url = 'mms://203.162.1.217/HTV'; break;
		case DN1 : $chanel_url = 'mms://www.dongnai.gov.vn/dn1'; break;
		case DN2 : $chanel_url = 'mms://www.dongnai.gov.vn/dn2'; break;

	}
}
else $chanel_url = 'img/TV.jpg';

$main = $tpl->get_tpl('truyen_hinh');
$main = $tpl->assign_vars($main,
	array(
		'URL'	=>	$chanel_url,
	)
);
$tpl->parse_tpl($main);

?>