alter table `t_new_student` add column `id_card_no` char(18);
alter table `t_new_student` add column `major` tinyint not null default 0;
alter table `t_new_student` add column `middle_school` varchar(128);
alter table `t_new_student` add column `middle_school_class` varchar(64);
alter table `t_new_student` add column `height` int(11) unsigned;
alter table `t_new_student` add column `weight` int(11) unsigned;
alter table `t_new_student` add column `shoe_size` int(11) unsigned;
alter table `t_new_student` add column `shape` tinyint not null default 0;
alter table `t_new_student` add column `father_name` varchar(16);
alter table `t_new_student` add column `father_phone` varchar(16);
alter table `t_new_student` add column `mother_name` varchar(16);
alter table `t_new_student` add column `mother_phone` varchar(16);

alter table `t_new_student` drop column `dress_size`;
alter table `t_new_student` drop column `exam_score`;
alter table `t_new_student` drop column `phone`;

INSERT INTO t_lookup(type,name,val,seq_num)
value
('MAJOR', '0', '无', 1),
('MAJOR', '1', '音乐', 2),
('MAJOR', '2', '美术', 3),
('BODY_SHAPE', '0', '正常', 1),
('BODY_SHAPE', '1', '偏胖', 2),
('BODY_SHAPE', '2', '偏瘦', 3)
;