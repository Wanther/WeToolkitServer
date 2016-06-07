<?php
namespace Admin\Model;

use \Think\Model;

class AppReleaseModel extends Model{

	protected $patchValidate = true;

	protected $_validate = array(
		array('name', 'require', '请填写名称', self::MUST_VALIDATE),
		array('code', '/[a-zA-Z0-9_]{1,32}/', '只能有数字字母或者下划线组成，32个字符以内', self::MUST_VALIDATE, 'regex'),
		array('code', '', '标识已存在', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
		array('platform', 'require', '请选择平台', self::MUST_VALIDATE),
		array('seq_num', 'integer', '只能是整数', self::MUST_VALIDATE)
	);

	protected $_auto = array(
		array('created', NOW_TIME, self::MODEL_INSERT),
		array('created_by', 'is_login', self::MODEL_INSERT, 'function')
	);
}