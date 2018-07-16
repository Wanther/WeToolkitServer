<?php

namespace Admin\Model;

use \Think\Model;

class NewStudentModel extends Model{

	protected $patchValidate = true;

	protected $_validate = array(
		array('exam_num', 'require', '请填写考号', self::MUST_VALIDATE),
		array('exam_num', 'integer', '请填写中考分数', self::MUST_VALIDATE),
		array('exam_num', '', '该考号已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
		array('name', 'require', '请填写姓名', self::MUST_VALIDATE),
		array('gender', array(1, 2), '请选择性别', self::MUST_VALIDATE, 'in'),
		array('nation', 'require', '请选择民族', self::MUST_VALIDATE),
		array('exam_score', '/^\d+(\.\d)?$/', '请填写中考分数，例：600,600.5', self::MUST_VALIDATE, 'regex')
	);

	protected $_auto = array(
		array('created', NOW_TIME, self::MODEL_INSERT),
		array('created_by', 'is_login', self::MODEL_INSERT, 'function'),
		array('last_upd', NOW_TIME, self::MODEL_BOTH),
		array('last_upd_by', 'is_login', self::MODEL_BOTH, 'function')
	);
}