DROP TABLE IF EXISTS `t_tag`;
CREATE TABLE `t_tag` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `pid` int(11) unsigned NOT NULL,
    `name` varchar(32) NOT NULL,
    `path` varchar(64) NOT NULL,
    `created` int(11) NOT NULL DEFAULT 0,
    `created_by` int(11) unsigned NOT NULL,
    `last_upd` int(11) NOT NULL DEFAULT 0,
    `last_upd_by` int(11) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_tag_object`;
CREATE TABLE `t_tag_object` (
    `tag_id` int(11) unsigned NOT NULL,
    `obj_id` int(11) unsigned NOT NULL,
    `created` int(11) NOT NULL DEFAULT 0,
    `created_by` int(11) unsigned NOT NULL,
    `last_upd` int(11) NOT NULL DEFAULT 0,
    `last_upd_by` int(11) unsigned NOT NULL,
    PRIMARY KEY (`tag_id`, `obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_object`;
CREATE TABLE `t_object` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `type` tinyint NOT NULL,
    `title` varchar(128),
    `inactive` char(1) NOT NULL DEFAULT 'N',
    `created` int(11) NOT NULL DEFAULT 0,
    `created_by` int(11) unsigned NOT NULL,
    `last_upd` int(11) NOT NULL DEFAULT 0,
    `last_upd_by` int(11) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `t_pic`;
CREATE TABLE `t_pic` (
    `id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_video`;
CREATE TABLE `t_video` (
    `id` int(11) unsigned NOT NULL,
    `cover` varchar(255) NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;