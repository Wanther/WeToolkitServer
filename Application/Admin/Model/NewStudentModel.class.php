<?php

namespace Admin\Model;

use \Think\Model;

class NewStudentModel extends Model{

	protected $patchValidate = true;

	protected $_validate = array(
		array('exam_num', 'require', '请填写考号', self::EXISTS_VALIDATE),
		array('exam_num', 'integer', '请填写中考分数', self::EXISTS_VALIDATE),
		array('exam_num', '', '该考号已经存在！', self::EXISTS_VALIDATE, 'unique', self::MODEL_BOTH),
		array('name', 'require', '请填写姓名', self::EXISTS_VALIDATE),
		array('id_card_no', '/^\d{17}(\d|[Xx])$/', '请填写正确的身份证号', self::EXISTS_VALIDATE, 'regex'),
		array('height', 'integer', '请填写身高', self::VALUE_VALIDATE),
		array('weight', 'integer', '请填写体重', self::VALUE_VALIDATE),
		array('shoe_size', 'integer', '请填写鞋码', self::VALUE_VALIDATE),
		array('father_phone', '/^\d{8}|\d{11}|\d{12}$/', '请填写父亲电话', self::VALUE_VALIDATE, 'regex'),
		array('mother_phone', '/^\d{8}|\d{11}|\d{12}$/', '请填写母亲电话', self::VALUE_VALIDATE, 'regex')
	);

	protected $_auto = array(
		array('created', NOW_TIME, self::MODEL_INSERT),
		array('created_by', 'is_login', self::MODEL_INSERT, 'function'),
		array('last_upd', NOW_TIME, self::MODEL_BOTH),
		array('last_upd_by', 'is_login', self::MODEL_BOTH, 'function')
	);
}