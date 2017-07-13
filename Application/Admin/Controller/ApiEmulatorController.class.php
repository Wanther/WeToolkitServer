<?php
namespace Admin\Controller;

class ApiEmulatorController extends AdminController {

	public function lists() {
		$this->authView(202);

		$descTxt = I('get.desc_txt');

		$condition = array();
		if($descTxt){
			$condition['e.desc_txt'] = array('LIKE', "%{$descTxt}%");
		}

		$ApiEmulator = D('ApiEmulator');

		$totalCount = $ApiEmulator->alias('e')->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$ApiEmulator->alias('e')
			->field("e.id,e.desc_txt,e.path_match,e.created,e.last_upd,uc.name created_by,uu.name last_upd_by")
			->join("__ADMIN_USER__ uc on uc.id=e.created_by")
			->join("__ADMIN_USER__ uu on uu.id=e.last_upd_by")
			->where($condition);
		$sortInfo = get_sort_info();
		
		if (!$sortInfo) {
			$sortInfo = array();
		}
		$sortInfo['e.created'] = 'desc';

		if(!empty($sortInfo)){
			$ApiEmulator->order($sortInfo);
		}
		
		$apiList = $ApiEmulator->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $apiList);
		$this->assign('page', $Page->show());

		$this->display('lists');
	}

	public function add(){
		$this->authView(202);

		if(IS_POST){
			$ApiEmulator = D('ApiEmulator');
			$data = $ApiEmulator->create();

			if(!$data){
				$this->errorInput($ApiEmulator->getError(), 'ApiEmulator/edit');
			}

			$id = $ApiEmulator->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'ApiEmulator/lists');
			}

			$this->successMessage('添加成功', 'ApiEmulator/lists');
		}else{
			$this->display('edit');
		}
	}

	public function edit($id) {
		$this->authView(202);

		$ApiEmulator = D('ApiEmulator');

		if (IS_POST) {
			$data = $ApiEmulator->create();
			if (!$data) {
				$this->errorInput($ApiEmulator->getError(), 'ApiEmulator/edit');
			}

			$ApiEmulator->save();

			$this->successMessage('操作成功', get_return_url('ApiEmulator/lists'));

		} else {
			$data = $ApiEmulator->find($id);

			if (empty($data)) {
				$this->errorMessage('该记录不存在或已被删除', 'ApiEmulator/lists');
			}

			$this->assign('data', $data);

			$this->display('edit');
		}		
	}

	public function delete() {
		$this->authView(202);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('ApiEmulator/lists')));
		}

		$ApiEmulator = M('ApiEmulator');

		if(is_array($id)){
			$ApiEmulator->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$ApiEmulator->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('ApiEmulator/lists')));
	}
}