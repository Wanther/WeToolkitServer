<?php
namespace Admin\Controller;

class MapController extends AdminController {
	public function lists() {
		$this->authView(501);

		$this->display();
	}

	public function info($id) {
		$info = M('MapNode')->find($id);

		$this->ajaxReturn(array('type'=>'detail', 'data'=>$info));
	}
}