<?php
namespace Home\Controller;

use Think\Controller;

class ResourceController extends Controller {
    public function index(){
    	$this->curNav = 3;

        //$tagList = M('Tag')->field('id,pid,name')->select();
        $tagList = array(array('name'=>'人物'), array('name'=>'地点'));

        $this->assign('tagList', $tagList);

        $this->display();
    }

    public function tag_list() {
        $tagList = M('Tag')->field('id,pid,name,type')->order('type')->select();
        $this->ajaxReturn($tagList);
    }
}