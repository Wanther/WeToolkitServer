<?php

namespace Admin\Model;

use \Think\Model;

class AppVerModel extends Model{
	protected $_validate = array(
		array('package', 'require', '请填写包名', self::MUST_VALIDATE)
	);
}