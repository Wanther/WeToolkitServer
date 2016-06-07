<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->ajaxReturn(array(
        	'title'=> 'App Api',
        	'body'=> 'Hello Api'
        ));
    }
}