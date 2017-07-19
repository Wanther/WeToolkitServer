<?php

namespace Home\Controller;

use Common\Controller\AppController;

class PromotionController extends AppController{

	public function index($who = ''){

		$promotions = array(
			'牛卡、学而思直播'=>'http://www.xdf100.com/niuka',
			'沃未来派官网'=>'http://www.xdf100.com'
		);

		if (IS_POST) {
			if (empty($who)) {
				$this->error('请填写手机号');
			}

			foreach ($promotions as $key => $value) {
				$promotions[$key] = $this->appendParameters($value, array('_who'=>$who));
			}

			$this->assign('promotions', $promotions);
		}

		$this->assign('curNav', 4);
		$this->display('index');
	}

	protected function appendParameters($url, $parameters = array()) {
		if (strrpos($url, '?')) {
			$url = $url.'&';
		} else {
			$url = $url.'?';
		}

		$i = 0;
		foreach ($parameters as $key => $value) {
			if ($i > 0) {
				$url = $url.'&';
			}
			$url = $url.$key.'='.$value;
		}

		return $url;
	}

}