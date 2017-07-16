ALTER TABLE `t_api_emulator` ADD COLUMN `path_match` varchar(128);
ALTER TABLE `t_api_emulator` MODIFY COLUMN `content` LONGTEXT;