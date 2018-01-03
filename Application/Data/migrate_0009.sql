DROP TABLE IF EXISTS `t_achievement`;
CREATE TABLE `t_achievement` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE INDEX `idx_achievement_name` ON t_achievement(name);

DROP TABLE IF EXISTS `t_achievement_item`;
CREATE TABLE `t_achievement_item` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT,
  `pid` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `phone` CHAR(11),
  `email` VARCHAR(64),
  `detail` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;