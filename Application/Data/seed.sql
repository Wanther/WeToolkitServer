INSERT INTO `t_lookup`(type,name,val,seq_num)
VALUES
('BOOL', '0', 'N', 1),
('BOOL', '1', 'Y', 2),
('GENDER', '0', '未知', 1),
('GENDER', '1', '男', 2),
('GENDER', '2', '女', 3),
('OWNER_TYPE', '0', '未知', 1),
('OWNER_TYPE', '1', '公司', 2),
('OWNER_TYPE', '2', '个人', 3)
;

INSERT INTO `t_admin_user`(username,password,auth)
VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '101')
;