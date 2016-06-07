<?php
namespace Admin\Controller;

class DeviceInfoController extends AdminController {
	public function lists() {
		$this->authView(301);

		$owner = I('get.owner');
		$user = I('get.user');

		$condition = array();
		if($owner || $owner === '0'){
			$condition['owner'] = $owner;
		}
		if ($user) {
			$condition['user'] = array('LIKE', "%{$user}%");
		}

		$DeviceInfo = D('Api/DeviceInfo');

		$totalCount = $DeviceInfo->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$DeviceInfo->field(true)->where($condition);
		$sortInfo = get_sort_info();

		if(!empty($sortInfo)){
			$DeviceInfo->order($sortInfo);
		}
		
		$infoList = $DeviceInfo->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $infoList);
		$this->assign('page', $Page->show());

		$this->display('lists');
	}

	public function edit($id) {
		$this->authView(301);

		$DeviceInfo = D('Api/DeviceInfo');

		if (IS_POST) {
			
			$data = $DeviceInfo->validate(array(array()))->create();

			if (!$data) {
				$data = $DeviceInfo->find($id);
				$this->errorInput($DeviceInfo->getError(), get_return_url('DeviceInfo/lists'), null, null, $data);
			}

			$DeviceInfo->where("id={$id}")->save($data);

			$this->successMessage('操作成功', get_return_url(U('DeviceInfo/lists')));
		} else {
			$data = $DeviceInfo->find($id);
			$this->assign('data', $data);
			$this->display('edit');
		}
	}
}