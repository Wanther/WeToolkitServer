<?php

namespace Admin\Model;

use \Think\Model;

class AchievementModel extends Model{
	protected $_validate = array(
		array('name', 'require', '请填写名称', self::MUST_VALIDATE)
	);
}