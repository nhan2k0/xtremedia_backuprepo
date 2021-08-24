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
	require_once('lib/St_XmlParser.class.php');	
	require_once('lib/St_ConfigManager.class.php');
	require_once('lib/St_FileDao.class.php');
	require_once('lib/St_PersistenceManager.class.php');
	require_once('lib/St_TemplateParser.class.php');
	require_once('lib/St_ViewManager.class.php');
	
	
	$viewManager =& new St_ViewManager();
	$viewManager->display();

?>