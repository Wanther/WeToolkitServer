<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
    public function index(){
    	$this->curNav = 1;

    	$App = M('AppRelease');
    	$appList = $App->where(array('inactive'=>'N'))->order('seq_num')->select();

    	$this->assign('appList', $appList);

        $this->display();
    }
}