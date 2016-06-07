<?php
namespace Api\Model;

use \Think\Model;

class DeviceInfoModel extends Model{
	protected $_validate = array(
		array('device_id,android_id,serial_num,mac_address', 'atLeastOneExist', '缺少必须的字段', self::MUST_VALIDATE, 'callback')
	);

	protected function atLeastOneExist($fields){
		if(!empty($fields)){
			foreach ($fields as $field) {
				if(!empty($field)){
					return true;
				}
			}
		}

		return false;
	}
}