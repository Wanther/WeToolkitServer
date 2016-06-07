ALTER TABLE `t_device_info` ADD COLUMN `desc_txt` VARCHAR(64);

INSERT INTO `t_lookup` (type,name,val,seq_num)
VALUES ('SCREEN_DENSITY', '0.75', 'ldpi', 1),
('SCREEN_DENSITY', '1.0', 'mdpi', 2),
('SCREEN_DENSITY', '1.5', 'hdpi', 3),
('SCREEN_DENSITY', '2.0', 'xhdpi', 4),
('SCREEN_DENSITY', '3.0', 'xxhdpi', 5);