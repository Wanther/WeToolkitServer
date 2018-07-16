DROP TABLE IF EXISTS `t_new_student`;
CREATE TABLE `t_new_student`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`created` int(11) NOT NULL DEFAULT 0,
	`created_by` int(11) unsigned NOT NULL,
	`last_upd` int(11) NOT NULL DEFAULT 0,
	`last_upd_by` int(11) unsigned NOT NULL,
	`exam_num` varchar(16) NOT NULL,
	`name` varchar(16) NOT NULL,
	`gender` TINYINT NOT NULL DEFAULT 0,
	`nation` TINYINT NOT NULL DEFAULT 1,
	`exam_score` DECIMAL(4, 1),
	`address` varchar(128),
	`phone` varchar(16),
	`is_payed` CHAR(1) NOT NULL DEFAULT 'N',
	`dress_size` varchar(16),
	`is_stay` CHAR(1) NOT NULL DEFAULT 'N',
	`is_pad` CHAR(1) NOT NULL DEFAULT 'N',
	`desc_txt_01` varchar(255),
	`desc_txt_02` varchar(255),
	`desc_txt_03` varchar(255),
	`desc_txt_04` varchar(255),
	`desc_txt_05` varchar(255),
	PRIMARY KEY(`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO t_lookup(type,name,val,seq_num)
value
('NATION','1','汉族',1),
('NATION','2','壮族',2),
('NATION','3','满族',3),
('NATION','4','回族',4),
('NATION','5','苗族',5),
('NATION','6','维吾尔族',6),
('NATION','7','土家族',7),
('NATION','8','彝族',8),
('NATION','9','蒙古族',9),
('NATION','10','藏族',10),
('NATION','11','布依族',11),
('NATION','12','侗族',12),
('NATION','13','瑶族',13),
('NATION','14','朝鲜族',14),
('NATION','15','白族',15),
('NATION','16','哈尼族',16),
('NATION','17','哈萨克族',17),
('NATION','18','黎族',18),
('NATION','19','傣族',19),
('NATION','20','畲族',20),
('NATION','21','傈僳族',21),
('NATION','22','仡佬族',22),
('NATION','23','东乡族',23),
('NATION','24','高山族',24),
('NATION','25','拉祜族',25),
('NATION','26','水族',26),
('NATION','27','佤族',27),
('NATION','28','纳西族',28),
('NATION','29','羌族',29),
('NATION','30','土族',30),
('NATION','31','仫佬族',31),
('NATION','32','锡伯族',32),
('NATION','33','柯尔克孜族',33),
('NATION','34','达斡尔族',34),
('NATION','35','景颇族',35),
('NATION','36','毛南族',36),
('NATION','37','撒拉族',37),
('NATION','38','塔吉克族',38),
('NATION','39','阿昌族',39),
('NATION','40','普米族',40),
('NATION','41','鄂温克族',41),
('NATION','42','怒族',42),
('NATION','43','京族',43),
('NATION','44','基诺族',44),
('NATION','45','德昂族',45),
('NATION','46','保安族',46),
('NATION','47','俄罗斯族',47),
('NATION','48','裕固族',48),
('NATION','49','乌兹别克族',49),
('NATION','50','门巴族',50),
('NATION','51','鄂伦春族',51),
('NATION','52','独龙族',52),
('NATION','53','塔塔尔族',53),
('NATION','54','赫哲族',54),
('NATION','55','珞巴族',55),
('NATION','56','布朗族',56);