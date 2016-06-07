ALTER TABLE `t_release_log` ADD COLUMN `ver_code` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `t_release_log` ADD COLUMN `ver_name` VARCHAR(16);
ALTER TABLE `t_release_log` ADD COLUMN `server_env` VARCHAR(16);
ALTER TABLE `t_app_ver` ADD COLUMN `ver_name` VARCHAR(16);
ALTER TABLE `t_app_ver` ADD COLUMN `force_update` CHAR(1) NOT NULL DEFAULT 'N';

INSERT INTO `t_lookup` (type,name,val,seq_num)
VALUES ('SERVER_ENV', 'test', 'DEV', 1),
('SERVER_ENV', 'staging', 'Staging', 2),
('SERVER_ENV', 'product', '线上', 3);