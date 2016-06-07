<?php
namespace Think\Template\TagLib;

use Think\Template\TagLib;
/**
 * bootstrap标签库解析类
 */
class Bs extends TagLib {

	// 标签定义
	protected $tags   =  array(
		// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
		'actionError'=>array('close'=>0),
		'actionMessage'=>array('close'=>0),
		'controlGroup'=>array('attr'=>'label,field,cssclass', 'close'=>1)
	);

	public function _actionError($tag, $content){
		$parseStr = '<?php if(!isset($_ACTERR_)){ ?>';
		$parseStr .= 	'<?php if(session("?_ACTERR_")) { ?>';
		$parseStr .= 		'<?php $_ACTERR_ = session("_ACTERR_"); ?>';
		$parseStr .= 		'<?php session("_ACTERR_", null); ?>';
		$parseStr .= 	'<?php } ?>';
		$parseStr .= '<?php } ?>';
		$parseStr .= '<?php if(isset($_ACTERR_) && !empty($_ACTERR_)):?>';
		$parseStr .= '<div class="alert alert-error">';
		$parseStr .= 	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
		$parseStr .= 	'<?php echo $_ACTERR_; ?>';
		$parseStr .= '</div>';
		$parseStr .= '<?php endif;?>';

		return $parseStr;
	}

	public function _actionMessage($tag, $content){
		$parseStr = '<?php if(!isset($_ACTMSG_)){ ?>';
		$parseStr .= 	'<?php if(session("?_ACTMSG_")) { ?>';
		$parseStr .= 		'<?php $_ACTMSG_ = session("_ACTMSG_"); ?>';
		$parseStr .= 		'<?php session("_ACTMSG_", null); ?>';
		$parseStr .= 	'<?php } ?>';
		$parseStr .= '<?php } ?>';
		$parseStr .= '<?php if(isset($_ACTMSG_) && !empty($_ACTMSG_)):?>';
		$parseStr .= '<div class="alert alert-success">';
		$parseStr .= 	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
		$parseStr .= 	'<?php echo $_ACTMSG_; ?>';
		$parseStr .= '</div>';
		$parseStr .= '<?php endif;?>';

		return $parseStr;
	}

	public function _controlGroup($tag, $content){
		$label = $this->getAttrValue($tag, 'label');
		$field = $this->getAttrValue($tag, 'field');
		$cssClass = $this->getAttrValue($tag, 'cssclass');

		$str = '<?php $__ERRTTXT__ = "";?>';
		$str .= '<div class="control-group';
		if($cssClass){
			$str .= ' ' . $cssClass;
		}

		$str .= '<?php if(isset($_FIELDERR_)){ ?>';
		$str .= 	'<?php $field = explode(",", "' . $field . '"); ?>';
		$str .= 	'<?php foreach($field as $f){ ?>';
		$str .=			'<?php if(isset($_FIELDERR_[$f])){ ?>';
		$str .= 			'<?php $__ERRTTXT__ .= (empty($__ERRTTXT__) ? "" : ",") . $_FIELDERR_[$f];?>';
		$str .=				'<?php echo " error";?>';
		$str .= 			'<?php break; ?>';
		$str .= 		'<?php } ?>';
		$str .= 	'<?php } ?>';
		$str .= '<?php }?>';

		$str .= '">';

		if($label){
			$str .= '<label class="control-label">'.$label.'</label>';
		}

		$str .= '<div class="controls">';

		$str .= $this->tpl->parse($content);
		$str .= '<?php if(!empty($__ERRTTXT__)){ ?>';
		$str .= 	'<span class="help-block"><small><?php echo $__ERRTTXT__; ?></small></span>';
		$str .= '<?php } ?>';
		$str .= '</div>';

		$str .= '</div>';

		return $str;
	}

	private function getAttrValue($tag, $name, $default=false){
		if(isset($tag[$name])){
			if($tag[$name] == 'false'){
				return false;
			}
			if($tag[$name] == 'true'){
				return true;
			}
			return $tag[$name];
		}
		return $default;
	}

}
