<?php
namespace Common\Model;

use \Think\Model;

class LookupModel extends Model{
	public static $cache;

	protected $patchValidate = true;

	protected $_validate = array(
		array('type', 'require', '请填写Type', self::MUST_VALIDATE),
		array('name', 'require', '请填写Name', self::MUST_VALIDATE),
		array('val', 'require', '请填写值', self::MUST_VALIDATE)
	);

	public function lists($type){
		return $this->field('name,val')
					->where(array('type'=>$type, 'inactive'=>'N'))
					->order('seq_num')
					->select();
	}
}