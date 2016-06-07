<?php
namespace Admin\Model;

use \Think\Model;

class ApiEmulatorModel extends Model{

	protected $patchValidate = true;

	protected $_validate = array(
		array('desc_txt', 'require', '请填写描述，方便查询', self::MUST_VALIDATE),
		array('content', 'require', '请填写返回报文', self::MUST_VALIDATE)
	);
	
	protected $_auto = array(
		array('created', NOW_TIME, self::MODEL_INSERT),
		array('created_by', 'is_login', self::MODEL_INSERT, 'function'),
		array('last_upd', NOW_TIME, self::MODEL_BOTH),
		array('last_upd_by', 'is_login', self::MODEL_BOTH, 'function')
	);
}