CREATE TABLE `media_ads` (
  `ads_id` int(2) NOT NULL auto_increment,
  `ads_web` varchar(255) NOT NULL default '',
  `ads_url` varchar(255) NOT NULL default '',
  `ads_img` varchar(255) NOT NULL default '',
  `ads_count` int(5) NOT NULL default '0',
 PRIMARY KEY (`ads_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_album` (
  `album_id` int(10) NOT NULL auto_increment,
  `album_name` varchar(255) NOT NULL default '',
  `album_name_ascii` varchar(255) NOT NULL default '',
  `album_singer` varchar(50) NOT NULL default '',
  `album_img` varchar(255) NOT NULL default '',
  `album_info` text NOT NULL default '',
  `album_viewed` int(10) NOT NULL default '0',
 PRIMARY KEY (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_cat` (
  `cat_id` int(3) NOT NULL auto_increment,
  `m_title_ascii` varchar(120) NOT NULL default '',
  `cat_name` varchar(120) NOT NULL default '',
  `cat_type` char(3) NOT NULL default '',
  `cat_order` int(3) NOT NULL default '0',
  `sub_id` int(3) default NULL,
 PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_comment` (
  `comment_id` int(5) NOT NULL auto_increment,
  `comment_media_id` int(5) NOT NULL default '0',
  `comment_poster` varchar(5) NOT NULL default '',
  `comment_content` text NOT NULL default '',
  `comment_time` varchar(12) NOT NULL default '',
 PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_config` (
  `config_name` varchar(50) NOT NULL default '',
  `config_value` text NOT NULL default '',
 PRIMARY KEY (`config_name`)
) ENGINE=MyISAM;

INSERT INTO `media_config` (`config_name`, `config_value`) VALUES ('default_tpl','funnycolors'),
('total_visit','6571'),
('announcement',''),
('must_login_to_play','0'),
('web_title','XtreMedia'),
('web_url','http://localhost/xtremedia'),
('must_login_to_download','0'),
('server_folder',''),
('server_url',''),
('current_month','2'),
('web_email','redphenix89@yahoo.com'),
('download_salt','16-06-89'),
('media_per_page','30'),
('mod_permission','0'),
('intro_song',''),
('intro_song_is_local','0');

CREATE TABLE `media_counter` (
  `ip` varchar(15) NOT NULL default '',
  `sid` varchar(32) NOT NULL default '',
  `time` varchar(12) NOT NULL default '0'
) ENGINE=MyISAM;

CREATE TABLE `media_data` (
  `m_id` int(10) NOT NULL auto_increment,
  `m_title` varchar(120) NOT NULL default '',
  `m_title_ascii` varchar(120) NOT NULL default '',
  `m_singer` int(5) NOT NULL default '0',
  `m_album` int(5) NOT NULL default '0',
  `m_cat` varchar(120) NOT NULL default '',
  `m_url` varchar(250) NOT NULL default '',
  `m_poster` varchar(5) NOT NULL default '',
  `m_is_local` tinyint(1) NOT NULL default '0',
  `m_lyric` text default NULL,
  `m_type` int(1) NOT NULL default '0',
  `m_width` int(3) default NULL,
  `m_height` int(3) default NULL,
  `m_viewed` int(10) NOT NULL default '0',
  `m_viewed_month` int(10) NOT NULL default '0',
  `m_downloaded` int(5) NOT NULL default '0',
  `m_downloaded_month` int(10) NOT NULL default '0',
  `m_date` date NOT NULL default '0000-00-00',
  `m_is_broken` tinyint(1) NOT NULL default '0',
 PRIMARY KEY (`m_id`),
 KEY `m_title` (`m_title`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_gift` (
  `gift_id` varchar(20) NOT NULL default '',
  `gift_media_id` int(5) NOT NULL default '0',
  `gift_sender_id` int(5) NOT NULL default '0',
  `gift_sender_name` varchar(100) NOT NULL default '',
  `gift_sender_email` varchar(100) NOT NULL default '',
  `gift_recip_name` varchar(100) NOT NULL default '',
  `gift_recip_email` varchar(100) NOT NULL default '',
  `gift_message` text NOT NULL default '',
  `gift_time` varchar(12) NOT NULL default '',
 PRIMARY KEY (`gift_id`)
) ENGINE=MyISAM;

CREATE TABLE `media_manage` (
  `manage_type` varchar(25) NOT NULL default '',
  `manage_user` varchar(5) NOT NULL default '',
  `manage_media` varchar(5) NOT NULL default '',
  `manage_timeout` varchar(12) NOT NULL default ''
) ENGINE=MyISAM;

CREATE TABLE `media_online` (
  `timestamp` varchar(15) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `sid` varchar(32) NOT NULL default '',
 KEY `timestamp` (`timestamp`),
 KEY `ip` (`ip`)
) ENGINE=MyISAM;

CREATE TABLE `media_playlist` (
  `playlist_id` varchar(20) NOT NULL default '',
  `playlist_contents` varchar(255) NOT NULL default ''
) ENGINE=MyISAM;

CREATE TABLE `media_singer` (
  `singer_id` int(10) NOT NULL auto_increment,
  `singer_name` varchar(255) NOT NULL default '',
  `singer_name_ascii` varchar(255) NOT NULL default '',
  `singer_img` varchar(255) NOT NULL default '',
  `singer_info` text NOT NULL default '',
  `singer_type` char(1) NOT NULL default '',
 PRIMARY KEY (`singer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `media_tpl` (
  `tpl_id` int(3) NOT NULL auto_increment,
  `tpl_sname` varchar(20) NOT NULL default '',
  `tpl_fname` varchar(255) NOT NULL default '',
  `tpl_order` int(3) NOT NULL default '0',
 PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2;

INSERT INTO `media_tpl` (`tpl_id`, `tpl_sname`, `tpl_fname`, `tpl_order`) VALUES ('1','funnycolors','FunnyColors','1');

CREATE TABLE `media_user` (
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
 PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

INSERT INTO `media_user` (`user_id`, `user_name`, `user_password`, `user_new_password`, `user_email`, `user_sex`, `user_hide_info`, `user_regdate`, `user_level`, `user_playlist_id`, `user_online`, `user_ip`, `user_identifier`, `user_timeout`, `user_lastvisit`) VALUES ('1','admin','21232f297a57a5a743894a0e4a801fc3','fWQU59WMsWZVuok','redphoenix89@yahoo.com','1','1','2006-12-20','3','nI8RG0eJt3pilGZ8HFjy','1','127.0.0.1','fdb80a443e22fdf3a048d734586e3176','1172645018','1172386900');
