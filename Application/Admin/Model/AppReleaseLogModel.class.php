<?php
namespace Admin\Model;

use \Think\Model;

class AppReleaseLogModel extends Model{

	protected $tableName = 'release_log';

	protected $patchValidate = true;

	protected $_validate = array(
		array('pid', 'require', '请选择APP', self::MUST_VALIDATE),
		array('msg', 'require', '请填写更新信息', self::MUST_VALIDATE),
		array('msg', '1,80', '80个字以内', self::MUST_VALIDATE, 'length', self::MODEL_BOTH)
	);

	protected $_auto = array(
		array('created', NOW_TIME, self::MODEL_INSERT),
		array('created_by', 'is_login', self::MODEL_INSERT, 'function')
	);
}