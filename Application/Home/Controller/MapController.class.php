<?php
namespace Home\Controller;

use Think\Controller;

class MapController extends Controller {
    public function index(){
    	$this->curNav = 5;

        $this->display();
    }

    public function geodata($id, $type = 'region') {

    	$parent = M('MapNode')->field('geodata', true)->find($id);

        $dataList = null;

        if ($type == 'region') {
            $dataList = M('MapNode')->alias('a')
                ->field(array('a.geodata'=>'geometry', 'COUNT(b.id)'=>'count'))
                ->join("__MAP_NODE__ b ON b.type=100 AND b.path like CONCAT(a.path, '/%')", "LEFT")
                ->where("a.pid=%d AND a.id<>a.pid", $id)
                ->group("a.id")
                //->having('count > 0')
                ->select();
        } elseif ($type == 'point') {
            $dataList = M('MapNode')->field(array('geodata'=>'geometry'))->where("type=100 AND path like '{$parent['path']}/%'")->select();
        }

        foreach ($dataList as &$value) {
            $value['geometry'] = json_decode($value['geometry']);
            // if (isset($value['count']) && $value['count'] <= 0) {
            //     $value['count'] = rand(1, 100);
            // }
        }

    	$this->ajaxReturn($dataList);
    }

    public function get_children($pid) {

    	if ($pid == '#') {
    		$children = M('MapNode')->field('geodata', true)->where('id=pid')->order('CONVERT(name USING gbk)')->select();
    	} else {
    		$children = M('MapNode')->field('geodata', true)->where("pid=%d and id<>pid", $pid)->order('CONVERT(name USING gbk)')->select();
    	}

    	if (empty($children)) {
    		$this->ajaxReturn(array());
    		return;
    	}

    	foreach ($children as &$value) {
    		$value['children'] = $value['type'] == 100 ? false : true;
            if ($value['type'] == 100) {
                $value['icon'] = 'jstree-file';
            }
    		$value['text'] = $value['name'];

    		if ($pid == '#') {
    			$value['state'] = array('opened'=>true);
    		}

    		unset($value['name']);
    	}

    	$this->ajaxReturn($children);
    }
}