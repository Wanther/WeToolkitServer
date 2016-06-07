<?php
namespace Admin\Widget;

use \Think\Controller;

class FormWidget extends Controller{
	public function searchInput($name, $label, $value = null){
		$html = "<label>{$label}：</label>";

		if($value === null){
			$value = I("get.{$name}", '');
		}

		$html .= "<input type=\"text\" name=\"{$name}\" value=\"{$value}\" class=\"input-medium\">";

		return $html;
	}

	public function lookupSelect($name, $value, $type, $first=false){
		$lookupList = lookup_list($type);
		if(empty($lookupList)){
			$lookupList = array();
		}

		if($first){
			if(is_array($first)){
				array_unshift($lookupList, $first);
			}else{
				array_unshift($lookupList, array('name'=>'', 'val'=>$first));
			}
		}

		$html = "<select id=\"{$name}\" name=\"{$name}\" class=\"form-control\">";
		foreach ($lookupList as $lu) {
			$html .= "<option value=\"{$lu['name']}\"";
			if($lu['name'] == $value){
				$html .= ' selected';
			}
			$html .= ">{$lu['val']}</option>";
		}
		$html .= '</select>';

		return $html;
	}

	public function listSelect($name, $value, $model, $keyField = 'id', $valueField = 'value', $where = null, $order = null){
		if(is_string($model)){
			$model = M($model);
		}
		$model->field("$keyField,$valueField");
		if($where){
			$model->where($where);
		}
		if($order){
			$model->order($order);
		}
		$dataList = $model->select();
		$html = "<select id='{$name}' name='{$name}' class='form-control'>";
		$html .= '<option value="">===请选择===</option>';
		foreach ($dataList as $data) {
			$key = $data[$keyField];
			$val = $data[$valueField];
			$html .= "<option value='{$key}'";
			if($key == $value){
				$html .= ' selected';
			}
			$html .= ">{$val}</option>";
		}
		$html .= "</select>";

		return $html;
	}

	public function datetimepicker($name, $value = '', $format = 'yyyy-mm-dd hh:ii:ss'){
		// 0 - hour,1 - day, 2 - month, 3 - year, 4 - ten years
		$minView = 0;
		$maxView = 4;
		$startView = 2;

		if(stripos($format, 'i') !== false){
			$minView = 0;
		}elseif(stripos($format, 'h') !== false){
			$minView = 1;
		}elseif(stripos($format, 'd') !== false){
			$minView = 2;
		}elseif(stripos($format, 'm') !== false){
			$minView = 3;
		}

		if(stripos($format, 'y') !== false){
			$maxView = 4;
		}elseif(stripos($format, 'm') !== false){
			$maxView = 3;
		}elseif(stripos($format, 'd') !== false){
			$maxView = 2;
		}elseif(stripos($format, 'h') !== false){
			$maxView = 1;
		}

		if($startView < $minView){
			$startView = $minView;
		}

		if($startView > $maxView){
			$startView = $maxView;
		}

		$this->assign('name', $name);
		$this->assign('value', $value);
		$this->assign('format', $format);
		$this->assign('minView', $minView);
		$this->assign('maxView', $maxView);
		$this->assign('startView', $startView);

		$this->display('Widget:datetimepicker');
	}

	public function picUpload($name, $value, $limit = 1, $upload = null){
		if($upload == null){
			$upload = U('File/upload_picture');
		}

		$this->assign('name', $name);
		$this->assign('value', $value);
		$this->assign('limit', $limit);
		$this->assign('upload', $upload);

		$this->display('Widget:pic_upload');
	}
}