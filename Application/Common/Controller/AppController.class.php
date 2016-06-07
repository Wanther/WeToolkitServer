<?php
namespace Common\Controller;

use \Think\Controller;

abstract class AppController extends Controller{
	const KEY_ACT_ERR = '_ACTERR_';
	const KEY_ACT_MSG = '_ACTMSG_';
	const KEY_FIELD_ERR = '_FIELDERR_';
	const KEY_DATA = 'data';

	/**
	 * 跳转到一个页面，设置上用户的输入，带上错误信息，可能是actionerror也可能是fielderror
	 * $message mixed
	 *		可以是一个字符串，或者是一个数组，如果是字符串则认为是actionerror，如果是数组，认为是fielderror
	 * $jumbUrl string
	 *		同 error 里的第二个参数
	 * $only,$except array
	 *		只设置的字段，或者只排除的字段，默认空的话全都设置，顺序是先only然后except
	 */
	protected function errorInput($message = '', $jumpUrl = '', $only = array(), $except = array(), $mergedData = null){
		if(IS_AJAX){
			$this->error($message, U($jumpUrl));
		}
		$formData = I('post.');
		if(!empty($formData)){
			if (!empty($mergedData)) {
				$formData = array_merge($mergedData, $formData);
			}

			foreach ($formData as $key => $value) {
				if(!empty($only)){
					if(!in_array($key, $only)){
						unset($formData[$key]);
					}
				}
				if(!empty($except)){
					if(in_array($key, $except)){
						unset($formData[$key]);
					}
				}
			}

			$this->assign(self::KEY_DATA, $formData);
		}

		if(is_array($message)){
			$this->assign(self::KEY_FIELD_ERR, $message);
		}else{
			if($message == L('_TOKEN_ERROR_')){
				$this->error($message);
			}

			$this->assign(self::KEY_ACT_ERR, $message);
		}

		$this->display($jumpUrl);
		exit();
	}

	protected function successMessage($message, $jumpUrl){
		session(self::KEY_ACT_MSG, $message);
		if(strpos($jumpUrl, MODULE_NAME) === 0
			|| strpos($jumpUrl, CONTROLLER_NAME) === 0){

			$this->redirect($jumpUrl);
		}else{
			redirect($jumpUrl);
		}
	}

	protected function errorMessage($message, $jumpUrl){
		session(self::KEY_ACT_ERR, $message);
		if(strpos($jumpUrl, MODULE_NAME) === 0
			|| strpos($jumpUrl, CONTROLLER_NAME) === 0){

			$this->redirect($jumpUrl);
		}else{
			redirect($jumpUrl);
		}
	}

	/**
	 * 404
	 */
	public function _empty(){
		$this->error('404:Not Found');
	}
}