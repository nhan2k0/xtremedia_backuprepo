<?php
if (!defined('IN_MEDIA_ADMIN')) die("Hacking attempt");
$menu_arr = array(
	'cat'	=>	array(
		'Thể loại',
		array(
			'edit'	=>	array('Quản lý Thể loại','act=cat&mode=edit'),
			'add'	=>	array('Thêm Thể loại','act=cat&mode=add'),
		),
	),
	'media'	=>	array(
		'Media',
		array(
			'edit'	=>	array('Quản lý Media','act=song&mode=edit'),
			'edit_broken'	=>	array('Quản lý Media bị lỗi','act=song&mode=edit&show_broken=1'),
			'add'	=>	array('Thêm Media','act=song&mode=add'),
			'add_multi'	=>	array('Thêm nhiều Media','act=song&mode=multi_add'),
		),
	),
	'singer'	=>	array(
		'Ca sỹ',
		array(
			'edit'	=>	array('Quản lý Ca sỹ','act=singer&mode=edit'),
			'add'	=>	array('Thêm Ca sỹ','act=singer&mode=add'),
		),
	),
	'album'	=>	array(
		'Album',
		array(
			'edit'	=>	array('Quản lý Album','act=album&mode=edit'),
			'add'	=>	array('Thêm Album','act=album&mode=add'),
		),
	),
	'user'	=>	array(
		'User',
		array(
			'edit'	=>	array('Quản lý User','act=user&mode=edit'),
			'add'	=>	array('Thêm User','act=user&mode=add'),
		),
	),
	'link'	=>	array(
		'Liên kết',
		array(
			'edit'	=>	array('Quản lý Liên kết','act=ads&mode=edit'),
			'add'	=>	array('Thêm Liên kết','act=ads&mode=add'),
		),
	),
	'template'	=>	array(
		'Giao diện',
		array(
			'edit'	=>	array('Quản lý giao diện','act=tpl&mode=edit'),
			'add'	=>	array('Thêm giao diện','act=tpl&mode=add'),
		),
	),
	'config'	=>	array(
		'Cấu hình',
		array(
			'set_mod_permission'	=>	array('Phân quyền MOD','act=mod_permission'),
			'config'	=>	array('Cấu hình','act=config'),
			'config_server'	=>	array('Cấu hình Server Media','act=server'),
			'backup_data'	=>	array('Sao lưu dữ liệu','act=backup'),
		),
	)
);
if ($level == 2) {

	unset($menu_arr['config']);
	foreach ($menu_arr as $key => $v) {
		if (!$mod_permission['add_'.$key]) unset($menu_arr[$key][1]['add']);
		if (!$mod_permission['edit_'.$key]) unset($menu_arr[$key][1]['edit']);
		
		if ($key == 'media' && !$mod_permission['edit_'.$key]) unset($menu_arr[$key][1]['edit_broken']);
		if ($key == 'media' && !$mod_permission['add_'.$key]) unset($menu_arr[$key][1]['add_multi']);
		
		if (!$menu_arr[$key][1]) unset($menu_arr[$key]);
	}
}
echo "<div><a href='index.php?act=main'><b>Trang chủ</b></a> || <a href='logout.php'><b>Thoát</b></a></div>";
foreach ($menu_arr as $key => $arr) {
	echo "<table cellpadding=2 cellspacing=0 width=100% class=border style='margin-bottom:5'>";
	echo "<tr><td class=title><b>".$arr[0]."</b></td></tr>";
	foreach ($arr[1] as $m_key => $m_val) {
		echo "<tr><td><a href=\"?".$m_val[1]."\">".$m_val[0]."</a></td></tr>";
	}
	echo "</table>";
}
echo "<div class=footer><b>redphoenix89@yahoo.com</b></div>";
?>