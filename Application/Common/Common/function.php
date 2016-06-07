<?php

function lookup_list($type){
	
	if(!isset(Common\Model\LookupModel::$cache[$type])){
		$Lookup = new Common\Model\LookupModel();
		Common\Model\LookupModel::$cache[$type] = $Lookup->lists($type);
	}

	return Common\Model\LookupModel::$cache[$type];
}

function lookup_value($type, $name){
	$valueList = lookup_list($type);
	
	if(!empty($valueList)){
		foreach($valueList as $lu){
			if($name == $lu['name']){
				return $lu['val'];
			}
		}
	}

	return $name;
}