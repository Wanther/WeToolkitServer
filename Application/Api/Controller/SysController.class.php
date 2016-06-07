<?php
namespace Api\Controller;

use \Think\Controller;

class SysController extends Controller{
	public function check_update(){
		$package = I('get.package');
		$versionCode = I('get.ver');

		if(empty($package)){
			$headers = getallheaders();
			if(isset($headers['User-Agent'])){
				$userAgent = $headers['User-Agent'];
				$userAgent = explode('/', $userAgent);
				$package = $userAgent[0];
				$versionCode = $userAgent[1];
			}
		}

		if(empty($package)){
			$this->ajaxReturn(array('err_code'=>1001, 'err_msg'=>'缺少参数'));
		}

		if(empty($versionCode)){
			$versionCode = 0;
		}

		$AppVer = M('AppVer');
		$data = $AppVer->where(array('package'=>$package))->find();
		if($data['ver'] > $versionCode){
			$this->ajaxReturn($data);
		}else{
			$this->ajaxReturn((object)array());
		}
	}
}