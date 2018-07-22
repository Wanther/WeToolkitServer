<?php
namespace Admin\Controller;

class ProxyServerController extends AdminController {
	public function cache() {
		$this->authView(204);

		$this->display();
	}
	
	public function deleteCache() {
		$this->authView(204);

		if (!IS_POST) {
			$this->error("只能使用POST方法");
		}

		$url = I('post.url');

		$urlRegex = '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/';

		if (preg_match($urlRegex, $url) !== 1) {
			$this->errorInput(array('url'=>'请填写正确的URL'), "ProxyServer/cache");
		}

		$encodedUrl = urlencode($url);
		$result = file_get_contents("http://cachemonitor.tianchuang.com/ci/delete_url?url=$encodedUrl");

		$this->assign('result', $result);

		$this->display('cache');
	}

}