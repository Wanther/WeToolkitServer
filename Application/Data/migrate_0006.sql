DROP TABLE IF EXISTS `t_word`;
CREATE TABLE `t_word` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) NOT NULL,
	`rid` int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_rule`;
CREATE TABLE `t_rule` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`pid` int(11) unsigned NOT NULL,
	`name` varchar(64) NOT NULL,
	`depth` tinyint NOT NULL DEFAULT 0,
	`leaf` char(1) NOT NULL DEFAULT 'Y',
	`remark` varchar(255),
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;