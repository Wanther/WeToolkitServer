<?php
namespace Admin\Model;

use \Think\Model;

class AdminUserModel extends Model{

	public static $loginUser;

	protected $patchValidate = true;

	protected $_validate = array(
		array('username', '/[a-zA-Z0-9_]{1,32}/', '请填写用户名，只能有数字字母或者下划线组成', self::MUST_VALIDATE, 'regex'),
		array('username', '', '用户名已存在', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
		array('name', 'require', '请填写姓名', self::MUST_VALIDATE)
	);
	
}