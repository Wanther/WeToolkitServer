DROP TABLE IF EXISTS `t_map_node`;
CREATE TABLE `t_map_node` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT,
  `pid` INT(11) UNSIGNED NOT NULL,  
  `type` TINYINT NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `path` VARCHAR(64) NOT NULL,
  `geodata` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE INDEX `idx_map_node_pid` ON t_map_node(pid);
CREATE INDEX `idx_map_node_type_path` ON t_map_node(type, path);

DROP TABLE IF EXISTS `t_region_area`;
CREATE TABLE `t_region_area` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT,
  `pid` INT(11) UNSIGNED NOT NULL,
  `type` TINYINT NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `path` VARCHAR(64) NOT NULL,
  `geodata` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;