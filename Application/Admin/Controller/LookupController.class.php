<?php
namespace Admin\Controller;

class LookupController extends AdminController{

	public function lists(){
		$this->authView(104);

		$type = I('get.type');

		$condition = array();
		if($type){
			$condition['type'] = array('LIKE', "%{$type}%");
		}

		$Lookup = D('Common/Lookup');

		$totalCount = $Lookup->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$Lookup->field(true)->where($condition);
		$sortInfo = get_sort_info();
		if(!empty($sortInfo)){
			$Lookup->order($sortInfo);
		}
		$lvList = $Lookup->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $lvList);
		$this->assign('page', $Page->show());

		$this->display();
	}

	public function add(){
		$this->authView(104);

		if(IS_POST){
			$Lookup = D('Common/Lookup');
			$data = $Lookup->create();

			if(!$data){
				$this->errorInput($Lookup->getError(), 'Lookup/edit');
			}

			$id = $Lookup->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'Lookup/lists');
			}

			$this->successMessage('添加成功', 'Lookup/lists');
		}else{
			$this->display('edit');
		}
	}

	public function edit($id) {
		$this->authView(104);

		$Lookup = D('Common/Lookup');

		if (IS_POST) {
			$data = $Lookup->create();
			if (!$data) {
				$this->errorInput($Lookup->getError(), 'Lookup/edit');
			}

			$Lookup->save();

			$this->successMessage('操作成功', get_return_url('Lookup/lists'));
		} else {
			$data = $Lookup->field(true)->find($id);
			$this->assign('data', $data);
			$this->display('edit');
		}
	}

	public function delete(){
		$this->authView(104);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('Lookup/lists')));
		}

		$Lookup = D('Common/Lookup');

		if(is_array($id)){
			$Lookup->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$Lookup->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('Lookup/lists')));
	}

}