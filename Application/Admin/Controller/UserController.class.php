<?php
namespace Admin\Controller;

class UserController extends AdminController{
	public function login(){
		if(!IS_POST){
			return $this->display();
		}

		$validate = array(
			array('username', 'require', '请输入用户名', 1),
			array('password', 'require', '请输入密码', 1)
		);
		$LoginUser = D('AdminUser');
		$data = $LoginUser->validate($validate)->create();
		if(!$data){
			$this->errorInput($LoginUser->getError(), 'User/login', array('username'));
		}
		
		$user = $LoginUser->where(array('username'=>$data['username']))->find();

		if(empty($user)){
			$this->errorInput('用户不存在');
		}

		if(md5($data['password']) != $user['password']){
			$this->errorInput('密码不正确');
		}

		session('uid', $user['id']);

		redirect(get_return_url(U('Index/index')));
	}

	public function logout(){
		$user = get_login_user();
		if(!empty($user)){
			session('uid', null);
		}

		cookie('return_url', null);

		$this->redirect('User/login');
	}

	public function lists(){
		$this->authView(101);

		$username = I('get.username');

		$condition = array();
		if($username){
			$condition['username'] = array('LIKE', "%{$username}%");
		}

		$AdminUser = D('AdminUser');

		$totalCount = $AdminUser->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$AdminUser->field(true)->where($condition);
		$sortInfo = get_sort_info();
		if(!empty($sortInfo)){
			$AdminUser->order($sortInfo);
		}
		$userList = $AdminUser->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $userList);
		$this->assign('page', $Page->show());

		$this->display();
	}

	public function add(){
		$this->authView(101);

		if(IS_POST){
			$AdminUser = D('AdminUser');
			$data = $AdminUser->create();

			if(!$data){
				$this->errorInput($AdminUser->getError(), 'User/edit');
			}

			if(!isset($data['password'])){
				$data['password'] = md5($data['username']);
			}

			$id = $AdminUser->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'User/lists');
			}

			$this->successMessage('添加成功', 'User/lists');
		}else{
			$this->display('edit');
		}
	}

	public function edit($id) {
		$this->authView(101);

		$AdminUser = D('AdminUser');

		if (IS_POST) {
			$validate = array(
				array('name', 'require', '请填写姓名', \Think\Model::MUST_VALIDATE)
			);

			$data = $AdminUser->validate($validate)->create();
			if (empty($data)) {
				$data = $AdminUser->find($id);
				$this->errorInput($AdminUser->getError(), 'User/edit', null, null, $data);
			}
			$AdminUser->where("id={$id}")->save($data);
			$this->successMessage('操作成功', get_return_url(U('User/lists')));
		} else {
			$data = $AdminUser->find($id);
			if (empty($data)) {
				$this->error('该用户不存在或已被删除', get_return_url(U('User/lists')));
			}

			$this->assign('data', $data);
			$this->display('edit');
		}
	}

	public function delete(){
		$this->authView(101);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('User/lists')));
		}

		$AdminUser = M('AdminUser');

		if(is_array($id)){
			$AdminUser->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$AdminUser->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('User/lists')));
	}

	public function assignauth($id){

		$this->authView(101);

		$AdminUser = M('AdminUser');

		if(IS_POST){
			$auth = I('auth');
			if(empty($auth)){
				$auth = null;
			}else{
				$auth = implode(',', $auth);
			}
			
			$AdminUser->where(array('id'=>(int)$id))->setField('auth', $auth);

			$this->successMessage('权限设置成功', get_return_url(U('User/lists')));
		}else{
			$user = $AdminUser->find($id);
			
			$this->assign('menuList', C('SYS_MENU'));
			$this->assign('user', $user);

			return $this->display();
		}
	}

	public function resetpassword($id){
		$AdminUser = M('AdminUser');

		$user = $AdminUser->find($id);

		$AdminUser->setField('password', md5($user['username']));

		$this->successMessage('重置成功', get_return_url(U('User/lists')));
	}

	public function changePassword() {
		if (IS_POST) {
			$user = $this->_LOGINUSER_;
			$oldPwd = I('post.old_pwd');
			$newPwd = I('post.new_pwd');
			$confirmPwd = I('post.confirm_pwd');

			if (empty($oldPwd) || empty($newPwd) || empty($confirmPwd)) {
				$this->errorMessage('请正确输入信息', U('User/changePassword'));
			}

			if (preg_match('/^[0-9a-zA-Z_]{6,12}$/', $newPwd) === 0) {
				$this->errorInput(array('new_pwd'=>'密码只能是数字、字母或下划线，6-12位'), 'User/change_pwd');
			}

			if ($newPwd != $confirmPwd) {
				$this->errorInput(array('confirm_pwd'=>'两次输入密码不一致'), 'User/change_pwd');
			}
			
			$AdminUser = D('AdminUser');
			$dbUser = $AdminUser->field('password')->find($user['id']);

			if($dbUser['password'] != md5($oldPwd)) {
				$this->errorInput(array('old_pwd'=>'旧密码不正确'), 'User/change_pwd');
			}

			$AdminUser->where(array('id'=>$user['id']))->setField('password', md5($newPwd));

			$this->successMessage('修改密码成功', U('Index/index'));

		} else {
			$this->display('change_pwd');
		}
	}

}