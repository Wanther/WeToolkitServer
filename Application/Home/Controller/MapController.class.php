<?php
namespace Home\Controller;

use Think\Controller;

class MapController extends Controller {
    public function index(){
    	$this->curNav = 5;

        $this->display();
    }

    public function geodata($id) {
    	//$dataList = M('MapNode')->field(array('geodata'=>'geometry'), false)->where("pid=%d AND pid<>id", $id)->select();
        $dataList = M('MapNode')->alias('a')
            ->field(array('a.geodata'=>'geometry', 'COUNT(b.id)'=>'count'))
            ->join("__MAP_NODE__ b ON b.type=100 AND b.path like CONCAT(a.path, '/%')", "LEFT")
            ->where("a.pid=%d AND a.id<>a.pid", $id)
            ->group("a.id")
            ->select();

    	// if (empty($dataList)) {
    	// 	$dataList = array();
    	// 	$data = M('MapNode')->field(array('geodata'=>'geometry'), false)->find($id);
    	// 	array_push($dataList, $data);
    	// }

        foreach ($dataList as &$value) {
            $value['geometry'] = json_decode($value['geometry']);
        }

    	$this->ajaxReturn($dataList);
    }

    public function get_children($pid) {

    	if ($pid == '#') {
    		$children = M('MapNode')->field('geodata', true)->where('id=pid')->select();
    	} else {
    		$children = M('MapNode')->field('geodata', true)->where("pid=%d and id<>pid", $pid)->select();
    	}

    	if (empty($children)) {
    		$this->ajaxReturn(array());
    		return;
    	}

    	foreach ($children as &$value) {
    		$value['children'] = $value['type'] == 100 ? false : true;
    		$value['text'] = $value['name'];

    		if ($pid == '#') {
    			$value['state'] = array('opened'=>true);
    		}

    		unset($value['name']);
    	}

    	$this->ajaxReturn($children);
    }
}