DROP TABLE IF EXISTS `t_lookup`;
CREATE TABLE `t_lookup` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`type` varchar(32) NOT NULL,
	`name` varchar(32) NOT NULL,
	`val` varchar(32) NOT NULL,
	`inactive` char(1) NOT NULL DEFAULT 'N',
	`seq_num` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_picture`;
CREATE TABLE `t_picture` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_admin_user`;
CREATE TABLE `t_admin_user` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(32) NOT NULL COMMENT '用户名',
	`password` char(32) NOT NULL COMMENT '密码',
	`auth` varchar(255) DEFAULT NULL COMMENT '权限配置',
	PRIMARY KEY (`id`),
	UNIQUE KEY `idx_admin_user_username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_device_info`;
CREATE TABLE `t_device_info` (
	`id` int (11) unsigned NOT NULL AUTO_INCREMENT,
	`device_id` char(15),
	`android_id` varchar(32),
	`mac_address` char(17),
	`serial_num` varchar(32),
	`phone_type` varchar(32),
	`os_ver` varchar(32),
	`sdk_ver` int(11) unsigned NOT NULL DEFAULT 0,
	`screen_w` int(11) unsigned NOT NULL DEFAULT 0,
	`screen_h` int(11) unsigned NOT NULL DEFAULT 0,
	`density` decimal(3, 1) NOT NULL DEFAULT 0,
	`owner` tinyint NOT NULL DEFAULT 0,
	`user` varchar(32),
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_app_ver`;
CREATE TABLE `t_app_ver` (
	`package` varchar(32) NOT NULL,
	`ver` int(11) unsigned NOT NULL DEFAULT 0,
	`download_url` varchar(255) NOT NULL,
	`desc_txt` varchar(64),
	PRIMARY KEY (`package`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_app_release`;
CREATE TABLE `t_app_release` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`created` int(11) NOT NULL DEFAULT 0,
	`created_by` int(11) unsigned NOT NULL,
	`name` varchar(64) NOT NULL,
	`code` varchar(32) NOT NULL,
	`platform` varchar(12) NOT NULL,
	`inactive` char(1) NOT NULL DEFAULT 'N',
	`qrcode_url` varchar(255),
	`pkg_path` varchar(64),
	`desc_txt` varchar(255),
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_release_log`;
CREATE TABLE `t_release_log` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`created` int(11) NOT NULL DEFAULT 0,
	`created_by` int(11) unsigned NOT NULL,
	`pid` int(11) unsigned NOT NULL,
	`msg` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_api_emulator`;
CREATE TABLE `t_api_emulator` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`created` int(11) NOT NULL DEFAULT 0,
	`created_by` int(11) unsigned NOT NULL,
	`last_upd` int(11) NOT NULL DEFAULT 0,
	`last_upd_by` int(11) unsigned NOT NULL,
	`desc_txt` varchar(255),
	`content` text,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;