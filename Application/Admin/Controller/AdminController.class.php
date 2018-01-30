<?php
namespace Admin\Controller;

use \Common\Controller\AppController;

abstract class AdminController extends AppController{
	protected $accessControl = array(
		'public'=>array(
			'User/login', 'User/logout', 'File/verify_code'
		)
	);

	protected function _initialize(){
		$user = get_login_user();

		if(!in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $this->accessControl['public']) && empty($user)){
			
			cookie('return_url', $_SERVER['REQUEST_URI']);

			if(IS_AJAX){
				$this->ajaxReturn(array('status'=>98, 'data'=>'请先登录'));
			}else{
				$this->redirect('User/login');
			}
		}
		
		$this->assign('_LOGINUSER_', $user);
	}

	/**
	 * 菜单与tab的选中状态
	 */
	protected function authView($viewId){
		if(empty($this->_LOGINUSER_)){
			$this->redirect('User/login');
		}

		if(!has_auth($this->_LOGINUSER_, $viewId)){
			$this->error('没有权限');
		}

		$this->assign('_CURVIEW_', $viewId);
	}
}