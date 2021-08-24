<?php
	require_once('checklogin.php');

/*
// View for Login
if ($isLoggedIn) {
	$username = m_get_data('USER',$_SESSION['user_id']);
	$HttpRequest['name'] = $username;
}
else {
	exit();
}
*/
    require_once('lib/domit/xml_domit_lite_include.php');
	require_once('lib/view_full_St_ConfigManager.class.php');
	require_once('lib/view_full_St_XmlParser.class.php');	
	require_once('lib/St_FileDao.class.php');
	require_once('lib/St_PersistenceManager.class.php');
	require_once('lib/view_full_St_TemplateParser.class.php');
	require_once('lib/St_ViewManager.class.php');
	
	
	$viewManager =& new St_ViewManager();
	$viewManager->display();
	
	echo "<p align=\"center\">";
	$tongsotrang = $tong_so_tin_nhan/$max_message_per_page;
	for($i=1; $i < $tongsotrang+1;$i++){ echo "<a href=\"full.php?p=$i\">[$i]</a> ";}
	echo "</div><p>&nbsp;</p></body></html>";
?>