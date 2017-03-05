<?php
namespace Admin\Controller;

class WordController extends AdminController {
	public function lists() {
		$this->authView(401);

		$ruleList = M('rule')->select();

		$rootRuleList = list_to_tree($ruleList, 'id', 'pid', 'children', 0);

		$ruleList = array();
		foreach($rootRuleList as $key=>$value) {
			$ruleList[] = $value;
		}

		$this->assign('ruleList', $ruleList);
		$this->display('lists');
	}

	public function ruleList() {
		
	}

	public function ruleWordList($ruleId) {
		$wordList = M('Word')->where(array('rid'=>$ruleId))->order('name', 'asc')->select();
		$this->ajaxReturn($wordList);
	}

	public function noRuleWordList() {
		$wordList = M('Word')->where(array('rid'=>0))->order('name', 'asc')->select();
		$this->ajaxReturn($wordList);
	}

	public function assignRule($ruleId, $wordList) {
		$wordList = explode(',', $wordList);
		foreach($wordList as $k=>$v) {
			if (!$v) {
				unset($wordList[$k]);
			}
		}
		
		M('Word')->where(array('name'=>array('in', $wordList)))->setField('rid', $ruleId);
		$this->ajaxReturn('success');
	}
}