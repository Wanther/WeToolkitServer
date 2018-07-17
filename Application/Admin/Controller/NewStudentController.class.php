<?php
namespace Admin\Controller;

class NewStudentController extends AdminController{

	public function lists(){
		$this->authView(403);

		$name = I('get.name');

		$condition = array();
		if($name){
			$condition['name'] = array('LIKE', "%{$name}%");
		}

		$NewStudent = D('NewStudent');

		$totalCount = $NewStudent->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$NewStudent->field(true)->where($condition);
		$sortInfo = get_sort_info();
		if(!empty($sortInfo)){
			$NewStudent->order($sortInfo);
		}
		$dataList = $NewStudent->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $dataList);
		$this->assign('page', $Page->show());

		$this->display("lists_{$this->getViewName()}");
	}

	public function add(){
		$this->authView(403);

		if(IS_POST){
			$NewStudent = D('NewStudent');
			$data = $NewStudent->create();

			if(!$data){
				$this->errorInput($NewStudent->getError(), "NewStudent/edit_{$this->getViewName()}");
			}

			$id = $NewStudent->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'NewStudent/lists');
			}

			$this->successMessage('添加成功', 'NewStudent/lists');
		}else{
			$this->display("edit_{$this->getViewName()}");
		}
	}

	public function edit($id) {
		$this->authView(403);

		$NewStudent = D('NewStudent');

		if (IS_POST) {

			$origin = $NewStudent->find($id);

			$data = $NewStudent->create();
			if (!$data) {
				$this->errorInput($NewStudent->getError(), "NewStudent/edit_{$this->getViewName()}", array(), array(), $origin);
			}

			$NewStudent->save();

			$this->successMessage('操作成功', get_return_url("NewStudent/lists"));
		} else {
			$data = $NewStudent->field(true)->find($id);
			$this->assign('data', $data);

			$this->display("edit_{$this->getViewName()}");
		}
	}

	/*public function update($id) {
		$this->authView(403);

		if (!IS_POST) {
			$this->errorMessage('更新失败', 'NewStudent/lists');
		}

		$NewStudent = D('NewStudent');

		$data = I('post.');

		unset($data['id']);

		$NewStudent->where(array('id'=>$id))->save($data);

		$this->successMessage('操作成功', get_return_url('NewStudent/lists'));

	}*/

	public function delete(){
		$this->authView(403);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('NewStudent/lists')));
		}

		$NewStudent = D('NewStudent');

		if(is_array($id)){
			$NewStudent->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$NewStudent->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('NewStudent/lists')));
	}

	protected function getViewName() {
		$view = \Admin\Model\AdminUserModel::$loginUser['username'];
		if ($view == 'wangzaiyou') {
			$view = 'dianjiaochu';
		}

		return $view;
	}

}